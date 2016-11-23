<?php

class Model_Settings extends Model
{
    private $config_file;
    public function __construct(){
        parent::__construct();
        
        $this->config_file = dirname(dirname(__DIR__)).'/config/settings.ini';
    }
    
    public function update(){
        
        $conf_file = file_get_contents($this->config_file);
        
        foreach($_POST['settings'] as $param_name=>$param_value) {
            if(Validation::check_text($param_name) && Validation::check_text($param_value)) {
                    $conf_file = preg_replace("/".$param_name.".*;/i", $param_name." = '".$param_value."';", $conf_file);
            }
        }
        
        file_put_contents($this->config_file, $conf_file);
    }
}
