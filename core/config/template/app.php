<?php

$path = '/var/www/twilio-app';

/**
 *  app config
 *  example:
 *      conf('app.env');
 *
 */
return [

    /**
     *  Environment
     *
     *      training    - 開發者環境
     *      production  - 正式環境
     */
    'env' => 'production',

    /**
     *  project info
     */
    'project' => [
        'name' => 'Twilio Tool',
    ],

    /**
     *  app path
     */
    'path' => $path,

    /**
     *  timezone
     *
     *      +0 => UTC
     *      -7 => America/Los_Angeles
     *      +8 => Asia/Taipei
     */
    'timezone' => 'America/Los_Angeles',

];
