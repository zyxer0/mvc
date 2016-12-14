<?php

class Model_Order extends Model
{
    
    protected $query;
    protected $goods;
    
    public function __construct($db){
        parent::__construct($db);
    }
    
    public function add_purchase() {
        if(!empty($this->purchase)) {
            $this->query = "INSERT INTO 
                                `order_purchases` 
                            SET
                                `order_id`='".$this->purchase->order_id."',
                                `good_id`='".$this->purchase->good_id."',
                                `name`='".$this->purchase->name."',
                                `price`='".$this->purchase->price."',
                                `amount`='".$this->purchase->amount."'
                                ";
            $this->db->make_query($this->query);
        }
    }
    
    public function get_order($id = null) {
        
        if(empty($id)) {
            $id = trim(strip_tags(htmlspecialchars($_GET['id'])));
        }
        
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
            
            if(!empty($main_images_ids)) {
                foreach($this->get_images(['id'=>$main_images_ids]) as $image) {
                    $main_images[$image->id] = $image;
                }
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
    
    public function add_order() {
        
        $email      = trim(htmlspecialchars(strip_tags($_POST['email'])));
        $name       = trim(htmlspecialchars(strip_tags($_POST['name'])));
        $last_name  = trim(htmlspecialchars(strip_tags($_POST['last_name'])));
        $patronymic = trim(htmlspecialchars(strip_tags($_POST['patronymic'])));
        $city       = trim(htmlspecialchars(strip_tags($_POST['city'])));
        $country    = trim(htmlspecialchars(strip_tags($_POST['country'])));
        $phone      = trim(htmlspecialchars(strip_tags($_POST['phone'])));
        $comment    = trim(htmlspecialchars(strip_tags($_POST['comment'])));
        $newposht_address = trim(htmlspecialchars(strip_tags($_POST['newposht_address'])));
        $delivery_type = trim(htmlspecialchars(strip_tags($_POST['delivery_type'])));
        
        if (!Validation::validate_form(["text"=>$name, "email"=>$email])) {
            die();
        }
        
        setcookie('cart', '', time()-60*60*24*30, '/');
        
        do {
            $url = md5(time().'jdaslkjf847345HJKJHsdfhhshj'.rand(0, 999999));
            $this->db->make_query("SELECT `id` FROM `orders` WHERE url='".$url."' LIMIT 1");
        } while($this->db->result());
        
        $order = new stdClass;
        $order->user_id    = null;
        $order->email      = $email;
        $order->name       = $name;
        $order->last_name  = $last_name;
        $order->patronymic = $patronymic;
        $order->city       = $city;
        $order->country    = $country;
        $order->phone      = $phone;
        $order->comment    = $comment;
        $order->newposht_address    = $newposht_address;
        $order->delivery_type    = $delivery_type;
        $order->url        = $url;
        
        $order->email      = mysqli_real_escape_string($this->db->dbc, $order->email);
        $order->name       = mysqli_real_escape_string($this->db->dbc, $order->name);
        $order->last_name  = mysqli_real_escape_string($this->db->dbc, $order->last_name);
        $order->patronymic = mysqli_real_escape_string($this->db->dbc, $order->patronymic);
        $order->city       = mysqli_real_escape_string($this->db->dbc, $order->city);
        $order->country    = mysqli_real_escape_string($this->db->dbc, $order->country);
        $order->phone      = mysqli_real_escape_string($this->db->dbc, $order->phone);
        $order->comment    = mysqli_real_escape_string($this->db->dbc, $order->comment);
        $order->newposht_address    = mysqli_real_escape_string($this->db->dbc, $order->newposht_address);
        $order->delivery_type    = mysqli_real_escape_string($this->db->dbc, $order->delivery_type);
        $order->url        = mysqli_real_escape_string($this->db->dbc, $order->url);
        
        if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            $order->user_id = (int)$_SESSION['user_id'];
        }
        
        $this->query = "INSERT INTO 
                            `orders` 
                        SET
                            `user_id`='".$order->user_id."',
                            `email`='".$order->email."',
                            `name`='".$order->name."',
                            `last_name`='".$order->last_name."',
                            `patronymic`='".$order->patronymic."',
                            `city`='".$order->city."',
                            `country`='".$order->country."',
                            `phone`='".$order->phone."',
                            `comment`='".$order->comment."',
                            `newposht_address`='".$order->newposht_address."',
                            `delivery_type`='".$order->delivery_type."',
                            `url`='".$order->url."'
                            ";
        $this->db->make_query($this->query);
        $id = $this->db->insert_id();
        
        if(!empty($id)) {
            
            $cart = $this->get_cart();
        
            foreach($cart->goods as $good) {
                $this->purchase = new stdClass;
                $this->purchase->order_id = $id;
                $this->purchase->good_id  = $good->id;
                $this->purchase->name     = $good->name;
                $this->purchase->price    = $good->price;
                $this->purchase->amount   = $good->amount;
                $this->add_purchase();
            }
            
            $router = new stdClass;
            $router->alias = 'order/'.$order->url;
            $router->route = 'order/index?id='.$id;
            $router->object_id = $id;
            $router->object = 'order';
            
            $this->query = "INSERT INTO 
                                `router` 
                            SET
                                `alias`='".$router->alias."',
                                `route`='".$router->route."',
                                `object_id`='".$router->object_id."',
                                `object`='".$router->object."'
                                ";
            $this->db->make_query($this->query);
            
            return $order->url;
        } else {
            return false;
        }
        
    }
}
