<?php

class Model_Cart extends Model
{
    
    protected $query;
    protected $goods;
    
    public function __construct($db){
        parent::__construct($db);
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
        return $good;
    }
    
    public function add_item($good_id, $amount = 1) {
        if(isset($_COOKIE['cart'])) {
            $cart = unserialize(trim(strip_tags($_COOKIE['cart'])));
        } else {
            $cart = [];
        }
        
        if(isset($cart[$good_id])) {
            $cart[$good_id] += $amount;
        } else {
            $cart[$good_id] = $amount;
        }
        
        $_COOKIE['cart'] = serialize($cart);
        setcookie('cart', serialize($cart), time()+60*60*24*30, '/');
    }
    
    function change_amount($good_id, $amount = 1){
        
        if(isset($_COOKIE['cart'])){
            $cart = unserialize(trim(strip_tags($_COOKIE['cart'])));
        } else {
            $cart = [];
        }
        
        $cart[$good_id] = $amount;
        $_COOKIE['cart'] = serialize($cart);
        setcookie('cart', serialize($cart), time()+60*60*24*30, '/');
        
    }
    
    function remove($good_id){
        if(!isset($_COOKIE['cart'])){
            return false;
        }
        
        $cart = unserialize(trim(strip_tags($_COOKIE['cart'])));
        unset($cart[$good_id]);
        setcookie('cart', serialize($cart), time()+60*60*24*30, '/');
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
