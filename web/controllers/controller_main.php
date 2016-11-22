<?php

class Controller_Main extends Controller
{

    public function __construct(){
        parent::__construct();
    }

    function action_index()
    {
        $this->design_var->meta_title = 'Мой интернет магазин';
        $this->design_var->meta_description = 'Мой интернет магазин';
        $this->design_var->meta_keywords = 'Мой интернет магазин';
        $this->view->generate('main_view.php', 'wrapper_view.php', $this->design_var);
        return true;
    }
}