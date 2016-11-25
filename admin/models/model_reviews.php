<?php

class Model_Reviews extends Model
{
    
    protected $query;
    
    public function __construct(){
        parent::__construct();
    }
    
    public function get_reviews(){
        $this->query = "SELECT *
                FROM reviews
                LIMIT 100
        ";
        
        $this->db->make_query($this->query);
        return $this->db->results();
    }
    
    public function remove($id){
        if(empty($id)){
            return false;
        }
        
        $this->query = "DELETE FROM reviews
                WHERE id=" . $id . "
                LIMIT 1
                ";
        $this->db->make_query($this->query);
        
        return true;
    }
    
    public function approve($id){
        if(empty($id)){
            return false;
        }
        
        $this->query = "UPDATE
                    reviews 
                    SET
                    `approved`='1'
                    WHERE `id`=" . $id . "
                    LIMIT 1
                ";
                
        $this->db->make_query($this->query);
    }
}
