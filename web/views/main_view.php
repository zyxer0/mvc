<h1>Главная</h1>

<div class="content">
Интернет-магазин learn.com, занимается розничной и оптовой торговлей электроникой из поднебесной. Мы рады предложить вашему вниманию китайские мобильные телефоны, планшеты, регистраторы.  В нашем магазине постоянно действуют различные акции, система скидок, все это делает покупки - простым и приятным событием!
</div>
<br />
<div class="content">

    <h2>Хиты продаж</h2>

    <div class="tiny_products">
        <?
        if(!empty($goods)){
            $width = 'percent_20';
            foreach ($goods as $good) {
                if($good['sticker_class'] == 'topSales'){
                    ?>
                    <div class="product <? isset($width) ? print $width : ''?>">
                        <div class="inner">
                            <div class="image">
                                <?if($good['image']){?>
                                    <a href="index.php?route=good&id=<?=$good['id']?>">
                                        <img src="<?=GOOD_IMAGES_DIR?><?=$good["image"]?>" alt="<?=$good['image_alt']?>" title="<?=$good['image_title']?>">
                                    </a>
                                <?}?>
                                
                                <?if( $good["sticker_class"] ){?>
                                    <span class="sticker <?=$good["sticker_class"]?>"><?=$good["sticker_text"]?></span>
                                <?}?>
                                <?if(isset($good['colors'])){?>
                                    <div class="product_colors">
                                        <?foreach ($good['colors'] as $color) {?>
                                            <a class="color <?=$color['color']?>" href="<?=$color['link']?>"></a>
                                        <?}?>
                                    </div>
                                <?}?>
                            </div>
                            <h2 class="name"><a href="index.php?route=good&id=<?=$good['id']?>"><?=$good['name']?></a></h2>
                            
                            <?if($good['old_price']){?>
                                <div class="old_price"><i><span><?=convert($good["old_price"])?></span> грн</i></div>
                            <?}?>
                            
                            <div class="price">
                                <?=convert($good["price"])?> грн
                            </div>
                            <div class="clear"></div>
                            
                            <form method="post" class="buy_form" action="/cms/actions/index.php?action=cart">
                                <input type="hidden" name="product_id" value="<?=$good['id']?>" />
                                <button type="submit" class="button buy_button" name="buy" data-result_text="Куплено">Купить</button>
                            </form>
                            
                            <div class="clear"></div>
                            <?if($good["description"]){?>
                                <div class="description"><?=$good["description"]?></div>
                            <?}?>
                            
                        </div>
                    </div>
                    <?
                }
            }
        }?>
    </div>
</div>