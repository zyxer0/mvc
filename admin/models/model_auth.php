<?php

class Model_Auth extends Model
{
    protected $username;
    protected $pass;
    
    public function __construct(){
        parent::__construct();
        if(isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id'])){
            header('Location: /admin');
        }
    }
    
    public function login_admin()
    {
        if(isset($_POST['auth']) && trim(htmlspecialchars(strip_tags($_POST['auth'])))) {
            // Validation
            $this->username = trim(htmlspecialchars(strip_tags($_POST['username'])));
            $this->pass = trim(htmlspecialchars(strip_tags($_POST['pass'])));
            
            $this->username = mysqli_real_escape_string($this->db->dbc, $this->username);
            $this->pass = mysqli_real_escape_string($this->db->dbc, $this->pass);
            
            $this->query = "SELECT 
                        `id`,
                        `password`
                    FROM managers
                    WHERE 
                        `username` = '$this->username'
                    LIMIT 1
                    ";
            $this->db->make_query($this->query);
            $result = $this->db->result();
            if(password_verify($this->pass, $result->password)){
                return $result->id;
            }
            
            /* if($manager = $this->managers->get_manager($login)){
                if(password_verify($pass, $manager->password)){
                    return $manager->id;
                }
            } */
        }
        return false;
    }
}
