<?php
# базовый класс для моделей
class Model {
    
    protected $db;

    public function __construct()
    {
        $this->db = App::$db;
    }
}