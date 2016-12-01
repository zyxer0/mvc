<?php

class Controller_Categories extends Controller
{
    function __construct() {
        parent::__construct();
        
        if(!isset($this->manager->permissions) || !in_array('categories', array_keys($this->manager->permissions))){
            Route::ErrorPage404();
            die();
        }
        
        $this->model = new Model_Categories();
    }
    
    function action_index() {
        $this->design_var->categories = $this->model->get_categories();
        $this->design_var->count_categories = $this->model->count_categories();
        $this->view->generate('categories_view.php', 'wrapper_view.php', $this->design_var);
    }
    
    function action_view() {
        $id = trim(htmlspecialchars(strip_tags($_GET['id'])));
        $this->design_var->category = $this->model->get_category((int)$id);
        $this->design_var->all_categories = $this->model->get_categories();
        $this->view->generate('update_category_view.php', 'wrapper_view.php', $this->design_var);
    }
    
    function action_update() {
        $id = (int) trim(htmlspecialchars(strip_tags($_GET['id'])));
        if(!empty($id)){
            if($this->model->update($id)){
                $_SESSION['category_status']['success'] = 'updated';
            }
            header('Location: /administrator/categories/view?id=' . $id);
            die();
        }
        header('Location: /administrator/categories');
    }
    
    function action_remove() {
        $id = (int) trim(htmlspecialchars(strip_tags($_POST['remove_id'])));
        if(!empty($id)){
            $this->model->remove($id);
        }
        header('Location: /administrator/categories');
    }
    
    function action_add() {
        
        
        if(isset($_POST['apply'])){
            if($id = $this->model->add()){
                $_SESSION['category_status']['success'] = 'added';
                header('Location: /administrator/categories/view?id=' . $id);
            }
        }
        $this->design_var->all_categories = $this->model->get_categories();
        $this->view->generate('add_category_view.php', 'wrapper_view.php', $this->design_var);
        
    }
    
}