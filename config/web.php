<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'GYa1XSzR2r-pg5PYH-RWPTpWQkxucW3L',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,

        'urlManager' => [
            'enableStrictParsing' => true,
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'client/post/index',
                '/panel' => 'content/index',
                '/content/publish' => 'content/get-publish', // Publish
                '/content/moder' => 'content/get-moder', // Moder
                '/content/reject' => 'content/get-reject', // Reject
                '/content/getContentById' => 'content/get-content-by-id', // get content by id
                '/content/delete' => 'content/delete',

                '/content/setPublish' => 'content/set-publish', // set Publish
                '/content/setModer' => 'content/set-moder', // set Moder
                '/content/setReject' => 'content/set-reject', // set Reject

                '/content/checkPublish' => 'content/check-publish', // check Publish
                '/content/checkModer' => 'content/check-moder', // check Moder
                '/content/checkReject' => 'content/check-reject', // check Reject
                '/content/checkDelete' => 'content/check-delete', // check Delete
                '/content/getStats' => 'content/get-stats', // check Reject

                '/content/instagram' => 'content/get-instagram-content',

                '/post/checkSession' => 'content/check-session', // check session
                '/post/getSession' => 'content/get-session', // check session

                '/post/publish' => 'content/create', // create post by client
                '/post/update' => 'content/update', // create post by client
                '/@<url:.*>' => 'client/post/index', // get post by url for client

                /* Шаблоны вывода авторизации */
                '/login' => 'users/login/index',
                '/reg' => 'users/registration/index',
                '/exit' => 'user/exit',
                /* API регистрации */
                'user/login' => 'user/login',
                'user/new' => 'user/registration',

                /** pic */
                '/content/img/upload' => 'file/upload',
                '/content/img/delete' => 'file/delete',
                //'<controller:\w+>/<action:\w+>/' => '<controller>/<action>',
                /** setting **/
                '/setting/setLimit' => 'setting/set-limit',
                '/setting/getLimit' => 'setting/get-limit',
            ],
        ],

    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
