<div class="product_page">
    <?if(isset($_SESSION['category_status']['success']) && !empty($_SESSION['category_status']['success'])){?>
    <div class="message_success">
        <?
        switch($_SESSION['category_status']['success']){
            case 'updated':
                print 'Категория успешно обновлена';
            break;
            case 'added':
                print 'Категория успешно добавлена';
            break;
        }
        ?>
        <?
            print "<a href=\"/" . $category->url . $settings->prefix . "\">Открыть на сайте</a>";
        ?>
    </div>
    <?}?>
    <?unset($_SESSION['category_status']);?>
    <form method="POST" enctype="multipart/form-data" action="/administrator/categories/update?id=<?=$category->id?>">
        <div class="name_block">
            <input name="name" type="text" value="<?isset($category->name) ? print $category->name : ''?>" placeholder="Имя категории" />
        </div>
        
        <div class="left_block">
            <h2>СЕО блок</h2>
            <ul class="properties">
                <li>
                    <label for="meta_title">Мета тайтл</label><input value="<?isset($category->meta_title) ? print $category->meta_title : ''?>" type="text" class="text_input" name="meta_title" id="meta_title" />
                </li>
                <li>
                    <label for="meta_description">Мета описание</label><input value="<?isset($category->meta_description) ? print $category->meta_description : ''?>" type="text" class="text_input" name="meta_description" id="meta_description" />
                </li>
                <li>
                    <label for="meta_keywords">Ключевые слова</label><input value="<?isset($category->meta_keywords) ? print $category->meta_keywords : ''?>" type="text" class="text_input" name="meta_keywords" id="meta_title" />
                </li>
            </ul>
            <hr />
            <h2>Информация о категории</h2>
            <ul class="properties">
                <li>
                    <label for="visible">Активна</label><input value="1" type="checkbox" class="checkbox_input" name="visible" id="visible" <?!empty($category->visible) ? print 'checked' : ''?> />
                </li>
                <li>
                    <label for="url">URL категории</label><input value="<?isset($category->url) ? print $category->url : ''?>" type="text" class="text_input" name="url" id="url" />
                </li>
                <li>
                    <label for="reviews_link">Родительская категория</label>
                    <select name="parent_id" class="text_input">
                        <option value="0" <?$category->parent_id == 0 ? print "selected" : ''?>>Корневая категория</option>
                        <?
                            function set_space($level) {
                                $space = '';
                                for($i=1; $i<=$level; $i++) {
                                    $space .= "&nbsp;&nbsp;";
                                }
                                return $space;
                            }
                            
                            function categories_tree($categories, $level, $category){
                                if($categories) {
                                    foreach($categories as $c) {
                                        if($c->id != $category->id) {
                                            print "<option value='{$c->id}' ".($c->id == $category->parent_id ? "selected" : "").">".set_space($level)."{$c->name}</option>";
                                        
                                            if(isset($c->subcategories) && count($c->subcategories) > 0) {
                                                categories_tree($c->subcategories, ++$level, $category);
                                            }
                                        }
                                    }
                                    
                                }
                            }
                            
                            if($all_categories) {
                                categories_tree($all_categories, 1, $category);
                            }
                            
                        ?>
                    </select>
                </li>
            </ul>
            <hr />
            
        </div>
        
        <div class="clear"></div>
        <h2>Описание категории</h2>
        <textarea name="description" id="editor"><?isset($category->description) ? print $category->description : ''?></textarea>
        
        <input type="submit" name="apply" value="Применить">
        
    </form>
</div>

<script>
$(function(){
    
});
</script>

