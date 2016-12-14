// Аяксовая корзина
$('form.buy_form').live('submit', function(e) {
    e.preventDefault();
    var button = $(this).find('[type="submit"]');
    var action = $(this).attr('action');
    var good_id = $(this).find('input[name=good_id]').val();

    var amount = 1;
    if($(this).find('input[name=amount]').size()>0)
        amount = $(this).find('input[name=amount]').val();
    
    $.ajax({
        url: action,
        data: {good_id: good_id, amount: amount, is_ajax: 1},
        dataType: 'json',
        success: function(data){
            if(button.data('result_text')){
                button.text(button.data('result_text'));
            }
            
            if(data.total_goods > 0) {
                $('#cart_informer').html('<a href="/cart.html">В корзине товаров '+data.total_goods+'</a>');
            } else {
                $('#cart_informer').html('<span>Корзина пуста</span>');
            }
        }
    });
    return false;
});
