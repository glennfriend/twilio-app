<?php
namespace App\Utility\Url;

/**
 *
 */
class TwilioWrap
{
    /**
     *  @return array or throw error
     */
    public static function get($url)
    {
        // validate input url
        if (!preg_match('/^[a-zA-Z0-9\-\_\/\&\?\.]+$/', $url)) {
            throw new \Exception('TwilioWrap Error: url error');
            exit;
        }

        if (false !== stristr($url, '..')) {
            throw new \Exception('TwilioWrap Error: url error (2)');
            exit;
        }

        $url    = trim($url);
        $auth   = conf('twilio.sid') . ':' . conf('twilio.token');
        $error  = null;
        exec("curl -G 'https://api.twilio.com/{$url}' -u {$auth} 2> /dev/null", $output);

        if (!is_array($output)) {
            $error = 'error-1';
        }

        if (1!==count($output)) {
            $error = 'error-2';
        }

        $result = json_decode($output[0], true);
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
            break;

            case JSON_ERROR_DEPTH:
                $error = 'Maximum stack depth exceeded';
            break;
            case JSON_ERROR_STATE_MISMATCH:
                $error = 'Underflow or the modes mismatch';
            break;
            case JSON_ERROR_CTRL_CHAR:
                $error = 'Unexpected control character found';
            break;
            case JSON_ERROR_SYNTAX:
                $error = 'Syntax error, malformed JSON';
            break;
            case JSON_ERROR_UTF8:
                $error = 'Malformed UTF-8 characters, possibly incorrectly encoded';
            break;
        }

        if ($error) {
            throw new Exception('TwilioWrap Error: '. $error);
            exit;
        }

        return $result;
    }

}
