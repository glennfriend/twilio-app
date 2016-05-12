<?php
namespace App\Utility\Console;

class CliManager
{
    /**
     *
     */
    protected static $args = [];

    /**
     *  init
     *
     *  @see https://github.com/pwfisher/CommandLine.php
     */
    public static function init($arguments)
    {
        if ($arguments) {
            self::$args = CommandLine::parseArgs($arguments);
        }
    }

    /* --------------------------------------------------------------------------------

    -------------------------------------------------------------------------------- */

    /**
     *  php command.php hello
     *      -> get(0)
     *      // "hello"
     *
     *  php command.php --hi="hello world"
     *      -> get('hi')
     *      // "hello world"
     *
     */
    public static function get($key, $defaultValue=null)
    {
        if (isset(self::$args[$key])) {
            return self::$args[$key];
        }
        return $defaultValue;
    }

    /**
     *
     */
    public static function has($key)
    {
        if (isset(self::$args[$key])) {
            return true;
        }
        return false;
    }

}
