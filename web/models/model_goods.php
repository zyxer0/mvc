<?php

class Model_Goods extends Model
{
    
    protected $query;
    protected $goods;
    
    public function __construct(){
        parent::__construct();
    }
    
    public function get_goods()
    {
        $this->query = "SELECT *
                FROM goods
                LIMIT 100
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
        
        $good->features = $this->get_features(['good_id'=>$good->id]);
        $good->colors = $this->get_good_colors(['good_id'=>$good->id]);
        $good->images   = $this->get_images(['good_id'=>$good->id]);
        
        if(!empty($good->main_image_id)) {
            $good->image = $this->get_image(['id'=>$good->main_image_id]);
        } else {
            $good->image = reset($good->images);
        }
        
        return $good;
    }
    
    private function get_features($filter = []){
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
    
    private function get_good_colors($filter = []){
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
    
    private function get_images($filter = []){
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
    
    private function get_image($id = []){
        
        if(empty($id)){
            return false;
        }
        
        if(isset($id['id'])){
            $where = "AND id=" . mysqli_real_escape_string($this->db->dbc, $id['id']);
        } else if(isset($id['good_id'])){
            $where = "AND good_id=" . mysqli_real_escape_string($this->db->dbc, $id['good_id']);
        }
        
        $this->query = "SELECT 
                    `id` ,
                    `filename_full`,
                    `filename_middle`,
                    `filename_small`,
                    `good_id`,
                    `alt`,
                    `title`
                FROM goods_images
                WHERE 1 
                    $where
                LIMIT 1
        ";
        
        $this->db->make_query($this->query);
        return $this->db->result();
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
    
}
