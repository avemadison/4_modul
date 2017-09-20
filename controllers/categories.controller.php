<?php
# страница категорий

Class CategoriesController extends Controller {

    # конструтор для CategoriesController

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new Category();
    }

    # функция для построения дерева с категориями

    public function build_tree($id_parent, $level){
        # если существуют категории с таким id_parent
        if (isset($this->data['cat'][$id_parent])) {
            foreach ($this->data['cat'][$id_parent] as $value) { # проверяем
                
                # пишем категории
                # $level * 25 = margin, $level содержит текущий уровень (0,1,2...)

                switch (App::getRouter()->getMethodPrefix()) {
                    case 'admin_':
                        $this->data['tree'] .= "<div style='margin-left:".($level*25)."px;'><h3>
                                            <a href='/admin/categories/view/". $value['id'] . "'>" . $value['name'] . "</a></h3></div>" . PHP_EOL;
                        break;
                    case 'user_':
                        $this->data['tree'] .= "<div style='margin-left:".($level*25)."px;'><h3>
                                            <a href='/user/categories/view/". $value['id'] . "'>" . $value['name'] . "</a></h3></div>" . PHP_EOL;
                        break;
                    case '':
                        $this->data['tree'] .= "<div style='margin-left:".($level*25)."px;'><h3>
                                            <a href='/categories/view/". $value['id'] . "'>" . $value['name'] . "</a></h3></div>" . PHP_EOL;
                        break;
                    default:
                        break;
                }
                
                $level++;
                $this->build_tree($value['id'],$level);
                $level--;
            }
        }
    }

    # дефолт экшен для категорий контроллера и для страниц категории
    public function index()
    {

        $this->data['cat'] = $this->model->getCategoryTree();
        $this->data['tree'] = "";
        $this->build_tree(0,0);


        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }

    # создаю view для просмотра категорий
    public function view()
    {
        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $id = strtolower($params[0]);
            $this->data['category'] = $this->model->getCategoryName($id);
            $this->data['category_news'] = $this->model->getNewsByCategoryId($id);
        }

        # создаю массив для пагинации дерева

        $count_rows  = 0;
        $this->data['pagination_news'] = array();
        foreach($this->data['category_news'] as $news) {
            $this->data['pagination_news'][$count_rows/5][] = $news;
            $count_rows++;
        }
        
        # data для разбивки на страницы

        $this->data['current_category'] = (isset($params[0])) ? $params[0] : 0;
        $this->data['current_pag'] = (isset($params[1])) ? $params[1] : 0;
        $this->data['last_pag'] = (int)(count($this->data['category_news'])/5)-1;

        // data для рекламы
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }

    
    # !!!! экшены для админов !!!!

    public function admin_index()
    {

        $this->data['cat'] = $this->model->getCategoryTree();
        $this->data['tree'] = "";
        $this->build_tree(0,0);

        if ($_POST) {

            if ($this->model->createCategory($_POST['id_parent'],$_POST['category_name'])) {
                Router::redirect('/admin/categories/');
            }
        }
        $this->data['parents-category'] = $this->model->getParentsCategories();

    }


     # показываем 1 категорию
     # params может устанавливать идентификатор категории и идентификатор страницы для разбивки на страницы

    public function admin_view()
    {
        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $id = strtolower($params[0]);
            $this->data['category'] = $this->model->getCategoryName($id);
            $this->data['category_news'] = $this->model->getNewsByCategoryId($id);
        }

          # массив для пагинации дерева

        $count_rows  = 0;
        $this->data['pagination_news'] = array();
        foreach($this->data['category_news'] as $news) {
            $this->data['pagination_news'][$count_rows/5][] = $news;
            $count_rows++;
        }

        # для разбивки на страницы
        $this->data['current_category'] = (isset($params[0])) ? $params[0] : 0;
        $this->data['current_pag'] = (isset($params[1])) ? $params[1] : 0;
        $this->data['last_pag'] = (int)(count($this->data['category_news'])/5)-1;
    }


    # !!!! экшены для залогиненых юзеров !!!!

    public function user_index()
    {

        $this->data['cat'] = $this->model->getCategoryTree();
        $this->data['tree'] = "";
        $this->build_tree(0,0);


        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }


    public function user_view()
    {
        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $id = strtolower($params[0]);
            $this->data['category'] = $this->model->getCategoryName($id);
            $this->data['category_news'] = $this->model->getNewsByCategoryId($id);
        }


         # массив для пагинации дерева

        $count_rows  = 0;
        $this->data['pagination_news'] = array();
        foreach($this->data['category_news'] as $news) {
            $this->data['pagination_news'][$count_rows/5][] = $news;
            $count_rows++;
        }

        # данные для разбивки страницы

        $this->data['current_category'] = (isset($params[0])) ? $params[0] : 0;
        $this->data['current_pag'] = (isset($params[1])) ? $params[1] : 0;
        $this->data['last_pag'] = (int)(count($this->data['category_news'])/5)-1;

        # данные для рекламы

        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }

    
   # !!!! экшены для модераторов !!!!

    # данные для просмотра категории модератора

    public function moderator_index()
    {
        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $id = strtolower($params[0]);
            $this->data['category'] = $this->model->getCategoryName($id);
            $this->data['category_news'] = $this->model->getNewsByCategoryId($id);
        }

        # массив для пагинации дерева

        $count_rows  = 0;
        $this->data['pagination_news'] = array();
        foreach($this->data['category_news'] as $news) {
            $this->data['pagination_news'][$count_rows/5][] = $news;
            $count_rows++;
        }

        # данные для разбивки страницы

        $this->data['current_category'] = (isset($params[0])) ? $params[0] : 0;
        $this->data['current_pag'] = (isset($params[1])) ? $params[1] : 0;
        $this->data['last_pag'] = (int)(count($this->data['category_news'])/5);

        # данные для рекламы
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }
    
    public function admin_political()
    {
        if ($_POST) {
            $this->model->editComment($_POST);
        }
        $this->data['all_comments'] = $this->model->getPoliticComments();
    }
    
    
}
