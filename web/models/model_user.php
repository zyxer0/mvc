<?
class Model_User extends Model
{
    protected $name;
    protected $passwd;
    protected $phone;
    protected $email;
    protected $query;
    protected $valid;

    public function __construct(){
        parent::__construct();
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
        
        $this->query = "SELECT `name`, `email`, `phone` FROM `users` WHERE `id`='" . $_SESSION['user_id'] . "' LIMIT 1";
        $this->db->make_query($this->query);
        if($result = $this->db->result()){
            return $result;
        }
        return false;
        
    }
    
    public function update_user(){
        
        if(isset($_POST['update']) && trim(htmlspecialchars(strip_tags($_POST['update'])))) {
            $this->name = trim(htmlspecialchars(strip_tags($_POST['name'])));
            $this->email = trim(htmlspecialchars(strip_tags($_POST['email'])));
            $this->phone = trim(htmlspecialchars(strip_tags($_POST['phone'])));
            $this->password = null;
            
            
            $this->valid = Validation::validate_form(["text"=>$this->name, "email"=>$this->email]);
            
            if (!$this->valid) {
                die();
            }
            
            if(isset($_POST['password']) && !empty($_POST['password'])) {
                $this->password = trim(htmlspecialchars(strip_tags($_POST['password'])));
                $this->password = password_hash($this->password, PASSWORD_DEFAULT);
                $this->password = mysqli_real_escape_string($this->db->dbc, $this->password);
                $this->valid = Validation::validate_form(['password'=>$this->password]);
            }
            
            if (!$this->valid) {
                die();
            }
            
            $this->name = mysqli_real_escape_string($this->db->dbc, $this->name);
            $this->email = mysqli_real_escape_string($this->db->dbc, $this->email);
            $this->phone = mysqli_real_escape_string($this->db->dbc, $this->phone);
            
            $password_update = '';
            
            if($this->password){
                $password_update = ",`password`='$this->password'";
            }
            
            $this->query = "UPDATE `users` SET 
                            `name`='$this->name',
                            `email`='$this->email',
                            `phone`='$this->phone'
                            $password_update
                            WHERE `id`='" . $_SESSION['user_id'] . "' LIMIT 1";
            $this->db->make_query($this->query);
        }
    }
    
    public function reg_user(){
        
        if(isset($_POST['register']) && trim(htmlspecialchars(strip_tags($_POST['register'])))) {
            $this->name = trim(htmlspecialchars(strip_tags($_POST['name'])));
            $this->password = trim(htmlspecialchars(strip_tags($_POST['password'])));
            $this->email = trim(htmlspecialchars(strip_tags($_POST['email'])));
            $this->phone = trim(htmlspecialchars(strip_tags($_POST['phone'])));
            
            $this->valid = Validation::validate_form(["text"=>$this->name, 'password'=>$this->password, "email"=>$this->email]);
            
            if (!$this->valid) {
                die();
            }
            
            $this->password = password_hash($this->password, PASSWORD_DEFAULT);
            
            $this->name = mysqli_real_escape_string($this->db->dbc, $this->name);
            $this->password = mysqli_real_escape_string($this->db->dbc, $this->password);
            $this->email = mysqli_real_escape_string($this->db->dbc, $this->email);
            $this->phone = mysqli_real_escape_string($this->db->dbc, $this->phone);
            
            $this->query = "SELECT id FROM `users` WHERE `email`='$this->email' LIMIT 1";
            $this->db->make_query($this->query);
            if(!$this->db->result('id')){
                $this->query = "INSERT INTO `users`(`name`, `password`, `email`, `phone`) VALUES ('$this->name', '$this->password', '$this->email', '$this->phone')";
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

}
?>