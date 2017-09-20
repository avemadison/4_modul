<?php
# основной класс для все контроллеров

class Controller {
    # Все данные, которые мы передаем от контроллера к представлению
    protected $data;

    # Для доступа к объекту модели
    protected $model;

    # Параметры из строки запроса
    protected $params;

    # Конструктор контроллера
    public function __construct($data = array()) 
    {
        $this->data = $data;
        $this->params = App::getRouter()->getParams();
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }
}