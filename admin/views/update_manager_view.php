<div class="product_page">
    <form method="POST" enctype="multipart/form-data" action="/administrator/managers/update?id=<?=$update_manager->id?>">
        <div class="name_block">
            <input name="username" class="name" type="text" value="<?=$update_manager->username?>" placeholder="Имя (логин) менеджера" />
        </div>
        
        <div class="left_block">
            <ul class="properties">
                <li>
                    <label for="password">Пароль</label><input value="" type="text" class="text_input" name="password" id="password" />
                </li>
            </ul>
            <hr />
            <h2>Доступы</h2>
            <ul class="properties">
                <?foreach($all_permissions as $permission){?>
                <li>
                    <label for="permission_<?=$permission->id?>"><?=$permission->name_menu_item?></label><input value="<?=$permission->id?>" type="checkbox" class="checkbox_input" name="permissions[]" <?in_array($permission->id, $update_manager->permissions_ids) ? print "checked" : ""?> id="permission_<?=$permission->id?>" />
                </li>
                <?}?>
                
            </ul>
            
        
        </div>
        <div class="clear"></div>
        
        <input type="submit" name="apply" value="Применить">
        
    </form>
</div>