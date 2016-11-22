<!DOCTYPE html>
<html>
<head>
    <title>Админская часть</title>
    <link href="/admin/static/css/style.css" rel="stylesheet" type="text/css" media="screen"/>
    <script src="/admin/static/js/jquery/jquery.js"></script>
    
    <script type="text/javascript" src="/admin/static/js/ckeditor/ckeditor.js"></script>

    <script type="text/javascript">
        window.onload = function()
        {
            CKEDITOR.replace('editor');
        };
    </script>
    
</head>
<body>



<div class="wrapper">
    <div class="header">
        <ul class="admin_menu">
            <?foreach($manager->permissions as $permission=>$menu_name){
                ?>
                <li class="item"><a href="/administrator/<?=$permission?>"><?=$menu_name?></a></li>
            <?
            }?>
            <li class="clear"></li>
        </ul>
    </div>
    <?php
    if(!empty($content_view)){
        include dirname(__FILE__) . '/' . $content_view;
    }
    ?>
    </div>
    <div class="clear"></div>
    <div class="footer">
        <div class="wrapper">
            Вы вошли как <?=$manager->username?> 
            
            <form method="post" action="/administrator/auth/logout">
                <input name="logout" value="Выйти" type="submit" />
            </form>
        </div>
    </div>
    
</body>
</html>