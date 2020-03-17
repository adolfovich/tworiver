<?php
include_once "../core/db_connect.php";


	$html = '';

  //echo $_POST['user'];

  if (isset($_POST['pay_variant'])) {
    switch ($_POST['pay_variant']) {
    case 1:
        //echo "Оплата электроэнергии";
				$html .= '<div class="form-group">';
				$html .= '<label for="amount">Сумма оплаты</label>';
				$html .= '<input id="inputAmount" name="amount" type="text" class="form-control" id="amount" placeholder="Укажите сумму" aria-describedby="amountHelp" onKeyup="checkAmount(this.value)">';
				$html .= '<small id="amountHelp" class="form-text text-muted">Максимальный размер платежа составляет 15 000 рублей</small>';
				$html .= '<input type="hidden" id="paymentUser" name="paymentUser" value="'.$_POST['user'].'">';
				$html .= '</div>';
				echo $html;
        break;
    case 2:
        //echo "Оплата членских взносов";
				$html .= '';
        break;
    case 3:
        //echo "Оплата целевых взносов";
				$html .= '';
        break;
		}
  }
