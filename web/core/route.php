<?php

/*
Класс-маршрутизатор для определения запрашиваемой страницы.
> цепляет классы контроллеров и моделей;
> создает экземпляры контролеров страниц и вызывает действия этих контроллеров.
*/
class Route
{
    public static $settings;
    private static $db;
    static function start()
    {
        self::$db = new Db();
        // контроллер и действие по умолчанию

        $controller_name = 'Main';
        $action_name = 'index';
        
        $alias = explode('?', $_SERVER['REQUEST_URI']);
        $alias = trim(htmlspecialchars(strip_tags($alias[0])));
        
        if($alias != '' && $alias != '/'){
        
            mb_internal_encoding("UTF-8");
            $alias = mb_substr($alias, 1);
            
            if(!preg_match("/".self::$settings->prefix."$/i", $alias)) {
                Route::ErrorPage404();
            }
            
            $alias = preg_replace("/".self::$settings->prefix."$/i", "", $alias);
            $alias = mysqli_real_escape_string(self::$db->dbc, $alias);
            self::$db->make_query("SELECT `route` FROM `router` WHERE `alias` = '$alias' LIMIT 1");
            
            $route = self::$db->result('route');
            if(!$route) {
                Route::ErrorPage404();
            }
            $route = explode('?', $route);
            
            if(isset($route[1])){
                parse_str($route[1], $_GET);
            }
            
            if(!$route){
                Route::ErrorPage404();
            }
            
            $routes = explode('/', $route[0]);

            // получаем имя контроллера
            if (!empty($routes[0])) {
                $controller_name = $routes[0];
            }
            
            // получаем имя экшена
            if (!empty($routes[1])) {
                $action_name = $routes[1];
            }
        }

        // добавляем префиксы
        $model_name = 'Model_'.$controller_name;
        $controller_name = 'Controller_'.$controller_name;
        $action_name = 'action_'.$action_name;

        // подцепляем файл с классом модели (файла модели может и не быть)
        $model_file = strtolower($model_name).'.php';
        $model_path = "web/models/".$model_file;
        if(file_exists($model_path))
        {
            include "web/models/".$model_file;
        }

        // подцепляем файл с классом контроллера
        $controller_file = strtolower($controller_name).'.php';
        $controller_path = "web/controllers/".$controller_file;
        if(file_exists($controller_path)) {
            include "web/controllers/".$controller_file;
        } else {
            /*
            правильно было бы кинуть здесь исключение,
            но для упрощения сразу сделаем редирект на страницу 404
            */
            Route::ErrorPage404();
        }
        
        // создаем контроллер
        $controller = new $controller_name;
        $action = $action_name;
        
        if(method_exists($controller, $action)) {
            // вызываем действие контроллера
            if(!$controller->$action()) {
                Route::ErrorPage404();
            }
        } else {
            // здесь также разумнее было бы кинуть исключение
            Route::ErrorPage404();
        }
    
    }

    private static function ErrorPage404()
    {
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
        header("Status: 404 Not Found");
        header('Location:'.$host.'404'.self::$settings->prefix);
        die();
    }
    
}
