<?php
/**
 * @package Tartan
 */
namespace Tartan;

/**
 * Class Mandrill
 * @package Tartan
 * @author  Aboozar Ghafari <me@tartan.pro>
 * @link    http://git.tartan.pro
 */
use Tartan\Mandrill\Exception,
    Phalcon\Mvc\User\Component;

class Mandrill extends Component
{
    const API_VERSION = '1.0';
    const END_POINT   = 'https://mandrillapp.com/api/';

    public $api;
    public $output;
    public $key;

    function __construct ($api = null)
    {
        if ($api) {
            $this->init($api);
            $this->key = $api;
        }
        return $this;
    }

    function init ($api)
    {
        if (empty($api)) throw new Exception('Invalid API key');
        try {

            $response = $this->request('users/ping2', ['key' => $api]);
            if (!isset($response['PING']) || $response['PING'] != 'PONG!') throw new Exception('Invalid API key');

            $this->api = $api;

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    static function getAttachmentStruct ($path)
    {

        $struct = [];

        try {

            if (!@is_file($path)) throw new Exception($path . ' is not a valid file.');

            $filename = basename($path);

            if (!function_exists('get_magic_quotes')) {
                function get_magic_quotes ()
                {
                    return false;
                }
            }
            if (!function_exists('set_magic_quotes')) {
                function set_magic_quotes ($value)
                {
                    return true;
                }
            }

            if (strnatcmp(phpversion(), '6') >= 0) {
                $magic_quotes = get_magic_quotes_runtime();
                //set_magic_quotes_runtime(0); /* Depricated */
                ini_set('magic_quotes_runtime', 0);
            }

            $file_buffer = file_get_contents($path);
            $file_buffer = chunk_split(base64_encode($file_buffer), 76, "\n");

            if (strnatcmp(phpversion(), '6') >= 0) set_magic_quotes_runtime($magic_quotes);

            if (strnatcmp(phpversion(), '5.3') >= 0) {
                $finfo     = finfo_open(FILEINFO_MIME_TYPE);
                $mime_type = finfo_file($finfo, $path);
            }
            else {
                $mime_type = mime_content_type($path);
            }

            if (!Mandrill::isValidContentType($mime_type))
                throw new Exception($mime_type . ' is not a valid content type (it should be ' . implode('*,', self::getValidContentTypes()) . ').');

            $struct['type']    = $mime_type;
            $struct['name']    = $filename;
            $struct['content'] = $file_buffer;

        } catch (Exception $e) {
            throw new Exception('Error creating the attachment structure: ' . $e->getMessage());
        }

        return $struct;
    }

    static function isValidContentType ($ct)
    {
        $valids = self::getValidContentTypes();

        foreach ($valids as $vct) {
            if (strpos($ct, $vct) !== false) return true;
        }

        return false;
    }

    static function getValidContentTypes ()
    {
        return [
            'image/',
            'text/',
            'application/pdf',
        ];
    }

    /**
     * Work horse. Every API call use this function to actually make the request to Mandrill's servers.
     *
     * @link https://mandrillapp.com/api/docs/
     *
     * @param string $method API method name
     * @param array $args    query arguments
     * @param string $http   GET or POST request type
     * @param string $output API response format (json,php,xml,yaml). json and xml are decoded into arrays automatically.
     *
     * @return array|string|Exception
     */
    function request ($method, $args = [], $http = 'POST', $output = 'json')
    {
        if (!isset($args['key']))
            $args['key'] = $this->api;

        $this->output = $output;

        $api_version = self::API_VERSION;
        $dot_output  = ('json' == $output) ? '' : ".{$output}";

        $url = self::END_POINT . "{$api_version}/{$method}{$dot_output}";

        switch ($http) {

            case 'GET':
                //some distribs change arg sep to &amp; by default
                $sep_changed = false;
                if (ini_get("arg_separator.output") != "&") {
                    $sep_changed = true;
                    $orig_sep    = ini_get("arg_separator.output");
                    ini_set("arg_separator.output", "&");
                }

                $url .= '?' . http_build_query($args);

                if ($sep_changed) {
                    ini_set("arg_separator.output", $orig_sep);
                }

                $response = $this->http_request($url, [], 'GET');
                break;

            case 'POST':
                $response = $this->http_request($url, $args, 'POST');
                break;

            default:
                throw new Exception('Unknown request type');
        }

        $response_code = $response['header']['http_code'];
        $body          = $response['body'];

        switch ($output) {

            case 'json':

                $body = json_decode($body, true);
                break;

            case 'php':

                $body = unserialize($body);
                break;
        }

        if (200 == $response_code) {

            return $body;
        }
        else {

            $message = isset($body['message']) ? $body['message'] : '';

            throw new Exception($message . ' - ' . $body, $response_code);
        }
    }

    function http_request ($url, $fields = [], $method = 'POST')
    {

        if (!in_array($method, ['POST', 'GET'])) $method = 'POST';
        if (!isset($fields['key'])) $fields['key'] = $this->api;

        //some distribs change arg sep to &amp; by default
        $sep_changed = false;
        if (ini_get("arg_separator.output") != "&") {
            $sep_changed = true;
            $orig_sep    = ini_get("arg_separator.output");
            ini_set("arg_separator.output", "&");
        }

        $fields = is_array($fields) ? http_build_query($fields) : $fields;

        if ($sep_changed) {
            ini_set("arg_separator.output", $orig_sep);
        }

        if (defined('WP_DEBUG') && WP_DEBUG !== false) {
            error_log("\nMandrill::http_request: URL: $url - Fields: $fields\n");
        }

        if (function_exists('curl_init') && function_exists('curl_exec')) {

            if (!ini_get('safe_mode')) {
                set_time_limit(2 * 60);
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);

            curl_setopt($ch, CURLOPT_POST, $method == 'POST');

            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Expect:']);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2 * 60 * 1000);

            $response = curl_exec($ch);
            $info     = curl_getinfo($ch);
            $error    = curl_error($ch);

            curl_close($ch);

        }
        elseif (function_exists('fsockopen')) {
            $parsed_url = parse_url($url);

            $host = $parsed_url['host'];
            if (isset($parsed_url['path'])) {
                $path = $parsed_url['path'];
            }
            else {
                $path = '/';
            }

            $params = '';
            if (isset($parsed_url['query'])) {
                $params = $parsed_url['query'] . '&' . $fields;
            }
            elseif (trim($fields) != '') {
                $params = $fields;
            }

            if (isset($parsed_url['port'])) {
                $port = $parsed_url['port'];
            }
            else {
                $port = ($parsed_url['scheme'] == 'https') ? 443 : 80;
            }

            $response = false;

            $errno  = '';
            $errstr = '';
            ob_start();
            $fp = fsockopen('ssl://' . $host, $port, $errno, $errstr, 5);

            if ($fp !== false) {
                stream_set_timeout($fp, 30);

                $payload = "$method $path HTTP/1.0\r\n" .
                    "Host: $host\r\n" .
                    "Connection: close\r\n" .
                    "Content-type: application/x-www-form-urlencoded\r\n" .
                    "Content-length: " . strlen($params) . "\r\n" .
                    "Connection: close\r\n\r\n" .
                    $params;
                fwrite($fp, $payload);
                stream_set_timeout($fp, 30);

                $info = stream_get_meta_data($fp);
                while ((!feof($fp)) && (!$info["timed_out"])) {
                    $response .= fread($fp, 4096);
                    $info = stream_get_meta_data($fp);
                }

                fclose($fp);
                ob_end_clean();

                list($headers, $response) = explode("\r\n\r\n", $response, 2);

                if (ini_get("magic_quotes_runtime")) $response = stripslashes($response);
                $info = ['http_code' => 200];
            }
            else {
                ob_end_clean();
                $info = ['http_code' => 500];
                throw new Exception($errstr, $errno);
            }
            $error = '';
        }
        else {
            throw new Exception("No valid HTTP transport found", -99);
        }

        return ['header' => $info, 'body' => $response, 'error' => $error];
    }

    /**
     * @link https://mandrillapp.com/api/docs/users.html#method=ping
     *
     * @return array|Exception
     */
    function users_ping ()
    {

        return $this->request('users/ping');
    }

    /**
     * @link https://mandrillapp.com/api/docs/users.html#method=info
     *
     * @return array|Exception
     */
    function users_info ()
    {

        return $this->request('users/info');
    }

    /**
     * @link https://mandrillapp.com/api/docs/users.html#method=senders
     *
     * @return array|Exception
     */
    function users_senders ()
    {

        return $this->request('users/senders');
    }

    /**
     * @link https://mandrillapp.com/api/docs/users.html#method=disable-sender
     *
     * @return array|Exception
     */
    function users_disable_sender ($domain)
    {

        return $this->request('users/disable-senders', ['domain' => $domain]);
    }

    /**
     * @link https://mandrillapp.com/api/docs/users.html#method=verify-sender
     *
     * @return array|Exception
     */
    function users_verify_sender ($email)
    {

        return $this->request('users/verify-senders', ['domain' => $email]);
    }

    /**
     * @link https://mandrillapp.com/api/docs/senders.html#method=domains
     *
     * @return array|Exception
     */
    function senders_domains ()
    {

        return $this->request('senders/domains');
    }

    /**
     * @link https://mandrillapp.com/api/docs/senders.html#method=list
     *
     * @return array|Exception
     */
    function senders_list ()
    {

        return $this->request('senders/list');
    }

    /**
     * @link https://mandrillapp.com/api/docs/senders.html#method=info
     *
     * @return array|Exception
     */
    function senders_info ($email)
    {

        return $this->request('senders/info', ['address' => $email]);
    }

    /**
     * @link https://mandrillapp.com/api/docs/senders.html#method=time-series
     *
     * @return array|Exception
     */
    function senders_time_series ($email)
    {

        return $this->request('senders/time-series', ['address' => $email]);
    }

    /**
     * @link https://mandrillapp.com/api/docs/tags.html#method=list
     *
     * @return array|Exception
     */
    function tags_list ()
    {

        return $this->request('tags/list');
    }

    /**
     * @link https://mandrillapp.com/api/docs/tags.html#method=info
     *
     * @return array|Exception
     */
    function tags_info ($tag)
    {

        return $this->request('tags/info', ['tag' => $tag]);
    }

    /**
     * @link https://mandrillapp.com/api/docs/tags.html#method=time-series
     *
     * @return array|Exception
     */
    function tags_time_series ($tag)
    {

        return $this->request('tags/time-series', ['tag' => $tag]);
    }

    /**
     * @link https://mandrillapp.com/api/docs/tags.html#method=all-time-series
     *
     * @return array|Exception
     */
    function tags_all_time_series ()
    {

        return $this->request('tags/all-time-series');
    }

    /**
     * @link https://mandrillapp.com/api/docs/templates.html#method=add
     *
     * @return array|Exception
     */
    function templates_add ($name, $code)
    {

        return $this->request('templates/add', ['name' => $name, 'code' => $code]);
    }

    /**
     * @link https://mandrillapp.com/api/docs/templates.html#method=update
     *
     * @return array|Exception
     */
    function templates_update ($name, $code)
    {

        return $this->request('templates/update', ['name' => $name, 'code' => $code]);
    }

    /**
     * @link https://mandrillapp.com/api/docs/templates.html#method=delete
     *
     * @return array|Exception
     */
    function templates_delete ($name)
    {

        return $this->request('templates/delete', ['name' => $name]);
    }

    /**
     * @link https://mandrillapp.com/api/docs/templates.html#method=info
     *
     * @return array|Exception
     */
    function templates_info ($name)
    {

        return $this->request('templates/info', ['name' => $name]);
    }

    /**
     * @link https://mandrillapp.com/api/docs/templates.html#method=list
     *
     * @return array|Exception
     */
    function templates_list ()
    {

        return $this->request('templates/list');
    }

    /**
     * @link https://mandrillapp.com/api/docs/templates.html#method=time-series
     *
     * @return array|Exception
     */
    function templates_time_series ($name)
    {

        return $this->request('templates/time-series', ['name' => $name]);
    }

    /**
     * @link https://mandrillapp.com/api/docs/urls.html#method=list
     *
     * @return array|Exception
     */
    function urls_list ()
    {

        return $this->request('urls/list');
    }

    /**
     * @link https://mandrillapp.com/api/docs/urls.html#method=time-series
     *
     * @return array|Exception
     */
    function urls_time_series ($name)
    {

        return $this->request('urls/time-series', ['name' => $name]);
    }

    /**
     * @link https://mandrillapp.com/api/docs/urls.html#method=search
     *
     * @return array|Exception
     */
    function urls_search ($q)
    {

        return $this->request('urls/search', ['q' => $q]);
    }

    /**
     * @link https://mandrillapp.com/api/docs/webhooks.html#method=add
     *
     * @return array|Exception
     */
    function webhooks_add ($url, $events)
    {

        return $this->request('webhooks/add', ['url' => $url, 'events' => $events]);
    }

    /**
     * @link https://mandrillapp.com/api/docs/webhooks.html#method=update
     *
     * @return array|Exception
     */
    function webhooks_update ($url, $events)
    {

        return $this->request('webhooks/update', ['url' => $url, 'events' => $events]);
    }

    /**
     * @link https://mandrillapp.com/api/docs/webhooks.html#method=delete
     *
     * @return array|Exception
     */
    function webhooks_delete ($id)
    {

        return $this->request('webhooks/delete', ['id' => $id]);
    }

    /**
     * @link https://mandrillapp.com/api/docs/webhooks.html#method=info
     *
     * @return array|Exception
     */
    function webhooks_info ($id)
    {

        return $this->request('webhooks/info', ['id' => $id]);
    }

    /**
     * @link https://mandrillapp.com/api/docs/webhooks.html#method=list
     *
     * @return array|Exception
     */
    function webhooks_list ()
    {
        return $this->request('webhooks/list');
    }

    /**
     * @link https://mandrillapp.com/api/docs/messages.html#method=search
     *
     * @return array|Exception
     */
    function messages_search ($query, $date_from = '', $date_to = '', $tags = [], $senders = [], $limit = 100)
    {
        return $this->request('messages/search', compact('query', 'date_from', 'date_to', 'tags', 'senders', 'limit'));
    }

    /**
     * @link https://mandrillapp.com/api/docs/messages.html#method=send
     *
     * @return array|Exception
     */
    function messages_send ($message)
    {
        return $this->request('messages/send', ['message' => $message]);
    }

    /**
     * @link https://mandrillapp.com/api/docs/messages.html#method=parse
     *
     * @return array|Exception
     */
    function messages_parse ($message)
    {
        return $this->request('messages/parse', ['key' => $this->key, 'raw_message' => $message]);
    }

    /**
     * @link https://mandrillapp.com/api/docs/messages.html#method=send-raw
     *
     * @return array|Exception
     */
    function messages_sendRaw ($message)
    {
        return $this->request('messages/send-raw', ['key' => $this->key, 'raw_message' => $message]);
    }

    /**
     * @link https://mandrillapp.com/api/docs/messages.html#method=send-template
     *
     * @return array|Exception
     */
    function messages_send_template ($template_name, $template_content, $message)
    {
        return $this->request('messages/send-template', compact('template_name', 'template_content', 'message'));
    }
}
