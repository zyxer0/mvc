<?if(isset($order->purchases)){?>
    <h1>Заказ №<?echo $order->id?>
    на сумму <?=$order->total_price?> <?=$settings->currency?>
    </h1>
    <p>Статус заказа: <?=$order->status?></p>
    <table class="cart_purchases">
        <tr>
            <th class="image"></th>
            <th class="name">Название</th>
            <th class="price">Цена</th>
            <th class="amount">количество</th>
            <th class="total">Итого</th>
        </tr>
        <?foreach($order->purchases as $purchase){?>
            <tr>
                <td class="image">
                    <?if(isset($purchase->good->image)) {?>
                    <a href="/<?=$purchase->good->url?><?=$settings->prefix?>">
                        <img src="/<?=$settings->goods_images_dir?><?=$purchase->good->image->filename_small?>" alt="<?=$purchase->good->image->alt?>" title="<?=$purchase->good->image->title?>">
                    </a>
                    <?}?>
                </td>
                <td class="name">
                    <a href="/<?=$purchase->good->url?><?=$settings->prefix?>">
                        <?=$purchase->name?>
                    </a>
                </td>
                <td class="price">
                    <?=$purchase->price?> <?=$settings->currency?>
                </td>
                <td class="amount">
                        <?=$purchase->amount?> <?=$settings->unit?>
                </td>
                <td class="total">
                    <?=$purchase->price*$purchase->amount?> <?=$settings->currency?>
                </td>
            </tr>
        <?}?>
    </table>
    
    <h2>Детали заказа</h2>
    <table class="order_details">
        <tr>
            <td class="label">
                Имя
            </td>
            <td>
                <?=$order->name?>
            </td>
        </tr>
        <tr>
            <td class="label">
                Фамилия
            </td>
            <td>
                <?=$order->last_name?>
            </td>
        </tr>
        <tr>
            <td class="label">
                Отчество
            </td>
            <td>
                <?=$order->patronymic?>
            </td>
        </tr>
        <tr>
            <td class="label">
                E-mail
            </td>
            <td>
                <?=$order->email?>
            </td>
        </tr>
        <tr>
            <td class="label">
                Телефон
            </td>
            <td>
                <?=$order->phone?>
            </td>
        </tr>
        <tr>
            <td class="label">
                Страна
            </td>
            <td>
                <?=$order->country?>
            </td>
        </tr>
        <tr>
            <td class="label">
                Город
            </td>
            <td>
                <?=$order->city?>
            </td>
        </tr>
    </table>
    
<?}