<?php

class Model
{
    public $settings;
    protected $db;
    /* protected $menu = [
                        'goods'    => 'Товары',
                        'managers' => 'Менеджеры',
                        'users'    => 'Пользователи',
                    ]; */
    /*
        Модель обычно включает методы выборки данных, это могут быть:
            > методы нативных библиотек pgsql или mysql;
            > методы библиотек, реализующих абстракицю данных. Например, методы библиотеки PEAR MDB2;
            > методы ORM;
            > методы для работы с NoSQL;
            > и др.
    */
    public function __construct(){
        $this->db = new Db();
        global $settings;
        $this->settings = $settings;
        
    }
    // метод выборки данных
    public function get_data($type, $param)
    {
        switch($type){
            case 'admin': 
                $query = "SELECT `username`, `id` FROM `managers` WHERE `id`='".$param['id']."' LIMIT 1";
                $this->db->make_query($query);
                $manager = $this->db->result();
                
                $query = "SELECT `permission_id` FROM `managers_permissions` WHERE `manager_id`='" . $manager->id . "'";
                $this->db->make_query($query);
                $permissions = $this->db->results('permission_id');
                if($permissions) {
                    foreach($permissions as $permission){
                        if(empty($permissions_string)) {
                            $permissions_string = "'" . $permission . "'";
                        } else {
                            $permissions_string .= ", '" . $permission . "'";
                        }
                    }
                    
                    $query = "SELECT `permission`, `name_menu_item` FROM `permissions` WHERE `id` in (" . $permissions_string . ")";
                    
                    $this->db->make_query($query);
                    foreach($this->db->results() as $result) {
                        $manager->permissions[$result->permission] = $result->name_menu_item;
                    }
                    
                } else {
                    $manager->permissions = [];
                }
                
                return $manager;
                break;
            /* case 'menu': 
                $result = [];
                foreach($this->menu as $permission=>$menu_name){
                    
                    if(in_array($permission, $param['permisions'])){
                        $result[$permission] = $menu_name;
                    }
                }
                return $result;
                break; */
        }
    }
    
    public function get_images($filter = []){
        $limit = 100;
        $filter_id_where = '';
        $filter_good_id_where = '';
        
        if(isset($filter['id'])){
            $images_ids = implode(',', (array)$filter['id']);
            $filter_id_where = "AND `id` in ($images_ids)";
        }
        
        if(isset($filter['good_id'])){
            $goods_ids = implode(',', (array)$filter['good_id']);
            $filter_good_id_where = "AND `good_id` in ($goods_ids)";
        }
        
        $this->query = "SELECT 
                    `id`,
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
}