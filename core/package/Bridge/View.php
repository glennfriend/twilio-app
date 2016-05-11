<?php
namespace Bridge;

class View
{

    protected static $engine;

    /**
     *  request init
     */
    public static function init($conf=[])
    {
      //self::$engine = new Options\ViewTwig($conf);
        self::$engine = new Options\ViewNormal();
    }

    public static function getLayout()
    {
        return self::$engine->get('layout');
    }

    public static function setLayout($path)
    {
        return self::$engine->set('layout', $path);
    }

    public static function setRenderBeforeEvent(callable $callback)
    {
        return self::$engine->set('renderBeforeEvent', $callback);
    }

    public static function render($templateName, $params)
    {
        return self::$engine->render($templateName, $params);
    }

}
