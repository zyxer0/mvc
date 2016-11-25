<?php

class Model_Managers extends Model
{
    
    protected $query;
    
    public function __construct(){
        parent::__construct();
    }
    
    public function get_managers(){
        $this->query = "SELECT *
                FROM managers
                LIMIT 100
        ";
        
        $this->db->make_query($this->query);
        return $this->db->results();
    }
    
    public function get_all_permissions(){
        $this->query = "SELECT *
                FROM permissions
                LIMIT 100
        ";
        
        $this->db->make_query($this->query);
        return $this->db->results();
    }
    
    public function remove($id){
        if(empty($id)){
            return false;
        }
        
        $this->query = "DELETE FROM managers
                WHERE id=" . $id . "
                LIMIT 1
                ";
        $this->db->make_query($this->query);
        
        return true;
    }
    
    public function get_manager($id){
        if(!$id){
            return false;
        }
        $id = mysqli_real_escape_string($this->db->dbc, $id);
        $this->query = "SELECT *
                FROM managers
                WHERE id = ". $id ."
                LIMIT 1
        ";
        
        $this->db->make_query($this->query);
        $manager = $this->db->result();
        
        $this->query = "SELECT `permission_id` FROM `managers_permissions` WHERE `manager_id`='" . $manager->id . "'";
        $this->db->make_query($this->query);
        $permissions = $this->db->results('permission_id');
        if($permissions) {
            foreach($permissions as $permission){
                if(empty($permissions_string)) {
                    $permissions_string = "'" . $permission . "'";
                } else {
                    $permissions_string .= ", '" . $permission . "'";
                }
            }
            
            $this->query = "SELECT `id` FROM `permissions` WHERE `id` in (" . $permissions_string . ")";
            
            $this->db->make_query($this->query);
            foreach($this->db->results() as $result) {
                $manager->permissions_ids[] = $result->id;
            }
            
        } else {
            $manager->permissions_ids = [];
        }
                
        return $manager;
    }
    
    public function add(){
        
        $manager = new stdClass;
        $manager->username = trim(htmlspecialchars(strip_tags($_POST['username'])));
        $manager->password  = password_hash(trim(htmlspecialchars(strip_tags($_POST['password']))), PASSWORD_DEFAULT);
        
        if(!Validation::check_empty($manager->username) || !Validation::check_text($manager->username)){
            return false;
        }
        if(!Validation::check_empty($manager->password) || !Validation::check_text($manager->password)){
            return false;
        }
        $manager->username = mysqli_real_escape_string($this->db->dbc, $manager->username);
        $manager->password = mysqli_real_escape_string($this->db->dbc, $manager->password);
        
        $manager->password  = password_hash($manager->password, PASSWORD_DEFAULT);
        
        $this->query = "INSERT INTO 
                    managers 
                    SET
                    `username`='" . $manager->username . "',
                    `password`='" . $manager->password . "'
                ";
                
        if(!$this->db->make_query($this->query)){
            return false;
        }
        
        if($id = $this->db->insert_id()) {
            
            foreach($_POST['permissions'] as $permission_id) {
                $permission_id = (int)mysqli_real_escape_string($this->db->dbc, $permission_id);
                $this->query = "INSERT INTO 
                                managers_permissions
                                SET
                                `manager_id`='" . $id . "',
                                `permission_id`='" . $permission_id . "'
                            ";
                
                $this->db->make_query($this->query);
            }
            
            return $id;
        }
        
        return false;
        
    }
    
    public function update($id){
        if(empty($id)){
            return false;
        }
        
        $manager = new stdClass;
        $manager->username = trim(htmlspecialchars(strip_tags($_POST['username'])));
        $manager->password  = trim(htmlspecialchars(strip_tags($_POST['password'])));
        
        if(!Validation::check_empty($manager->username) || !Validation::check_text($manager->username)){
            return false;
        }
        $manager->username = mysqli_real_escape_string($this->db->dbc, $manager->username);
        
        $password_update = '';
        if(!empty($manager->password)) {
            $manager->password = mysqli_real_escape_string($this->db->dbc, $manager->password);
        
            $manager->password  = password_hash($manager->password, PASSWORD_DEFAULT);
            $password_update = ", `password`='" . $manager->password . "'";
        }
        
        
        
        $this->query = "UPDATE
                    managers 
                    SET
                    `username`='" . $manager->username . "'
                    $password_update
                    WHERE `id`=" . $id . "
                    LIMIT 1
                ";
                
        if(!$this->db->make_query($this->query)){
            return false;
        }
        
        $this->query = "DELETE FROM
                    managers_permissions 
                    WHERE `manager_id`='" . $id . "' LIMIT 100
                ";
        $this->db->make_query($this->query);
        
        foreach($_POST['permissions'] as $permission_id) {
            $permission_id = (int)mysqli_real_escape_string($this->db->dbc, $permission_id);
            $this->query = "INSERT INTO 
                            managers_permissions
                            SET
                            `manager_id`='" . $id . "',
                            `permission_id`='" . $permission_id . "'
                        ";
            
            $this->db->make_query($this->query);
        }
        
        return true;
    }
}
