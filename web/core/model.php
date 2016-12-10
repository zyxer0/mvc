<?php

class Model
{
    protected $db;
    public $all_categories;
    public $categories_tree;
    /*
        Модель обычно включает методы выборки данных, это могут быть:
            > методы нативных библиотек pgsql или mysql;
            > методы библиотек, реализующих абстракицю данных. Например, методы библиотеки PEAR MDB2;
            > методы ORM;
            > методы для работы с NoSQL;
            > и др.
    */
    public function __construct($db){
        $this->db = $db;
    }
    // метод выборки данных
    public function get_data($type, $param = array())
    {
        switch($type){
            case 'user_name': 
                $query = "SELECT name FROM `users` WHERE `id`='".$param['id']."' LIMIT 1";
                $this->db->make_query($query);
                return $this->db->result('name');
                break;
            case 'user': 
                $query = "SELECT * FROM `users` WHERE `id`='".$param['id']."' LIMIT 1";
                $this->db->make_query($query);
                return $this->db->result();
                break;
            case 'user_remember_token': 
                $query = "SELECT remember_token FROM `users` WHERE `id`='".$param['id']."' LIMIT 1";
                $this->db->make_query($query);
                return $this->db->result('remember_token');
                break;
            case 'categories': 
                return $this->get_categories();
                break;
            case 'cart': 
                return $this->get_cart();
                break;
        }
    }
    
    public function get_cart(){
        
        if(!isset($_COOKIE['cart'])){
            return false;
        }
        
        $cart_cookie = unserialize(trim(strip_tags($_COOKIE['cart'])));
        if(count($cart_cookie)>0){
            $cart = new stdClass;
            $cart->total_goods = 0;
            $cart->total_price = 0;
            
            $goods_ids = implode(',', (array)array_keys($cart_cookie));
            $query = "SELECT * FROM `goods` WHERE `id` in ($goods_ids)";
            $this->db->make_query($query);
            
            $goods = [];
            foreach($this->db->results() as $good) {
                $goods[$good->id] = $good;
            }
            
            foreach($cart_cookie as $good_id=>$amount){
                $cart->goods[$good_id] = $goods[$good_id];
                $cart->goods[$good_id]->amount = $amount;
                $cart->total_goods += $amount;
                $cart->total_price += $cart->goods[$good_id]->price * $amount;
            }
            
            return $cart;
        } else {
            return false;
        }
    }
    
    public function get_categories() {
        if(!isset($this->categories_tree)) {
            $this->init_categories();
        }
        return $this->categories_tree;
    }
    
    public function init_categories() {
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
}