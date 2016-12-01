<div class="product_page">
    <form method="POST" enctype="multipart/form-data" action="/administrator/categories/add">
        <div class="name_block">
            <input name="name" type="text" value="" placeholder="Имя категории" />
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
            <h2>Информация о категории</h2>
            <ul class="properties">
                <li>
                    <label for="visible">Активна</label><input value="1" type="checkbox" class="checkbox_input" name="visible" id="visible" />
                </li>
                <li>
                    <label for="url">URL категории</label><input value="" type="text" class="text_input" name="url" id="url" />
                </li>
                <li>
                    <label for="reviews_link">Родительская категория</label>
                    <select name="parent_id" class="text_input">
                        <option value="0">Корневая категория</option>
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
                                        print "<option value='{$c->id}'>".set_space($level)."{$c->name}</option>";
                                        if(isset($c->subcategories) && count($c->subcategories) > 0) {
                                            categories_tree($c->subcategories, ++$level);
                                        }
                                    }
                                    
                                }
                            }
                            
                            if($all_categories) {
                                categories_tree($all_categories, 1);
                            }
                            
                        ?>
                    </select>
                </li>
            </ul>
            <hr />
            
        </div>
        
        <div class="clear"></div>
        <h2>Описание категории</h2>
        <textarea name="description" id="editor"></textarea>
        
        <input type="submit" name="apply" value="Применить">
        
    </form>
</div>

<script>
$(function(){
    
});
</script>

