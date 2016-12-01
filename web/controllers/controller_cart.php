<?php

class Controller_Cart extends Controller
{
    function __construct($db) {
        parent::__construct($db);
        $this->model = new Model_Cart($db);
    }
    
    function action_index() {
        $cart = $this->model->get_cart();
        
        if(!empty($cart->goods)) {
            
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
        }
        $this->design_var->cart = $cart;
        $this->design_var->meta_title = 'Корзина';
        $this->design_var->meta_description = 'Корзина';
        $this->design_var->meta_keywords = 'Корзина';
        $this->view->generate('cart_view.php', 'wrapper_view.php', $this->design_var);
        return true;
    }
    
    function action_add() {
        $good_id = trim(htmlspecialchars(strip_tags($_GET['good_id'])));
        $amount = (int)trim(htmlspecialchars(strip_tags($_GET['amount'])));
        $amount = max(1, $amount);
        
        if(!$this->model->get_good($good_id)) {
            return false;
        }
        
        $this->model->add_item($good_id, $amount);
        
        if(isset($_GET['is_ajax'])) {
            print json_encode($this->model->get_cart());
        }
        return true;
    }
    
    function action_update() {
        
        if(isset($_POST['remove']) && !empty($_POST['remove'])) {
            $this->model->remove((int)$_POST['remove']);
        } else if(!empty($_POST['amounts'])) {
            foreach($_POST['amounts'] as $good_id=>$amount) {
                $this->model->change_amount($good_id, $amount);
            }
        }
        
        header('Location: /cart'.$this->settings->prefix);
        return true;
    }
}