<?
class Validation
{

    public static function validate_form($arr){
        if (count($arr) === 0) {
            return false;
        }
        foreach ($arr as $rule => $value) {
            if (!self::check_empty($value) || !self::check_type($value)) {
                return false;
            }
            switch ($rule) {
                case 'passwd':
                    if (!self::check_passwd($value) || !self::check_length($value, 6, 8)) {
                        echo "passwd";
                        return false;
                    }
                    break;
                case 'email':
                    if (!self::check_email($value) || !self::check_length($value, 8, 255)) {
                        echo "email";
                        return false;
                    }
                    break;
                case 'text':
                    if (!self::check_text($value) || !self::check_length($value, 2, 255)) {
                        echo "text";
                        return false;
                    }
                    break;
            }
        }
        return true;
    }

    public static function check_type($data, $type="string"){
        if (gettype($data) == $type) {
            return true;
        }
        return false;
    }

    public static function check_empty($data){
        if ($data==="") {
            echo "empty";
            return false;
        }
        return true;
    }

    public static function check_length($data, $min = 4, $max = 255){
        if (iconv_strlen($data) < $min || iconv_strlen($data) > $max) {
            return false;
        }
        return true;
    }

    public static function check_passwd($passwd){
        if (preg_match('/^[A-Za-z0-9_@%/]{8,30}$/',$passwd) && preg_match('/[A-Z]+/',$passwd) && preg_match('/[a-z]+/',$passwd)) {
            return true;
        }
        return false;
    }
    public static function check_email($email){
        if (preg_match('/^([a-zA-Z][a-zA-Z0-9_\-\.])*[a-zA-Z0-9_\-]{2,100}@[a-zA-Z0-9_\-]+(\.[a-zA-Z0-9_\-]+)*\.[a-zA-Z]{2,6}$/',$email)) {
            return true;
        }
        return false;
    }
    public static function check_phone(){

    }
    public static function check_text($text){
        if(preg_match('/[a-zA-Zа-яА-Я]*[\s]*/',$text)) {
            return true;
        }
        return false;
    }
    
    public static function check_url($url){
        if(preg_match('/^[a-zA-Z0-9_]+\.[a-zA-Z]{1,6}$/',$url)) {
            return true;
        }
        return false;
    }
    public static function check_file(){

    }
}
