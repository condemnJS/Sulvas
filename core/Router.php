<?php

namespace app\core; // в composer.json прописан автозагрузка классов для "namespace"

class Router
{
    public Request $request;
    protected array $routes = [];

    public function __construct(\app\core\Request $request)
    {
        $this->request = $request;
    }

    public function get($path, $callback){
        $this->routes['get'][$path] = $callback;
    }
    public function resolve(){
        $this->request->getPath();
    }
}