<div class="product_page">
    <?if(isset($_SESSION['order_status']['success']) && !empty($_SESSION['order_status']['success'])){?>
    <div class="message_success">
        <?
        switch($_SESSION['order_status']['success']){
            case 'updated':
                print 'Заказ успешно обновлен';
            break;
        }
        ?>
    </div>
    <?}?>
    <?unset($_SESSION['order_status']);?>
    <form method="POST" enctype="multipart/form-data" action="/administrator/orders/update?id=<?=$order->id?>">
        <div class="name_block">
            <p>Заказ №<?=$order->id?></p>
            
            <label>Статус заказа</label>
            <select name="status_id">
                <?
                foreach($statuses as $status) {
                    ?>
                    <option value="<?=$status->id?>" <?=($order->status_id == $status->id ? 'selected' : '')?>><?=$status->status_name?></option>
                    <?
                }
                ?>
            </select>
        </div>
        <div class="left_block">
            <h2>Данные клиента <a href="#" class="edit_user_info" title="Редактировать"><img src="/admin/static/images/edit.png"/></a></h2>
            <ul class="properties user_info">
                <li>
                    <label for="name">Имя</label>
                    <span class="view_block"><?=$order->name?></span>
                    <span class="edit_block">
                        <input value="<?=$order->name?>" type="text" class="text_input" name="name" id="name" />
                    </span>
                </li>
                <li>
                    <label for="last_name">Фамилия</label>
                    <span class="view_block"><?=$order->last_name?></span>
                    <span class="edit_block">
                        <input value="<?=$order->last_name?>" type="text" class="text_input" name="last_name" id="last_name" />
                    </span>
                </li>
                <li>
                    <label for="patronymic">Отчество</label>
                    <span class="view_block"><?=$order->patronymic?></span>
                    <span class="edit_block">
                        <input value="<?=$order->patronymic?>" type="text" class="text_input" name="patronymic" id="patronymic" />
                    </span>
                </li>
                <li>
                    <label for="email">E-mail</label>
                    <span class="view_block"><?=$order->email?></span>
                    <span class="edit_block">
                        <input value="<?=$order->email?>" type="text" class="text_input" name="email" id="email" />
                    </span>
                </li>
                <li>
                    <label for="phone">Телефон</label>
                    <span class="view_block"><?=$order->phone?></span>
                    <span class="edit_block">
                        <input value="<?=$order->phone?>" type="text" class="text_input" name="phone" id="phone" />
                    </span>
                </li>
                <li>
                    <label for="country">Страна</label>
                    <span class="view_block"><?=$order->country?></span>
                    <span class="edit_block">
                        <input value="<?=$order->country?>" type="text" class="text_input" name="country" id="country" />
                    </span>
                </li>
                <li>
                    <label for="city">Город</label>
                    <span class="view_block"><?=$order->city?></span>
                    <span class="edit_block">
                        <input value="<?=$order->city?>" type="text" class="text_input" name="city" id="city" />
                    </span>
                </li>
            </ul>
            <hr />
        </div>
        
        <div class="right_block">
            
            <h2>Товары</h2>
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
                        <?if($purchase->good->image) {?>
                        <img src="/<?=$settings->goods_images_dir?><?=$purchase->good->image->filename_small?>" alt="<?=(isset($purchase->good->image->alt) ? $purchase->good->image->alt : '')?>" title="<?=(isset($good->image->title) ? $good->image->title : '')?>">
                        <?}?>
                    </div>
                    <div class="cell name">
                        <a href="/administrator/goods/view?id=<?=$purchase->good->id?>"><?=$purchase->good->name?></a>
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
            <hr />
            
            
        </div>
        <div class="clear"></div>
        
        <input type="submit" name="apply" value="Применить">
        
    </form>
</div>

<script>
$(function(){
    
    $('.edit_user_info').on('click', function(){
        $('.user_info .view_block').hide();
        $('.user_info .edit_block').show();
        return false;
    });
});
</script>

