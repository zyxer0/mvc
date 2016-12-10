<?if(isset($cart->total_goods) && $cart->total_goods > 0){?>
    <h1>Товаров в корзине <?echo $cart->total_goods?>
    на сумму <?=$cart->total_price?> <?=$settings->currency?>
    </h1>
    <form name="cart" method="POST" enctype="multipart/form-data" action="cart_update<?=$settings->prefix?>">
        <table class="cart_purchases">
            <tr>
                <th class="image"></th>
                <th class="name">Название</th>
                <th class="price">Цена</th>
                <th class="amount">количество</th>
                <th class="total">Итого</th>
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
                        <?=$good->price?> <?=$settings->currency?>
                    </td>
                    <td class="amount">
                        <select name="amounts[<?=$good->id?>]" onchange="document.cart.submit()">
                            <?for($amount=1; $amount<=50; $amount++){?>
                                <option value="<?=$amount?>" <?$amount==$good->amount ? print 'selected' : ''?>><?=$amount?> <?=$settings->unit?></option>
                            <?}?>
                        </select>
                    </td>
                    <td class="total">
                        <?=$good->price*$good->amount?> <?=$settings->currency?>
                    </td>
                    <td class="remove"><button name="remove" value="<?=$good->id?>">X</button></td>
                </tr>
            <?}?>
        </table>
        <a class="button checkout_button" href="/order/contact<?=$settings->prefix?>">Оформить заказ</a>
    </form>
<?} else {?>
    <h1>Корзина пуста</h1>
<?}?>