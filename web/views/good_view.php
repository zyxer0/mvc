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
            <div class="clear"></div>
            <div class="reviews">
                <?if(!empty($reviews)) {?>
                    <h2>Отзывы</h2>
                    <?foreach($reviews as $review) {?>
                        <div class="review">
                            <div class="name">
                                <?=$review->name?> 
                                <span class="date"><?=date($settings->date_format, $review->time_stamp)?></span>
                            </div>
                            <div class="text"><?=$review->text?></div>
                        </div>
                    <?}?>
                <?} else {?>
                <p>Пока нет отзывов</p>
                <?}?>
                
                <form class="review_form" method="POST">
                    <h3>Добавить отзыв</h3>
                    <input type="text" name="name" placeholder="Введите имя" value="<?if(!empty($user->name)) print $user->name?>" />
                    <div class="review_rating">
                        <input type="radio" name="rating" value="5" id="rating_5">
                        <label for="rating_5"></label>
                        <input type="radio" name="rating" value="4" id="rating_4">
                        <label for="rating_4"></label>
                        <input type="radio" name="rating" value="3" id="rating_3">
                        <label for="rating_3"></label>
                        <input type="radio" name="rating" value="2" id="rating_2">
                        <label for="rating_2"></label>
                        <input type="radio" name="rating" value="1" id="rating_1">
                        <label for="rating_1"></label>
                    </div>
                    <textarea name="text" placeholder="Введите отзыв"></textarea>
                    <button type="submit" class="button" name="add_review">Добавить отзыв</button>
                    
                </form>
                
            </div>
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
                <h2>Описание товара</h2>
                <?=$good->description?>
            </div>
        <?}?>
        
        
        
    </div>
</div>
