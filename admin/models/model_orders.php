<?php

class Model_Orders extends Model
{
    
    protected $query;
    
    public function __construct() {
        parent::__construct();
    }
    
    public function get_orders() {
        $status_id_filter = '';
        
        if(isset($_GET['status_id'])) {
            $status_id = trim(strip_tags(htmlspecialchars($_GET['status_id'])));
            $status_id_filter = "AND status_id='".mysqli_real_escape_string($this->db->dbc, (int)$status_id)."'";
        }
        
        $this->query = "SELECT *
                FROM orders
                WHERE 1
                    $status_id_filter
        ";
        
        $this->db->make_query($this->query);
        $orders = $this->db->results();
        
        return $orders;
    }
    
    public function get_statuses() {
        
        $this->query = "SELECT *
                FROM order_statuses
        ";
        
        $this->db->make_query($this->query);
        return $this->db->results();
    }
    
    public function get_order() {
        
        $id = trim(strip_tags(htmlspecialchars($_GET['id'])));
        
        if(!empty($id)) {
            
            $query = "SELECT o.*, os.`status_name` as `status` FROM `orders` AS o LEFT JOIN `order_statuses` AS os ON o.`status_id`=os.`id` WHERE o.`id`='$id'";
            
            $this->db->make_query($query);
            $order = $this->db->result();
            
            $query = "SELECT * FROM `order_purchases` WHERE `order_id`='$id'";
            $this->db->make_query($query);
            
            $purchases = [];
            foreach($this->db->results() as $purchase) {
                $purchases[$purchase->good_id] = $purchase;
            }
            
            if(empty($purchases)) {
                return false;
            }
            $order->purchases = $purchases;
            
            $purchases_ids = implode(',', (array)array_keys($purchases));
            $query = "SELECT * FROM `goods` WHERE `id` in ($purchases_ids)";
            $this->db->make_query($query);
            
            $goods = [];
            $main_images_ids = [];
            foreach($this->db->results() as $good) {
                $goods[$good->id] = $good;
                if($good->main_image_id) {
                    $main_images_ids[] = $good->main_image_id;
                }
            }
            
            $images = $this->get_images(['good_id'=>array_keys($goods)]);
            foreach($images as $image) {
                $goods[$image->good_id]->images[] = $image;
            }
            
            foreach($this->get_images(['id'=>$main_images_ids]) as $image) {
                $main_images[$image->id] = $image;
            }
            
            foreach($goods as $good) {
                if(!empty($good->main_image_id)) {
                    $good->image = $main_images[$good->main_image_id];
                } else {
                    $good->image = reset($good->images);
                }
            }
            
            foreach($goods as $good) {
                if(isset($order->purchases[$good->id])) {
                    $order->purchases[$good->id]->good = $good;
                }
            }
            
            $order->total_price = 0;
            foreach($order->purchases as $purchase){
                $order->total_price += $purchase->price * $purchase->amount;
            }
            return $order;
        } else {
            return false;
        }
    }
    
    public function update(){
        
        $id = trim(strip_tags(htmlspecialchars($_GET['id'])));
        
        if(empty($id)){
            return false;
        }
        
        $order = new stdClass;
        $order->status_id    = trim(htmlspecialchars(strip_tags($_POST['status_id'])));
        $order->name         = trim(htmlspecialchars(strip_tags($_POST['name'])));
        $order->last_name    = trim(htmlspecialchars(strip_tags($_POST['last_name'])));
        $order->patronymic   = trim(htmlspecialchars(strip_tags($_POST['patronymic'])));
        $order->email        = trim(htmlspecialchars(strip_tags($_POST['email'])));
        $order->phone        = trim(htmlspecialchars(strip_tags($_POST['phone'])));
        $order->country      = trim(htmlspecialchars(strip_tags($_POST['country'])));
        $order->city         = trim(htmlspecialchars(strip_tags($_POST['city'])));
        
        if(!Validation::check_text($order->name)){
            return false;
        }
        if(!Validation::check_text($order->last_name)){
            return false;
        }
        if(!Validation::check_text($order->patronymic)){
            return false;
        }
        if(!Validation::check_email($order->email)){
            return false;
        }
        if(!Validation::check_text($order->phone)){
            return false;
        }
        if(!Validation::check_text($order->country)){
            return false;
        }
        if(!Validation::check_text($order->city)){
            return false;
        }
        
        
        $this->query = "UPDATE
                    orders 
                    SET
                    `status_id`='" . $order->status_id . "',
                    `name`='" . $order->name . "',
                    `last_name`='" . $order->last_name . "',
                    `patronymic`='" . $order->patronymic . "',
                    `email`='" . $order->email . "',
                    `phone`='" . $order->phone . "',
                    `country`='" . $order->country . "',
                    `city`='" . $order->city . "'
                    WHERE `id`=" . $id . "
                    LIMIT 1
                ";
                
        if(!$this->db->make_query($this->query)){
            return false;
        }
        /* 
        $this->query = "UPDATE
                    router 
                    SET
                    `alias`='" . $good->url . "'
                    WHERE `route`='goods/view?id=" . $id . "' LIMIT 1
                ";
        
        $this->db->make_query($this->query);
        
         */
        return $id;
    }
}
