<?php

class Controller_Goods extends Controller
{
    function __construct($db) {
        parent::__construct($db);
        $this->model = new Model_Goods($db);
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
        
        if(isset($_POST['add_review'])) {
            $this->model->add_review($good->id);
            $good = $this->model->get_good($good->id);
        }
        
        $good->features = $this->model->get_features(['good_id'=>$good->id]);
        $good->colors = $this->model->get_good_colors(['good_id'=>$good->id]);
        $good->images   = $this->model->get_images(['good_id'=>$good->id]);
        
        if(!empty($good->main_image_id)) {
            $images = $this->model->get_images(['id'=>$good->main_image_id]);
            $good->image = reset($images);
        } else {
            $good->image = reset($good->images);
        }
        
        $this->design_var->reviews = $this->model->get_reviews($good->id);
        $this->design_var->good = $good;
        $this->design_var->meta_title = $good->meta_title;
        $this->design_var->meta_description = $good->meta_description;
        $this->design_var->meta_keywords = $good->meta_keywords;
        
        $this->view->generate('good_view.php', 'wrapper_view.php', $this->design_var);
        return true;
    }
    
    function action_category() {
        $id = trim(htmlspecialchars(strip_tags($_GET['id'])));
        $category = $this->model->get_category($id);
        
        if(!$category || (!$category->visible && !isset($_SESSION['admin_id']))){
            return false;
        }
        
        $filter = [];
        $filter['category_id'] = $category->children;
        
        $goods = [];
        $main_images_ids = [];
        foreach($this->model->get_goods($filter) as $good) {
            $goods[$good->id] = $good;
            if($good->main_image_id) {
                $main_images_ids[] = $good->main_image_id;
            }
        }
        
        if(!empty($goods)) {
            $colors = $this->model->get_good_colors(['good_id'=>array_keys($goods)]);
            foreach($colors as $color) {
                $goods[$color->good_id]->colors[] = $color;
            }
            
            $images = $this->model->get_images(['good_id'=>array_keys($goods)]);
            foreach($images as $image) {
                $goods[$image->good_id]->images[] = $image;
            }
            
            foreach($this->model->get_images(['id'=>$main_images_ids]) as $image) {
                $main_images[$image->id] = $image;
            }
            
            foreach($goods as $good) {
                if(!empty($good->main_image_id)) {
                    $good->image = $main_images[$good->main_image_id];
                } else {
                    $good->image = reset($good->images);
                }
            }
        }
        
        $this->design_var->category = $category;
        $this->design_var->goods = $goods;
        $this->design_var->meta_title = $category->meta_title;
        $this->design_var->meta_description = $category->meta_description;
        $this->design_var->meta_keywords = $category->meta_keywords;
        
        $this->view->generate('goods_view.php', 'wrapper_view.php', $this->design_var);
        return true;
    }
}