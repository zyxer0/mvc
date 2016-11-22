
<div class="tiny_products">
    <div><a class="add_product" href="/administrator/goods/add">Добавить товар</a></div>
    <form method="POST" action="/administrator/goods/remove">
        <?
        
        if(!empty($goods)){
            ?>
            <h2>Всего товаров <?=count($goods)?></h2>
            <?
            foreach ($goods as $good) {
                ?>
                <div class="product">
                    <div class="image">
                        <?if($good->image){?>
                            <a href="/administrator/goods/view?id=<?=$good->id?>">
                                <img src="/<?=$settings->goods_images_dir?><?=$good->image->filename_small?>" alt="<?isset($good->image->alt) ? print $good->image->alt : ''?>" title="<?isset($good->image->title) ? print $good->image->title : ''?>">
                            </a>
                        <?}?>
                    </div>
                    <div class="name"><a href="/administrator/goods/view?id=<?=$good->id?>"><?=$good->name?></a> </div>
                    <div class="icons_block">
                        <button name="remove_id" value="<?=$good->id?>" class="icon delete" type="submit" title="Удалить"></button>
                        <a href="/<?=$good->url?>" target="_blank" class="icon show_on_site" title="Открыть на сайте"></a>
                    </div>
                    <div class="clear"></div>
                </div>
                <?
            }
        } else {?>
            Товары не найдены
        <?}?>
    </form>
</div>

