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
        $this->routes['get'][$path] = $callback; // Добавляем в массив routes, новый элемент
    }
    public function resolve(){
        $path = $this->request->getPath(); // Получаем путь #('/users')
        $method = $this->request->getMethod(); // Метод #('get')
        $callback = $this->routes[$method][$path] ?? false; // Проверяем если существует такой callback в нашем массиве routes
        if(!$callback){ // Если нет то роут не найден в будущем можно будет Редирект делать на Not Found
            return 'Not Found';
        }
        // Иначе вызываем callback, который передан, поскольку имя функции нету, можно вызвать только так
        if(is_string($callback)){
            return $this->renderView($callback);
        }
        return call_user_func($callback); // Вызывает функцию, принимает саму фукцию или название функции
    }
    public function renderView($view){
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view);
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }
    protected function layoutContent(){
        ob_start();
        include_once Application::$ROOT_DIR."/views/layouts/main.php";
        return ob_get_clean();
    }
    protected function renderOnlyView($view){
        ob_start();
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }
}