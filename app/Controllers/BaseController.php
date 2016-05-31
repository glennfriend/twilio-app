<?php
namespace App\Controllers;

use Bridge\Input;
use App\Utility\Console\CliManager;
use App\Utility\Identity\UserManager;
use App\Utility\Project\SlimManager;

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
        $this->baseLoader();
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

        if (isCli()) {
            CliManager::init($argv);
        }
        else {
            SlimManager::init($app, $controllerArgs);
            Input::init($controllerArgs);
        }

        // 如果有回傳值, 則不往下執行
        $result = $this->initBefore();
        if (null !== $result) {
            return $result;
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
     *  @see assignParamToView()
     */
    protected $_assignParams = [];

    /**
     *  在 controller 如果會有常用的 變數 要寫入 view
     *  可以在這裡設定, 不需要每次都在 render() 的時候才代入參數
     *  這裡代入的值, 會被呼叫 render() 相同名稱的變數所覆蓋
     *
     *  常會使用的參數性質:
     *      - 管理界面登入後, 該使用者的 User 資料
     *      - Page Limit 資料
     *
     */
    protected function assignParam($key, $value)
    {
        $this->_assignParams[$key] = $value;
    }

    /**
     *  僅供 extend controller rewrite
     *  最終端 Controller 請使用 init()
     */
    protected function initBefore()
    {
        // 僅供 extend controller rewrite
    }

    /**
     *  供給最終端 Controller 使用, 用來處理該 Controller 共用的流程
     */
    protected function init()
    {
        // 僅供 最終端 Controller rewirt
    }

    /**
     *  base loader
     */
    private function baseLoader()
    {
        // 在 controler 之中可以使用登入者的資料 $authUser 
        $this->authUser = UserManager::getUser();

        // 在 template 之中可以使用登入者的資料 $authUser 
        $this->assignParam('authUser', $this->authUser);
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
            Example:
                $di
                    ->register('example', 'Lib\Abc')
                    ->addArgument('%app.path%');                    // __construct
                    ->setProperty('setDb', [new Reference('db')]);  // ??
        */

        // view
        $viewConfig = [
            'view_path' => conf('app.path') . '/resource/views'
        ];
        $di->register('view', 'Bridge\View')
           ->addMethodCall('init', [$viewConfig]);

        // queue
        // $di->register('queue', 'Bridge\Queue');

        // home url manager
        $di->register('homeUrl', 'App\Utility\Url\HomeUrlManager');
        $di->get('homeUrl')->init([
            'basePath'  =>  conf('app.path'),
            'baseUrl'   =>  conf('home.base.url'),
            'host'      =>  isCli() ? '' :  $_SERVER['HTTP_HOST'],
        ]);

        // admin url manager
        $di->register('AdminUrl', 'App\Utility\Url\AdminUrlManager');
        $di->get('AdminUrl')->init([
            'basePath'  =>  conf('app.path'),
            'baseUrl'   =>  conf('admin.base.url'),
            'host'      =>  isCli() ? '' :  $_SERVER['HTTP_HOST'],
        ]);

    }

    /**
     *  render view
     */
    protected function render($templateDotName, $params=[])
    {
        $view = di('view');
        $template = $view->getPathFile($templateDotName);
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

        // 在 controler 所設定的值要寫入 view 當變數
        foreach ($this->_assignParams as $key => $value) {
            $view->assingViewParam($key, $value);
        }

        // render view
        echo $view->render($template, $params);
    }

}
