<?php

class Controller_Goods extends Controller
{
    function __construct() {
        parent::__construct();
        
        if(!isset($this->manager->permissions) || !in_array('goods', array_keys($this->manager->permissions))){
            Route::ErrorPage404();
            die();
        }
        
        $this->model = new Model_Goods();
    }
    
    function action_index() {
        $this->design_var->goods = $this->model->get_goods();
        $this->view->generate('goods_view.php', 'wrapper_view.php', $this->design_var);
    }
    
    function action_view() {
        $id = trim(htmlspecialchars(strip_tags($_GET['id'])));
        $this->design_var->good = $this->model->get_good($id);
        $this->design_var->categories = $this->model->get_categories();
        $this->view->generate('update_good_view.php', 'wrapper_view.php', $this->design_var);
    }
    
    function action_update() {
        $id = (int) trim(htmlspecialchars(strip_tags($_GET['id'])));
        if(!empty($id)){
            if($this->model->update($id)){
                $_SESSION['good_status']['success'] = 'updated';
            }
            header('Location: /administrator/goods/view?id=' . $id);
            die();
        }
        header('Location: /administrator/goods');
    }
    
    function action_remove() {
        $id = (int) trim(htmlspecialchars(strip_tags($_POST['remove_id'])));
        if(!empty($id)){
            $this->model->remove($id);
        }
        header('Location: /administrator/goods');
    }
    
    function action_add() {
        
        $this->design_var->categories = $this->model->get_categories();
        if(isset($_POST['apply'])){
            if($id = $this->model->add()){
                $_SESSION['good_status']['success'] = 'added';
                header('Location: /administrator/goods/view?id=' . $id);
            }
        }
        
        $this->view->generate('add_good_view.php', 'wrapper_view.php', $this->design_var);
        
    }
    
}