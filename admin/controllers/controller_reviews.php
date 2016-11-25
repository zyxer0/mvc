<?php

class Controller_Reviews extends Controller
{
    function __construct() {
        parent::__construct();
        
        if(!$this->manager->permissions || !in_array('reviews', array_keys($this->manager->permissions))){
            Route::ErrorPage404();
            die();
        }
        
        $this->model = new Model_Reviews();
    }
    
    function action_index() {
        $this->design_var->reviews = $this->model->get_reviews();
        $this->view->generate('reviews_view.php', 'wrapper_view.php', $this->design_var);
    }
    
    function action_approve() {
        $id = (int) trim(htmlspecialchars(strip_tags($_POST['approve_id'])));
        if(!empty($id)){
            $this->model->approve($id);
        }
        header('Location: /administrator/reviews');
    }
    
    function action_remove() {
        $id = (int) trim(htmlspecialchars(strip_tags($_POST['remove_id'])));
        if(!empty($id)){
            $this->model->remove($id);
        }
        header('Location: /administrator/reviews');
    }
    
}