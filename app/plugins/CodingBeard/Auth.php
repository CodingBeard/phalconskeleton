<?php

/**
 * Authentication
 * 
 * User/Role functions require models: User, Userroles, Roles
 * Login functions require models: Logins
 * Cookie functions require models: Authtokens
 *
 * @category 
 * @package phalconskeleton
 * @author Tim Marshall <Tim@CodingBeard.com>
 * @copyright (c) 2015, Tim Marshall
 * @license New BSD License
 */

namespace CodingBeard;

use models\Authtokens;
use models\Logins;
use models\Users;
use Phalcon\Mvc\User\Component;
use Phalcon\Text;

class Auth extends Component
{

    /**
     * Whether the user is logged in
     * @var bool
     */
    public $loggedIn;

    /**
     * Database user id
     * @var int
     */
    public $user_id;

    /**
     * User model cache
     * @var Users
     */
    public $user = false;

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
        if (!$this->session->has('auth')) {
            $auth = [
                'loggedIn' => false,
                'tokenKey' => Text::random(Text::RANDOM_ALNUM, 20),
                'token' => Text::random(Text::RANDOM_ALNUM, 20),
            ];
            $this->session->set('auth', (object) $auth);
        }
        $this->initialize();
    }

    /**
     * Initialize this object with the values stored in session
     */
    public function initialize()
    {
        $auth = $this->session->get('auth');
        foreach ($auth as $key => $value) {
            $this->$key = $value;
        }
        if ($this->user_id) {
            $this->setRoles();
        }
    }

    /**
     * Redirect a user with an alert message
     * @param string $path
     * @param string $alertType
     * @param string $alertMessage
     */
    public function redirect($path, $alertType, $alertMessage)
    {
        $this->flashSession->message($alertType, $alertMessage);
        $this->response->redirect($path);
        $this->view->disable();
        return true;
    }

    /**
     * Throttle users with multiple failed logins
     * @param int $user_id
     * @return boolean to show a captcha or not
     */
    public function attemptThrolling($user_id)
    {
        $login = new Logins();
        $login->user_id = $user_id;
        $login->ip = $this->request->getClientAddress();
        $login->attempt = time();
        $login->success = 0;
        $login->save();

        $attempts = Logins::count([
            'ip = :a: AND attempt >= :b:',
            'bind' => [
                'a' => $this->request->getClientAddress(),
                'b' => (time() - 3600)
            ]
        ]);

        switch ($attempts) {
            case 1:
            case 2:
            case 3:
                break;
            case 4:
                sleep(2);
                break;
            default:
                sleep(4);
                break;
        }
    }

    /**
     * Whether the login form needs a captcha
     * @return boolean
     */
    public function loginCaptcha()
    {
        $attempts = Logins::count([
            'ip = :a: AND attempt >= :b: AND success = 0',
            'bind' => [
                'a' => $this->request->getClientAddress(),
                'b' => (time() - 3600)
            ]
        ]);

        switch ($attempts) {
            case 0:
            case 1:
                return false;
            default:
                return true;
        }
    }

    /**
     * Log the supplied user in
     * @param Users $user
     */
    public function logUserIn($user)
    {
        $failedAttempts = Logins::find([
            'user_id = :a: AND ip = :b: AND success = 0',
            'bind' => ['a' => $user->id, 'b' => $this->request->getClientAddress()]
        ]);
        if ($failedAttempts) {
            $failedAttempts->delete();
        }

        $login = new Logins();
        $login->user_id = $user->id;
        $login->ip = $this->request->getClientAddress();
        $login->attempt = time();
        $login->success = 1;
        $login->save();

        $auth = $this->session->get('auth');
        $auth->loggedIn = true;
        $auth->user_id = $user->id;
        if (!$auth->audit_id) {
            $auth->audit_id = $user->id;
        }
        $this->session->set('auth', $auth);
        $this->initialize();
        $this->setRoles();
    }

    /**
     * Log the user out
     */
    public function logUserOut()
    {
        $this->session->destroy();
        $this->redirect('', 'success', 'Logged out.');
    }

    /**
     * Gets user model if logged in
     * @return Users
     */
    public function getUser()
    {
        if ($this->user_id) {
            if (!$this->user) {
                $this->user = Users::findFirstById($this->user_id);
            }
            return $this->user;
        }
        return false;
    }

    /**
     * Update the roles from database
     */
    public function setRoles($force = false)
    {
        if (!is_array($this->roles) || $force) {
            foreach ($this->getUser()->roles as $role) {
                if ($role->id == 1)
                    $this->isAdmin = true;
                $roles[] = $role->name;
            }
            $this->roles = $roles;
        }
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
     * @return boolean
     */
    public function checkToken()
    {
        if ($this->request->hasPost($this->tokenKey)) {
            if ($this->request->getPost($this->tokenKey) == $this->token) {
                return true;
            }
        }
        return false;
    }

    /**
     * Add a remember me cookie to the user's browser
     */
    public function createAuthCookie()
    {
        $authtoken = Authtokens::newToken(['user_id' => $this->user_id, 'type' => 'cookie_RMT', 'expires' => 7, 'unique' => true]);
        $domain = $this->config->application->domain;
        $https = $this->config->application->https;
        $this->cookies->set("RMT", $authtoken->string, time() + 604800, '/', $https, $domain, true);
    }

    /**
     * Check the validity of a cookie token
     * @return boolean
     */
    public function checkAuthCookie()
    {
        if ($this->cookies->has("RMT")) {
            $token = $this->cookies->get("RMT")->getValue();
            if (($authtoken = Authtokens::checkToken('cookie_RMT', $token))) {
                return $authtoken->users;
            }
            else {
                $this->removeAuthCookie();
            }
        }
        return false;
    }

    /**
     * Delete cookie tokens
     */
    public function removeAuthCookie()
    {
        if ($this->cookies->has("RMT")) {
            $key = $this->cookies->get("RMK")->getValue();
            $tokens = Authtokens::find([
                'type = "cookie" AND expires > :a: AND user_id = :b:',
                'bind' => ['a' => date('Y-m-d H:i:s'), 'b' => $key]
            ]);
            foreach ($tokens as $token) {
                $token->expires = date('Y-m-d H:i:s');
                $token->save();
            }
            $domain = $this->config->application->domain;
            $https = $this->config->application->https;
            $this->cookies->set("RMT", null, time() - 3600, '/', $https, $domain, true);
        }
    }

}
