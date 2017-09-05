<?php

	include_once "core/db_connect.php";
	include_once "include/auth.php";

	$month_name = array(
		1 => 'января',
		2 => 'февраля',
		3 => 'марта',
		4 => 'апреля',
		5 => 'мая',
		6 => 'июня',
		7 => 'июля',
		8 => 'августа',
		9 => 'сентября',
		10 => 'октября',
		11 => 'ноября',
		12 => 'декабря'
	);

	$curdate = date("Y-m-d");

	if ($is_auth == 1) {

		if (isset($_GET['hagreement']) && isset($_GET['agreement']) && $_GET['agreement'] == 1) {
			//обновляем информацию о соглажении у пользователя
			mysql_query("UPDATE users SET user_agreement = 1 WHERE email = '".$_COOKIE["user"]."'") or die(mysql_error());

		}
		else if (isset($_GET['hagreement'])) {
			$error_msg = '<script type="text/javascript">swal("", "Вы не приняли Пользовательское соглашение", "error")</script>';
		}

		$q_user_detail = "SELECT * FROM users WHERE email = '".$_COOKIE["user"]."'";
		$result_user_detail = mysql_query($q_user_detail) or die(mysql_error());

		while ($user_detail = mysql_fetch_assoc($result_user_detail)) {
			$user_id = $user_detail['id'];
			$user_uchastok = $user_detail['uchastok'];
			$user_sch_model = $user_detail['sch_model'];
			$user_sch_num = $user_detail['sch_num'];
			$user_sch_plomb_num = $user_detail['sch_plomb_num'];
			$balans = $user_detail['balans'];
			$phone = $user_detail['phone'];
			$email = $user_detail['email'];
			$sms_notice = $user_detail['sms_notice'];
			$email_notice = $user_detail['email_notice'];
			$pass_md5 = $user_detail['pass'];
			$user_agreement = $user_detail['user_agreement'];
		}

		//echo $user_agreement;



		//Выбираем действующий договор пользователя
		$q_user_conrtact = "SELECT * FROM users_contracts WHERE user = $user_id AND type = 1 AND date_end IS NULL";
		$result_user_conrtact = mysql_query($q_user_conrtact) or die(mysql_error());
		while ($user_conrtact = mysql_fetch_assoc($result_user_conrtact)) {
			$user_conrtact_num = $user_conrtact['num'];
			$user_conrtact_date = date('d.m.Y',strtotime($user_conrtact['date_start']));//echo $user_conrtact_date;
		}

		if (isset($_GET['changePhone'])) {
			if (strlen($_GET['changePhone']) != 0) {
				$chPhone = $_GET['changePhone'];
				if ($chPhone[0] == '8') {
					$chPhone = '7' . substr($chPhone, 1);
				}
				else if ($chPhone[0] == '+') {
					$chPhone = substr($chPhone, 1);
				}
				if (strlen($_GET['changeEmail']) != 0) {
					$chEmail = $_GET['changeEmail'];
					if (isset($_GET['changeSmsNotice']) && $_GET['changeSmsNotice'] == 1) {
						$changeSmsNotice = 1;
					}
					else {
						$changeSmsNotice = 0;
					}
					if (isset($_GET['changeEmailNotice']) && $_GET['changeEmailNotice'] == 1) {
						$changeEmailNotice = 1;
					}
					else {
						$changeEmailNotice = 0;
					}
					if (isset($_GET['oldPass']) && strlen($_GET['oldPass']) != 0) {
						//echo 'from base'.$pass_md5 . '<br>';
						//echo 'from GET'.md5($_GET['oldPass']) . '<br>';
						if ($pass_md5 == md5($_GET['oldPass'])) {
							if ($_GET['newPass'] == $_GET['newPass2']) {
								//меняем настройки и пароль
								$q_upd = "UPDATE users SET phone = '$chPhone', email = '$chEmail', sms_notice = $changeSmsNotice, email_notice = $changeEmailNotice, pass = '".md5($_GET['newPass'])."' WHERE email = '".$_COOKIE["user"]."'";
								//echo $q_upd;
								mysql_query($q_upd) or die(mysql_error());
								$error_msg = '<script type="text/javascript">swal("", "Настройки сохранены, пароль изменен", "success")</script>';
								header("Location: user.php");
							}
							else {
								$error = 'Веденные пароли не совпадают';
							}
						}
						else {
							$error = 'Старый пароль неверный';
						}
					}
					else {
						//меняем настройки и не меняем пароль
						$q_upd = "UPDATE users SET phone = '$chPhone', email = '$chEmail', sms_notice = $changeSmsNotice, email_notice = $changeEmailNotice WHERE email = '".$_COOKIE["user"]."'";
						//echo $q_upd;
						mysql_query($q_upd) or die(mysql_error());
						$error_msg = '<script type="text/javascript">swal("", "Настройки сохранены", "success")</script>';
						header("Location: user.php");
					}
				}
				else {
					$error = 'Поле Email не может быть пустым';
				}
			}
			else {
				$error = 'Поле номер телефона не может быть пустым';
			}
		}

	}
?>

<!DOCTYPE html>
<html lang="ru">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Система управления СНТ</title>

		<script src="http://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
		<!-- Optional theme -->
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
		<!-- Latest compiled and minified JavaScript -->
		<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="css/font-awesome.min.css">
		<link rel="stylesheet" href="css/sweetalert.css">
		<script src="js/sweetalert.min.js"></script>

		<style>
			#header {
				background: url(img/header.jpg);
				min-height: 280px;
				background-size: cover;
				background-repeat: no-repeat;
			}
			.news_date {
				color: #777;
			}
		</style>

	</head>
	<body>
		<?php echo $error_msg; ?>
		<?php echo $error; ?>
		<?php include_once "include/head.php"; ?>

		<div class="jumbotron" id="header">
			<div class="container" ></div>
		</div>

		<div class="container" style="padding-bottom: 50px;">

					<?php
					if ($is_auth == 1) {
					?>

						<?php
							if ($user_agreement == 0) {
						?>

						<div class="row">
							<div class="col-md-12">
								<h3>Пользовательское соглашение</h3>
								<hr>
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<center><h4>Обработка персональных данных</h4></center>
								<p>Предоставляя свои персональные данные Пользователь даёт согласие на обработку, хранение и использование своих персональных данных на основании ФЗ № 152-ФЗ «О персональных данных» от 27.07.2006 г. в следующих целях:</p>
								<ul>
									<li>Осуществление поддержки</li>
									<li>Получения Пользователем информации о деятельности СНТ "Двуречье"</li>
								</ul>
								<p>Под персональными данными подразумевается любая информация личного характера, позволяющая установить личность Пользователя/Покупателя такая как:</p>
								<ul>
									<li>Фамилия, Имя, Отчество</li>
									<li>Дата рождения</li>
									<li>Контактный телефон</li>
									<li>Адрес электронной почты</li>
									<li>Почтовый адрес</li>
								</ul>
								<p>Персональные данные Пользователей хранятся исключительно на электронных носителях и обрабатываются с использованием автоматизированных систем, за исключением случаев, когда неавтоматизированная обработка персональных данных необходима в связи с исполнением требований законодательства.</p>
								<p>СНТ "Двуречье" обязуется не передавать полученные персональные данные третьим лицам, за исключением следующих случаев:</p>
								<ul>
									<li>По запросам уполномоченных органов государственной власти РФ только по основаниям и в порядке, установленным законодательством РФ</li>
									<li>Партнерам, которые работают с Компанией для предоставления услуг. Третьим лицам предоставляется минимальный объем персональных данных, необходимый только для оказания требуемой услуги или проведения необходимой транзакции.</li>
								</ul>
								<p>СНТ "Двуречье" оставляет за собой право вносить изменения в одностороннем порядке в настоящие правила, при условии, что изменения не противоречат действующему законодательству РФ. Изменения условий настоящих правил вступают в силу после их публикации на Сайте.</p>

								<center><h4>Соглашение об использовании файлов cookie</h4></center>
								<p>Интернет-сайт СНТ-ДВУРЕЧЬЕ.РФ (далее - «Сайт») использует файлы cookie и схожие технологии, чтобы гарантировать максимальное удобство пользователям (далее - «Пользователи»), предоставляя персонализированную информацию, запоминая предпочтения в области маркетинга и контента Сайта, а также помогая получить нужную Пользователю информацию. При использовании данного сайта, вы подтверждаете свое согласие на использование файлов cookie в соответствии с настоящим уведомлением в отношении данного типа файлов. Если вы не согласны с тем, чтобы мы использовали данный тип файлов, то вы должны соответствующим образом установить настройки вашего браузера или не использовать Сайт.</p>
								<p>Нижеследующее соглашение касается правил Сайта относительно личной информации, предоставляемой администрации Сайта Пользователями.</p>
								<p>1. Введение</p>
								<p>Данное соглашение касается использования Сайтом информации, получаемой от Пользователей Сайта. В этом документе также содержится информация о файлах "cookie", об использовании файлов "cookie" Сайтом и третьими сторонами, а также о том, как вы можете отказаться от такого рода файлов.</p>
								<p>2. Информация для Пользователей</p>
								<p>Во время просмотра любой страницы Сайта на ваш компьютер загружается сама страница, а также может загружаться небольшой текстовый файл под названием "cookie", позволяющий определить, был ли конкретный компьютер (и, вероятно, его Пользователь) на этом сайте раньше. Это происходит во время повторного посещения сайта посредством проверки компьютера пользователя на наличие файла "cookie", оставшегося с прошлого посещения. Файлы cookie широко используются владельцами сайтов для обеспечения работы сайтов или повышения эффективности работы, а также для получения аналитической информации.</p>
								<p>Файлы cookie могут размещаться на Вашем устройстве администрацией Сайта (эти файлы cookie называются «собственными»). Некоторые файлы cookie могут размещаться на Вашем устройстве другими операторами. Такие файлы cookie называются файлами «третьих лиц».</p>
								<p>Мы и третьи лица можем использовать файлы cookie, чтобы узнать, когда Вы посещаете Сайт, как взаимодействуете контентом. На основе файлов cookie может собираться и использоваться обобщенная и другая информация, не связанная с идентификацией отдельных пользователей (например, об операционной системе, версии браузера и URL-адресе, с которого выполнен переход на данную страницу, в том числе из электронного письма или рекламного объявления) — благодаря этому мы можем предоставить Вам более широкие возможности и проанализировать маршруты посещения сайтов, что служит инструментом для сбора обобщенной статистики об использовании сайта в целях аналитического исследования и помогает нам оптимизировать сайт.</p>
								<p>Информация, которую мы получаем посредством "cookie"-файлов, помогает нам предоставлять вам наши услуги в наиболее удобном для вас виде, а также может помочь нам составить представление о наших читателях.</p>
								<p>3. Информация о "cookie"</p>
								<p>Файл "cookie" представляет собой небольшое количество данных, среди которых часто содержится уникальный анонимный идентификатор, посылаемый вашему браузеру компьютером сайта и сохраняемый на жестком диске вашего компьютера. Каждый сайт может посылать свои файлы "cookie" на ваш компьютер, если настройки вашего браузера разрешают это. В то же время (чтобы сохранить конфиденциальность ваших данных) ваш браузер открывает сайтам доступ только к вашим собственным "cookie", но не позволяет им пользоваться такими же файлами "cookie", оставленными другими сайтами.</p>
								<p>В файлах "cookie" хранится информация о ваших предпочтениях в интернете. Пользователи могут настроить свои компьютеры так, чтобы они автоматически принимали все файлы "cookie", либо предупреждали каждый раз, когда сайт пытается записать свой "cookie" на жесткий диск пользователя, либо вовсе не принимать никаких "cookie"-файлов. Последний вариант означает, что некоторые персональные услуги не могут быть предоставлены пользователям, а также что пользователи, выбравшие такие настройки, не смогут получить полный доступ ко всем разделам Сайта.</p>
								<p>Каждый браузер уникален, так что обратитесь к функции "Помощь" вашего браузера, чтобы узнать, как настроить работу с файлами "cookie".</p>
								<p>Если вы настроили свой компьютер на полный запрет приема "cookie" файлов, вы по-прежнему можете анонимно посещать Сайт до тех пор, пока вы не пожелаете воспользоваться одной из услуг сайта.</p>
								<p>5. Срок хранения файлов cookie</p>
								<p>Некоторые файлы cookie действуют с момента вашего входа на сайт до конца данной конкретной сессии работы в браузере. При закрытии браузера эти файлы становятся ненужными и автоматически удаляются. Такие файлы cookie называются «сеансовыми».</p>
								<p>Некоторые файлы cookie сохраняются на устройстве и в промежутке между сессиями работы в браузере — они не удаляются после закрытия браузера. Такие файлы cookie называются «постоянными».</p>
								<p>6. Использование cookie в интернет-рекламе и мобильной рекламе</p>
								<p>Мы вместе с третьими лицами, включая технологических партнеров и поставщиков услуг, участвуем в ориентированной на интересы пользователей рекламной деятельности, предоставляя рекламу и персонализированный контент, который, по нашему мнению и по мнению других рекламодателей, будет представлять интерес для Вас. Сторонние поставщики используют файлы cookie при реализации сервисов для нас или других компаний; в таких случаях мы не контролируем использование указанной технологии или полученной при этом информации и не несем ответственности за любые действия или политики третьих лиц.</p>
								<p>Реклама может предоставляться Вам с учетом характера Вашей деятельности в Интернете или при использовании мобильных устройств, а также с учетом Ваших действий при поиске, Ваших откликов на одно из наших рекламных объявлений или электронных писем, посещенных Вами страниц, Вашего географического региона или другой информации. Такие рекламные объявления могут появляться на нашем сайте или на сайтах третьих лиц. Технологические партнеры, с которыми мы сотрудничаем и которые помогают нам проводить рекламные кампании с учетом Ваших интересов, могут являться участниками саморегулируемых ассоциаций. На данном сайте Вы можете также видеть рекламу третьих лиц в зависимости от того, какие страницы Вы посещаете, какие действия выполняете на нашем сайте и на других сайтах.</p>
								<p>7. Использование веб-трекинга и cookie-файлов</p>
								<p>Мы используем программное обеспечение для определения числа пользователей, посещающих наш веб-сайт, и регулярности посещения. Мы не используем программы для сбора персональных данных или IP-адресов отдельных лиц, и не создаем индивидуальный профиль ваших действий в интернете. Данные используются исключительно анонимным образом в сводной форме в статистических целях, а также для разработки веб-сайта.</p>
								<p>Содержимое постоянных cookie-файлов ограничивается идентификационным номером. Имя, адрес электронной почты, IP-адрес и т.д. не сохраняются.</p>
								<p>Существует исключение: Cookie-файлы Google Analytics.</p>
								<p>Cookie-файлы Google Analytics используют IP-адрес для распознавания пользователя, однако не проводят персональную идентификацию (информация собирается анонимно). Указанная информация используется для составления отчетов и помогают нам совершенствовать Сайт.</p>
								<p>Вы можете отказаться от использования cookie-файлов Google Analytics для отслеживания вашей активности на всех веб-сайтах, пройдя по следующей ссылке: Google Analytics Opt-out Browser Add-on (http://tools.google.com/dlpage/gaoptout)</p>
								<p>8. Cookie-файлы третьих лиц</p>
								<p>На Сайте используются кнопки обмена информацией, позволяющие посетителям поставить закладку на странице и поделиться ее содержимым в любимых социальных сетях. При нажатии на одну из этих кнопок выбранные Вами для обмена информацией социальные медиа могут создать cookie-файл. Сайт не контролирует использование этих cookie-файлов, поэтому Вам следует обратиться на веб-сайт соответствующей третьей стороны за дополнительной информацией.</p>
								<p>Обратите внимание: при посещении страницы с контентом, вставленным с других сайтов, указанные сайты могут создавать собственные cookie-файлы в вашем браузере. Сайт не контролирует использование этих cookie-файлов и не может получить к ним доступ в силу особенностей работы cookie-файлов — доступ к ним имеет лишь та сторона, которая создавала их изначально. Ищите более подробную информацию об этих cookie-файлах на веб-сайтах третьих сторон.</p>
								<p>Веб-маяки</p>
								<p>Мы используем аналитические веб-службы, например Яндекс.Метрика, GoogleAnalytics, которые помогают нам понять, как люди пользуются нашими веб-сайтами, и тем самым обеспечить их релевантность, удобство использования и актуальность информационного наполнения. Эти службы пользуются такими технологиями сбора данных, как веб-маяки. Веб-маяки — небольшие электронные изображения, которые размещают cookie-файлы, подсчитывают число посещений и оценивают показатели использования и эффективность использования веб-сайта.</p>
								<p>Информация является анонимной и используется исключительно в статистических целях. Данные веб-аналитики и cookie-файлы невозможно использовать для того, чтобы установить Вашу личность, поскольку они никогда не содержат персональные данные, включая Ваши имя или адрес электронной почты.</p>
								<p>7. Управление файлами cookie</p>
								<p>Вы можете:</p>
								<ul>
								<li>отключить сохранение cookie-файлов;</li>
								<li>ограничить их создание конкретными веб-сайтами;</li>
								<li>установить уведомление об отправке cookie-файлов в своем браузере;</li>
								<li>в любой момент удалить cookie-файлы с жесткого диска своего ПК (файл: «cookies»).</li>
								</ul>
								<p>Пожалуйста, обратитесь к инструкции браузера для того, чтобы узнать больше о том, как скорректировать или изменить настройки браузера.</p>
								<p>Если отключить cookie, это может повлиять на работу Пользователя в Интернете. Если Пользователь использует различные устройства для просмотра и доступа к Сайту (например, компьютер, смартфон, планшет и т.д.), он должны убедиться, что каждый браузер на каждом устройстве настроен в соответствии с предпочтениями на работу с файлами cookie.</p>
								<form method="GET">
									<div class="form-group">
										<center><label for="agreement">
											<input name="hagreement" type="hidden" value="1" >
											<input name="agreement" type="checkbox" id="agreement" value="1" >
											 Я принимаю условия соглашения
										</label></center>
									</div>
									<div class="form-group">
										<center><input type="submit" id="agreement" value="Подтвердить" id="submit_agree" ></center>
									</div>
								</form>
							</div>
						</div>

						<?php
							}
							else {
						?>
						<div class="row">
							<div class="col-md-12">
								<h2>Личный кабинет: участок №<?php echo $user_uchastok;?></h2>

								<hr>



								<p><a href="#editSettings" class="btn btn-primary" data-toggle="modal"><i class="fa fa-sliders" aria-hidden="true"></i> Изменить настройки</a></p>
								<!-- HTML-код модального окна -->
									<div id="editSettings" class="modal fade">
									  <div class="modal-dialog">
										<div class="modal-content">
										  <!-- Заголовок модального окна -->
										  <div class="modal-header">
											<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
											<h4 class="modal-title">Редактирование настроек</h4>
										  </div>
										  <!-- Основное содержимое модального окна -->
										  <div class="modal-body">
											<form method="GET" role="form" id="ChangeSettings">
												<div class="form-group">
													<label for="changePhone">Номер телефона</label>
													<input name="changePhone" type="text" class="form-control" id="changePhone" placeholder="+7XXXXXXXXXX" value="<?php echo '+'.$phone; ?>">
												</div>
												<div class="form-group">
													<label for="changeEmail">Email</label>
													<input name="changeEmail" type="text" class="form-control" id="changeEmail" placeholder="email@domine.ru" value="<?php echo $email; ?>">
												</div>
												<div class="form-group">
													<label for="changeSmsNotice">
													<?php
													if ($sms_notice == 1) {
														echo '<input name="changeSmsNotice" type="checkbox" id="changeSmsNotice" checked value="1">';
													}
													else {
														echo '<input name="changeSmsNotice" type="checkbox" id="changeSmsNotice" value="1">';
													}
													?>
													 Получать уведомление по SMS
												</label>
												</div>
												<div class="form-group">
													<label for="changeEmailNotice">
													<?php
													if ($email_notice == 1) {
														echo '<input name="changeEmailNotice" type="checkbox" id="changeEmailNotice" checked value="1">';
													}
													else {
														echo '<input name="changeEmailNotice" type="checkbox" id="changeEmailNotice" value="1">';
													}
													?>
													 Получать уведомление по Email
													</label>
												</div>
												<div class="form-group">
													<label for="changePass">Изменить пароль</label>
													<input name="oldPass" type="password" class="form-control" id="changePass" placeholder="Старый пароль" >
													<input name="newPass" type="password" class="form-control" id="newPass" placeholder="Новый пароль" >
													<input name="newPass2" type="password" class="form-control" id="newPass2" placeholder="Повторить новый пароль" >
												</div>
											</form>
										  </div>
										  <!-- Футер модального окна -->
										  <div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
											<button type="button" class="btn btn-primary" onclick="document.getElementById('ChangeSettings').submit(); return false;" >Сохранить</button>
										  </div>
										</div>
									  </div>
									</div>



							</div>

						</div>
						<div class="row">
							<div class="col-md-12">
								<h3>Договор на электропотребление №<?php echo $user_conrtact_num;?> от <?php echo $user_conrtact_date;?></h3>
								<hr>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
							  <h3>Данные об установленном счетчике</h3>
							  <p><strong>Марка: </strong><?php echo $user_sch_model;?></p>
							  <p><strong>Номер: </strong><?php echo $user_sch_num;?></p>
							  <p><strong>Пломба №: </strong><?php echo $user_sch_plomb_num;?></p>
							</div>
							<div class="col-md-4">
								<?php
									if ($balans < 0) {
										$balans_color = "color: red;";
									}
								?>
								<h3>
									Баланс: <span style="<?php echo $balans_color; ?>"><?php echo $balans;?></span>

									<?php
									if ($balans < 0) {
										echo '<button type="button" class="btn btn-primary">Оплатить</button>';
									}
									else {
										echo '<button type="button" class="btn btn-primary" disabled="disabled" >Оплатить</button>';
									}
									?>

									</h3>
								<?php
								//выбираем тарифы которые есть у пользователя
								//echo ;

								$result_user_tarifs = mysql_query("SELECT t.id as id, ut.tarif as tarif_id, t.name as tarif_name FROM users_tarifs ut, tarifs t WHERE ut.tarif = t.id AND ut.user = (SELECT id FROM users WHERE email = '".$_COOKIE["user"]."')") or die(mysql_error());

								echo '<p><strong>Последние показания: </strong></p>';
								while ($user_tarif = mysql_fetch_assoc($result_user_tarifs)) {

									//Выбираем показания по тарифу
									$result_user_indications = mysql_query("SELECT * FROM Indications WHERE user = (SELECT id FROM users WHERE email = '".$_COOKIE['user']."') AND tarif = ".$user_tarif['id']." ORDER BY date DESC LIMIT 1") or die(mysql_error());

									while ($user_indications = mysql_fetch_assoc($result_user_indications)) {


										echo '<p><strong>'. $user_tarif['tarif_name'].': </strong>'.$user_indications['Indications'].'</p>';
									}

								}

								//Выбираем последний платеж пользователя
								$result_user_payment = mysql_query("SELECT * FROM payments WHERE user = (SELECT id FROM users WHERE email = '".$_COOKIE['user']."') ORDER BY date DESC LIMIT 1") or die(mysql_error());
								while ($user_payment = mysql_fetch_assoc($result_user_payment)) {
									$payment_date = date("d.m.Y", strtotime($user_payment['date']));
									echo '<p><strong>Последний платеж: </strong> '.$user_payment['sum'].'р от '.$payment_date.'</p>';
								}
								?>



							</div>
						</div>
						<?php } ?>
					<?php
					}
					else
					{
					?>
					  <div class="col-md-12">
						  <h2>Вы не авторизованы</h2>
						  <hr>
					  </div>
					<?php
					}
					?>


			<hr>
		</div>

		<?php include_once "include/footer.php"; ?>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="js/bootstrap.min.js"></script>


	</body>
</html>
