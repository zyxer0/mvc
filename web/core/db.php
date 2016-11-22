<?
class Db 
{
    public $dbc;
    protected $query;
    protected $query_result;

    function __construct()
    {
        $this->dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWD, DB_NAME);
        if (!$this->dbc) {
            die();
        }
        mysqli_set_charset($this->dbc, 'utf8');
    }

    function __destruct(){
        mysqli_close($this->dbc);
    }
    
    public function make_query($query){
        $this->query_result = mysqli_query($this->dbc, $query);
        if ($this->query_result) {
            return true;
        }
        return false;
    } 
    
    public function results($field = null){
        
        if(!$this->query_result){
            return false;
        }
        
        $results = array();
        while($row = mysqli_fetch_object($this->query_result)){
            if($field){
                $results[] = $row->$field;
            } else {
                $results[] = $row;
            }
            
        }
        return $results;
    }
    
    public function result($field = null){
        
        if(!$this->query_result){
            return false;
        }
        
        $row = mysqli_fetch_object($this->query_result);
        if($row){
            if($field){
                return $row->$field;
            }
            
            return $row;
        }
        return false;
    }
    
    public function insert_id(){
        return mysqli_insert_id($this->dbc);
    }
}
?>