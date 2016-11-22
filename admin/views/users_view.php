
<div class="tiny_products">
    <div><a class="add_product" href="/administrator/users/add">Добавить пользователя</a></div>
    <form method="POST" action="/administrator/users/remove">
        <?
        if(!empty($users)){
            ?>
            <h2>Всего пользователей <?=count($users)?></h2>
            <?
            foreach ($users as $user) {
                ?>
                <div class="product">
                    <div class="name"><a href="/administrator/users/view?id=<?=$user->id?>"><?=$user->name?></a> </div>
                    <div class="icons_block">
                        <button name="remove_id" value="<?=$user->id?>" class="icon delete" type="submit" title="Удалить"></button>
                    </div>
                    <div class="clear"></div>
                </div>
                <?
            }
        }?>
    </form>
</div>

