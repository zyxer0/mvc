<?if(isset($cart->total_goods) && $cart->total_goods > 0){?>
    <h1>Товаров в корзине <?echo $cart->total_goods?>
    на сумму <?=$cart->total_price?> грн
    </h1>
    <a class="" href="/cart<?=$settings->prefix?>">Редактировать покупки</a>
    <form name="cart" method="POST" action="/order/add<?=$settings->prefix?>">
        <div class="contacts_form">
            <h3>Контактные данные</h3>
            
            <input type="text" class="form_input" placeholder="Введите имя *"     required name="name"  value="<?isset($user->name) ? print $user->name : ''?>" />
            <input type="text" class="form_input" placeholder="Введите фамилию"  name="last_name" value="<?isset($user->last_name) ? print $user->last_name : ''?>" />
            <input type="text" class="form_input" placeholder="Введите отчество" name="patronymic" value="<?isset($user->patronymic) ? print $user->patronymic : ''?>" />
            <input type="text" class="form_input" placeholder="Введите телефон *" required name="phone" value="<?isset($user->phone) ? print $user->phone : ''?>" />
            <input type="text" class="form_input" placeholder="Введите E-mail *"  required name="email" value="<?isset($user->email) ? print $user->email : ''?>" />
            <input type="text" class="form_input" placeholder="Ваша страна" name="country"  value="<?isset($user->country) ? print $user->country : ''?>" />
            <input type="text" class="form_input" placeholder="Ваш город"   name="city"     value="<?isset($user->city) ? print $user->city : ''?>" />
            <input type="text" class="form_input" placeholder="Ваш адрес"   name="address"  value="<?isset($user->address) ? print $user->address : ''?>" />
            <textarea name="comment" class="form_textarea" placeholder="Комментарий к заказу"></textarea>
            <input type="submit" class="button" name="checkout" value="Оформить заказ" />
        </div>
    </form>
<?} else {?>
    <h1>Корзина пуста</h1>
<?}?>