<?php

namespace app\core; // в composer.json прописан автозагрузка классов для "namespace"

class Router
{
    public Request $request;
    public Response $response;
    protected array $routes = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback){
        $this->routes['get'][$path] = $callback; // Добавляем в массив routes, новый элемент
    }
    public function post($path, $callback){
        $this->routes['post'][$path] = $callback;
    }
    public function resolve(){
        $path = $this->request->getPath(); // Получаем путь #('/users')
        $method = $this->request->method(); // Метод #('get')
        $callback = $this->routes[$method][$path] ?? false; // Проверяем если существует такой callback в нашем массиве routes
        if(!$callback){ // Если нет то роут не найден в будущем можно будет Редирект делать на Not Found
            // Application::$app === $this (Класса Application)v
            $this->response->setStatusCode(404);
            return $this->renderView('404');
        }
        // Иначе вызываем callback, который передан, поскольку имя функции нету, можно вызвать только так
        if(is_string($callback)){
            $className = explode('@', $callback);
            $class = dirname(__NAMESPACE__, 1)."\\controllers\\$className[0]"; // Путь до нужного мне класса
            if(count($className) < 2){ // Костыль: Если длина класса меньше 2
                return 'Incorrect Route';
            }
            Application::$app->controller = new $class();
            return call_user_func([Application::$app->controller, $className[1]], $this->request); // Первый параметр это экземпляр класса, вторым идет метод который нужно вызвать
        }
        return call_user_func($callback, $this->request); // Вызывает функцию, принимает саму фукцию или название функции
    }
    public function renderView($view, $params = []){
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view, $params);
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }
    protected function layoutContent(){
        $layout = Application::$app->controller->layout;
        var_dump($layout);
        ob_start();
        include_once Application::$ROOT_DIR."/views/layouts/$layout.php";
        return ob_get_clean();
    }
    protected function renderOnlyView($view, $params){
        foreach ($params as $key => $value){
            $$key = $value; // $$ - сделал доступ до ключа name, и теперь он доступен как переменная $name
        }
        ob_start();
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }
}