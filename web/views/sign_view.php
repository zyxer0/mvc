<h1>Авторизация</h1>
<form action="/auth.html" method="POST" class="login_form">
    <input type="text" name="email" placeholder="email" />
    <input type="password" name="password" placeholder="password" />
    <div>
        <input name="remember" value="1" type="checkbox" id="auth_remember">
        <label for="auth_remember">Запомнить меня</label>
    </div>
    <input type="submit" name="auth" class="button" />
</form>

<h1>Регистрация</h1>
<form action="/registration.html" method="POST" class="login_form">
    <input type="text" placeholder="name" name="name" />
    <input type="text" placeholder="email" name="email" />
    <input type="text" placeholder="phone" name="phone" />
    <input type="password" placeholder="password" name="password" />
    <div>
        <input name="remember" value="1" type="checkbox" id="register_remember">
        <label for="register_remember">Запомнить меня</label>
    </div>
    <input type="submit" name="register" class="button" />
</form>