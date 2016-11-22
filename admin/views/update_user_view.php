<div class="product_page">
    <?if(isset($_SESSION['user_status']['success']) && !empty($_SESSION['user_status']['success'])){?>
    <div class="message_success">
        <?
        switch($_SESSION['user_status']['success']){
            case 'updated':
                print 'Пользователь успешно обновлен';
            break;
            case 'added':
                print 'Пользователь успешно добавлен';
            break;
        }
        ?>
    </div>
    <?}?>
    <?unset($_SESSION['user_status']);?>
    <form method="POST" enctype="multipart/form-data" action="/administrator/users/update?id=<?=$user->id?>">
        <div class="name_block">
            <input name="name" class="name" type="text" value="<?=$user->name?>" placeholder="Имя пользователя" />
        </div>
        
        <div class="left_block">
            <ul class="properties">
                <li>
                    <label for="email">Email</label><input value="<?=$user->email?>" type="text" class="text_input" name="email" id="email" />
                </li>
                <li>
                    <label for="phone">Телефон</label><input value="<?=$user->phone?>" type="text" class="text_input" name="phone" id="phone" />
                </li>
                <li>
                    <label for="password">Пароль</label><input value="" type="text" class="text_input" name="password" id="password" />
                </li>
            </ul>
            <hr />
        </div>
        <div class="clear"></div>
        
        <input type="submit" name="apply" value="Применить">
        
    </form>
</div>