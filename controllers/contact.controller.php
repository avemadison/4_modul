<?php
/**
 * TODO finish contact us form
 */

class ContactController extends Controller{
    
    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new Message();
    }

    public function index()
    {
        # данные для рекламы
        $this->data['adv'] = $this->model->getAdvertising();
        shuffle($this->data['adv']);
        $this->data['adv_left'] = array_slice($this->data['adv'],0,4);
        $this->data['adv_right'] = array_slice($this->data['adv'],3,4);
        
        if ($_POST) {
            if ($this->model->save($_POST)) {
                Session::setFlash('Ваше сообщение было успешно отправлено!');
            } else {
                Session::setFlash('Упс...что-то пошло не так.');
            }
        }
    }
    
    public function admin_index() 
    {
        $this->data['messages'] = $this->model->getList();
    }
}