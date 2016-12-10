<?php

class Controller_Orders extends Controller
{
    function __construct() {
        parent::__construct();
        
        if(!isset($this->manager->permissions) || !in_array('orders', array_keys($this->manager->permissions))){
            Route::ErrorPage404();
            die();
        }
        
        $this->model = new Model_Orders();
        $this->design_var->statuses = $this->model->get_statuses();
    }
    
    function action_index() {
        $this->design_var->orders = $this->model->get_orders();
        $this->view->generate('orders_view.php', 'wrapper_view.php', $this->design_var);
    }
    
    function action_view() {
        $this->design_var->order = $this->model->get_order();
        $this->view->generate('update_order_view.php', 'wrapper_view.php', $this->design_var);
    }
    
    function action_update() {
        if($id = $this->model->update()){
            $_SESSION['order_status']['success'] = 'updated';
            header('Location: /administrator/orders/view?id=' . $id);
            die();
        }
        header('Location: /administrator/orders');
        die();
    }
    
    function action_remove() {
        $id = (int) trim(htmlspecialchars(strip_tags($_POST['remove_id'])));
        if(!empty($id)){
            $this->model->remove($id);
        }
        header('Location: /administrator/goods');
    }
    
}