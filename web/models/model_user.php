<?
class Model_User extends Model
{
    protected $name;
    protected $passwd;
    protected $phone;
    protected $email;
    protected $query;
    protected $valid;

    public function __construct($db){
        parent::__construct($db);
    }

    public function auth_user(){
        
        if(isset($_POST['auth']) && trim(htmlspecialchars(strip_tags($_POST['auth'])))) {
            // Validation
            $this->password = trim(htmlspecialchars(strip_tags($_POST['password'])));
            $this->email = trim(htmlspecialchars(strip_tags($_POST['email'])));

            $this->valid = Validation::validate_form(['password'=>$this->password, "email"=>$this->email]);

            if (!$this->valid) {
                die();
            }
            
            $this->password = mysqli_real_escape_string($this->db->dbc, $this->password);
            $this->email = mysqli_real_escape_string($this->db->dbc, $this->email);
        
        
            $this->query = "SELECT id, password FROM `users` WHERE `email`='$this->email' LIMIT 1";
            $this->db->make_query($this->query);
            if($result = $this->db->result()){
                if(password_verify($this->password, $result->password)){
                    
                    if(isset($_POST['remember']) && !empty($_POST['remember'])){
                        $remember_token = md5($this->email . time() . rand(1, 99999));
                        $remember = [
                            'user_id'=>$result->id,
                            'token'=>$remember_token,
                        ];
                        
                        $this->query = "UPDATE `users` SET 
                            `remember_token`='$remember_token'
                            WHERE `id`='" . $result->id . "' LIMIT 1";
                        $this->db->make_query($this->query);
                        
                        setcookie('remember', serialize($remember), time() + 60*60*24*30, '/');
                    }
                    return $result->id;
                }
                return false;
            }
        }
        return false;
        
    }
    
    public function delete_user(){
        
        $this->query = "SELECT id, password,`name`, `email`, `phone` FROM `users` WHERE `id`='" . $_SESSION['user_id'] . "' LIMIT 1";
        $this->db->make_query($this->query);
        $result = $this->db->result();
        
        $this->query = "INSERT INTO `deleted_users` (`original_id`, `name`, `password`, `email`, `phone`) VALUES ('$result->id', '$result->name', '$result->password', '$result->email', '$result->phone')";
        $this->db->make_query($this->query);
        
        $this->query = "DELETE FROM `users` WHERE `id`='" . $_SESSION['user_id'] . "' LIMIT 1";
        $this->db->make_query($this->query);
        
    }
    
    public function get_user_info(){
        
        $this->query = "SELECT * FROM `users` WHERE `id`='" . $_SESSION['user_id'] . "' LIMIT 1";
        $this->db->make_query($this->query);
        if($result = $this->db->result()){
            return $result;
        }
        return false;
        
    }
    
    public function update_user(){
        
        if(isset($_POST['update']) && trim(htmlspecialchars(strip_tags($_POST['update'])))) {
            $this->name         = trim(htmlspecialchars(strip_tags($_POST['name'])));
            $this->email        = trim(htmlspecialchars(strip_tags($_POST['email'])));
            $this->phone        = trim(htmlspecialchars(strip_tags($_POST['phone'])));
            $this->last_name    = trim(htmlspecialchars(strip_tags($_POST['last_name'])));
            $this->patronymic   = trim(htmlspecialchars(strip_tags($_POST['patronymic'])));
            $this->country      = trim(htmlspecialchars(strip_tags($_POST['country'])));
            $this->city         = trim(htmlspecialchars(strip_tags($_POST['city'])));
            $this->address      = trim(htmlspecialchars(strip_tags($_POST['address'])));
            $this->password     = null;
            
            
            $this->valid = Validation::validate_form(["text"=>$this->name, "email"=>$this->email]);
            
            if (!$this->valid) {
                die();
            }
            
            if(isset($_POST['password']) && !empty($_POST['password'])) {
                $this->password = trim(htmlspecialchars(strip_tags($_POST['password'])));
                $this->password = password_hash($this->password, PASSWORD_DEFAULT);
                $this->password = mysqli_real_escape_string($this->db->dbc, $this->password);
                $this->valid    = Validation::validate_form(['password'=>$this->password]);
            }
            
            if (!$this->valid) {
                die();
            }
            
            $this->name         = mysqli_real_escape_string($this->db->dbc, $this->name);
            $this->email        = mysqli_real_escape_string($this->db->dbc, $this->email);
            $this->phone        = mysqli_real_escape_string($this->db->dbc, $this->phone);
            $this->last_name    = mysqli_real_escape_string($this->db->dbc, $this->last_name);
            $this->patronymic   = mysqli_real_escape_string($this->db->dbc, $this->patronymic);
            $this->country      = mysqli_real_escape_string($this->db->dbc, $this->country);
            $this->city         = mysqli_real_escape_string($this->db->dbc, $this->city);
            $this->address      = mysqli_real_escape_string($this->db->dbc, $this->address);
            
            $password_update = '';
            
            if($this->password){
                $password_update = ",`password`='$this->password'";
            }
            
            $this->query = "UPDATE `users` SET 
                            `name`='$this->name',
                            `email`='$this->email',
                            `phone`='$this->phone',
                            `last_name`='$this->last_name',
                            `patronymic`='$this->patronymic',
                            `country`='$this->country',
                            `city`='$this->city',
                            `address`='$this->address'
                            $password_update
                            WHERE `id`='" . $_SESSION['user_id'] . "' LIMIT 1";
            $this->db->make_query($this->query);
        }
    }
    
    public function reg_user(){
        
        if(isset($_POST['register']) && trim(htmlspecialchars(strip_tags($_POST['register'])))) {
            $this->name         = trim(htmlspecialchars(strip_tags($_POST['name'])));
            $this->password     = trim(htmlspecialchars(strip_tags($_POST['password'])));
            $this->email        = trim(htmlspecialchars(strip_tags($_POST['email'])));
            $this->phone        = trim(htmlspecialchars(strip_tags($_POST['phone'])));
            $this->last_name    = trim(htmlspecialchars(strip_tags($_POST['last_name'])));
            $this->patronymic   = trim(htmlspecialchars(strip_tags($_POST['patronymic'])));
            $this->country      = trim(htmlspecialchars(strip_tags($_POST['country'])));
            $this->city         = trim(htmlspecialchars(strip_tags($_POST['city'])));
            $this->address      = trim(htmlspecialchars(strip_tags($_POST['address'])));
            
            $this->valid = Validation::validate_form(["text"=>$this->name, 'password'=>$this->password, "email"=>$this->email]);
            
            if (!$this->valid) {
                die();
            }
            
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
            
            $this->name         = mysqli_real_escape_string($this->db->dbc, $this->name);
            $this->password     = mysqli_real_escape_string($this->db->dbc, $this->password);
            $this->email        = mysqli_real_escape_string($this->db->dbc, $this->email);
            $this->phone        = mysqli_real_escape_string($this->db->dbc, $this->phone);
            $this->last_name    = mysqli_real_escape_string($this->db->dbc, $this->last_name);
            $this->patronymic   = mysqli_real_escape_string($this->db->dbc, $this->patronymic);
            $this->country      = mysqli_real_escape_string($this->db->dbc, $this->country);
            $this->city         = mysqli_real_escape_string($this->db->dbc, $this->city);
            $this->address      = mysqli_real_escape_string($this->db->dbc, $this->address);
            
            $this->query = "SELECT id FROM `users` WHERE `email`='$this->email' LIMIT 1";
            $this->db->make_query($this->query);
            if(!$this->db->result('id')){
                $this->query = "INSERT INTO `users`(`name`, `password`, `email`, `phone`, `last_name`, `patronymic`, `country`, `city`, `address`) VALUES ('$this->name', '$this->password', '$this->email', '$this->phone', '$this->last_name', '$this->patronymic', '$this->country', '$this->city', '$this->address')";
                if($this->db->make_query($this->query)){
                    
                    $user_id = $this->db->insert_id();
                    
                    if(isset($_POST['remember']) && !empty($_POST['remember'])){
                        $remember_token = md5($this->email . time() . rand(1, 99999));
                        $remember = [
                            'user_id'=>$user_id,
                            'token'=>$remember_token,
                        ];
                        
                        $this->query = "UPDATE `users` SET 
                            `remember_token`='$remember_token'
                            WHERE `id`='" . $user_id . "' LIMIT 1";
                        $this->db->make_query($this->query);
                        setcookie('remember', serialize($remember), time() + 60*60*24*30, '/');
                    }
                    
                    return $user_id;
                } else {
                    return false;
                }
            }
        }
        return false;
        
    }
    
    public function get_orders() {
        
        $id = (int)$_SESSION['user_id'];
        
        if(!empty($id)) {
            
            $orders = [];
            $query = "SELECT o.*, os.`status_name` as `status` FROM `orders` AS o LEFT JOIN `order_statuses` AS os ON o.`status_id`=os.`id` WHERE o.`user_id`='$id'";
            
            $this->db->make_query($query);
            foreach($this->db->results() as $order) {
                $orders[$order->id] = $order;
            }
            
            if(!empty($orders)) {
                $orders_ids = implode(',', array_keys($orders));
                
                $query = "SELECT * FROM `order_purchases` WHERE `order_id` in ($orders_ids)";
                $this->db->make_query($query);
                
                $orders_purchases = [];
                $purchases = [];
                foreach($this->db->results() as $purchase) {
                    $orders_purchases[$purchase->order_id][$purchase->good_id] = $purchase;
                    $purchases[$purchase->good_id] = $purchase;
                }
                
                foreach($orders as $order) {
                    if(isset($orders_purchases[$order->id])) {
                        $order->purchases = $orders_purchases[$order->id];
                    }
                }
                
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
                
                foreach($orders as $id=>$order) {
                    if(empty($order->purchases)) {
                        unset($orders[$id]);
                    } else {
                        $order->total_price = 0;
                        foreach($order->purchases as $purchase){
                            if(isset($goods[$purchase->good_id])) {
                                $purchase->good = $goods[$purchase->good_id];
                            } else {
                                $purchase->good = new stdCLass;
                            }
                            
                            $order->total_price += $purchase->price * $purchase->amount;
                        }
                    }
                }
            }
            return $orders;
        } else {
            return false;
        }
    }
    
}
?>