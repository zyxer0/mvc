
<div class="tiny_products">
    <div><a class="add_product" href="/administrator/managers/add">Добавить менеджера</a></div>
    <form method="POST" action="/administrator/managers/remove">
        <?
        if(!empty($managers)){
            ?>
            <h2>Всего менеджеров <?=count($managers)?></h2>
            <?
            foreach ($managers as $m) {
                ?>
                <div class="product">
                    <div class="name"><a href="/administrator/managers/view?id=<?=$m->id?>"><?=$m->username?></a> </div>
                    <div class="icons_block">
                        <button name="remove_id" value="<?=$m->id?>" class="icon delete" type="submit" title="Удалить"></button>
                    </div>
                    <div class="clear"></div>
                </div>
                <?
            }
        }?>
    </form>
</div>

