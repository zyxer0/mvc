<!DOCTYPE html>
<html>
<head>
    <title>Админская часть</title>
    <link href="/admin/static/css/style.css" rel="stylesheet" type="text/css" media="screen"/>
    <script src="/admin/static/js/jquery/jquery.js"></script>
</head>
<body>



<div class="wrapper auth_page">

    <form method="post" action="/administrator/auth/login" id="auth_form">
        <input name="username" value="" type="text" placeholder="Введите имя пользователя" />
        <input name="pass" value="" type="password" placeholder="Введите пароль" />
        <input name="auth" value="Войти" type="submit" />
    </form>
    
</div>
</body>
</html>