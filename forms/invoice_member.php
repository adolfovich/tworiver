<style>
	table.invoice {
		width: 1000px;
		border-spacing: 0px;
		border-collapse: collapse;
		border: #000 1px solid;
	}
	table.invoice td {
		border: #000 1px solid;
		height: 30px;
		padding: 5px;
	}
	.left {
		width: 200px;
	}
	.bottom_text {
		font-size:10px;
		font-weight: 100;
	}
	.numeral_table{
		display: inline-block;
	}
	.numeral_table td {
		text-align: center;
		font-weight: 700;
		padding: 2px !important;
		height: 20px !important;
		width: 20px;
	}
	.bottom_border_table {
		display: inline-block;
		border-spacing: 0px;
		border-collapse: collapse;
	}
	.bottom_border_table td {
		text-align: center;
		font-weight: 700;
		padding: 2px !important;
		height: 20px !important;
		width: 20px;
		border-top: none !important;
		border-left: none !important;
		border-right: none !important;

	}
	.separator{
		height: 5px !important;
	}

	.noborder {
		border: none !important;
		border-right: #000 1px solid !important;
	}
	
	hr {
		margin-top: 5px;
		margin-bottom: 5px;
		border: 0;
		border-top: 1px solid #000;
	}

</style>

<table class="invoice">
	<tr>
		<td class="left" rowspan="100"></td>
		<td><b><?php echo $snt_bank_name; ?></b></td>
	</tr>
	<tr>
		<td>Получатель платежа: <b><?php echo $snt_name; ?></b></td>
	</tr>
	<tr>
		<td class="noborder">
			<table class="numeral_table" width="52%">
				<tr>
					<td style="border:none; text-align: right; width: 90px; line-height: 10px;"><b>БИК</b><br><span class="bottom_text">банка получателя</span></td>
					<?php
					foreach (str_split($snt_bank_bik) as $value) {
						echo "<td>$value</td>";
					}
					?>
				</tr>
			</table>
			<table class="numeral_table">
				<tr>
					<td style="border:none; text-align: right; width: 80px; line-height: 10px;"><b>ИНН</b><br><span class="bottom_text">получателя</span></td>
					<?php
					foreach (str_split($snt_inn) as $value) {
						echo "<td>$value</td>";
					}
					?>
				</tr>
			</table>

		</td>
	</tr>
	<tr>
		<td class="noborder">
			<table class="numeral_table">
				<tr>
					<td style="border:none; text-align: left; width: 150px; font-weight: 100;">Счет получателя:</td>
					<?php
					foreach (str_split($snt_bank_rs) as $value) {
						echo "<td>$value</td>";
					}
					?>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="noborder">
			<table class="numeral_table">
				<tr>
					<td style="border:none; text-align: left; width: 150px; font-weight: 100;">Корр. счет:</td>
					<?php
					foreach (str_split($snt_bank_ks) as $value) {
						echo "<td>$value</td>";
					}
					?>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="noborder" colspan="20"></td>
	</tr>
	<tr>
		<td class="noborder">
			<table class="bottom_border_table">
				<tr>
					<td style="border:none; text-align: left; width: 150px; font-weight: 100;">ФИО</td>
					<td colspan="22" style="width: 600px;"><?php echo $user_name;?></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="noborder">
			<table class="numeral_table">
				<tr>
					<td style="border:none; text-align: right; width: 150px; font-weight: 100;"><b>Участок № </b></td>
					<?php
					foreach (str_split($user_uchastok) as $value) {
						echo "<td>$value</td>";
					}
					?>


				</tr>
			</table>
			<table class="numeral_table">
				<tr>
					<td style="border:none; text-align: right; width: 150px; font-weight: 100;"><b>Телефон: </b></td>
					<?php
					foreach (str_split($phone) as $value) {
						echo "<td>$value</td>";
					}
					?>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="noborder">
			<table class="numeral_table">
				<tr>
					<td style="border:none; text-align: right; width: 200px; font-weight: 100; vertical-align: top;" rowspan="2"><b>Вид платежа:</b><br>оплата членских взносов</td>
					<td style="border:none; text-align: right; width: 200px; font-weight: 100; vertical-align: top;" colspan="5">
					<?php
							foreach ($members as $value) {
								echo '<b>'.$value.'</b><hr>';
								//var_dump($value);
							}
					?>
					</td>
				</tr>
				<tr>
					<td style="border:none; vertical-align: top; padding-left: 90px !important;">Сумма:</td>
					<td style="border:none; vertical-align: top; padding: 0 !important; width: auto;">
						<table class="numeral_table" style="">
							<tr>
							<?php
							
							
							foreach (str_split($members_sum[0]) as $value) {
								echo "<td>$value</td>";
							}
							?>
							</tr>
						</table>

					</td>
					<td style="border:none; vertical-align: top; padding-left: 5px !important; padding-right: 5px !important;">руб.</td>
					<td style="border:none; vertical-align: top; padding: 0 !important; width: auto;">
						<table class="numeral_table">
							<tr>
							<?php
							$tmp = str_split($members_sum[1]);
							if ($tmp[0] == "") {
								echo "<td>0</td>";
								echo "<td>0</td>";
							}
							else {
								foreach (str_split($members_sum[1]) as $value) {
									echo "<td>$value</td>";
								}
							}
							?>
							</tr>
						</table>

					</td>
					<td style="border:none; vertical-align: top; padding-left: 5px !important; padding-right: 5px !important;">коп.</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="noborder">С условиями приема платежа ознакомлен и согласен</td>
	</tr>
	<tr>
		<td class="noborder" style="text-align: right; border-bottom: #000 1px solid !important;">
			<table class="bottom_border_table">
				<tr>
					<td style="border:none; text-align: left; width: 200px; ">Подпись плательщика</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>


				</tr>
			</table>
		</td>
	</tr>

</table>
