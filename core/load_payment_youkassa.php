<?php

error_reporting(E_ALL);
ini_set('display_startup_errors', 1);
ini_set('display_errors', '1');

    include ('../_conf.php');
    include ('../classes/safemysql.class.php');
    $db = new SafeMySQL(array('host' => $db_host,'user' => $db_user, 'pass' => $db_pass, 'db' => $db_name, 'charset' => 'utf8'));

    require_once('../classes/core.class.php');

    $core  = new Core();

    $url = $core->url;
    $form = $core->form;
    $ip = $core->ip;
    $get = $core->setGet();

    echo '<pre>';

$file = 'yoomoney.csv';
$lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($lines as $line) {
    $data = explode(";", $line);
    var_dump($data);



    //ищем id для таблицы pre_payments
    $arr1 = explode('|', $data[7]);

    if (stripos($arr1[0], 'Оплата электроэнергии') !== false) {
        $pre_payments_id = mb_substr($arr1[0], 23);
        echo 'Оплата электроэнергии'.'<br>';
        $pre_payment_variant = 1;
    } else if (stripos($arr1[0], 'Оплата членских взносов') !== false) {
        $pre_payments_id = mb_substr($arr1[0], 25);
        echo '<b>Оплата членских взносов</b>'.'<br>';
        $pre_payment_variant = 2;
    } else if (stripos($arr1[0], 'Оплата целевых взносов') !== false) {
        $pre_payments_id = mb_substr($arr1[0], 24);
        echo '<b>Оплата целевых взносов</b>'.'<br>';
        $pre_payment_variant = 3;
    } else {
        die("!!!!!!!!!!!!!!!!!!!!!!");
    }

    $pre_payments_id = trim($pre_payments_id, ' ');
    var_dump($pre_payments_id);


    //ищем user_id для таблицы pre_payments
    $pre_payments_uchastok = mb_substr($arr1[1], 10);
    $pre_payments_user_q = $db->parse("SELECT id FROM `users` WHERE uchastok = ?s AND is_del = 0", $pre_payments_uchastok);
    var_dump($pre_payments_user_q);
    $pre_payments_user = $db->getOne($pre_payments_user_q);
    if ($pre_payments_user) {
        echo 'pre_payments_user';
        var_dump($pre_payments_user);
    } else {
        die('Пользователь не найден');
    }

    //ищем сумму платежа для таблицы pre_payments
    $pre_payments_amount = $data[1];

    //ищем дату платежа для таблицы pre_payments
    $pre_payments_date = date('Y-m-d H:i:s', strtotime($data[5]));

    //ищем id платежа юкасса для таблицы pre_payments
    $pre_payments_yid = $data[0];

    echo '<br><br>';

    //создаем запись в таблице pre_payments

    $insert_pre_payments = [
        'id' => $pre_payments_id,
        'user_id' => $pre_payments_user,
        'variant' => $pre_payment_variant,
        'amount' => $pre_payments_amount,
        'date' => $pre_payments_date,
        'status' => 0,
        'destanation_order_id' => $pre_payments_yid
    ];

    var_dump($insert_pre_payments);

    $pre_payments_q = $db->parse("
        INSERT INTO pre_payments SET ?u",
        $insert_pre_payments
    );
    var_dump($pre_payments_q);
    $db->query($pre_payments_q);

    //проводим платёж
    $payment = $_GET;

    //$payment_log = json_encode($payment);
    //$db->query("INSERT INTO payment_logs SET type = 'debug', text = ?s", [$payment_log]);



        $pay_order = $pre_payments_id;
        $pay_id = $pre_payments_id;
        //$pay_operation = $payment['operation'];

        $order_data = $db->getRow("SELECT * FROM pre_payments WHERE id = ?i", $pay_order);

        if ($order_data) {
            if ($order_data['status'] == 0) {

                $core->changeBalance($pre_payments_user, $pre_payment_variant, 4, $order_data['amount'], 'Онлайн оплата Yookassa#'.$pre_payments_yid, $order_data['date']);

                $db->query("UPDATE pre_payments SET status = 1 WHERE id = ?i", $pay_order);

                $insert = [
                    'user' => $order_data['user_id'],
                    'sum' => $order_data['amount'],
                    'date' => $order_data['date'],
                    'base' => 'Онлайн оплата Yookassa #'.$order_data['id']
                ];
                $db->query("INSERT INTO payments SET ?u", $insert);

            } else {
                $db->query("INSERT INTO payment_logs SET type = 'error', text = 'order has already been paid'");
            }
        } else {
            $db->query("INSERT INTO payment_logs SET type = 'error', text = 'order not found'");
        }



}


