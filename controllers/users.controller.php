<?php
# Контроллер для входа в систему и выхода из системы. Использует Model user

class UsersController extends Controller{

    # UsersController constructor

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new User();
    }

    # экшены для входа
    # set Session[login]
    # set Session[role]

    public function login()
    {
        # реклама
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);

        if ($_POST) {
            if (!empty($_POST['login']) && !empty($_POST['password'])) {

                $user = $this->model->getByLogin($_POST['login']); // false or true
                $hash = md5(Config::get('salt') . $_POST['password']);

                if ($user && $hash == $user['password']) {
                    Session::set('id', $user['id']);
                    Session::set('login', $user['login']);
                    Session::set('role', $user['role']);
                }

                if (Session::get('role') == 'admin') {
                    Router::redirect('/admin/');
                } else {
                    Router::redirect('/user/');
                }
            } else {
                Session::setFlash('Please fill in all fields');
            }
        } else {
            Session::setFlash('Login and password are incorrect');
            return false;
        }
    }
    
    # закончить сессию и совершить редирект
    public function logout()
    {
        Session::destroy();
        Router::redirect('/');
    }

    # для зарегистрированых юзеров

    public function registration()
    {
        # реклама
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);


        if ($_POST) {
            if (!empty($_POST['first_name']) && !empty($_POST['second_name'])&& !empty($_POST['login_name']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['date_of_birth'])) {

                $first_name  = $_POST['first_name'];
                $second_name = $_POST['second_name'];
                $login       = $_POST['login_name'];
                $email       = $_POST['email'];
                $password    = md5(Config::get('salt').$_POST['password']); # salt + password
                $date        = $_POST['date_of_birth'];

                # php фильтр для валидации емейл
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    Session::setFlash('Please enter real email address!');
                }
                
                # проверка емейл
                if ($this->model->getByEmail($email)) {
                    Session::setFlash('This email is used!');
                    return false;
                }
                
                # проверка логина
                if ($this->model->getByLogin($login)) {
                    Session::setFlash('The login is used!');
                    return false;
                }
                
                $this->model->registerUser($first_name, $second_name, $login, $email, $password, $date);

                Session::set('login',$login);
                Session::set('role','user');
                
                Router::redirect('/user/'); # редирект на домашнюю
            } else { 
                Session::setFlash('Please fill in all fields!');
            }
        } else {
            return false;
        }
    }

    # показать всех юзеров

    public function index()
    {

        $this->data['users'] = $this->model->getUsersList();

        # реклама
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }


    # показать одного юзера со всеми его комментами

    public function view()
    {
        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $id = strtolower($params[0]);
            $this->data['user'] = $this->model->getUserById($id);
            $this->data['user_comments'] = $this->model->getUserComments($id);
        }

        # массив для пагинации дерева

        $count_rows  = 0;
        $this->data['pagination_news'] = array();
        foreach($this->data['user_comments'] as $comment) {
            $this->data['pagination_comment'][$count_rows/20][] = $comment;
            $count_rows++;
        }

        # для разбивки на страницы
        $this->data['current_comment'] = (isset($params[0])) ? $params[0] : 0;
        $this->data['current_pag'] = (isset($params[1])) ? $params[1] : 0;
        $this->data['last_pag'] = (int)(count($this->data['user_comments'])/20)-1;

        # для рекламы
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }
    
    # для просмотра всех юзеров

    public function admin_index() 
    {

        $this->data['users'] = $this->model->getUsersList();
    }

    # показать одного юзера со всеми его комментами

    public function admin_view()
    {
        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $id = strtolower($params[0]);
            $this->data['user'] = $this->model->getUserById($id);
            $this->data['user_comments'] = $this->model->getUserComments($id);
        }

        # массив для пагинации дерева
        $count_rows  = 0;
        $this->data['pagination_news'] = array();
        foreach($this->data['user_comments'] as $comment) {
            $this->data['pagination_comment'][$count_rows/20][] = $comment;
            $count_rows++;
        }

        # для разбивки на страницы
        $this->data['current_comment'] = (isset($params[0])) ? $params[0] : 0;
        $this->data['current_pag'] = (isset($params[1])) ? $params[1] : 0;
        $this->data['last_pag'] = (int)(count($this->data['user_comments'])/20)-1;
    }

   # для просмотра всех

    public function user_index ()
    {

        $this->data['users'] = $this->model->getUsersList();

        # реклама
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }

    # показать юзера со всеми его комментами

    public function user_view()
    {
        $params = App::getRouter()->getParams();

        if (isset($params[0])) {
            $id = strtolower($params[0]);
            $this->data['user'] = $this->model->getUserById($id);
            $this->data['user_comments'] = $this->model->getUserComments($id);
        }

        # массив для пагинации дерева

        $count_rows  = 0;
        $this->data['pagination_news'] = array();
        foreach($this->data['user_comments'] as $comment) {
            $this->data['pagination_comment'][$count_rows/20][] = $comment;
            $count_rows++;
        }

        # для разбивки на страницы

        $this->data['current_comment'] = (isset($params[0])) ? $params[0] : 0;
        $this->data['current_pag'] = (isset($params[1])) ? $params[1] : 0;
        $this->data['last_pag'] = (int)(count($this->data['user_comments'])/20)-1;

        # реклама
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
    }
}

