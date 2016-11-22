<div class="product_page">
    <?if(isset($_SESSION['good_status']['success']) && !empty($_SESSION['good_status']['success'])){?>
    <div class="message_success">
        <?
        switch($_SESSION['good_status']['success']){
            case 'updated':
                print 'Товар успешно обновлен';
            break;
            case 'added':
                print 'Товар успешно добавлен';
            break;
        }
        ?>
        <?
            print "<a href=\"/" . $good->url . "\" target=\"_blank\">Открыть на сайте</a>";
        ?>
    </div>
    <?}?>
    <?unset($_SESSION['good_status']);?>
    <form method="POST" enctype="multipart/form-data" action="/administrator/goods/update?id=<?=$good->id?>">
        <div class="name_block">
            <input name="name" type="text" value="<?isset($good->name) ? print $good->name : ''?>" placeholder="Название товара" />
        </div>
        
        <div class="left_block">
            <h2>СЕО блок</h2>
            <ul class="properties">
                <li>
                    <label for="meta_title">Мета тайтл</label><input value="<?isset($good->meta_title) ? print $good->meta_title : ''?>" type="text" class="text_input" name="meta_title" id="meta_title" />
                </li>
                <li>
                    <label for="meta_description">Мета описание</label><input value="<?isset($good->meta_description) ? print $good->meta_description : ''?>" type="text" class="text_input" name="meta_description" id="meta_description" />
                </li>
                <li>
                    <label for="meta_keywords">Ключевые слова</label><input value="<?isset($good->meta_keywords) ? print $good->meta_keywords : ''?>" type="text" class="text_input" name="meta_keywords" id="meta_title" />
                </li>
            </ul>
            <hr />
            <h2>Информация о товаре</h2>
            <ul class="properties">
                <li>
                    <label for="visible">Активен</label><input value="1" type="checkbox" class="checkbox_input" name="visible" id="visible" <?!empty($good->visible) ? print 'checked' : ''?> />
                </li>
                <li>
                    <label for="url">URL товара</label><input value="<?isset($good->url) ? print $good->url : ''?>" type="text" class="text_input" name="url" id="url" />
                </li>
                <li>
                    <label for="price">Цена</label><input value="<?isset($good->price) ? print $good->price : ''?>" type="text" class="text_input" name="price" id="price" />
                </li>
                <li>
                    <label for="old_price">Старая цена</label><input value="<?isset($good->old_price) ? print $good->old_price : ''?>" type="text" class="text_input" name="old_price" id="old_price" />
                </li>
                <li>
                    <label for="media_link_video">Линк видео</label><input value="<?isset($good->media_link_video) ? print $good->media_link_video : ''?>" type="text" class="text_input" name="media_link_video" id="media_link_video" />
                </li>
                <li>
                    <label for="media_link_demo">Линк демо</label><input value="<?isset($good->media_link_demo) ? print $good->media_link_demo : ''?>" type="text" class="text_input" name="media_link_demo" id="media_link_demo" />
                </li>
                <li>
                    <label for="raiting">Рейтинг товара</label><input value="<?isset($good->raiting) ? print $good->raiting : ''?>" type="text" class="text_input" name="raiting" id="raiting" />
                </li>
                
                <li>
                    <label for="ending_good">Товар заканчивается</label><input value="1" type="checkbox" class="checkbox_input" name="ending_good" id="ending_good" <?!empty($good->ending_good) ? print 'checked' : ''?> />
                </li>
                <li>
                    <label for="reviews_link">Линк отзывов</label><input value="<?isset($good->reviews[0]) ? print $good->reviews[0] : ''?>" type="text" class="text_input" name="reviews_link" id="reviews_link" />
                </li>
                <li>
                    <label for="reviews_count">Рейтинг</label><input value="<?isset($good->reviews_count) ? print $good->reviews_count : ''?>" type="text" class="text_input" name="reviews_count" id="reviews_count" />
                </li>
                <li>
                    <label for="sticker_class">Класс стикера</label><input value="<?isset($good->sticker_class) ? print $good->sticker_class : ''?>" type="text" class="text_input" name="sticker_class" id="sticker_class" />
                </li>
                <li>
                    <label for="sticker_text">Текст стикера</label><input value="<?isset($good->sticker_text) ? print $good->sticker_text : ''?>" type="text" class="text_input" name="sticker_text" id="sticker_text" />
                </li>
                
            </ul>
            <hr />
            
            <h2>Свойства товара</h2>
            <ul class="properties features">
                <li><a href="#" class="add_feature">Добавить свойство</a></li>
                
                <?if(!empty($good->features)){
                    foreach($good->features as $feature){?>
                        <li class="product_feature">
                            <img src="/<?=$settings->goods_features_images_dir?><?=$feature->image?>">
                            <input value="<?=$feature->image?>" type="hidden" name="features[<?=$feature->id?>]" />
                            
                            <button class="delete" type="button" title="Удалить"></button>
                        </li>
                <?  }
                }?>
            </ul>
            <hr />
        
        </div>
        
        <div class="right_block">
            
            <h2>Изображение товара</h2>
            <ul class="properties">
                <li class="image_select">
                    <label class="select_image" for="good_image">Изображения товара<input value="" multiple type="file" class="text_input" name="good_images[]" id="good_image" />
                    </label>
                </li>
                <?if(!empty($good->images)){?>
                    <?foreach($good->images as $image) {?>
                        <li class="product_image">
                            <div class="alt_block">
                                <input value="<?isset($image->alt) ? print $image->alt : ''?>" type="text" class="" placeholder="Альт" name="image_alt[<?=$image->id?>]" />
                            </div>
                            
                            <div class="title_block">
                                <input value="<?isset($image->title) ? print $image->title : ''?>" type="text" placeholder="Тайтл" class="" name="image_title[<?=$image->id?>]" />
                            </div>
                            <input id="image_<?=$image->id?>" type="radio" name="main_image_id" value="<?=$image->id?>" <?$image->id == $good->main_image_id ? print "checked" : ""?> />
                            <label class="main_image_label" for="image_<?=$image->id?>">
                                <img src="/<?=$settings->goods_images_dir?><?=$image->filename_middle?>">
                            </label>
                            
                            <input name="delete_images[]" type="hidden" value="<?=$image->id?>" disabled />
                            <button class="delete_image" type="button" title="Удалить"></button>
                        </li>
                    <?}?>
                    <li class="clear"></li>
                <?}?>
            </ul>
            <hr />
            
            <h2>Цвета товара</h2>
            <ul class="properties">
                <?if(!empty($good->colors)){
                    foreach($good->colors as $k=>$color){
                        ?>
                        <li>
                            <label for="colors_<?=$color->id?>">Цвет <?=$k+1?></label><input value="<?=$color->color?>" type="text" class="text_input" name="colors[]" id="colors_<?=$color->id?>" />
                        </li>
                        <?
                    }
                    ?>
                    <li>
                        <label for="colors_<?=$k+2?>">Цвет <?=$k+2?></label><input value="" type="text" class="text_input" name="colors[]" id="colors_<?=$k+2?>" />
                    </li>
                    <?
                    
                } else {?>
                    <li>
                        <label for="colors_1">Цвет 1</label><input value="" type="text" class="text_input" name="colors[]" id="colors_1" />
                    </li>
                    <li>
                        <label for="colors_2">Цвет 2</label><input value="" type="text" class="text_input" name="colors[]" id="colors_2" />
                    </li>
                    <li>
                        <label for="colors_3">Цвет 3</label><input value="" type="text" class="text_input" name="colors[]" id="colors_3" />
                    </li>
                    <li>
                        <label for="colors_4">Цвет 4</label><input value="" type="text" class="text_input" name="colors[]" id="colors_4" />
                    </li>
                    <li>
                        <label for="colors_5">Цвет 5</label><input value="" type="text" class="text_input" name="colors[]" id="colors_5" />
                    </li>
                <?}?>
            </ul>
            <hr />
            
            
            
            
            
        </div>
        <div class="clear"></div>
        <h2>Описание товара</h2>
        <textarea name="description" id="editor"><?isset($good->description) ? print $good->description : ''?></textarea>
        
        <input type="submit" name="apply" value="Применить">
        
    </form>
</div>

<script>
$(function(){
    /* $('.product_image .delete').on('click', function(){
        $(this).closest('li').remove();
        $('.image_select').show().find('input').attr('disabled', false);
    });  */
    
    $('.product_feature .delete').on('click', function(){
        $(this).closest('li').remove();
    });
    $('.product_image .delete_image').on('click', function(){
        $(this).closest('li').hide().find('input[name*="delete_images"]').attr('disabled', false);
        $(this).closest('li').hide().find('input[name*="image_alt"], input[name*="image_title"]').attr('disabled', true);
    });
    
    $('a.add_feature').on('click', function(){
        
        $(this).closest('ul').append('<li class="image_select"><label class="select_image" for="feature_1">Свойство<input value="" type="file" class="text_input" name="features[]" id="feature_1" /></label></li>')
        
        return false;
    });
    
    
    
});
</script>

