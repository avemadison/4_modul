<?php
/**
 * need for request processing and to call controllers action /
 * protected static $router /
 * public static $db /
 */
# для обработки запросов и для вызова контроллеров действий. Protected static $router. Public static $ db

class App {

    # этот параметр является статическим, потому что нам нужен только один router
    protected static $router;

    # этот параметр является общедоступным, потому что всем нужен доступ к нем
    public static $db;

    # Обратный пример текущего Router

    public static function getRouter()
    {
        return self::$router;
    }

    # это действие предназначено для простого доступа к примеру класса Router
    public static function run($uri) 
    {
        self::$router = new Router($uri);
        self::$db = new DB(Config::get('db.host'), Config::get('db.user'), Config::get('db.password'), Config::get('db.name'));
        
        Lang::load(self::$router->getLanguage()); // loading language settings

        # ucfirst потому что имена контроллеров начинаются с заглавной буквы
        # example : NewsController
        $controller_class = ucfirst(self::$router->getController()) . 'Controller';
        
        # example : index
        $controller_method = strtolower(self::$router->getMethodPrefix() . self::$router->getAction());

        # result = '',admin,user,moderator
        $layout = self::$router->getRouter();
        if (($layout == 'admin' && Session::get('role') != 'admin') || ($layout == 'user' && Session::get('role') != 'user')) {
            if (($layout == 'admin' || $layout == 'user') && Session::get('role') != 'admin') {
                if ($controller_method != 'admin_login') {
                    Router::redirect('/users/login/');
                }
            }
            if ($layout == 'user' && (Session::get('role') != 'user' || Session::get('role') != 'admin')) {
                if ($controller_method != 'user_login') {
                    Router::redirect('/users/login/');
                }
            }
        }

        # теперь у нас есть имя класса контроллера и имя метода
        # мы создадим экземпляр контроллера
        $controller_object = new $controller_class();
        
        # если метод существует
        if (method_exists($controller_object,$controller_method)) {
            
            # действие контроллера может возвращать путь просмотра
            $view_path = $controller_object->$controller_method();
            $view_object = new View($controller_object->getData(), $view_path);
            $content = $view_object->render();
            // after this we have content from action like
            // controller -> action data from this action was render
            // by controller - view and included
        } else {
            throw new Exception ('Method ' .  $controller_method . ' in ' . $controller_class . ' doesn\'t exist');
        }

        $layout_path = VIEWS_PATH.DS.$layout.'.html';
        
        # compact создает массив из переменных
        $layout_view_object = new View(compact('content'),$layout_path);
        echo $layout_view_object->render();
    }
}