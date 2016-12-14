<?

require_once '../Payment.php';

function response($code = 200, $response = "") {
    http_response_code($code);
}

if(isset($_POST) && !empty($_POST)) {
    $db = new Db();
    $Controller_Order = new Controller_Order($db);

    $payment = $_POST['payment'];
    $signature = $_POST['signature'];

    parse_str($payment, $payment_url);

    if (empty($payment_url['order']))
        response(400, "Оплачиваемый заказ не найден");
    else
        $order_id = $payment_url['order'];

    $order = $Controller_Order->model->get_order((int)$order_id);
    $payment_settings = (object)parse_ini_file('settings.ini');

    if(empty($order))
      response(400, 'Оплачиваемый заказ не найден');

    // Нельзя оплатить уже оплаченный заказ
    if($order->paid)
      response(400, 'Этот заказ уже оплачен');

    if ($signature != sha1(md5($payment . $payment_settings->privat24_pass)))
        response(400, "bad sign\n");

    if ($payment_url['state'] == 'fail')
        response(400, "ошибка");

    $db->make_query("UPDATE orders SET paid=1 WHERE id=".$order->id);

    response(200, "ok");
}