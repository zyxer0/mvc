<?php

class Controller_Goods extends Controller
{
    function __construct() {
        parent::__construct();
        $this->model = new Model_Goods();
    }
    
    function action_index() {
        $this->design_var->goods = $this->model->get_goods();
        $this->view->generate('goods_view.php', 'wrapper_view.php', $this->design_var);
        return true;
    }
    
    function action_view() {
        $id = trim(htmlspecialchars(strip_tags($_GET['id'])));
        $good = $this->model->get_good($id);
        
        if(!$good || (!$good->visible && !isset($_SESSION['admin_id']))){
            return false;
        }
        
        $this->design_var->good = $good;
        $this->design_var->meta_title = $good->meta_title;
        $this->design_var->meta_description = $good->meta_description;
        $this->design_var->meta_keywords = $good->meta_keywords;
        
        $this->view->generate('good_view.php', 'wrapper_view.php', $this->design_var);
        return true;
    }
}