// Аяксовая корзина
$('form.buy_form').live('submit', function(e) {
    e.preventDefault();
    button = $(this).find('[type="submit"]');
    
    product_id = $(this).find('input[name=product_id]').val();

    amount = 1;
    if($(this).find('input[name=amount]').size()>0)
        amount = $(this).find('input[name=amount]').val();
    
    $.ajax({
        url: "actions/index.php",
        data: {action: 'ajax_cart', product_id: product_id, amount: amount},
        dataType: 'json',
        success: function(data){
            if(button.data('result_text')){
                button.text(button.data('result_text'));
            }
            
            $('#cart_informer').html(data);
        }
    });
    return false;
});
