<?php

# контроллер для всех запросов ajax

class RequestsController extends Controller
{
    public function __construct($data = array())
    {
        parent::__construct(!$data);
        $this->model = new Request();
    }

    # поисковые теги в nav bar

    public function search_tags()
    {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->model->newSearchTags($_POST['search']);
        }
    }

    # получить количество всех читателей статьи
    public function all_readers()
    {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->model->allReaders($_POST['search'],$_POST['reading']);
        }
    }

    # добавить плюс ли минус к статье
    public function add_plus_minus()
    {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            if (array_key_exists("symbol",$_POST)) {
                if($_POST['symbol'] == "1") {
                    $this->model->addPlus($_POST['search']);
                } elseif ($_POST['symbol'] == "2") {
                    $this->model->addMinus($_POST['search']);
                }
            }
        }
    }

    # для добавления комментариев к новостям
    public function add_comments()
    {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            
        }
    }
    
    public function build_category() 
    {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->model->allCategories($_POST['search']);
        }
    }
    
    public function header_color() 
    {
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $this->model->getColor();
        }
    }
}
