<?php

class Model
{
    protected $db;
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
        
    }
    // метод выборки данных
    public function get_data($type, $param)
    {
        switch($type){
            case 'user_name': 
                $query = "SELECT name FROM `users` WHERE `id`='".$param['id']."' LIMIT 1";
                $this->db->make_query($query);
                return $this->db->result('name');
                break;
            case 'user_remember_token': 
                $query = "SELECT remember_token FROM `users` WHERE `id`='".$param['id']."' LIMIT 1";
                $this->db->make_query($query);
                return $this->db->result('remember_token');
                break;
        }
    }
}