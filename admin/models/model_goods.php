<?php

class Model_Goods extends Model
{
    
    protected $query;
    
    public function __construct(){
        parent::__construct();
    }
    
    public function get_goods()
    {
        $this->query = "SELECT *
                FROM goods
                LIMIT 100
        ";
        
        $this->db->make_query($this->query);
        $goods = $this->db->results();
        
        foreach($goods as $good) {
            if(!empty($good->main_image_id)) {
                $images = $this->get_images(['id'=>$good->main_image_id]);
                $good->image = reset($images);
            } else {
                $images = $this->get_images(['good_id'=>$good->id]);
                $good->image = reset($images);
            }
        }
        
        return $goods;
    }
    
    public function remove($id){
        if(empty($id)){
            return false;
        }
        
        $good = $this->get_good($id);
        
        if($good) {
        
            if(!empty($good->image) && file_exists(dirname(dirname(__DIR__)) . '/' . $this->settings->goods_images_dir . $good->image)){
                unlink(dirname(dirname(__DIR__)) . '/' . $this->settings->goods_images_dir . $good->image);
            }
            
            //$good->original_id = $good->id;
            
            $this->query = "INSERT INTO 
                        deleted_goods 
                        SET
                        `original_id`='" . $good->id . "',
                        `media_link_video`='" . $good->media_link_video . "',
                        `media_link_demo`='" . $good->media_link_demo . "',
                        `name`='" . $good->name . "',
                        `url`='" . $good->url . "',
                        `ending_good`='" . $good->ending_good . "',
                        `old_price`='" . $good->old_price . "',
                        `price`='" . $good->price . "',
                        `description`='" . $good->description . "',
                        `sticker_class`='" . $good->sticker_class . "',
                        `sticker_text`='" . $good->sticker_text . "',
                        `raiting`='" . $good->raiting . "',
                        `meta_title`='" . $good->meta_title . "',
                        `meta_description`='" . $good->meta_description . "',
                        `meta_keywords`='" . $good->meta_keywords . "'
                    ";
            
            $this->db->make_query($this->query);
            
            $this->delete_image(['good_id'=>$id]);
            
            foreach($this->get_features(['good_id'=>$id]) as $feature){
                $this->delete_feature(['id'=>$feature->id]);
                if(file_exists(dirname(dirname(__DIR__)) . '/' . $this->settings->goods_features_images_dir . $feature->image)){
                    unlink(dirname(dirname(__DIR__)) . '/' . $this->settings->goods_features_images_dir . $feature->image);
                }
            }
            
            $this->delete_good_color(['good_id'=>$id]);
            
            $this->query = "DELETE FROM goods
                    WHERE id=" . $id . "
                    LIMIT 1
                    ";
            $this->db->make_query($this->query);
            
            $this->query = "DELETE FROM
                    router 
                    WHERE
                    `alias`='" . $good->url . "' LIMIT 1
                ";
            
            $this->db->make_query($this->query);
            return true;
        }
        return false;
    }
    
    public function get_good($id){
        if(!$id){
            return false;
        }
        $id = mysqli_real_escape_string($this->db->dbc, $id);
        $this->query = "SELECT *
                FROM goods
                WHERE id = ". $id ."
                LIMIT 1
        ";
        
        $this->db->make_query($this->query);
        $good = $this->db->result();
        
        $good->features = $this->get_features(['good_id'=>$good->id]);
        $good->colors   = $this->get_good_colors(['good_id'=>$good->id]);
        $good->images   = $this->get_images(['good_id'=>$good->id]);
        return $good;
    }
    
    public function add(){
        
        $good = new stdClass;
        $good->media_link_video = trim(htmlspecialchars(strip_tags($_POST['media_link_video'])));
        $good->media_link_demo  = trim(htmlspecialchars(strip_tags($_POST['media_link_demo'])));
        $good->name             = trim(htmlspecialchars(strip_tags($_POST['name'])));
        $good->url              = trim(htmlspecialchars(strip_tags($_POST['url'])));
        $good->visible          = isset($_POST['visible']) ? true : false;
        $good->ending_good      = isset($_POST['ending_good']) ? true : false;
        $good->old_price        = trim(htmlspecialchars(strip_tags($_POST['old_price'])));
        $good->price            = trim(htmlspecialchars(strip_tags($_POST['price'])));
        $good->description      = trim($_POST['description']);
        $good->sticker_class    = trim(htmlspecialchars(strip_tags($_POST['sticker_class'])));
        $good->sticker_text     = trim(htmlspecialchars(strip_tags($_POST['sticker_text'])));
        $good->image_alt        = trim(htmlspecialchars(strip_tags($_POST['image_alt'])));
        $good->image_title      = trim(htmlspecialchars(strip_tags($_POST['image_title'])));
        $good->raiting          = trim(htmlspecialchars(strip_tags($_POST['raiting'])));
        $good->meta_title       = trim(htmlspecialchars(strip_tags($_POST['meta_title'])));
        $good->meta_description = trim(htmlspecialchars(strip_tags($_POST['meta_description'])));
        $good->meta_keywords    = trim(htmlspecialchars(strip_tags($_POST['meta_keywords'])));
        
        if(!Validation::check_empty($good->name) || !Validation::check_text($good->name)){
            return false;
        }
        if(!Validation::check_text($good->media_link_demo)){
            return false;
        }
        if(!Validation::check_text($good->media_link_video)){
            return false;
        }
        if(!Validation::check_text($good->meta_title)){
            return false;
        }
        if(!Validation::check_text($good->meta_description)){
            return false;
        }
        if(!Validation::check_text($good->meta_keywords)){
            return false;
        }
        
        if(!$good->image = $this->upload_image($_FILES['good_image']['name'], $_FILES['good_image']['tmp_name'], $this->settings->goods_images_dir)) {
            $good->image = '';
        }
        
        $this->query = "INSERT INTO 
                    goods 
                    SET
                    `media_link_video`='" . $good->media_link_video . "',
                    `media_link_demo`='" . $good->media_link_demo . "',
                    `name`='" . $good->name . "',
                    `visible`='" . $good->visible . "',
                    `ending_good`='" . $good->ending_good . "',
                    `old_price`='" . $good->old_price . "',
                    `price`='" . $good->price . "',
                    `description`='" . $good->description . "',
                    `sticker_class`='" . $good->sticker_class . "',
                    `sticker_text`='" . $good->sticker_text . "',
                    `raiting`='" . $good->raiting . "',
                    `meta_title`='" . $good->meta_title . "',
                    `meta_description`='" . $good->meta_description . "',
                    `meta_keywords`='" . $good->meta_keywords . "'
                ";
                
        if(!$this->db->make_query($this->query)){
            return false;
        }
        
        if($id = $this->db->insert_id()) {
            
            if(isset($_FILES['good_images']['name'][0]) && !empty($_FILES['good_images']['name'][0])) {
                foreach($_FILES['good_images']['name'] as $k=>$name) {
                    $file_output = $this->generate_image_name($name, $this->settings->goods_images_dir);
                    $dir = dirname(dirname(__DIR__)) . '/' . $this->settings->goods_images_dir;
                    
                    $filename_full   = $file_output->file_name . '_FULL'   . $file_output->extension;
                    $filename_small  = $file_output->file_name . '_SMALL'  . $file_output->extension;
                    $filename_middle = $file_output->file_name . '_MIDDLE' . $file_output->extension;
                    $tmp_file = $_FILES['good_images']['tmp_name'][$k];
                    
                    $this->resize($tmp_file, $dir . $filename_full,   $this->settings->full_size_x,   $this->settings->full_size_y);
                    $this->resize($tmp_file, $dir . $filename_small,  $this->settings->small_size_x,  $this->settings->small_size_y);
                    $this->resize($tmp_file, $dir . $filename_middle, $this->settings->middle_size_x, $this->settings->middle_size_y);
                    
                    $image['filename_full']   = $filename_full;
                    $image['filename_small']  = $filename_small;
                    $image['filename_middle'] = $filename_middle;
                    $image['good_id']         = $id;
                    $image['alt']             = '';
                    $image['title']           = '';
                    
                    $this->add_image($image);
                }
            }
            
            if(empty($good->url) || !Validation::check_url($good->url)){
                $good->url = $this->translit($good->name) . '_' . $id;
            }
            
            do {
                $this->query = "
                                SELECT 
                                `id`
                                FROM goods
                                WHERE `id`!='" . $id . "' AND `url`='" . $good->url . "' LIMIT 1
                            ";
                
                $this->db->make_query($this->query);
                
                if($result = $this->db->result()) {
                    $good->url .=  $id;
                }
            } while($result);
            
            $this->query = "INSERT INTO 
                    router 
                    SET
                    `alias`='" . $good->url . "',
                    `route`='goods/view?id=" . $id . "'
                ";
                
            $this->db->make_query($this->query);
            
            
            $this->query = "UPDATE
                    goods 
                    SET
                    `url`='" . $good->url . "'
                    WHERE `id`=" . $id . "
                    LIMIT 1
                ";
                
            $this->db->make_query($this->query);
            
            if($_POST['colors']){
                foreach($_POST['colors'] as $color){
                    if(!empty($color) && Validation::validate_form(['text'=>$color])){
                        $color = trim(htmlspecialchars(strip_tags($color)));
                        $color = mysqli_real_escape_string($this->db->dbc, $color);
                        $this->add_good_color(['link'=>'#'.$color, 'color'=>$color, 'good_id'=>$id]);
                    }
                }
            }
            
            $new_features_ids = [];
            if(!empty($_FILES['features'])){
                $feature['good_id'] = $id;
                foreach($_FILES['features']['name'] as $feature_id=>$feature_filename){
                    if($feature['image'] = $this->upload_image($feature_filename, $_FILES['features']['tmp_name'][$feature_id], $this->settings->goods_features_images_dir)){
                        $new_features_ids[] = $this->add_feature($feature);
                    }
                }
            }
            
            if(!empty($_POST['features'])){
                $feature['good_id'] = $id;
                foreach($_POST['features'] as $f_id=>$f){
                    $new_features_ids[] = $f_id;
                }
            }
            
            foreach($this->get_features(['good_id'=>$id]) as $old_feature){
                if(!in_array($old_feature->id, $new_features_ids)){
                    $this->delete_feature(['id'=>$old_feature->id]);
                    
                    if(file_exists(dirname(dirname(__DIR__)) . '/' . $this->settings->goods_features_images_dir . $old_feature->image)){
                        unlink(dirname(dirname(__DIR__)) . '/' . $this->settings->goods_features_images_dir . $old_feature->image);
                    }
                }
            }
            return $id;
        }
        
        return false;
        
    }
    
    public function update($id){
        if(empty($id)){
            return false;
        }
        
        $good = new stdClass;
        $good->media_link_video = trim(htmlspecialchars(strip_tags($_POST['media_link_video'])));
        $good->media_link_demo  = trim(htmlspecialchars(strip_tags($_POST['media_link_demo'])));
        $good->name             = trim(htmlspecialchars(strip_tags($_POST['name'])));
        $good->url              = trim(htmlspecialchars(strip_tags($_POST['url'])));
        $good->visible          = isset($_POST['visible']) ? true : false;
        $good->ending_good      = isset($_POST['ending_good']) ? true : false;
        $good->old_price        = trim(htmlspecialchars(strip_tags($_POST['old_price'])));
        $good->price            = trim(htmlspecialchars(strip_tags($_POST['price'])));
        $good->description      = trim($_POST['description']);
        $good->sticker_class    = trim(htmlspecialchars(strip_tags($_POST['sticker_class'])));
        $good->sticker_text     = trim(htmlspecialchars(strip_tags($_POST['sticker_text'])));
        $good->raiting          = trim(htmlspecialchars(strip_tags($_POST['raiting'])));
        $good->meta_title       = trim(htmlspecialchars(strip_tags($_POST['meta_title'])));
        $good->meta_description = trim(htmlspecialchars(strip_tags($_POST['meta_description'])));
        $good->meta_keywords    = trim(htmlspecialchars(strip_tags($_POST['meta_keywords'])));
        $good->main_image_id    = null;
        
        if(!empty($_POST['main_image_id'])) {
            $good->main_image_id = trim(htmlspecialchars(strip_tags($_POST['main_image_id'])));
        }
        
        if(!Validation::check_empty($good->name) || !Validation::check_empty($good->name)){
            return false;
        }
        if(!Validation::check_text($good->media_link_demo)){
            return false;
        }
        if(!Validation::check_text($good->media_link_video)){
            return false;
        }
        if(!Validation::check_text($good->meta_title)){
            return false;
        }
        if(!Validation::check_text($good->meta_description)){
            return false;
        }
        if(!Validation::check_text($good->meta_keywords)){
            return false;
        }
        
        if(isset($_FILES['good_images']['name'][0]) && !empty($_FILES['good_images']['name'][0])) {
            foreach($_FILES['good_images']['name'] as $k=>$name) {
                $file_output = $this->generate_image_name($name, $this->settings->goods_images_dir);
                $dir = dirname(dirname(__DIR__)) . '/' . $this->settings->goods_images_dir;
                
                $filename_full   = $file_output->file_name . '_FULL'   . $file_output->extension;
                $filename_small  = $file_output->file_name . '_SMALL'  . $file_output->extension;
                $filename_middle = $file_output->file_name . '_MIDDLE' . $file_output->extension;
                $tmp_file = $_FILES['good_images']['tmp_name'][$k];
                
                $this->resize($tmp_file, $dir . $filename_full,   $this->settings->full_size_x,   $this->settings->full_size_y);
                $this->resize($tmp_file, $dir . $filename_small,  $this->settings->small_size_x,  $this->settings->small_size_y);
                $this->resize($tmp_file, $dir . $filename_middle, $this->settings->middle_size_x, $this->settings->middle_size_y);
                
                $image['filename_full']   = $filename_full;
                $image['filename_small']  = $filename_small;
                $image['filename_middle'] = $filename_middle;
                $image['good_id']         = $id;
                $image['alt']             = '';
                $image['title']           = '';
                
                $this->add_image($image);
            }
        }
        if(isset($_POST['delete_images'])){
            foreach($_POST['delete_images'] as $id) {
                $this->delete_image(['id'=>$id]);
            }
        }
        
        if(isset($_POST['image_alt'])){
            foreach($_POST['image_alt'] as $image_id=>$alt) {
                
                $alt      = mysqli_real_escape_string($this->db->dbc, $alt);
                $image_id = mysqli_real_escape_string($this->db->dbc, $image_id);
                $title    = mysqli_real_escape_string($this->db->dbc, $_POST['image_title'][$image_id]);
                
                $this->query = "UPDATE
                            goods_images 
                            SET `alt`='$alt',
                                `title`='$title'
                            WHERE `id`=$image_id
                            ";
                $this->db->make_query($this->query);
            }
        }
        
        $this->delete_good_color(['good_id'=>$id]);
        
        if($_POST['colors']){
            foreach($_POST['colors'] as $color){
                if(!empty($color) && Validation::validate_form(['text'=>$color])){
                    $color = trim(htmlspecialchars(strip_tags($color)));
                    $color = mysqli_real_escape_string($this->db->dbc, $color);
                    $this->add_good_color(['link'=>'#'.$color, 'color'=>$color, 'good_id'=>$id]);
                }
            }
        }
        
        $new_features_ids = [];
        if(!empty($_FILES['features'])){
            $feature['good_id'] = $id;
            foreach($_FILES['features']['name'] as $feature_id=>$feature_filename){
                if($feature['image'] = $this->upload_image($feature_filename, $_FILES['features']['tmp_name'][$feature_id], $this->settings->goods_features_images_dir)){
                    $this->add_feature($feature);
                }
            }
        }
        
        if(!empty($_POST['features'])){
            $feature['good_id'] = $id;
            foreach($_POST['features'] as $f_id=>$f){
                $new_features_ids[] = $f_id;
            }
        }
        
        foreach($this->get_features(['good_id'=>$id]) as $old_feature){
            if(!in_array($old_feature->id, $new_features_ids)){
                $this->delete_feature(['id'=>$old_feature->id]);
                if(file_exists(dirname(dirname(__DIR__)) . '/' . $this->settings->goods_features_images_dir . $old_feature->image)){
                    unlink(dirname(dirname(__DIR__)) . '/' . $this->settings->goods_features_images_dir . $old_feature->image);
                }
            }
        }
        
        
        if(!empty($good->url) && !Validation::check_url($good->url)){
            $good->url = $this->translit($good->url);
        } else if(empty($good->url) || !Validation::check_url($good->url)){
            $good->url = $this->translit($good->name) . '_' . $id;
        }
        
        
        
        do {
            $this->query = "
                            SELECT 
                            `id`
                            FROM goods
                            WHERE `id`!='" . $id . "' AND `url`='" . $good->url . "' LIMIT 1
                        ";
            
            $this->db->make_query($this->query);
            
            if($result = $this->db->result()) {
                $good->url .=  $id;
            }
        } while($result);
        
        $this->query = "UPDATE
                    goods 
                    SET
                    `media_link_video`='" . $good->media_link_video . "',
                    `media_link_demo`='" . $good->media_link_demo . "',
                    `name`='" . $good->name . "',
                    `url`='" . $good->url . "',
                    `visible`='" . $good->visible . "',
                    `ending_good`='" . $good->ending_good . "',
                    `old_price`='" . $good->old_price . "',
                    `price`='" . $good->price . "',
                    `description`='" . $good->description . "',
                    `sticker_class`='" . $good->sticker_class . "',
                    `sticker_text`='" . $good->sticker_text . "',
                    `raiting`='" . $good->raiting . "',
                    `meta_title`='" . $good->meta_title . "',
                    `meta_description`='" . $good->meta_description . "',
                    `main_image_id`='" . $good->main_image_id . "',
                    `meta_keywords`='" . $good->meta_keywords . "'
                    WHERE `id`=" . $id . "
                    LIMIT 1
                ";
                
        if(!$this->db->make_query($this->query)){
            return false;
        }
        
        $this->query = "UPDATE
                    router 
                    SET
                    `alias`='" . $good->url . "'
                    WHERE `route`='goods/view?id=" . $id . "' LIMIT 1
                ";
        
        $this->db->make_query($this->query);
        
        
        return true;
    }
    
    private function delete_feature($id = []){
        if(empty($id)){
            return false;
        }
        
        if(isset($id['id'])){
            $where = "AND `id`=" . mysqli_real_escape_string($this->db->dbc, $id['id']);
        } else if(isset($id['good_id'])){
            $where = "AND `good_id`=" . mysqli_real_escape_string($this->db->dbc, $filter['good_id']);
        }
        
        $this->query = "DELETE FROM
                    goods_features 
                    WHERE 1
                    $where
                ";
                
        if(!$this->db->make_query($this->query)){
            return false;
        }
        return true;
    }
    
    private function get_features($filter = []){
        $limit = 100;
        $filter_id_where = '';
        $filter_good_id_where = '';
        
        if(isset($filter['id'])){
            $filter_id_where = "AND `id` = ". mysqli_real_escape_string($this->db->dbc, $filter['id']);
        }
        
        if(isset($filter['good_id'])){
            $filter_good_id_where = "AND `good_id` = ". mysqli_real_escape_string($this->db->dbc, $filter['good_id']);
        }
        
        $this->query = "SELECT 
                    `id` ,
                    `good_id` ,
                    `image`
                FROM goods_features
                WHERE 1 
                    $filter_id_where
                    $filter_good_id_where
                LIMIT $limit
        ";
        
        $this->db->make_query($this->query);
        return $this->db->results();
        
    }
    
    private function add_feature($feature){
        if(empty($feature)){
            return false;
        }
        
        $this->query = "INSERT INTO 
                    goods_features 
                    (
                        `good_id`,
                        `image`
                    )
                    VALUES
                    (
                        '" . $feature['good_id'] . "',
                        '" . $feature['image'] . "'
                    )
                ";
                
        $this->db->make_query($this->query);
        return $this->db->insert_id();
    }
    
    private function add_image($image){
        if(empty($image)){
            return false;
        }
        $image = (array)$image;
        
        $this->query = "INSERT INTO 
                    goods_images 
                    (
                        `filename_full`,
                        `filename_middle`,
                        `filename_small`,
                        `good_id`,
                        `alt`,
                        `title`
                    )
                    VALUES
                    (
                        '" . $image['filename_full'] . "',
                        '" . $image['filename_middle'] . "',
                        '" . $image['filename_small'] . "',
                        '" . $image['good_id'] . "',
                        '" . $image['alt'] . "',
                        '" . $image['title'] . "'
                    )
                ";
                
        $this->db->make_query($this->query);
        return $this->db->insert_id();
    }
    
    private function delete_image($id = []){
        if(empty($id)){
            return false;
        }
        
        if(isset($id['id'])){
            $where = "id=" . mysqli_real_escape_string($this->db->dbc, $id['id']);
            $images = $this->get_images($id);
            $images = reset($images);
        } else if(isset($id['good_id'])){
            $where = "good_id=" . mysqli_real_escape_string($this->db->dbc, $id['good_id']);
            $images = $this->get_images($id);
        }
        
        $images = (array)$images;
        
        foreach($images as $image) {
            @unlink(dirname(dirname(__DIR__)) . '/' . $this->settings->goods_images_dir . $image->filename_full);
            @unlink(dirname(dirname(__DIR__)) . '/' . $this->settings->goods_images_dir . $image->filename_small);
            @unlink(dirname(dirname(__DIR__)) . '/' . $this->settings->goods_images_dir . $image->filename_middle);
        }
        
        $this->query = "DELETE FROM
                    goods_images 
                    WHERE
                    $where
                ";
                
        if(!$this->db->make_query($this->query)){
            return false;
        }
        return true;
    }
    
    
    private function get_good_colors($filter = []){
        $limit = 100;
        $filter_id_where = '';
        $filter_good_id_where = '';
        
        if(isset($filter['id'])){
            $filter_id_where = "AND `id` = ". mysqli_real_escape_string($this->db->dbc, $filter['id']);
        }
        
        if(isset($filter['good_id'])){
            $filter_good_id_where = "AND `good_id` = ". mysqli_real_escape_string($this->db->dbc, $filter['good_id']);
        }
        
        $this->query = "SELECT 
                    `id` ,
                    `good_id` ,
                    `color` ,
                    `link`
                FROM goods_colors
                WHERE 1 
                    $filter_id_where
                    $filter_good_id_where
                LIMIT $limit
        ";
        
        $this->db->make_query($this->query);
        return $this->db->results();
        
    }
    
    private function add_good_color($color){
        if(empty($color)){
            return false;
        }
        
        
        $this->query = "INSERT INTO 
                    goods_colors 
                    (
                        `link`,
                        `color`,
                        `good_id`
                    )
                    VALUES
                    (
                        '" . $color['link'] . "',
                        '" . $color['color'] . "',
                        '" . $color['good_id'] . "'
                    )
                ";
                
        $this->db->make_query($this->query);
        return $this->db->insert_id();
    }
    
    private function delete_good_color($id = []){
        if(empty($id)){
            return false;
        }
        
        if(isset($id['id'])){
            $where = "AND id=" . mysqli_real_escape_string($this->db->dbc, $id['id']);
        } else if(isset($id['good_id'])){
            $where = "AND good_id=" . mysqli_real_escape_string($this->db->dbc, $id['good_id']);
        }
        
        $this->query = "DELETE FROM
                    goods_colors 
                    WHERE 1
                    $where
                ";
        
        if(!$this->db->make_query($this->query)){
            return false;
        }
        return true;
    }
    
    private function upload_image($file_name, $file_tmp_name, $images_dir){
        if(empty($file_name) || empty($file_tmp_name)){
            return false;
        }
        
        $file = $this->generate_image_name($file_name, $images_dir);
        
        move_uploaded_file($file_tmp_name, $file->file_name . $file->extension);
        return $file->file_name . $file->extension;
    }
    
    private function generate_image_name($file_name, $images_dir){
        if(empty($file_name) || empty($images_dir)){
            return false;
        }
        
        $allowed_extentions = ['.png', '.jpg', '.jpeg', '.gif'];
        
        $ext = [];
        $extension = '';
        if(preg_match_all('/\.[a-z]{3,4}$/i', $file_name, $ext)){
            $extension = $ext[0][0];
        }
        
        if(!in_array($extension, $allowed_extentions)){
            return false;
        }
        $path = dirname(dirname(__DIR__)) . '/' . $images_dir;
        
        do {
            $new_file_name = md5(microtime() . rand(0, 9999) . $file_name);
            $file = $path . $new_file_name . $extension;
        } while (file_exists($file));
        
        $result = new stdClass;
        $result->file_name = $new_file_name;
        $result->extension = $extension;
        
        return $result;
    }
    
    private function translit($text) {
        $ru = explode('-', "А-а-Б-б-В-в-Ґ-ґ-Г-г-Д-д-Е-е-Ё-ё-Є-є-Ж-ж-З-з-И-и-І-і-Ї-ї-Й-й-К-к-Л-л-М-м-Н-н-О-о-П-п-Р-р-С-с-Т-т-У-у-Ф-ф-Х-х-Ц-ц-Ч-ч-Ш-ш-Щ-щ-Ъ-ъ-Ы-ы-Ь-ь-Э-э-Ю-ю-Я-я");
        $en = explode('-', "A-a-B-b-V-v-G-g-G-g-D-d-E-e-E-e-E-e-ZH-zh-Z-z-I-i-I-i-I-i-J-j-K-k-L-l-M-m-N-n-O-o-P-p-R-r-S-s-T-t-U-u-F-f-H-h-TS-ts-CH-ch-SH-sh-SCH-sch---Y-y---E-e-YU-yu-YA-ya");
        
        $res = str_replace($ru, $en, $text);
        $res = preg_replace("/[\s]+/ui", '-', $res);
        $res = preg_replace("/[^a-zA-Z0-9\.\-\_]+/ui", '', $res);
        $res = strtolower($res);
        return $res;
    }
    
    private function resize($file_input, $file_output, $w_o, $h_o, $percent = false) {
        list($w_i, $h_i, $type) = getimagesize($file_input);
        if (!$w_i || !$h_i) {
            echo 'Невозможно получить длину и ширину изображения';
            return;
            }
            $types = array('','gif','jpeg','png');
            $ext = $types[$type];
            if ($ext) {
                    $func = 'imagecreatefrom'.$ext;
                    $img = $func($file_input);
            } else {
                    echo 'Некорректный формат файла';
            return;
            }
        if ($percent) {
            $w_o *= $w_i / 100;
            $h_o *= $h_i / 100;
        }
        if (!$h_o) $h_o = $w_o/($w_i/$h_i);
        if (!$w_o) $w_o = $h_o/($h_i/$w_i);

        $img_o = imagecreatetruecolor($w_o, $h_o);
        imagecopyresampled($img_o, $img, 0, 0, 0, 0, $w_o, $h_o, $w_i, $h_i);
        if ($type == 2) {
            return imagejpeg($img_o,$file_output,100);
        } else {
            $func = 'image'.$ext;
            return $func($img_o,$file_output);
        }
    }
    
}
