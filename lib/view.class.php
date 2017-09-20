<?php
# Класс witch получает данные и возвращает html-код
class View {

    # data from controller
    protected $data;

    #path to view file
    protected $path;

    /**
     * view constructor
     * @param array $data
     * @param int $path
     * @throws Exception
     */
    public function __construct($data = array() , $path = 0)
    {
        if (!$path) {
            //$path = default path ...
            $path = self::getDefaultViewPath();
        }
        if (!file_exists($path)) {
            throw new Exception('Template file is not found in path: ' . $path);
        }

        $this->data = $data;
        $this->path = $path;
    }

    /**
     * set default view
     * @return bool|string
     */
    protected static function getDefaultViewPath()
    {
        $router = App::getRouter();

        if (!$router) {
            return false;
        }

        # get address of the view file
        $controller_name_dir = $router->getController();
        $template_name = $router->getMethodPrefix().$router->getAction().'.html';
        return VIEWS_PATH.DS.$controller_name_dir.DS.$template_name;
    }

    /**
     * @return string 
     * return content
     */
    public function render(){
        // this variable will be available in in a template
        $data = $this->data;

        // buffering start,
        ob_start();
        include_once VIEWS_PATH.DS."parts".DS."header.html";
        include_once VIEWS_PATH.DS."parts".DS."flash_message.html";
        include($this->path);
        
        $content = ob_get_clean();
        return $content;
    }
}