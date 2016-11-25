<div class="tiny_products reviews">
    <?
    if(!empty($reviews)){
        ?>
        <h2>Всего отзывов <?=count($reviews)?></h2>
        <?
        foreach ($reviews as $review) {
            ?>
            <div class="product ">
                <div class="name_block">
                    <div class="name"><?=$review->name?></div>
                    <div class="text"><?=$review->text?></div>
                    <div class="info">
                        Отзыв оставлен <?=date($settings->date_format, $review->time_stamp)?>
                        Оценка товара <?=$review->rating?>
                    </div>
                </div>
                <div class="icons_block">
                    <?if(!$review->approved) {?>
                    <form method="POST" action="/administrator/reviews/approve" style="float: left;">
                        <button name="approve_id" value="<?=$review->id?>" class="icon apply" type="submit" title="Одобрить"></button>
                    </form>
                    <?}?>
                    <form method="POST" action="/administrator/reviews/remove" style="float: left;">
                        <button name="remove_id" value="<?=$review->id?>" class="icon delete" type="submit" title="Удалить"></button>
                    </form>
                </div>
                <div class="clear"></div>
            </div>
            <?
        }
    }?>
</div>

