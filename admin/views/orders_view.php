<div class="tiny_products">
    <div class="order_statuses">
        Статусы: 
        <a href="/administrator/orders/index" <?=(!isset($_GET['status_id']) ? 'class="selected"' : '')?>>Все</a>
        <?
        foreach($statuses as $status) {
            ?>
            <a href="/administrator/orders/index?status_id=<?=$status->id?>" <?=(isset($_GET['status_id']) && $_GET['status_id'] == $status->id ? 'class="selected"' : '')?>><?=$status->status_name?></a>
            <?
        }
        ?>
    </div>
    <?
    if(!empty($orders)){
        ?>
        <h2>Всего заказов <?=count($orders)?></h2>
        
        <?
        foreach ($orders as $order) {
            ?>
            <div class="product">
                <div class="name"><a href="/administrator/orders/view?id=<?=$order->id?>">Заказ №<?=$order->id?></a> </div>
                <div class="icons_block">
                    <a href="/order/<?=$order->url . $settings->prefix ?>" target="_blank" class="icon show_on_site" title="Открыть на сайте"></a>
                </div>
                <div class="clear"></div>
            </div>
            <?
        }
    } else {?>
        Заказы не найдены
    <?}?>
</div>

