<?php

class Model
{
    public $settings;
    protected $db;
    public $query;
    public $all_categories;
    public $categories_tree;
    
    public function __construct(){
        $this->db = new Db();
        global $settings;
        $this->settings = $settings;
        
    }
    // метод выборки данных
    public function get_data($type, $param)
    {
        switch($type){
            case 'admin': 
                $query = "SELECT `username`, `id` FROM `managers` WHERE `id`='".$param['id']."' LIMIT 1";
                $this->db->make_query($query);
                $manager = $this->db->result();
                
                $query = "SELECT `permission_id` FROM `managers_permissions` WHERE `manager_id`='" . $manager->id . "'";
                $this->db->make_query($query);
                $permissions = $this->db->results('permission_id');
                if($permissions) {
                    foreach($permissions as $permission){
                        if(empty($permissions_string)) {
                            $permissions_string = "'" . $permission . "'";
                        } else {
                            $permissions_string .= ", '" . $permission . "'";
                        }
                    }
                    
                    $query = "SELECT `permission`, `name_menu_item` FROM `permissions` WHERE `id` in (" . $permissions_string . ")";
                    
                    $this->db->make_query($query);
                    foreach($this->db->results() as $result) {
                        $manager->permissions[$result->permission] = $result->name_menu_item;
                    }
                    
                } else {
                    $manager->permissions = [];
                }
                
                return $manager;
                break;
        }
    }
    
    public function get_images($filter = []){
        $limit = 100;
        $filter_id_where = '';
        $filter_good_id_where = '';
        
        if(isset($filter['id'])){
            $images_ids = implode(',', (array)$filter['id']);
            $filter_id_where = "AND `id` in ($images_ids)";
        }
        
        if(isset($filter['good_id'])){
            $goods_ids = implode(',', (array)$filter['good_id']);
            $filter_good_id_where = "AND `good_id` in ($goods_ids)";
        }
        
        $this->query = "SELECT 
                    `id`,
                    `filename_full`,
                    `filename_middle`,
                    `filename_small`,
                    `good_id`,
                    `alt`,
                    `title`
                FROM goods_images
                WHERE 1 
                    $filter_id_where
                    $filter_good_id_where
                ORDER BY id ASC
                LIMIT $limit
        ";
        
        $this->db->make_query($this->query);
        return $this->db->results();
    }
    
    public function get_categories() {
        if(!isset($this->categories_tree)) {
            $this->init_categories();
        }
        return $this->categories_tree;
    }
    
    public function count_categories() {
        return count($this->all_categories);
    }
    
    private function init_categories() {
        // Дерево категорий
        $tree = new stdClass();
        $tree->subcategories = array();
        
        // Указатели на узлы дерева
        $pointers = array();
        $pointers[0] = &$tree;
        $pointers[0]->path = array();
        $pointers[0]->level = 0;
        
        $this->query = "SELECT *
                FROM categories
                LIMIT 100
        ";
        
        $this->db->make_query($this->query);
        $categories = $this->db->results();
        
        //$this->all_categories = $categories;
        
        $finish = false;
        // Не кончаем, пока не кончатся категории, или пока ниодну из оставшихся некуда приткнуть
        while(!empty($categories)  && !$finish) {
            $flag = false;
            // Проходим все выбранные категории
            foreach($categories as $k=>$category) {
                if(isset($pointers[$category->parent_id])) {
                    // В дерево категорий (через указатель) добавляем текущую категорию
                    $pointers[$category->id] = $pointers[$category->parent_id]->subcategories[] = $category;
                    
                    // Путь к текущей категории
                    $curr = $pointers[$category->id];
                    $pointers[$category->id]->path = array_merge((array)$pointers[$category->parent_id]->path, array($curr));
                    
                    // Убираем использованную категорию из массива категорий
                    unset($categories[$k]);
                    $flag = true;
                }
            }
            if(!$flag) $finish = true;
        }
        
        // Для каждой категории id всех ее деток узнаем
        $ids = array_reverse(array_keys($pointers));
        foreach($ids as $id) {
            if($id>0) {
                $pointers[$id]->children[] = $id;
                
                if(isset($pointers[$pointers[$id]->parent_id]->children)) {
                    $pointers[$pointers[$id]->parent_id]->children = array_merge($pointers[$id]->children, $pointers[$pointers[$id]->parent_id]->children);
                } else {
                    $pointers[$pointers[$id]->parent_id]->children = $pointers[$id]->children;
                }
            }
        }
        unset($pointers[0]);
        unset($ids);
        
        $this->categories_tree = $tree->subcategories;
        $this->all_categories = $pointers;
    }
    
    public function get_category($id){
        if(!isset($this->all_categories)) {
            $this->init_categories();
        }
        
        if(is_int($id) && array_key_exists(intval($id), $this->all_categories)) {
            return $category = $this->all_categories[intval($id)];
        }
        return false;
    }
}