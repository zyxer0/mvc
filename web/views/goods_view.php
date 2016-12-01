<div class="tiny_products">
    <?
    if(!empty($goods)){
        foreach ($goods as $good) {
            ?>
            <div class="product <? isset($width) ? print $width : ''?>">
                <div class="inner">
                    <div class="image">
                        <?if($good->image){?>
                            <a href="<?=$good->url?><?=$settings->prefix?>">
                                <img src="/<?=$settings->goods_images_dir?><?=$good->image->filename_small?>" alt="<?=$good->image->alt?>" title="<?=$good->image->title?>">
                            </a>
                        <?}?>
                        
                        <?if( $good->sticker_class ){?>
                            <span class="sticker <?=$good->sticker_class?>"><?=$good->sticker_text?></span>
                        <?}?>
                        <?if(isset($good->colors)){?>
                            <div class="product_colors">
                                <?foreach ($good->colors as $color) {?>
                                    <a class="color <?=$color->color?>" href="<?=$good->url?><?=$settings->prefix?><?=$color->link?>"></a>
                                <?}?>
                            </div>
                        <?}?>
                    </div>
                    <h2 class="name"><a href="<?=$good->url?><?=$settings->prefix?>"><?=$good->name?></a></h2>
                    
                    <?if($good->old_price){?>
                        <div class="old_price"><i><span><?=$good->old_price?></span> грн</i></div>
                    <?}?>
                    
                    <div class="price">
                        <?=$good->price?> грн
                    </div>
                    <div class="clear"></div>
                    
                    <form  method="GET" action="cart_add<?=$settings->prefix?>" class="buy_form">
                        <input type="hidden" name="good_id" value="<?=$good->id?>" />
                        <button type="submit" class="button buy_button" name="buy" data-result_text="Куплено">Купить</button>
                    </form>
                    
                    <div class="clear"></div>
                    <?if($good->description){?>
                        <div class="description"><?=$good->description?></div>
                    <?}?>
                    
                </div>
            </div>
            <?
        }
    } else {?>
        Товары не найдены
    <?}?>
</div>