<?php

class Controller {
    
    public $model;
    public $view;
    public $design_var;
    public $settings;
    
    function __construct()
    {
        global $settings;
        $this->settings = $settings;
        $this->view = new View();
        $this->model = new Model();
        $this->design_var = new stdClass();
        $this->design_var->settings = $this->settings;
        $this->design_var->meta_title = '';
        $this->design_var->meta_description = '';
        $this->design_var->meta_keywords = '';
        
        if(isset($_COOKIE['remember']) && !empty($_COOKIE['remember']) && !isset($_SESSION['user_id'])){
            $remember = unserialize($_COOKIE['remember']);
            $remember_token = $this->model->get_data('user_remember_token', ['id'=>$remember['user_id']]);
            if(!empty($remember_token) && $remember_token == $remember['token']){
                $_SESSION['user_id'] = $remember['user_id'];
            }
        }
        
        if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])){
            $this->design_var->user = new stdClass();
            $this->design_var->user->name = $this->model->get_data('user_name', ['id'=>$_SESSION['user_id']]);
        }
        
        
        
    }
    
    // действие (action), вызываемое по умолчанию
    function action_index()
    {
        // todo
    }
}
