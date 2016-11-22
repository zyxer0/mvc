<?php

/*
Класс-маршрутизатор для определения запрашиваемой страницы.
> цепляет классы контроллеров и моделей;
> создает экземпляры контролеров страниц и вызывает действия этих контроллеров.
*/
class Route
{

    static function start()
    {
        // контроллер и действие по умолчанию
        $controller_name = 'Main';
        $action_name = 'index';
        
        /* $subdir = substr(dirname(dirname(__FILE__)), strlen($_SERVER['DOCUMENT_ROOT']));
        $uri = $_SERVER['REQUEST_URI'];
        
        
        
        $page_url = trim(substr($uri, strlen($subdir)),"/");
        
        $routes = explode('/', $page_url); */
        // var_dump($_SERVER['REQUEST_URI']);

        $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
        
        $routes = explode('/', $uri);
        
        // получаем имя контроллера
        if ( !empty($routes[2]) )
        {
            $controller_name = $routes[2];
        }
        
        // получаем имя экшена
        if ( !empty($routes[3]) )
        {
            $action_name = $routes[3];
        }
        
        // добавляем префиксы
        $model_name = 'Model_'.$controller_name;
        $controller_name = 'Controller_'.$controller_name;
        $action_name = 'action_'.$action_name;

        if(!isset($_SESSION['admin_id']) && $controller_name != 'Controller_auth') {
            header('Location: /administrator/auth');
        }
        
        // echo "Model: $model_name <br>";
        // echo "Controller: $controller_name <br>";
        // echo "Action: $action_name <br>";
        

        // подцепляем файл с классом модели (файла модели может и не быть)

        $model_file = strtolower($model_name).'.php';
        $model_path = dirname(dirname(__FILE__)) . "/models/".$model_file;
        if(file_exists($model_path))
        {
            include dirname(dirname(__FILE__)) . "/models/".$model_file;
        }

        // подцепляем файл с классом контроллера
        $controller_file = strtolower($controller_name).'.php';
        $controller_path =  dirname(dirname(__FILE__)) . "/controllers/".$controller_file;
        if(file_exists($controller_path))
        {
            include dirname(dirname(__FILE__)) . "/controllers/".$controller_file;
        }
        else
        {
            /*
            правильно было бы кинуть здесь исключение,
            но для упрощения сразу сделаем редирект на страницу 404
            */
            Route::ErrorPage404();
        }
        
        if(!class_exists($controller_name)){
            Route::ErrorPage404();
        }
        
        // создаем контроллер
        $controller = new $controller_name;
        $action = $action_name;
        
        if(method_exists($controller, $action))
        {
            // вызываем действие контроллера
            $controller->$action();
        }
        else
        {
            // здесь также разумнее было бы кинуть исключение
            Route::ErrorPage404();
        }
    
    }

    static function ErrorPage404()
    {
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        //header('Location:'.$host.'404');
    }
    
}
