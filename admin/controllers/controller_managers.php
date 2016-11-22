<?php

class Controller_Managers extends Controller
{
    function __construct() {
        parent::__construct();
        
        if(!$this->manager->permissions || !in_array('managers', array_keys($this->manager->permissions))){
            Route::ErrorPage404();
            die();
        }
        
        $this->model = new Model_Managers();
    }
    
    function action_index() {
        $this->design_var->managers = $this->model->get_managers();
        $this->view->generate('managers_view.php', 'wrapper_view.php', $this->design_var);
    }
    
    function action_view() {
        $id = trim(htmlspecialchars(strip_tags($_GET['id'])));
        $this->design_var->update_manager = $this->model->get_manager($id);
        $this->design_var->all_permissions = $this->model->get_all_permissions();
        $this->view->generate('update_manager_view.php', 'wrapper_view.php', $this->design_var);
    }
    
    function action_update() {
        $id = (int) trim(htmlspecialchars(strip_tags($_GET['id'])));
        if(!empty($id)){
            if($this->model->update($id)){
                $_SESSION['manager_status']['success'] = 'updated';
            }
            header('Location: /administrator/managers/view?id=' . $id);
            die();
        }
        header('Location: /administrator/managers');
    }
    
    function action_remove() {
        $id = (int) trim(htmlspecialchars(strip_tags($_POST['remove_id'])));
        if(!empty($id)){
            $this->model->remove($id);
        }
        header('Location: /administrator/managers');
    }
    
    function action_add() {
        
        $this->design_var->all_permissions = $this->model->get_all_permissions();
        
        if(isset($_POST['apply'])){
            if($id = $this->model->add()){
                $_SESSION['manager_status']['success'] = 'added';
                header('Location: /administrator/managers/view?id=' . $id);
            }
        }
        
        $this->view->generate('add_manager_view.php', 'wrapper_view.php', $this->design_var);
        
    }
    
}