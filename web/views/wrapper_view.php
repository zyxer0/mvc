<!DOCTYPE html>
<html>
<head>
    <title><?=$meta_title?></title>
    <meta name="description" content="<?=$meta_description?>" />
    <meta name="keywords"    content="<?=$meta_keywords?>" />
    <link href="/web/static/css/style.css" rel="stylesheet" type="text/css" media="screen"/>
    
    <script src="/web/static/js/jquery/jquery.js"  type="text/javascript"></script>
    <script src="/web/static/js/ajax_cart.js"  type="text/javascript"></script>
</head>
<body>

    <div class="header">
        <div class="wrapper">
            <div class="logo">
                <a href="/"><img src="/web/static/images/logo.png" /></a>
            </div>
            <ul class="main_menu">
                <?
                if(isset($main_menu)){
                foreach($main_menu as $url=>$menu_name){?>
                    <li><a href="<?=$url?>" class="item"><?=$menu_name?></a></li>
                <?}
                }?>
                <li class="clear"></li>
            </ul>
            
            <div id="user_informer">
                <?if(!empty($user)){?>
                    <a href="/user<?=$settings->prefix?>"><?=$user->name?></a>
                    /
                    <a href="/logout<?=$settings->prefix?>">Выход</a>
                <?} else {?>
                    <a href="/user<?=$settings->prefix?>">Вход / Регистрация</a>
                <?}?>
            </div>
            
            <div id="cart_informer">
            
            </div>
            
        </div>
    </div>

    <div class="wrapper">
        <?php include 'web/views/'.$content_view; ?>
    </div>
        <div class="clear"></div>
        <div class="footer">
            <div class="wrapper">
                <span class="copyright">Все права защищены zyxer.404.dp.ua © 2016 Все материалы на этом сайте присутствуют исключительно в учебных целях <span style="font-size: 25px;">&#9786;</span></span>
            </div>
        </div>
    
</body>
</html>