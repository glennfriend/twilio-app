<?php

    $basePath = dirname(__DIR__);
    require_once $basePath . '/core/bootstrap.php';
    initialize($basePath);

    pr('Gearman version: '. gearman_version());

    $worker = di('queue')->factoryWorker();
    $worker->addFunction('sleep');
    $worker->run();

    function sleep_worker($job)
    {
        echo '1234123412341234';
        exit;
        //$message = date('y-m-d') . " - ip - ". $job->functionName() ." - ". $job->handle() . " - " . $job->workload();
        //pr($message);
        perform( unserialize( $job->workload()) );
    }

    /**
     *  執行的程式碼應該建立一個完整的 class 於 project 適當的位置
     *  以利於重覆使用
     */
    function perform($data)
    {
        //di('log')->write('gearman.worker.log', 'sleep start');
        echo "sleep 3\n";
        sleep(1);
        echo "sleep 2\n";
        sleep(1);
        echo "sleep 1\n";
        sleep(1);
        //di('log')->write('gearman.worker.log', 'sleep end');
    }
