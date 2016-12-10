<link href="/web/static/js/cropper/cropper.css" rel="stylesheet" type="text/css" media="screen"/>
<script src="/web/static/js/cropper/cropper.js"  type="text/javascript"></script>

<h1>Личный кабинет пользователя <?=$user->name?></h1>
<form action="/user-update<?=$settings->prefix?>" method="POST">
    <input type="text" placeholder="Ваше имя" name="name" value="<?=$user->name?>" /><br>
    <input type="text" placeholder="Ваша фамилия" name="last_name" value="<?=$user->last_name?>" /><br>
    <input type="text" placeholder="Ваше отчество" name="patronymic" value="<?=$user->patronymic?>" /><br>
    <input type="text" placeholder="Ваш Email" name="email"  value="<?=$user->email?>" /><br>
    <input type="text" placeholder="Ваш телефон" name="phone"  value="<?=$user->phone?>" /><br>
    <input type="text" placeholder="Ваша страна" name="country" value="<?=$user->country?>" /><br>
    <input type="text" placeholder="Ваш город" name="city" value="<?=$user->city?>" /><br>
    <input type="text" placeholder="Ваш адрес" name="address" value="<?=$user->address?>" /><br>
    <input type="password" placeholder="Ваш пароль" name="password" /><br>
    <input type="submit" name="update" value="Обновить" class="button" />
</form>

<a href="/user-delete<?=$settings->prefix?>">Удалить мой аккаунт</a>