<h1>Авторизация</h1>
<form action="/auth<?=$settings->prefix?>" method="POST" class="login_form">
    <input type="text" name="email" placeholder="email" />
    <input type="password" name="password" placeholder="password" />
    <div>
        <input name="remember" value="1" type="checkbox" id="auth_remember">
        <label for="auth_remember">Запомнить меня</label>
    </div>
    <input type="submit" name="auth" class="button" />
</form>

<h1>Регистрация</h1>
<form action="/registration<?=$settings->prefix?>" method="POST" class="login_form">
    <input type="text" placeholder="Ваше имя" name="name" value="" /><br>
    <input type="text" placeholder="Ваша фамилия" name="last_name" value="" /><br>
    <input type="text" placeholder="Ваше отчество" name="patronymic" value="" /><br>
    <input type="text" placeholder="Ваш Email" name="email"  value="" /><br>
    <input type="text" placeholder="Ваш телефон" name="phone"  value="" /><br>
    <input type="text" placeholder="Ваша страна" name="country" value="" /><br>
    <input type="text" placeholder="Ваш город" name="city" value="" /><br>
    <input type="text" placeholder="Ваш адрес" name="address" value="" /><br>
    <input type="password" placeholder="Ваш пароль" name="password" /><br>
    <div>
        <input name="remember" value="1" type="checkbox" id="register_remember">
        <label for="register_remember">Запомнить меня</label>
    </div>
    <input type="submit" name="register" class="button" />
</form>