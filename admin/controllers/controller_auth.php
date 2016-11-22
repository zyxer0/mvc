<?php

class Controller_Auth extends Controller
{
    function __construct()
    {
        parent::__construct();
        $this->model = new Model_Auth();
    }
    
    function action_index()
    {
        
        $this->view->generate('', 'auth_view.php', $this->design_var);
        
    }
    
    function action_login()
    {
        
        if($admin_id = $this->model->login_admin()){
            $_SESSION["admin_id"] = $admin_id;
        }
        header("location: /administrator");
    }
    
    function action_logout()
    {
        unset($_SESSION['admin_id']);
        header('Location: /administrator/auth');
    }
    
}
