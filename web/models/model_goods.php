<?php

class Model_Goods extends Model
{
    
    protected $query;
    protected $goods;
    
    public function __construct($db){
        parent::__construct($db);
    }
    
    public function get_goods($filter = [])
    {
        $limit = 100;
        $category_id_where = '';
        
        if(isset($filter['category_id'])) {
            $categories_ids = implode(',', (array)$filter['category_id']);
            $category_id_where = "AND `category_id` in ($categories_ids)";
        }
        
        $this->query = "SELECT *
                FROM goods
                WHERE 1
                    $category_id_where
                LIMIT $limit
        ";
        
        $this->db->make_query($this->query);
        $this->goods = $this->db->results();
        return $this->goods;
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
    
    public function get_features($filter = []){
        $limit = 100;
        $filter_id_where = '';
        $filter_good_id_where = '';
        
        if(isset($filter['id'])){
            $filter_id_where = "AND `id` = ". mysqli_real_escape_string($this->db->dbc, $filter['id']);
        }
        
        if(isset($filter['good_id'])){
            $filter_good_id_where = "AND `good_id` = ". mysqli_real_escape_string($this->db->dbc, $filter['good_id']);
        }
        
        $this->query = "SELECT 
                    `id` ,
                    `good_id` ,
                    `image`
                FROM goods_features
                WHERE 1 
                    $filter_id_where
                    $filter_good_id_where
                LIMIT $limit
        ";
        
        $this->db->make_query($this->query);
        return $this->db->results();
        
    }
    
    public function get_good_colors($filter = []){
        $limit = 1000;
        $filter_id_where = '';
        $filter_good_id_where = '';
        
        if(isset($filter['id'])){
            $filter_id_where = "AND `id` = ". mysqli_real_escape_string($this->db->dbc, $filter['id']);
        }
        
        if(isset($filter['good_id'])){
            $goods_ids = implode(',',  (array)$filter['good_id']);
            $filter_good_id_where = "AND `good_id` in ($goods_ids)";
        }
        
        $this->query = "SELECT 
                    `id` ,
                    `good_id` ,
                    `color` ,
                    `link`
                FROM goods_colors
                WHERE 1 
                    $filter_id_where
                    $filter_good_id_where
                LIMIT $limit
        ";
        
        $this->db->make_query($this->query);
        return $this->db->results();
    }
    
    public function get_reviews($good_id)
    {
        if(empty($good_id)) {
            return false;
        }
        
        $this->query = "SELECT *
                FROM reviews
                WHERE `good_id` = '".(int)$good_id."' AND `approved`=1
                ORDER BY time_stamp ASC
                LIMIT 100
        ";
        
        $this->db->make_query($this->query);
        return $this->db->results();
    }
    
    public function add_review($good_id){
        
        $review = new stdClass;
        
        if(!$review->user_id = (int)$_SESSION['user_id']) {
            $review->user_id = null;
        }
        $review->good_id = $good_id;
        $review->time_stamp = time();
        $review->text   = trim(htmlspecialchars(strip_tags($_POST['text'])));
        $review->name   = trim(htmlspecialchars(strip_tags($_POST['name'])));
        $review->rating = (int)trim(htmlspecialchars(strip_tags($_POST['rating'])));
        
        if(!Validation::check_empty($review->name) || !Validation::check_text($review->name)){
            return false;
        }
        if(!Validation::check_empty($review->text) || !Validation::check_text($review->text)){
            return false;
        }
        
        $review->text   = mysqli_real_escape_string($this->db->dbc, $review->text);
        $review->name   = mysqli_real_escape_string($this->db->dbc, $review->name);
        $review->rating = mysqli_real_escape_string($this->db->dbc, $review->rating);
        
        $this->query = "INSERT INTO 
                    reviews 
                    (
                        `good_id`,
                        `user_id`,
                        `time_stamp`,
                        `text`,
                        `name`,
                        `rating`,
                        `approved`
                    )
                    VALUES
                    (
                        '" . $review->good_id . "',
                        '" . $review->user_id . "',
                        '" . $review->time_stamp . "',
                        '" . $review->text . "',
                        '" . $review->name . "',
                        '" . $review->rating . "',
                        '0'
                    )
                ";
        
        $this->db->make_query($this->query);
        $review_id = $this->db->insert_id();
        
        $this->query = "UPDATE
                    goods 
                    SET
                    `raiting`=((`raiting`+".$review->rating.")/2)
                ";
        $this->db->make_query($this->query);
        
        return $review_id;
    }
    
    public function get_category($id){
        $id = (int)$id;
        if(!isset($this->all_categories)) {
            $this->init_categories();
        }
        
        if(array_key_exists(intval($id), $this->all_categories)) {
            return $category = $this->all_categories[intval($id)];
        }
        return false;
    }
}
