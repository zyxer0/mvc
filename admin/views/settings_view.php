
<div class="tiny_products">
    <form method="POST" enctype="myltipart/form-data" action="/administrator/settings/update">
        <div class="left_block">
            <h2>Настройки изображений</h2>
            <ul class="properties">
                <li>Размер большого изображения</li>
                <li>
                    <label for="full_size_x">Ширина</label><input value="<?=$settings->full_size_x?>" type="text" class="text_input small" name="settings[full_size_x]" id="full_size_x" /> px
                </li>
                <li>
                    <label for="full_size_y">Высота</label><input value="<?=$settings->full_size_y?>" type="text" class="text_input small" name="settings[full_size_y]" id="full_size_y" /> px
                </li>
                <li>Размер среднего изображения</li>
                <li>
                    <label for="middle_size_x">Ширина</label><input value="<?=$settings->middle_size_x?>" type="text" class="text_input small" name="settings[middle_size_x]" id="middle_size_x" /> px
                </li>
                <li>
                    <label for="middle_size_y">Высота</label><input value="<?=$settings->middle_size_y?>" type="text" class="text_input small" name="settings[middle_size_y]" id="middle_size_y" /> px
                </li>
                <li>Размер малого изображения</li>
                <li>
                    <label for="small_size_x">Ширина</label><input value="<?=$settings->small_size_x?>" type="text" class="text_input small" name="settings[small_size_x]" id="small_size_x" /> px
                </li>
                <li>
                    <label for="small_size_y">Высота</label><input value="<?=$settings->small_size_y?>" type="text" class="text_input small" name="settings[small_size_y]" id="small_size_y" /> px
                </li>
            </ul>
            <hr />
            
            <h2>Настройки SEO</h2>
            <ul class="properties">
                <li>
                    <label for="prefix">ЧПУ префикс</label><input value="<?=$settings->prefix?>" type="text" class="text_input small" name="settings[prefix]" id="prefix" />
                </li>
            </ul>
            <hr />
            
            <h2>Настройки магазина</h2>
            <ul class="properties">
                <li>
                    <label for="date_format">Формат даты</label><input value="<?=$settings->date_format?>" type="text" class="text_input small" name="settings[date_format]" id="date_format" />
                </li>
                <li>
                    <label for="unit">Единицы измерения</label><input value="<?=$settings->unit?>" type="text" class="text_input small" name="settings[unit]" id="unit" />
                </li>
                <li>
                    <label for="currency">Валюта</label><input value="<?=$settings->currency?>" type="text" class="text_input small" name="settings[currency]" id="currency" />
                </li>
            </ul>
            <hr />
        </div>
        <div class="clear"></div>
        
        <input type="submit" name="save" class="button" value="Сохранить">
    </form>
</div>

