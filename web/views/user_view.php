<link href="/web/static/js/cropper/cropper.css" rel="stylesheet" type="text/css" media="screen"/>
<script src="/web/static/js/cropper/cropper.js"  type="text/javascript"></script>

<h1>Личный кабинет пользователя <?=$user->name?></h1>
<form action="/user-update.thml" method="POST">
    <input type="text" placeholder="name" name="name" value="<?=$user->name?>" /><br>
    <input type="text" placeholder="email" name="email"  value="<?=$user->email?>" /><br>
    <input type="text" placeholder="phone" name="phone"  value="<?=$user->phone?>" /><br>
    <input type="password" placeholder="password" name="password" /><br>
    <input type="submit" name="update" value="Обновить" class="button" />
</form>

<a href="/user-delete.thml">Удалить мой аккаунт</a>