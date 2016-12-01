
<div class="tiny_products tiny_categories">
    <div><a class="add_product" href="/administrator/categories/add">Добавить категорию</a></div>
    <form method="POST" action="/administrator/categories/remove">
        <?
        
        if(!empty($categories)){
            ?>
            <h2>Всего категорий <?=$count_categories?></h2>
            <?
            function categories_tree($categories, $level) {
                foreach ($categories as $category) {
                    ?>
                    <div class="category category_level_<?=$level?>">
                        <div class="category_inner">
                            <div class="name_block" style="padding-left: <?=$level*15?>px;">
                                <div class="name"><a href="/administrator/categories/view?id=<?=$category->id?>"><?=$category->name?></a> </div>
                                <div class="clear"></div>
                            </div>
                            <div class="icons_block">
                                <button name="remove_id" value="<?=$category->id?>" class="icon delete" type="submit" title="Удалить"></button>
                                <a href="/<?=$category->url . $settings->prefix ?>" target="_blank" class="icon show_on_site" title="Открыть на сайте"></a>
                            </div>
                            <div class="clear"></div>
                        </div>
                        
                        <?
                        if(isset($category->subcategories) && count($category->subcategories) > 0) {
                            categories_tree($category->subcategories, $level+1);
                        }
                        ?>
                        
                    </div>
                    <?
                }
            }
            categories_tree($categories, 1);
        } else {?>
            Нет категорий
        <?}?>
    </form>
</div>

