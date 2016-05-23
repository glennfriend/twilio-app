<?php
use App\Utility\Output\ErrorSupportHelper;

/**
 *  建立一個 framework 基本的封裝
 */
class ProjectHelper
{
    /**
     *  build slim app
     */
    public static function buildApp()
    {
        $container = self::buildDefaultJsonContainer();
        $app = new Slim\App($container);
        return $app;
    }

    /**
     *  build slim container config
     */
    public static function buildDefaultJsonContainer()
    {
        $container = new Slim\Container();

        if (isTraining()) {
            $container['settings']['displayErrorDetails'] = true;
        }

        // Override the default Not Found Handler
        $container['notFoundHandler'] = function ($c) {
            return function ($request, $response) use ($c) {

                $error = ErrorSupportHelper::getJson('p404');
                return $c['response']
                    ->withStatus(404)
                    ->withHeader('Content-Type', 'application/json')
                    ->write($error);

            };
        };

        self::productionErrorHandler($container);

        return $container;

    }

    /**
     *  讓上線的程式可以查詢錯誤訊息
     */
    private static function productionErrorHandler($container)
    {
        if (isTraining()) {
            return;
        }

        $container['errorHandler'] = function ($c) {
            return function ($request, $response, $exception) use ($c) {

                $now = date("Y-m-d H:i:d") . ' (' . date_default_timezone_get() . ')';
                $errorContent = <<<"EOD"
Message: {$exception->getMessage()}
File___: {$exception->getFile()}
Line___: {$exception->getLine()}
At_____: {$now}
Trace__:
{$exception->getTraceAsString()}
EOD;

                $errorReportId = di('log')->systemErrorReport($errorContent);

                return $c
                    ->get('response')
                    ->withStatus(500)
                    ->withHeader('Content-Type', 'text/html')
                    ->write("Error report {$errorReportId}");

            };
        };
    }

}
