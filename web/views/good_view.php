<div class="product_page">
    <div class="product_left">
        <div class="image">
            <?if($good->image){?>
                <img src="/<?=$settings->goods_images_dir?><?=$good->image->filename_small?>" alt="<?=$good->image->alt?>" title="<?=$good->image->title?>">
            <?}?>
        </div>
        
        <div class="media_links">
            <?if($good->media_link_video){?>
                <a href="<?=$good->media_link_video?>" class="media_link_video"><span>Видеообзор</span></a>
            <?}?>
            <?if($good->media_link_demo){?>
                <a href="<?=$good->media_link_demo?>" class="media_link_video"><span>Демо</span></a>
            <?}?>
        </div>
        
    </div>
    
    <div class="product_info">
        <h1 class="product_name"><?=$good->name?><?isset($_SESSION['admin_id']) ? print "<a class=\"admin_edit\" href=\"/administrator/goods/view?id=" . $good->id . "\">Редактировать</a>" : ''?><?!$good->visible ? print "<span class=\"admin_hint\">(Товар не активен, его видите только вы)</span>" : ""?></h1>
        <?if(isset($good->colors)){?>
            <div class="product_colors">
                <div class="color_label">Цвет</div>
                <?foreach ($good->colors as $color) {?>
                    <a class="color <?=$color->color?>" href="<?=$color->link?>"></a>
                <?}?>
            </div>
        <?}?>
        
        <div class="clear"></div>
        
        <form method="post" class="buy_form">
            <?if($good->old_price > 0){?>
            <div class="old_price"><i><span><?=$good->old_price?> грн</span></i></div>
            <?}?>
            
            <?if($good->price > 0){?>
            <div class="price">
                <?=$good->price?> грн
            </div>
            <?}?>
            <div class="clear"></div>
        
            <input type="hidden" name="product_id" value="<?=$good->id?>" />
            <button type="submit" class="button buy_button" name="buy" data-result_text="Куплено">Купить</button>
        </form>
        <div class="clear"></div>
        <div class="rating_block">
            <div class="rating_off">
                <div class="rating_on" style="width: <?=$good->raiting * 25?>px;"></div>
            </div>
            <?/*?>
            <a href="<?=$good->reviews[0]?>" class="reviews_counter"><?=$good->reviews[1]?> <?=plural($good->reviews[1], 'отзыв', 'отзывов', 'отзыва')?></a>
            <?*/?>
            <div class="clear"></div>
        </div>
        <?if($good->features){?>
            <div class="features_block">
                <?foreach($good->features as $feature){?>
                    <img src="/<?=$settings->goods_features_images_dir?><?=$feature->image?>" />
                <?}?>
            </div>
        <?}?>
        <?if($good->description){?>
            <div class="description">
                <?=$good->description?>
            </div>
        <?}?>
        
        
        
    </div>
</div>
