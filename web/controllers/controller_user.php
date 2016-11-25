<?
class Controller_User extends Controller
{


    function __construct()
    {
        parent::__construct();
        $this->model = new Model_User();
    }

    function action_index()
    {
        if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])){
            $this->design_var->user = $this->model->get_user_info();
            
            $this->design_var->meta_title = 'Личный кабинет пользователя ' . $this->design_var->user->name;
            $this->design_var->meta_description = 'Личный кабинет пользователя ' . $this->design_var->user->name;
            $this->design_var->meta_keywords = 'Личный кабинет пользователя ' . $this->design_var->user->name;
            $this->view->generate('user_view.php', 'wrapper_view.php', $this->design_var);
        } else {
            $this->design_var->meta_title = 'Вход/регистрация';
            $this->design_var->meta_description = 'Вход/регистрация';
            $this->design_var->meta_keywords = 'Вход/регистрация';
            $this->view->generate('sign_view.php', 'wrapper_view.php', $this->design_var);
        }
        return true;
    }
    
    function action_update()
    {
        $this->model->update_user();
        header("location: /user".$this->settings->prefix);
        return true;
    }
    
    function action_reg()
    {
        if($user_id = $this->model->reg_user()){
            $_SESSION["user_id"] = $user_id;
        }
        header("location: /");
        return true;
    }
    
    function action_auth()
    {
        if($user_id = $this->model->auth_user()){
            $_SESSION["user_id"] = $user_id;
        }
        header("location: /");
        return true;
    }
    
    function action_delete()
    {
        if(isset($_SESSION["user_id"]) && !empty($_SESSION["user_id"])){
            $this->model->delete_user();
            unset($_SESSION["user_id"]);
            setcookie('remember', '', time() - 60*60*24*30, '/');
        }
        header("location: /");
        return true;
    }
    
    function action_logout()
    {
        unset($_SESSION["user_id"]);
        setcookie('remember', '', time() - 60*60*24*30, '/');
        header("location: /");
        return true;
    }
}

?>