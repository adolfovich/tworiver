<?php
include_once "../_conf.php";
ini_set('display_errors', 0);
include ('../classes/safemysql.class.php');
$db = new SafeMySQL(array('host' => $db_host,'user' => $db_user, 'pass' => $db_pass, 'db' => $db_name, 'charset' => 'utf8'));

require_once('../classes/core.class.php');

$core  = new Core();

$url = $core->url;
$form = $core->form;
$ip = $core->ip;
$get = $core->setGet();

$user_id = $get['user'];
$date_from = $get['datefrom'];
$date_to = $get['dateto'];

//выбираем данные по пользователю
$user_data = $db->getRow("SELECT * FROM users WHERE id = ?i", $user_id);

$user_uchastok = $user_data['uchastok'];
$user_name = $user_data['name'];

//выбираем данные договора на энергопотребление
$user_contracts = $db->getRow("SELECT * FROM users_contracts WHERE user = ?i AND date_end IS NULL", $user_id);

$contract_num = $user_contracts['num'];
$contract_date = $user_contracts['date_start'];

//выбираем данные по счетчику
$counter = $db->getRow("SELECT * FROM counters WHERE contract_id = ?i ", $user_contracts['id']);
$user_sch_model = $counter['model'];
$user_sch_num = $counter['num'];
$user_sch_plomb_num = $counter['plomb'];


//выбираем месяцы за которые нужно вывести показания

$months = $core->getMonthsBetweenDates($date_from, $date_to);

$count_sum_ind1 = 0;
$count_sum_ind2 = 0;

?>

<!DOCTYPE html>
<!--html lang="ru" onMouseOver="window.close();"-->
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Печать акта сверки - Система управления СНТ</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <!-- Optional theme -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
    <!-- Latest compiled and minified JavaScript -->
</head>
<body>

<style type="text/css" media="print">
    div.page
    {
        page-break-after: always;
        page-break-inside: avoid;
    }
</style>

<style>
    .indications>thead>tr>th {
        padding: 2px;
        text-align: center;
    }

    .indications>tbody>tr>td {
        padding: 2px;
        text-align: center;
    }
</style>

<div class="container" style="width: 100%;">
    <div class="row">
        <div class="col-md-12">

            <table style="width: 100%;">
                <tr>
                    <td colspan="3" style="text-align: center;">
                        <h4>Акт сверки оплаты за электроэнергию участок №<?=$user_data['uchastok']; ?> договор №<?=$contract_num; ?></h4>
                        <h4>за период с <?= date( 'd.m.Y',strtotime($date_from)); ?> по <?= date( 'd.m.Y',strtotime($date_to)); ?></h4>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left;"><h5>СНТ "Двуречье"</h5></td>
                    <td style="text-align: right;" colspan="2"><h5>"___" _________________ 202__ г.</h5></td>
                </tr>
                <tr>
                    <td style="text-align: left;"></td>
                    <td style="text-align: right;" colspan="2"></td>
                </tr>
                <tr>
                    <td style="text-align: left; font-weight: 700;">Участок №: <?php echo $user_uchastok; ?></td>
                    <td style="text-align: right; font-weight: 700;">Электросчетчик Марка</td>
                    <td style="text-align: right; width: 200px; border-bottom: 1px solid #000;"><?php echo $user_sch_model; ?></td>
                </tr>
                <tr>
                    <td style="text-align: left; font-weight: 700;">Владелец: <?php echo $user_name; ?></td>
                    <td style="text-align: right; font-weight: 700;">№</td>
                    <td style="text-align: right; width: 200px; border-bottom: 1px solid #000;"><?php echo $user_sch_num; ?></td>
                </tr>
                <tr>
                    <td style="text-align: left; font-weight: 700;"></td>
                    <td style="text-align: right; font-weight: 700;">Пломба №</td>
                    <td style="text-align: right; width: 200px; border-bottom: 1px solid #000;"><?php echo $user_sch_plomb_num; ?></td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <p><u>Показания счетчика</u></p>
            <table class="table table-bordered table-condensed indications" style="font-size: 10px;">
                <thead>
                    <tr>
                        <th rowspan="3">Месяц</th>
                        <th colspan="5">A* T1</th>
                        <th colspan="5">A* T2</th>
                        <th rowspan="3">Общая сумма к оплате</th>
                    </tr>
                    <tr>
                        <th colspan="3">Показания (кВт*ч)</th>
                        <th rowspan="2">Тариф</th>
                        <th rowspan="2">Сумма к оплате</th>
                        <th colspan="3">Показания (кВт*ч)</th>
                        <th rowspan="2">Тариф</th>
                        <th rowspan="2">Сумма к оплате</th>
                    </tr>
                    <tr>
                        <th>Начало</th>
                        <th>Расход</th>
                        <th>Конец</th>
                        <th>Начало</th>
                        <th>Расход</th>
                        <th>Конец</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($months as $month) {
                            $parts = explode('-', $month);
                            $monthNum = $parts[0];
                            $year = $parts[1];
                            $firstDay = $year.'-'.$monthNum.'-01';
                            $endDay = $year.'-'.$monthNum.'-'.$core->getLastDayOfMonth($monthNum, $year);

                            //получаем показания Т1
                            $t1_ind = $db->getAll("SELECT * FROM Indications i WHERE i.Indications != i.prev_indications AND i.user = ?i AND i.tarif = 2 AND i.date BETWEEN ?s AND ?s", $user_id, $firstDay, $endDay);

                            // Если в показаниях больше одной строки приводим к одной строке

                            if (count($t1_ind) <= 1) {
                                $t1_ind = $t1_ind[0];
                            } else {
                                $prev_ind = $t1_ind[0]['prev_indications'];
                                $additional = $t1_ind[0]['additional'];
                                foreach ($t1_ind as $ind) {
                                    $indications = $ind['Indications'];
                                }
                                $t1_ind = array();
                                $t1_ind['prev_indications'] = $prev_ind;
                                $t1_ind['Indications'] = $indications;
                                $t1_ind['additional'] = $additional;
                                $t1_ind['additional_sum'] = ($indications - $prev_ind) * $additional;

                            }
                            //получаем показания Т2
                            $t2_ind = $db->getAll("SELECT * FROM Indications i WHERE i.Indications != i.prev_indications AND i.user = ?i AND i.tarif = 3 AND i.date BETWEEN ?s AND ?s", $user_id, $firstDay, $endDay);
                            if (count($t2_ind) <= 1) {
                                $t2_ind = $t2_ind[0];
                            } else {
                                $prev_ind = $t2_ind[0]['prev_indications'];
                                $additional = $t2_ind[0]['additional'];
                                foreach ($t2_ind as $ind) {
                                    $indications = $ind['Indications'];
                                }
                                $t2_ind = array();
                                $t2_ind['prev_indications'] = $prev_ind;
                                $t2_ind['Indications'] = $indications;
                                $t2_ind['additional'] = $additional;
                                $t2_ind['additional_sum'] = ($indications - $prev_ind) * $additional;

                            }

                            if ($t1_ind || $t2_ind) {
                            ?>
                            <tr>
                                <td><?= $core->getMonthName($monthNum) . " " . $year?></td>
                                <td><?= $t1_ind['prev_indications'] ?></td>
                                <td><?= $t1_ind['Indications'] - $t1_ind['prev_indications'] ?></td>
                                <td><?= $t1_ind['Indications'] ?></td>
                                <td><?= $t1_ind['additional'] ?></td>
                                <td><?= $t1_ind['additional_sum'] ?></td>
                                <td><?= $t2_ind['prev_indications'] ?></td>
                                <td><?= $t2_ind['Indications'] - $t2_ind['prev_indications'] ?></td>
                                <td><?= $t2_ind['Indications'] ?></td>
                                <td><?= $t2_ind['additional'] ?></td>
                                <td><?= $t2_ind['additional_sum'] ?></td>
                                <td><?= $t1_ind['additional_sum'] + $t2_ind['additional_sum']?></td>
                            </tr>
                            <?php
                                $count_sum_ind1 = $count_sum_ind1 + $t1_ind['additional_sum'];
                                $count_sum_ind2 = $count_sum_ind2 + $t2_ind['additional_sum'];
                            }
                        }
                    ?>
                    <tr>
                        <td colspan="5" style="font-weight: 700; text-align: left;">ИТОГО:</td>
                        <td style="font-weight: 700;"><?php echo sprintf("%01.2f", $count_sum_ind1); ?></td>
                        <td colspan="4" style="font-weight: 700;"></td>
                        <td style="font-weight: 700;"><?php echo sprintf("%01.2f", $count_sum_ind2); ?></td>
                        <td style="font-weight: 700;"><?php echo sprintf("%01.2f", $count_sum_ind1+$count_sum_ind2); ?></td>
                    </tr>

                </tbody>

            </table>
        </div>
    </div>

    <?php
    //выбираем все оплаты

    $result_user_payments = $db->getAll("SELECT * FROM operations_jornal WHERE balance_type = 1 AND (op_type = 4 OR op_type = 1) AND user_id = ?i AND date BETWEEN ?s AND ?s", $user_id, $date_from, $date_to);

     ?>
    <div class="row">
        <div class="col-md-12">
            <p><u>Оплаты</u></p>
            <table class="table table-bordered table-condensed" style="font-size: 10px;">
                <tr>
                    <th style="padding: 2px;">Дата</th>
                    <th style="padding: 2px;">Основание</th>
                    <th style="padding: 2px;">Сумма</th>
                </tr>
                <?php
                $sum_payments = 0;
                foreach ($result_user_payments as $user_payments) {
                    echo '<tr>';
                    echo '<td style="padding: 1px;">' . date( 'd.m.Y',strtotime($user_payments['date'])) . '</td>';
                    echo '<td style="padding: 1px;">' . $user_payments['comment'] . '</td>';
                    echo '<td style="padding: 1px;">' . $user_payments['amount'] . '</td>';
                    echo '</tr>';

                    $sum_payments = $sum_payments + $user_payments['amount'];
                }
                ?>
                <tr>
                    <td colspan="2" style="font-weight: 700;">ИТОГО:</td>
                    <td style="font-weight: 700;"><?php echo sprintf("%01.2f", $sum_payments); ?></td>
                </tr>
            </table>
        </div>
    </div>

    <?php

    $saldo = $sum_payments - $sum_ind;
    if ($saldo > 0) {
        $saldo_name = 'Дебет';
        $saldo_cuirsive = $core->num2str($saldo);
    }
    else if ($saldo < 0) {
        $saldo_name = 'Кредит';
        $saldo_cuirsive = $core->num2str(-$saldo);
        $saldo = -$saldo;
    }
    else if ($saldo == 0) {
        $saldo_name = '';
        $saldo_cuirsive = $core->num2str($saldo);
    }
    ?>

    <div class="row">
        <div class="col-md-12">
            <p><u><b>Сальдо: <?php echo $saldo_name . ' ' . sprintf("%01.2f", $saldo) . ' ('.$saldo_cuirsive.')'; ?></b></u></p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table style="width: 100%; text-align: center; margin-top: 30px; margin-bottom: 30px;">
                <tr>
                    <td>Сверку провели: </td>
                    <td>Хакало Владимир Олегович _______________</td>
                    <td><?php echo $user_name; ?> _______________</td>
                </tr>
            </table>
        </div>
    </div>
</div>

</body>
</html>
