<?php
/**
 * отвечает за парсинг запросов, его задача получить контроллер метод url и другие его части
 * protected $uri
 * protected $controller
 * protected $action
 * protected $params
 * protected $router
 * protected $method_prefix
 * protected $language
 */
class Router {

    protected $uri;

    protected $controller;

    protected $action;

    # Массив параметров
    protected $params;

    protected $router;

    protected $method_prefix;

    protected $language;

    /**
     * на входе он получает uri который попадает перебросом в index.php /
     * @param $uri
     */
    public function __construct($uri) 
    {
        # trim нужен для обрезания / в конце и в начале uri
        # urldecode нужен для правильной обработки закодированных символов
        $this->uri = urldecode(trim($uri,'/'));

        $routes = Config::get('routes');

        # Получаем значения по-умолчанию
        $this->router        = Config::get('default_router');
        $this->method_prefix = Config::get('default_prefix');
        $this->language      = Config::get('default_language');
        $this->controller    = Config::get('default_controller');
        $this->action        = Config::get('default_action');

        # Сохраняем переданные методом get параметры
        $uri_parts = explode('?', $this->uri);

        # Get path like /lng/controller/action/param1/param2/ .. / .. / ..
        $path = $uri_parts[0];
        $path_parts = explode('/',$path);

        if (count($path_parts)) {

            // Get route or language at first element
            if (in_array( strtolower(current($path_parts)) , array_keys($routes))) {

                // Если задан route то перезаписываем значения по умолчанию route и приставку для всех методов
                $this->router = strtolower(current($path_parts));
                $this->method_prefix = isset($routes[$this->router]) ? $routes[$this->router] : '';
                array_shift($path_parts);
            } elseif (in_array( strtolower(current($path_parts)) , Config::get('languages'))) {

                $this->language = strtolower(current($path_parts));
                array_shift($path_parts);
            }

            // Get next element это может быть только controller
            if (current($path_parts)) {
                $this->controller = strtolower(current($path_parts));
                array_shift($path_parts);
            }

            // Get next element это может быть только action
            if (current($path_parts)) {
                $this->action = strtolower(current($path_parts));
                array_shift($path_parts);
            }
            
            // Если еще остались элементы в массиве path_parts то могут быть только параметры
            $this->params = $path_parts;
        }
    }

    /**
     * @return mixed 
     */
    public function getUri() 
    {
        return $this->uri;
    }

    /**
     * @return mixed
     */
    public function getController() 
    {
        return $this->controller;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @return mixed
     */
    public function getParams() 
    {
        return $this->params;
    }

    /**
     * @return mixed 
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @return mixed
     */
    public function getMethodPrefix()
    {
        return $this->method_prefix;
    }

    /**
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * simple redirection to location /
     * @param $location 
     */
    public static function redirect($location)
    {
        header("Location: $location");
    }
}