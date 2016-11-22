<?php

class Controller_Users extends Controller
{
    function __construct() {
        parent::__construct();
        
        if(!$this->manager->permissions || !in_array('users', array_keys($this->manager->permissions))){
            Route::ErrorPage404();
            die();
        }
        
        $this->model = new Model_Users();
    }
    
    function action_index() {
        $this->design_var->users = $this->model->get_users();
        $this->view->generate('users_view.php', 'wrapper_view.php', $this->design_var);
    }
    
    function action_view() {
        $id = trim(htmlspecialchars(strip_tags($_GET['id'])));
        $this->design_var->user = $this->model->get_user($id);
        $this->view->generate('update_user_view.php', 'wrapper_view.php', $this->design_var);
    }
    
    function action_update() {
        $id = (int) trim(htmlspecialchars(strip_tags($_GET['id'])));
        if(!empty($id)){
            if($this->model->update($id)){
                $_SESSION['user_status']['success'] = 'updated';
            }
            header('Location: /administrator/users/view?id=' . $id);
            die();
        }
        header('Location: /administrator/users');
    }
    
    function action_remove() {
        $id = (int) trim(htmlspecialchars(strip_tags($_POST['remove_id'])));
        if(!empty($id)){
            $this->model->remove($id);
        }
        header('Location: /administrator/users');
    }
    
    function action_add() {
        
        if(isset($_POST['apply'])){
            if($id = $this->model->add()){
                $_SESSION['user_status']['success'] = 'added';
                header('Location: /administrator/users/view?id=' . $id);
            }
        }
        
        $this->view->generate('add_user_view.php', 'wrapper_view.php', $this->design_var);
        
    }
    
}