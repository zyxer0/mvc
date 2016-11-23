<?php

class Controller_Settings extends Controller
{
    function __construct() {
        parent::__construct();
        
        if(!$this->manager->permissions || !in_array('settings', array_keys($this->manager->permissions))){
            Route::ErrorPage404();
            die();
        }
        $this->model = new Model_Settings();
    }
    
    function action_index() {
        $this->view->generate('settings_view.php', 'wrapper_view.php', $this->design_var);
    }
    
    function action_update() {
        $this->model->update();
        header('Location: /administrator/settings');
    }
}