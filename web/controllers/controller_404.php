<?php

class Controller_404 extends Controller
{
    
    function action_index()
    {
        $this->design_var->meta_title = 'Страница не найдена';
        $this->design_var->meta_description = 'Страница не найдена';
        $this->design_var->meta_keywords = 'Страница не найдена';
        $this->view->generate('404_view.php', 'wrapper_view.php', $this->design_var);
        return true;
    }
}
