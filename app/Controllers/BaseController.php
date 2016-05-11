<?php
namespace App\Controllers;

use Bridge\Input;
use App\Module\Console\CliManager;
use App\Module\View\ViewHelper;

/**
 *
 */
class BaseController
{
    /**
     *
     */
    public function __construct()
    {
        $this->baseDiLoader();
    }

    /**
     *
     */
    public function __call($method, $controllerArgs)
    {
        global $app;    // Slim app
        global $argv;   // by command line

        if (!method_exists($this, $method)) {
            throw new \Exception("API method '{$method}' is not exist!");
            exit;
        }

        di('view')->init();

        if (isCli()) {
            CliManager::init($argv);
        }
        else {
            \SlimManager::init($app, $controllerArgs);
            Input::init($controllerArgs);
        }

        // 如果有回傳值, 則不往下執行
        $result = $this->init();
        if (null !== $result) {
            return $result;
        }

        //
        return $this->$method();
    }

    /**
     *  you can rewrite in extend
     */
    protected function init()
    {
        // 僅供 rewirt
    }

    /**
     *  di loader
     *  @see https://github.com/symfony/dependency-injection
     *  @see http://symfony.com/doc/current/components/dependency_injection/factories.html
     *  @see http://symfony.com/doc/current/components/dependency_injection/introduction.html
     */
    private function baseDiLoader()
    {
        $basePath = conf('app.path');
        $di = di();
        $di->setParameter('app.path', $basePath);

        /*
        $di->register('abc', 'Lib\Abc')
            ->addArgument('%app.path%');                    // __construct
            ->setProperty('setDb', [new Reference('db')]);  // ??
        */

        // session
        $di->register('session', 'Bridge\Session');
        $di->get('session')->init([
            'sessionPath' => conf('app.path') . '/var/session',
        ]);

        // log & log folder
        $di->register('log', 'Bridge\Log')
            ->addMethodCall('init', ['%app.path%/var']);

        // view
        $di->register('view', 'Bridge\View');


        // url
        $di->register('url', 'App\Module\Url\HomeUrlManager');
        $di->get('url')->init([
            'basePath'  =>  conf('app.path'),
            'baseUrl'   =>  conf('home.base.url'),
            'host'      =>  isCli() ? '' :  $_SERVER['HTTP_HOST'],
        ]);

        // queue
        // $di->register('queue', 'Bridge\Queue');

        // cache
        $di->register('cache', 'Bridge\Cache')
            ->addMethodCall('init', ['%app.path%/var/cache']);
    }

    /**
     *  render view
     */
    protected function render($templateDotName, $params=[])
    {
        $viewPath = conf('app.path') . '/resource/views';

        // default layout
        $layout = di('view')->getLayout();
        if (!$layout) {
            $layout = ViewHelper::get('_global.layout.public');
            di('view')->setLayout($layout);
        }

        $template = ViewHelper::get($templateDotName);
        if (!file_exists($template)) {

            // 如果找不到 template
            // 輸出的錯誤息訊中, 請提供 猜測的 template name
            $classItems         = explode('\\', get_class($this));
            $count              = count($classItems);
            $errorMessage       = 'Error: view not found!';
            $quessTemplateNames = [];

            for ($i = 0; $i < $count; $i++) {

                $name = $classItems[$i];

                if (0 == $i) {
                    if ('App' !== $name) {
                        break;
                    }
                    continue;
                }

                if (1 == $i) {
                    if ('Controllers' !== $name) {
                        break;
                    }
                    continue;
                }

                $quessTemplateNames[]
                    = strtolower(substr($name, 0, 1))
                    . substr($name, 1);

            }

            $backtraces = debug_backtrace();
            if (isset($backtraces[2], 
                      $backtraces[2]['args'],
                      $backtraces[2]['args'][0])) {
                $methodName = $backtraces[2]['args'][0];
                $quessTemplateNames[] = $methodName;
                $errorMessage = 'Error: view not found! Guess template name maybe is ===> ['. join('.', $quessTemplateNames) .']';
            }

            throw new \Exception($errorMessage);
            exit;
        }

        di('view')->render($template, $params);
    }

}
