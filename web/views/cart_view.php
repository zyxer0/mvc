<?if(isset($cart->total_goods) && $cart->total_goods > 0){?>
    <h1>Товаров в корзине <?echo $cart->total_goods?>
    на сумму <?=$cart->total_price?> грн
    </h1>
    <form name="cart" method="POST" enctype="multipart/form-data" action="cart_update<?=$settings->prefix?>">
        <table class="cart_purchases">
            <tr>
                <th class="image"></th>
                <th class="name">Название</th>
                <th class="price">Цена</th>
                <th class="amount">количество</th>
                <th class="remove"></th>
            </tr>
            <?foreach($cart->goods as $good){?>
                <tr>
                    <td class="image">
                        <?if($good->image){?>
                        <a href="<?=$good->url?><?=$settings->prefix?>">
                            <img src="/<?=$settings->goods_images_dir?><?=$good->image->filename_small?>" alt="<?=$good->image->alt?>" title="<?=$good->image->title?>">
                        </a>
                        <?}?>
                    </td>
                    <td class="name">
                        <a href="<?=$good->url?><?=$settings->prefix?>">
                            <?=$good->name?>
                        </a>
                    </td>
                    <td class="price">
                        <?=$good->price?> грн
                    </td>
                    <td class="amount">
                        <select name="amounts[<?=$good->id?>]" onchange="document.cart.submit()">
                            <?for($amount=1; $amount<=50; $amount++){?>
                                <option value="<?=$amount?>" <?$amount==$good->amount ? print 'selected' : ''?>><?=$amount?> шт</option>
                            <?}?>
                        </select>
                    </td>
                    <td class="remove"><button name="remove" value="<?=$good->id?>">X</button></td>
                </tr>
            <?}?>
        </table>
        
        <div class="contacts_form">
            <h3>Контактные данные</h3>
            
            <input class="form_input" name="name" value="" placeholder="Введите имя *" />
            <input class="form_input" name="phone" value="" placeholder="Введите телефон *" />
            <input class="form_input" name="email" value="" placeholder="Введите E-mail *" />
            <textarea name="addres" class="form_textarea" placeholder="Введите адрес"></textarea>
            <input type="submit" name="checkout" value="Оформить заказ" />
        </div>
        
    </form>
<?} else {?>
    <h1>Корзина пуста</h1>
<?}?>