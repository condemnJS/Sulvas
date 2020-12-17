<?php

namespace app\controllers;

use app\core\Application;
use app\core\Controller;
use app\core\Request;

class SiteController extends Controller
{
    public function home(){
        $params = [
            'name' => 'TheCode'
        ];
        return $this->render('home', $params);
    }
    public function handleContact(Request $request){
        $body = $request->getBody();
        var_dump($body);
    }
    public function contact(){
        return $this->render('contact');
    }
}