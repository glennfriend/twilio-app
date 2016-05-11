<?php

function initialize($basePath)
{
    // --------------------------------------------------------------------------------
    //  start
    // --------------------------------------------------------------------------------

    error_reporting(-1);
    ini_set('html_errors','Off');
    ini_set('display_errors','Off');

    /**
     *  load helper function
     */
    include ('helper.php');

    /**
     *  load composer
     */
    $loadComposer = function($basePath)
    {
        $autoload = $basePath . '/composer/vendor/autoload.php';
        if (!file_exists($autoload)) {
            die('Lose your composer!');
        }
        require_once ($autoload);

        // custom extend load
        $loader = new Composer\Autoload\ClassLoader();
        $loader->addPsr4('Bridge\\',    "{$basePath}/core/package/Bridge/");
        $loader->addPsr4('Ydin\\',      "{$basePath}/core/package/Ydin/");
        $loader->addPsr4('App\\',       "{$basePath}/app/");
        $loader->register();
    };
    $loadComposer($basePath);

    // init config
    ConfigManager::init( $basePath . '/core/config');
    if ( conf('app.path') !== $basePath ) {
       show('base path setting error!');
       exit;
    }

    if (isTraining()) {
        error_reporting(E_ALL);
        ini_set('html_errors','On');
        ini_set('display_errors','On');
    }

    if (isCli()) {
        ini_set('html_errors','Off');
        ini_set('display_errors','Off');
    }

    date_default_timezone_set(conf('app.timezone'));

    // --------------------------------------------------------------------------------
    //  vlidate
    // --------------------------------------------------------------------------------

    if ( phpversion() < '5.5' ) {
        show("PHP Version need >= 5.5");
        exit;
    }

}
