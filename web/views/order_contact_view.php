<?if(isset($cart->total_goods) && $cart->total_goods > 0){?>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/chosen/1.0/chosen.jquery.js"></script>
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/chosen/1.0/chosen.min.css"/>
    <h1>Товаров в корзине <?echo $cart->total_goods?>
    на сумму <?=$cart->total_price?> грн
    </h1>
    <a class="" href="/cart<?=$settings->prefix?>">Редактировать покупки</a>
    <form name="cart" method="POST" action="/order/add<?=$settings->prefix?>">
        <div class="contacts_form">
            <h3>Контактные данные</h3>
            
            <input type="text" class="form_input" placeholder="Введите имя *"     required name="name"  value="<?isset($user->name) ? print $user->name : ''?>" />
            <input type="text" class="form_input" placeholder="Введите фамилию"  name="last_name" value="<?isset($user->last_name) ? print $user->last_name : ''?>" />
            <input type="text" class="form_input" placeholder="Введите отчество" name="patronymic" value="<?isset($user->patronymic) ? print $user->patronymic : ''?>" />
            <input type="text" class="form_input" placeholder="Введите телефон *" required name="phone" value="<?isset($user->phone) ? print $user->phone : ''?>" />
            <input type="text" class="form_input" placeholder="Введите E-mail *"  required name="email" value="<?isset($user->email) ? print $user->email : ''?>" />
            <div class="form_input">
                <span>
                    <label style="display: inline-block;vertical-align: top;" for="delivery_1">Курьерская доставка</label>
                    <input type="radio" name="delivery_type" id="delivery_1" value="1" />
                </span>
                <span>
                    <label style="display: inline-block;vertical-align: top;" for="delivery_2">Новая почта</label>
                    <input type="radio" name="delivery_type" id="delivery_2" value="2" />
                </span>
            </div>
            <div class="inputs_for_delivery inputs_for_delivery_1" style="display: none;">
                <input type="text" class="form_input address_input" disabled placeholder="Ваша страна" name="country" value="<?isset($user->country) ? print $user->country : ''?>" />
                <input type="text" class="form_input address_input" disabled placeholder="Ваш город"   name="city"    value="<?isset($user->city) ? print $user->city : ''?>" />
                <input type="text" class="form_input address_input" disabled placeholder="Ваш адрес"   name="address" value="<?isset($user->address) ? print $user->address : ''?>" />
            </div>
            <div class="inputs_for_delivery inputs_for_delivery_2" style="display: none;">
                <div class="novaposhta_div">
                    <input type="hidden" disabled class="address_input" name="newposht_address" value="" />
                    <div style="margin-bottom: 10px;">
                        <label style="display: inline-block; width: 100px;">
                            <span>Город: </span>
                        </label>
                        <select data-placeholder="Выберите город" name="city_newposht" tabindex="1" class="city_newposht"></select>
                    </div>
                    <div style="display: none;margin-bottom: 10px;">
                        <label style="display: inline-block; width: 100px;">
                            <span class="labelwarehouses_novaposhta">Пункт выдачи: </span>
                        </label>
                        <select name="warehouses_newposht" tabindex="1" class="warehouses_newposht"></select>
                    </div>
                </div>
            </div>
            <textarea name="comment" class="form_textarea" placeholder="Комментарий к заказу"></textarea>
            <input type="submit" class="button" name="checkout" value="Оформить заказ" />
        </div>
    </form>
<?} else {?>
    <h1>Корзина пуста</h1>
<?}?>
<script>
$(function(){
    $('input[name="delivery_type"]').on('change', function(){
        $('.inputs_for_delivery').hide().find('.address_input').attr('disabled', true);
        $('.inputs_for_delivery_'+$(this).val()).show().find('.address_input').attr('disabled', false);
    });
});

$(document).ready(function() {
    var city = $(".city_newposht");
    $.ajax({
        url: "/web/ajax/np.php",
        data: {method: 'get_cities'},
        dataType: 'json',
        success: function(data) {
            if(data.cities_response.success == 1){
                city.html(data.cities_response.cities);
            }
        }
    });
    
    $('select.city_newposht').on('change', function() {
        var city_newposht = $(this).children(':selected').data('city_ref');
        
        if(city_newposht != '') {
            $.ajax({
                url: "/web/ajax/np.php",
                data: {method: 'get_warehouses', city: city_newposht},
                dataType: 'json',
                success: function(data) {
                    if (data.warehouses_response.success) {
                        $('select.warehouses_newposht').html(data.warehouses_response.warehouses).attr('disabled', false);
                        $('select.warehouses_newposht').parent().show();
                        
                        $('select.warehouses_newposht').chosen('destroy');
                        
                        
                    } else {
                        $('select.warehouses_newposht').html('').attr('disabled', true);
                        $('select.warehouses_newposht').parent().hide();
                    }
                }
            });
        }
    });
    
    $('select.warehouses_newposht').on('change', function() {
        if($(this).val() != ''){
            $('input[name="newposht_address"]').val($('select.city_newposht').children(':selected').val()+', '+$(this).val());
        }
    });
    
    setInterval(function() { 
        if ($('.warehouses_newposht').children('option').length>1) {
            $('.warehouses_newposht').chosen({width: "255px",no_results_text: "Вашего отделения нет в списке"});
        };
    }, 100);
    
    setInterval(function() { 
        if ($('.city_newposht').children('option').length>1) {
            $('.city_newposht').chosen({width: "255px",no_results_text: "Вашего города нет в списке"});
        };
    }, 100);
});
</script>