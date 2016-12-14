<?
require_once '../Payment.php';

echo checkout_form(19);

function checkout_form($order_id, $button_text = null) {
    
    $db = new Db();
    $Controller_Order = new Controller_Order($db);
    
    $settings = (object) parse_ini_file("../../config/settings.ini");
    
    if(empty($button_text))
        $button_text = 'Перейти к оплате';
    
    $order = $Controller_Order->model->get_order((int)$order_id);
    $payment_settings = (object)parse_ini_file('settings.ini');
    
    // order description
    $desc = 'Оплата заказа №'.$order->id;

    $success_url = ROOT_URL . '/order/' . $order->url . $settings->prefix;
    $result_url = ROOT_URL.'/payments/Privat24/callback.php';
    
    $button =   '<form action="https://api.privatbank.ua/p24api/ishop" method="POST"/>'.
                '<input type="hidden" name="amt" value="' . $order->total_price . '"/>'.
                '<input type="hidden" name="ccy" value="' . $payment_settings->currency_code . '" />'.
                '<input type="hidden" name="merchant" value="' . $payment_settings->privat24_merchantid . '" />'.
                '<input type="hidden" name="order" value="' . $order->id . '" />'.
                '<input type="hidden" name="details" value="' . $desc . '" />'.
                '<input type="hidden" name="ext_details" value="" />'.
                '<input type="hidden" name="pay_way" value="privat24" />'.
                '<input type="hidden" name="return_url" value="' . $success_url . '" />'.
                '<input type="hidden" name="server_url" value="' . $result_url . '" />'.
                '<input type="submit" value="' . $button_text . '" />'.
                '</form>';

    return $button;
}
