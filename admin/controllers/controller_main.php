<?php

class Controller_Main extends Controller
{

    function action_index()
    {
        $this->view->generate('main_view.php', 'wrapper_view.php', $this->design_var);
    }
}