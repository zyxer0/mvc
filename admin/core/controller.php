<?php

class Controller {
    
    public $model;
    public $view;
    public $design_var;
    public $manager;
    //public $manager_permisions = [];
    
    function __construct()
    {
        global $settings;
        $this->view = new View();
        $this->model = new Model();
        $this->design_var = new stdClass();
        $this->design_var->settings = $settings;
        $this->design_var->meta_title = '';
        $this->design_var->meta_description = '';
        $this->design_var->meta_keywords = '';
        
        if(isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id'])){
            $this->manager = $this->model->get_data('admin', ['id'=>$_SESSION['admin_id']]);
            $this->design_var->manager = $this->manager;
            //$this->manager_permisions = $this->design_var->manager->permisions;
        }
        
        /* if(!empty($this->design_var->manager)){
            $this->design_var->menu = $this->model->get_data('menu', ['permisions'=>$this->manager_permisions]);
        } */
        
    }
    
    // действие (action), вызываемое по умолчанию
    function action_index()
    {
        // todo
    }
}
