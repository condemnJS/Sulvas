<?php

require_once __DIR__ . '/../vendor/autoload.php';

use app\controllers\SiteController;
use app\core\Application;

// dirname() - Возвращает имя родительского каталога,
// вторым параметром будет на сколько уровней надо подняться вверх по дирокториям
$app = new Application(dirname(__DIR__));

$app->router->get('/', 'SiteController@home');
$app->router->get('/contact',  'SiteController@contact');

$app->router->post('/contact', [app\controllers\SiteController::class, 'handleContact']);

$app->run();