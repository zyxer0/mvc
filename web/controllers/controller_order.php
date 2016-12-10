<?php

class Controller_Order extends Controller
{
    function __construct($db) {
        parent::__construct($db);
        $this->model = new Model_Order($db);
    }
    
    function action_index() {
        $order = $this->model->get_order();
        
        /* if(!empty($cart->goods)) {
            
            foreach($cart->goods as $good) {
                if($good->main_image_id) {
                    $main_images_ids[] = $good->main_image_id;
                }
            }
            
            $images = $this->model->get_images(['good_id'=>array_keys($cart->goods)]);
            foreach($images as $image) {
                $cart->goods[$image->good_id]->images[] = $image;
            }
            
            foreach($this->model->get_images(['id'=>$main_images_ids]) as $image) {
                $main_images[$image->id] = $image;
            }
            
            foreach($cart->goods as $good) {
                if(!empty($good->main_image_id)) {
                    $good->image = $main_images[$good->main_image_id];
                } else {
                    $good->image = reset($good->images);
                }
            }
        } */
        $this->design_var->order = $order;
        $this->design_var->meta_title = 'Заказ №'.$order->id;
        $this->design_var->meta_description = 'Заказ №'.$order->id;
        $this->design_var->meta_keywords = 'Заказ №'.$order->id;
        $this->view->generate('order_view.php', 'wrapper_view.php', $this->design_var);
        return true;
    }
    
    function action_contact() {
        $this->design_var->meta_title = 'Контактные данные';
        $this->design_var->meta_description = 'Контактные данные';
        $this->design_var->meta_keywords = 'Контактные данные';
        
        if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $this->design_var->user = $this->model->get_data('user', ['id'=>(int)$_SESSION['user_id']]);
        }
        
        $this->view->generate('order_contact_view.php', 'wrapper_view.php', $this->design_var);
        return true;
    }
    
    function action_add() {
        
        if(isset($_POST['checkout'])) {
            if(!$order_url = $this->model->add_order()) {
                return false;
            }
            header('Location: /order/'.$order_url.$this->settings->prefix);
            return true;
        } else {
            
            return false;
        }
    }
}