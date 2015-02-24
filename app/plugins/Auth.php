<?php

/**
 * Authentication
 * 
 * User/Role functions require models: User, Userroles, Roles
 * Cookie functions require models: Cookietokens
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall
 * @copyright (c) 2015, Tim Marshall
 * @version 
 */
class Auth
{

    /**
     * Database user id
     * @var int|bool 
     */
    public $id = false;

    /**
     * Array of Roles user has - Empty until User and Roles set
     * @var array 
     */
    public $roles;

    /**
     * If user is Administrator
     * @var bool 
     */
    public $isAdmin;

    /**
     * Anti-CRSF token key
     * @var string
     */
    public $tokenKey;

    /**
     * Anti-CRSF token value
     * @var string 
     */
    public $token;

    /**
     * User ID used in auditing, may be different to $this->id if admin is logged in as another user
     * @var int
     */
    public $audit_id;

    /**
     * Set a random Anti-CRSF token pair
     */
    function __construct()
    {
        $this->tokenKey = \Phalcon\Text::random(\Phalcon\Text::RANDOM_ALNUM, 22);
        $this->token = \Phalcon\Text::random(\Phalcon\Text::RANDOM_ALNUM, 22);
    }

    /**
     * Gets user model if logged in
     * @return \Users
     */
    public function getUser()
    {
        if ($this->id) {
            return \Users::findFirst([
                'id = :a:',
                'bind' => ['a' => $this->id]
            ]);
        }
        return false;
    }

    /**
     * Set the user (and audit ID if not previously set)
     * @param \Users $user
     */
    public function setUser(\Users $user)
    {
        $this->id = $user->id;
        if (!$this->audit_id) {
            $this->audit_id = $user->id;
        }
    }
    
    /**
     * Update the roles from database
     */
    public function setRoles()
    {
        foreach ($this->getUser()->roles as $role) {
            if ($role->name == 'Root Admin')
                $this->isAdmin = true;
            $roles[] = $role->name;
        }
        $this->roles = $roles;
    }

    /**
     * Returns the Anti-CRSF input field for forms
     * @return string
     */
    public function getSecurityField()
    {
        return '<input type="hidden" name="' . $this->tokenKey . '" value="' . $this->token . '"/>';
    }

    /**
     * Checks the validity of the Anti-CRSF on form submit
     * @param array $post $_POST
     * @return boolean
     */
    public function checkToken($post)
    {
        if (isset($post[$this->tokenKey])) {
            if ($post[$this->tokenKey] == $this->token) {
                return true;
            }
        }
        return false;
    }

    /**
     * Add a remember me cookie to the user's browser
     * @param \Phalcon\DI $di
     */
    public function createAuthCookie(\Phalcon\DI $di)
    {
        $cookie = $this->user()->createCookieToken(7);
        $cookies = $di->get('cookies');
        $cookies->set("RMT_" . $di->getShared('config')->application->name, $cookie['token'], time() + 604800, '/', true, $_SERVER['SERVER_NAME'], true);
        $cookies->set("RMK_" . $di->getShared('config')->application->name, $cookie['user_id'], time() + 604800, '/', true, $_SERVER['SERVER_NAME'], true);
    }

    /**
     * Check the validity of a cookie token
     * @param \Phalcon\DI $di
     * @return boolean
     */
    public static function checkAuthCookie(\Phalcon\DI $di)
    {
        $cookies = $di->get('cookies');
        if ($cookies) {
            if ($cookies->has("RMT_" . $di->getShared('config')->application->name)) {
                $token = $cookies->get("RMT_" . $di->getShared('config')->application->name)->getValue();
                $key = $cookies->get("RMK_" . $di->getShared('config')->application->name)->getValue();
                $cookietoken = \Usertokens::findFirst([
                    'type = "cookie" AND user_id = :a:',
                    'bind' => ['a' => $key]
                ]);
                if ($cookietoken) {
                    if (password_verify($token, $cookietoken->token)) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    /**
     * Delete cookie tokens
     * @param \Phalcon\DI $di
     * @return boolean
     */
    public static function removeAuthCookie(\Phalcon\DI $di)
    {
        $cookies = $di->get('cookies');
        if ($cookies) {
            if ($cookies->has("RMT_" . $di->getShared('config')->application->name)) {
                $key = $cookies->get("RMK_" . $di->getShared('config')->application->name)->getValue();
                \Usertokens::findFirst([
                    'type = "cookie" AND user_id = :a:',
                    'bind' => ['a' => $key]
                ])->delete();
                $cookies->set("RMT_" . $di->getShared('config')->application->name, null, time() - 604800, '/', true, $_SERVER['SERVER_NAME'], true);
                $cookies->set("RMK_" . $di->getShared('config')->application->name, null, time() - 604800, '/', true, $_SERVER['SERVER_NAME'], true);
            }
        }
        return false;
    }

}
