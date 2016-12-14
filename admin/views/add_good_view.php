<div class="product_page">
    <form method="POST" enctype="multipart/form-data">
        <div class="name_block">
            <input name="name" type="text" value="<?isset($good->name) ? print $good->name : ''?>" placeholder="Название товара" />
        </div>
        
        <div class="left_block">
            <h2>СЕО блок</h2>
            <ul class="properties">
                <li>
                    <label for="meta_title">Мета тайтл</label><input value="" type="text" class="text_input" name="meta_title" id="meta_title" />
                </li>
                <li>
                    <label for="meta_description">Мета описание</label><input value="" type="text" class="text_input" name="meta_description" id="meta_description" />
                </li>
                <li>
                    <label for="meta_keywords">Ключевые слова</label><input value="" type="text" class="text_input" name="meta_keywords" id="meta_title" />
                </li>
            </ul>
            <hr />
            <h2>Информация о товаре</h2>
            <ul class="properties">
                <li>
                    <label>Категория товара</label>
                    <select name="category_id" class="text_input">
                        <?
                            function set_space($level) {
                                $space = '';
                                for($i=1; $i<=$level; $i++) {
                                    $space .= "&nbsp;&nbsp;";
                                }
                                return $space;
                            }
                            
                            function categories_tree($categories, $level){
                                if($categories) {
                                    foreach($categories as $c) {
                                        print "<option value='{$c->id}' >".set_space($level)."{$c->name}</option>";
                                    
                                        if(isset($c->subcategories) && count($c->subcategories) > 0) {
                                            categories_tree($c->subcategories, ++$level);
                                        }
                                    }
                                }
                            }
                            
                            if($categories) {
                                categories_tree($categories, 1);
                            }
                            
                        ?>
                    </select>
                </li>
                <li>
                    <label for="visible">Активен</label><input value="1" type="checkbox" class="checkbox_input" name="visible" id="visible"  />
                </li>
                <li>
                    <label for="url">URL товара</label><input value="" type="text" class="text_input" name="url" id="url" />
                </li>
                <li>
                    <label for="price">Цена</label><input value="" type="text" class="text_input" name="price" id="price" />
                </li>
                <li>
                    <label for="old_price">Старая цена</label><input value="" type="text" class="text_input" name="old_price" id="old_price" />
                </li>
                <li>
                    <label for="media_link_video">Линк видео</label><input value="" type="text" class="text_input" name="media_link_video" id="media_link_video" />
                </li>
                <li>
                    <label for="media_link_demo">Линк демо</label><input value="" type="text" class="text_input" name="media_link_demo" id="media_link_demo" />
                </li>
                <li>
                    <label for="raiting">Рейтинг товара</label><input value="" type="text" class="text_input" name="raiting" id="raiting" />
                </li>
                
                <li>
                    <label for="ending_good">Товар заканчивается</label><input value="1" type="checkbox" class="checkbox_input" name="ending_good" id="ending_good" />
                </li>
                <li>
                    <label for="reviews_link">Линк отзывов</label><input value="" type="text" class="text_input" name="reviews_link" id="reviews_link" />
                </li>
                <li>
                    <label for="reviews_count">Рейтинг</label><input value="" type="text" class="text_input" name="reviews_count" id="reviews_count" />
                </li>
                <li>
                    <label for="sticker_class">Класс стикера</label><input value="" type="text" class="text_input" name="sticker_class" id="sticker_class" />
                </li>
                <li>
                    <label for="sticker_text">Текст стикера</label><input value="" type="text" class="text_input" name="sticker_text" id="sticker_text" />
                </li>
                
            </ul>
            <hr />
            
            <h2>Свойства товара</h2>
            <ul class="properties features">
                <li><a href="#" class="add_feature">Добавить свойство</a></li>
                
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
            </ul>
            <hr />
            
            <h2>Цвета товара</h2>
            <ul class="properties">
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
            </ul>
            <hr />
            
            
            
            
            
        </div>
        <div class="clear"></div>
        <h2>Описание товара</h2>
        <textarea name="description" id="editor"></textarea>
        
        <input type="submit" name="apply" value="Применить">
        
    </form>
</div>

<script>
$(function(){
    $('.product_image .delete').on('click', function(){
        $(this).closest('li').remove();
        $('.image_select').show().find('input').attr('disabled', false);
    }); 
    
    $('.product_feature .delete').on('click', function(){
        $(this).closest('li').remove();
    });
    
    $('a.add_feature').on('click', function(){
        
        $(this).closest('ul').append('<li class="image_select"><label class="select_image" for="feature_1">Свойство<input value="" type="file" class="text_input" name="features[]" id="feature_1" /></label></li>')
        
        return false;
    });
    
    
    
});
</script>

