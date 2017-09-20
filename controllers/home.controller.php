<?php

# для домашней страницы

class HomeController extends Controller {

     # конструктор MainController

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new Main();
    }

   # функция для построения дерева с категориями и новостями для них

    public function build_tree($id_parent, $level){
        # если существуют категории с таким id_paren
        if (isset($this->data['cat'][$id_parent])) {
            foreach ($this->data['cat'][$id_parent] as $value) { // check it

                # пишем категории


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

                # пишем одну новость с линком /news/view/id

                switch (App::getRouter()->getMethodPrefix()) {
                    case 'admin_':
                        # поиск новостей в этой категории
                        foreach ($this->data['tree_news'] as $one_news) {
                            if($one_news['id_category'] == $value['id']) {
                                $this->data['tree'] .= "<div style='margin-left:".($level*25)."px;'>
                                                    <a href='/admin/news/view/" . $one_news['id'] . "'>" . $one_news['title'] . "</a></div>";
                            }
                        }
                        break;
                    case 'user_':
                       # поиск новостей в этой категории
                        foreach ($this->data['tree_news'] as $one_news) {
                            if($one_news['id_category'] == $value['id']) {
                                $this->data['tree'] .= "<div style='margin-left:".($level*25)."px;'>
                                                    <a href='/user/news/view/" . $one_news['id'] . "'>" . $one_news['title'] . "</a></div>";
                            }
                        }
                        break;
                    case '':
                       # поиск новостей в этой категории
                        foreach ($this->data['tree_news'] as $one_news) {
                            if($one_news['id_category'] == $value['id']) {
                                $this->data['tree'] .= "<div style='margin-left:".($level*25)."px;'>
                                                    <a href='/news/view/" . $one_news['id'] . "'>" . $one_news['title'] . "</a></div>";
                            }
                        }
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
    
    
    # !!!действия для всех посетителей!!!
    
    # дефолтные экшены для домашнего контроллера и для домашней страницы

    public function index()
    {
        # data for OwlCarousel
        $this->data['carousel'] = $this->model->getCarouselData();
        
        # data for top 5 user with biggest amount of comments
        $this->data['users'] = $this->model->getUsersLogins(); 
        
        # data for top 3 topics with the most comments for previous day
        $this->data['topics'] = $this->model->getTopThreeTopics();
        
        # data for building categories tree TODONE fill data
        $this->data['cat'] = $this->model->getCategoryTree();
        $this->data['tree_news'] = $this->model->getNewsByCategory();
        $this->data['tree'] = "";
        $this->build_tree(0,0);

        # заголовки новостей данных для дерева категорий
        $this->data['news'] = $this->model->getNewsByCategory();

        # реклама
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }

    
    # экшены для админов


    # домашняя страница для админа

    public function admin_index()
    {
        // data for OwlCarousel
        $this->data['carousel'] = $this->model->getCarouselData();

        // data for top 5 user with biggest amount of comments
        $this->data['users'] = $this->model->getUsersLogins();

        // data for top 3 topics with the most comments for previous day
        $this->data['topics'] = $this->model->getTopThreeTopics();

        // data for building categories tree TODONE fill data
        $this->data['cat'] = $this->model->getCategoryTree();
        $this->data['tree_news'] = $this->model->getNewsByCategory();
        $this->data['tree'] = "";
        $this->build_tree(0,0);

        // data news titles for categories tree
        $this->data['news'] = $this->model->getNewsByCategory();

        // data for advertising
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }


     # для добавления bgi, adv blocks, color of nav

    public function admin_add()
    {
        if(isset($_POST['firm'])) {
            $this->model->saveAdvBlock($_POST);

            $last_adv = $this->model->getAdvBlockId(); # имя папки
            $id = $last_adv[0]['id'];
            mkdir(ROOT.DS."webroot".DS."uploads".DS."adv".$id); # создание папки

            $dir_name = ROOT.DS."webroot".DS."uploads".DS."adv".$id.DS;

            if ($_FILES) {
                foreach ($_FILES['image']['error'] as $key => $error) {

                    if ($error == UPLOAD_ERR_OK) {

                        $temp_file_name = $_FILES['image']['tmp_name'][$key];
                        $file_name = $dir_name . "adv".$id.".jpg";
                        move_uploaded_file($temp_file_name, $file_name);
                    }
                }
            }
        }
        if(isset($_POST['bgi'])) {
            $dir_name = ROOT . DS . "webroot" . DS . "uploads" . DS . "bgi" . DS;
            $files = glob($dir_name."*");
            if (count($files) > 0) {
                foreach ($files as $file) {
                    if (file_exists($file)) {
                        unlink($file);
                    }
                }
            }
            if ($_FILES) {
                if ($_FILES['image']['error'] == UPLOAD_ERR_OK) {
                    $temp_file_name = $_FILES['image']['tmp_name'];
                    $file_name = $dir_name . "Background.jpg";
                    move_uploaded_file($temp_file_name, $file_name);
                }

            }

        }
        if(isset($_POST['color'])) {
            $this->model->setColor($_POST['color']);
        }
    }

    
    # для залогиненых юзеров

    # домашння страница

    public function user_index()
    {
        # data for OwlCarousel
        $this->data['carousel'] = $this->model->getCarouselData();

        # data for top 5 user with biggest amount of comments
        $this->data['users'] = $this->model->getUsersLogins();

        # data for top 3 topics with the most comments for previous day TODONE fill top 3 topics data
        $this->data['topics'] = $this->model->getTopThreeTopics();

        # data for building categories tree TODONE fill data
        $this->data['cat'] = $this->model->getCategoryTree();
        $this->data['tree_news'] = $this->model->getNewsByCategory();
        $this->data['tree'] = "";
        $this->build_tree(0,0);

        # data news titles for categories tree TODONE fill data
        $this->data['news'] = $this->model->getNewsByCategory();

        # data for advertising
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }

    
    # экшены для модераторов
    
    # домашняя страница для модераторов

    public function moderator_index()
    {
        // data for OwlCarousel
        $this->data['carousel'] = $this->model->getCarouselData();

        // data for top 5 user with biggest amount of comments
        $this->data['users'] = $this->model->getUsersLogins();

        // data for top 3 topics with the most comments for previous day
        $this->data['topics'] = $this->model->getTopThreeTopics();

        // data for building categories tree TODONE fill data
        $this->data['cat'] = $this->model->getCategoryTree();
        $this->data['tree_news'] = $this->model->getNewsByCategory();
        $this->data['tree'] = "";
        $this->build_tree(0,0);

        // data news titles for categories tree
        $this->data['news'] = $this->model->getNewsByCategory();

        // data for advertising
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }
}