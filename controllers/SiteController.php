<?php

namespace app\controllers;

use app\core\Application;

class SiteController
{
    public function home(){
//        $params = [
//            'name' => 'TheCode'
//        ];
        return Application::$app->router->renderView('home');
    }
    public function handleContact(){
        return 'Hadnling submitted data';
    }
    public function contact(){
        return Application::$app->router->renderView('contact');
    }
}