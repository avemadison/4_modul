<?php
# Здесь у нас есть действия для тегов. Задача контроллера состоит в формировании данных, которые он отправит для просмотра, спользует модель тегов

class TagsController extends Controller {

    # конструктор NewsController

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new Tag();
    }


    # дефолтный экшн для представления всех тегов

    public function index()
    {

        $this->data['tags'] = $this->model->getList();

        # реклама
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }

    # просмотр всех новостей за 1 тег

    public function view()
    {
        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $id = strtolower($params[0]);
            $this->data['tag'] = $this->model->getTagName($id);
            $this->data['tag_news'] = $this->model->getNewsByTagId($id);
        }

        # массив для пагинации дерева


        $count_rows  = 0;
        $this->data['pagination_news'] = array();
        foreach($this->data['tag_news'] as $news) {
            $this->data['pagination_tags'][$count_rows/5][] = $news;
            $count_rows++;
        }

       # разбивка на страницы
        $this->data['current_tag'] = (isset($params[0])) ? $params[0] : 0;
        $this->data['current_pag'] = (isset($params[1])) ? $params[1] : 0;
        $this->data['last_pag'] = (int)(count($this->data['tag_news'])/5)-1;

        # для рекламы
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }


    # !!!Экшены для админов!!!

 # admin index для всех тегов
    public function admin_index()
    {
        $this->data['tags'] = $this->model->getList();

        if ($_POST) {
            $this->model->createTag($_POST['tag_name']);
        }
    }

    # представление всех новостей за 1 тег

    public function admin_view()
    {
        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $id = strtolower($params[0]);
            $this->data['tag'] = $this->model->getTagName($id);
            $this->data['tag_news'] = $this->model->getNewsByTagId($id);
        }

        # массив для пагинации дерева

        $count_rows  = 0;
        $this->data['pagination_news'] = array();
        foreach($this->data['tag_news'] as $news) {
            $this->data['pagination_tags'][$count_rows/5][] = $news;
            $count_rows++;
        }

        # для разбивки на страницы
        $this->data['current_tag'] = (isset($params[0])) ? $params[0] : 0;
        $this->data['current_pag'] = (isset($params[1])) ? $params[1] : 0;
        $this->data['last_pag'] = (int)(count($this->data['tag_news'])/5)-1;
    }

    /**
     * TODO create this action
     */
    public function admin_add()
    {
    }


    # экшены для залогиненых юзеров

   # дефолт для всех тегов
    public function user_index()
    {

        $this->data['tags'] = $this->model->getList(true);

        # реклама
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }

    # все новости за один тег
    public function user_view()
    {
        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $id = strtolower($params[0]);
            $this->data['tag'] = $this->model->getTagName($id);
            $this->data['tag_news'] = $this->model->getNewsByTagId($id);
        }

        # массив для пагинации дерева
        $count_rows  = 0;
        $this->data['pagination_news'] = array();
        foreach($this->data['tag_news'] as $news) {
            $this->data['pagination_tags'][$count_rows/5][] = $news;
            $count_rows++;
        }

        # разбивка на страницы
        $this->data['current_tag'] = (isset($params[0])) ? $params[0] : 0;
        $this->data['current_pag'] = (isset($params[1])) ? $params[1] : 0;
        $this->data['last_pag'] = (int)(count($this->data['tag_news'])/5)-1;


        # реклама
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }

    # экшены для админа

    public function moderator_index()
    {
        // TODO same as index()
    }

}