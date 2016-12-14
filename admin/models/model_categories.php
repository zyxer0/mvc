<?php

class Model_Categories extends Model {
    
    public function __construct(){
        parent::__construct();
    }
    
    public function remove($ids) {
        $ids = (array) $ids;
        foreach($ids as $id) {
            if($category = $this->get_category(intval($id))) {
                if(!empty($category->children)) {
                    $category_id_where = "";
                    
                    foreach($category->children as $cc) {
                        $remove_ids[] = "'$cc'";
                    }
                    $category_id_where = implode(",", $remove_ids);
                    
                    $this->query = "DELETE FROM 
                                categories 
                                WHERE `id` IN ($category_id_where)";
                    $this->db->make_query($this->query);
                    
                    $this->query = "DELETE FROM
                            router 
                            WHERE
                            `object_id` IN ($category_id_where) AND `object` = 'category'
                        ";
                    $this->db->make_query($this->query);
                }
            }
        }
        unset($this->categories_tree);
        unset($this->all_categories);
        return $id;
    }
    
    public function add(){
        
        $category = new stdClass;
        $category->name             = trim(htmlspecialchars(strip_tags($_POST['name'])));
        $category->url              = trim(htmlspecialchars(strip_tags($_POST['url'])));
        $category->visible          = isset($_POST['visible']) ? true : false;
        $category->meta_title       = trim(htmlspecialchars(strip_tags($_POST['meta_title'])));
        $category->meta_description = trim(htmlspecialchars(strip_tags($_POST['meta_description'])));
        $category->meta_keywords    = trim(htmlspecialchars(strip_tags($_POST['meta_keywords'])));
        $category->description      = trim($_POST['description']);
        $category->parent_id        = trim(htmlspecialchars(strip_tags($_POST['parent_id'])));
        
        if(!Validation::check_empty($category->name) || !Validation::check_empty($category->name)){
            return false;
        }
        if(!Validation::check_text($category->meta_title)){
            return false;
        }
        if(!Validation::check_text($category->meta_description)){
            return false;
        }
        if(!Validation::check_text($category->meta_keywords)){
            return false;
        }
        
        if(!empty($category->url) && !Validation::check_url($category->url)){
            $category->url = $this->translit($category->url);
        } else if(empty($category->url) || !Validation::check_url($category->url)){
            $category->url = $this->translit($category->name) . '_' . $id;
        }
        
        $this->query = "INSERT INTO 
                    categories 
                    SET
                    `name`='" . $category->name . "',
                    `url`='" . $category->url . "',
                    `parent_id`='" . $category->parent_id . "',
                    `visible`='" . $category->visible . "',
                    `description`='" . $category->description . "',
                    `meta_title`='" . $category->meta_title . "',
                    `meta_description`='" . $category->meta_description . "',
                    `meta_keywords`='" . $category->meta_keywords . "'
                ";
                
        if(!$this->db->make_query($this->query)){
            return false;
        }
        
        if($id = $this->db->insert_id()) {
            
            if(empty($category->url) || !Validation::check_url($category->url)){
                $category->url = $this->translit($category->name) . '_' . $id;
            }
            
            do {
                $this->query = "
                                SELECT 
                                `id`
                                FROM categories
                                WHERE `id`!='" . $id . "' AND `url`='" . $category->url . "' LIMIT 1
                            ";
                
                $this->db->make_query($this->query);
                
                if($result = $this->db->result()) {
                    $category->url .=  $id;
                }
            } while($result);
            
            $this->query = "INSERT INTO 
                    router 
                    SET
                    `alias`='" . $category->url . "',
                    `route`='goods/category?id=" . $id . "',
                    `object`='category',
                    `object_id`='" . $id . "'
                ";
                
            $this->db->make_query($this->query);
            
            
            $this->query = "UPDATE
                    categories 
                    SET
                    `url`='" . $category->url . "'
                    WHERE `id`=" . $id . "
                    LIMIT 1
                ";
                
            $this->db->make_query($this->query);
            
            return $id;
        }
        
        return false;
        
    }
    
    public function update($id){
        if(empty($id)){
            return false;
        }
        
        $category = new stdClass;
        $category->name             = trim(htmlspecialchars(strip_tags($_POST['name'])));
        $category->url              = trim(htmlspecialchars(strip_tags($_POST['url'])));
        $category->visible          = isset($_POST['visible']) ? true : false;
        $category->meta_title       = trim(htmlspecialchars(strip_tags($_POST['meta_title'])));
        $category->meta_description = trim(htmlspecialchars(strip_tags($_POST['meta_description'])));
        $category->meta_keywords    = trim(htmlspecialchars(strip_tags($_POST['meta_keywords'])));
        $category->description      = trim($_POST['description']);
        $category->parent_id        = trim(htmlspecialchars(strip_tags($_POST['parent_id'])));
        
        if(!Validation::check_empty($category->name) || !Validation::check_empty($category->name)){
            return false;
        }
        if(!Validation::check_text($category->meta_title)){
            return false;
        }
        if(!Validation::check_text($category->meta_description)){
            return false;
        }
        if(!Validation::check_text($category->meta_keywords)){
            return false;
        }
        
        if(!empty($category->url) && !Validation::check_url($category->url)){
            $category->url = $this->translit($category->url);
        } else if(empty($category->url) || !Validation::check_url($category->url)){
            $category->url = $this->translit($category->name) . '_' . $id;
        }
        
        if($category->parent_id == $category->id) {
            $category->parent_id = 0;
        }
        
        do {
            $this->query = "
                            SELECT 
                            `id`
                            FROM categories
                            WHERE `id`!='" . $id . "' AND `url`='" . $category->url . "' LIMIT 1
                        ";
            
            $this->db->make_query($this->query);
            
            if($result = $this->db->result()) {
                $category->url .=  $id;
            }
        } while($result);
        
        $this->query = "UPDATE
                    categories 
                    SET
                    `name`='" . $category->name . "',
                    `url`='" . $category->url . "',
                    `parent_id`='" . $category->parent_id . "',
                    `visible`='" . $category->visible . "',
                    `description`='" . $category->description . "',
                    `meta_title`='" . $category->meta_title . "',
                    `meta_description`='" . $category->meta_description . "',
                    `meta_keywords`='" . $category->meta_keywords . "'
                    WHERE `id`=" . $id . "
                    LIMIT 1
                ";
                
        if(!$this->db->make_query($this->query)){
            return false;
        }
        
        $this->query = "UPDATE
                    router 
                    SET
                    `alias`='" . $category->url . "'
                    WHERE `route`='goods/category?id=" . $id . "'
                    LIMIT 1
                ";
        
        $this->db->make_query($this->query);
        
        return true;
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
    
}
