<?php

use App\Utility\Config\Config;

function initialize($basePath)
{
    // --------------------------------------------------------------------------------
    //  start
    // --------------------------------------------------------------------------------

    error_reporting(-1);
    ini_set('html_errors','Off');
    ini_set('display_errors', 'Off');

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
    $errorMessage = Config::init(
        $basePath . '/core/config',
        $basePath . '/config.php'
    );
    if ($errorMessage) {
       show('Config Eerror: '. $errorMessage);
       exit;
    }

    if (conf('app.path') !== $basePath) {
       show('base path setting error!');
       exit;
    }

    if (isTraining()) {
        error_reporting(E_ALL);
        ini_set('html_errors', 'On');
        ini_set('display_errors', 'On');
    }

    if (isCli()) {
        ini_set('html_errors', 'Off');
        ini_set('display_errors', 'Off');
    }

    date_default_timezone_set(conf('app.timezone'));

    // --------------------------------------------------------------------------------
    //  load base DI infromation
    // --------------------------------------------------------------------------------
    /**
     *  init session
     */
    $initSession = function($basePath)
    {
        $di = di();
        $di->setParameter('app.path', $basePath);

        // session
        $di->register('session', 'Bridge\Session');
        $isExpire = $di->get('session')->init([
            'sessionPath' => conf('app.path') . '/var/session',
        ]);

        return $isExpire;
    };
    $isExpire = $initSession($basePath);

    // session 過期, 重新導向
    if ($isExpire) {
        $redirectUrl = $_SERVER['REQUEST_URI'];
        echo '<meta http-equiv="refresh" content="3; url='. $redirectUrl .'" />';
        echo 'Already Expired ...';
        exit;
    }

    /**
     *  load resorce
     */
    $loadResource = function($basePath)
    {
        $di = di();
        $di->setParameter('app.path', $basePath);

        // log & log folder
        $di->register('log', 'Bridge\Log')
           ->addMethodCall('init', ['%app.path%/var']);

        // cache
        $di->register('cache', 'Bridge\Cache')
            ->addMethodCall('init', ['%app.path%/var/cache']);

    };
    $loadResource($basePath);

    // --------------------------------------------------------------------------------
    //  vlidate
    // --------------------------------------------------------------------------------

    if ( phpversion() < '5.5' ) {
        show("PHP Version need >= 5.5");
        exit;
    }

}
