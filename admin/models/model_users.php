<?php

class Model_Users extends Model
{
    
    public function __construct(){
        parent::__construct();
    }
    
    public function get_users(){
        $this->query = "SELECT *
                FROM users
                LIMIT 100
        ";
        
        $this->db->make_query($this->query);
        return $this->db->results();
    }
    
    public function remove($id){
        if(empty($id)){
            return false;
        }
        
        $this->query = "DELETE FROM users
                WHERE id=" . $id . "
                LIMIT 1
                ";
        $this->db->make_query($this->query);
        
        return true;
    }
    
    public function get_user($id){
        if(!$id){
            return false;
        }
        $id = mysqli_real_escape_string($this->db->dbc, $id);
        $this->query = "SELECT *
                FROM users
                WHERE id = ". $id ."
                LIMIT 1
        ";
        
        $this->db->make_query($this->query);
        return $this->db->result();
    }
    
    public function add(){
        
        $user = new stdClass;
        $user->email = trim(htmlspecialchars(strip_tags($_POST['email'])));
        $user->name = trim(htmlspecialchars(strip_tags($_POST['name'])));
        $user->phone = trim(htmlspecialchars(strip_tags($_POST['phone'])));
        $user->password  = trim(htmlspecialchars(strip_tags($_POST['password'])));
        
        if(!Validation::check_empty($user->name) || !Validation::check_text($user->name)){
            return false;
        }
        if(!Validation::check_empty($user->email) || !Validation::check_email($user->email)){
            return false;
        }
        if(!Validation::check_empty($user->password) || !Validation::check_text($user->password)){
            return false;
        }
        
        $user->name = mysqli_real_escape_string($this->db->dbc, $user->name);
        $user->phone = mysqli_real_escape_string($this->db->dbc, $user->phone);
        $user->email = mysqli_real_escape_string($this->db->dbc, $user->email);
        $user->password = mysqli_real_escape_string($this->db->dbc, $user->password);
        
        $user->password  = password_hash($user->password, PASSWORD_DEFAULT);
        
        $this->query = "INSERT INTO 
                    users 
                    SET
                    `name`='" . $user->name . "',
                    `email`='" . $user->email . "',
                    `phone`='" . $user->phone . "',
                    `password`='" . $user->password . "'
                ";
                
        if(!$this->db->make_query($this->query)){
            return false;
        }
        
        if($id = $this->db->insert_id()) {
            return $id;
        }
        
        return false;
        
    }
    
    public function update($id){
        if(empty($id)){
            return false;
        }
        
        $user = new stdClass;
        $user->email = trim(htmlspecialchars(strip_tags($_POST['email'])));
        $user->name = trim(htmlspecialchars(strip_tags($_POST['name'])));
        $user->phone = trim(htmlspecialchars(strip_tags($_POST['phone'])));
        $user->password  = trim(htmlspecialchars(strip_tags($_POST['password'])));
        
        if(!Validation::check_empty($user->name) || !Validation::check_text($user->name)){
            return false;
        }
        if(!Validation::check_empty($user->email) || !Validation::check_email($user->email)){
            return false;
        }
        
        $user->name = mysqli_real_escape_string($this->db->dbc, $user->name);
        
        $password_update = '';
        if(!empty($user->password)) {
            $user->password = mysqli_real_escape_string($this->db->dbc, $user->password);
        
            $user->password  = password_hash($user->password, PASSWORD_DEFAULT);
            $password_update = ", `password`='" . $user->password . "'";
        }
        
        $this->query = "SELECT count(*) as count 
                        FROM users 
                        WHERE 
                            `email`='" . $user->email . "'
                            AND `id`!='" . $id . "'";
        $this->db->make_query($this->query);
        
        if($this->db->result('count') > 0) {
            return false;
        }
        
        $this->query = "UPDATE
                    users 
                    SET
                    `name`='" . $user->name . "',
                    `email`='" . $user->email . "',
                    `phone`='" . $user->phone . "'
                    $password_update
                    WHERE `id`=" . $id . "
                    LIMIT 1
                ";
                
        if(!$this->db->make_query($this->query)){
            return false;
        }
        
        return true;
    }
}
