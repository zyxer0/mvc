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

<?if(!empty($orders)) {?>
    <h2>Заказы</h2>
    
    <ul class="user_orders head">
        <li class="order_id">Номер заказа</li>
        <li class="status">Статус</li>
        <li class="comment">Доставка</li>
        <li class="purchases">Товары</li>
    </ul>
    
    <?foreach($orders as $order) {?>
        <ul class="user_orders">
            <li class="order_id">
                <a href="/order/<?=$order->url?><?=$settings->prefix?>">Заказ №<?=$order->id?></a>
            </li>
            <li class="status">
                <?=$order->status?>, <?=($order->paid) ? 'оплачен' : 'не оплачен'?>
            </li>
            <li class="comment">
                <?if($order->delivery_type == 1) {?>
                    <div>Курьерская доставка</div>
                    <div>Страна: <?=$order->country?></div>
                    <div>Город: <?=$order->city?></div>
                    <div>Адрес: <?=$order->address?></div>
                <?}?>
                <?if($order->delivery_type == 2) {?>
                    <div>Доставка Новой почтой</div>
                    <div>Адрес отделения: <?=$order->newposht_address?></div>
                <?}?>
            </li>
            <li class="purchases">
                <ul class="order_goods">
                    <li class="head">
                        <div class="cell image"></div>
                        <div class="cell name">Товар</div>
                        <div class="cell price">Цена</div>
                        <div class="cell amount">Кол-во</div>
                        <div class="cell total">Итого</div>
                    </li>
                    <?foreach($order->purchases as $purchase) {?>
                    <li>
                        <div class="cell image">
                            <?if(isset($purchase->good->image)) {?>
                            <img src="/<?=$settings->goods_images_dir?><?=$purchase->good->image->filename_small?>" alt="<?=(isset($purchase->good->image->alt) ? $purchase->good->image->alt : '')?>" title="<?=(isset($good->image->title) ? $good->image->title : '')?>">
                            <?}?>
                        </div>
                        <div class="cell name">
                            <?if(isset($purchase->good->id)) {?>
                                <a href="/administrator/goods/view?id=<?=$purchase->good->id?>"><?=$purchase->good->name?></a>
                            <?} else {?>
                                <?=$purchase->name?> (Товар был удален)
                            <?}?>
                        </div>
                        <div class="cell price">
                            <?=$purchase->price?> <?=$settings->currency?>
                        </div>
                        <div class="cell amount">
                            <?=$purchase->amount?> <?=$settings->unit?>
                        </div>
                        <div class="cell total">
                            <?=$purchase->price*$purchase->amount?> <?=$settings->currency?>
                        </div>
                    </li>
                    <?}?>
                </ul>
            </li>
        </ul>
    <?}?>
<?}?>

<a href="/user-delete<?=$settings->prefix?>">Удалить мой аккаунт</a>