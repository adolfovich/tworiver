<style>
  a.card:hover {
    text-decoration: none;
    box-shadow: rgb(122, 122, 134) 4px 8px 9px -2px;
    transition: 0.5s;
  }

  a.card {
    transition: 0.5s;
  }
</style>


<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h4 class="font-weight-bold mb-0">Личный кабинет: участок №<?=$user_data['uchastok']?> <?=$user_data['name']?></h4>
          </div>

        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <p class="card-title text-md-center text-xl-left" id="activator">Общий баланс</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <?php if ($total_balance < 0) {$c1text = 'red';} else {$c1text = 'black';}?>
              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" style="color:<?=$c1text?>"><?=$total_balance?>р.</h3>
              <i class="fas fa-ruble-sign"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 grid-margin stretch-card">
        <a class="card" href="cab/electricpower">
          <div class="card-body">
            <p class="card-title text-md-center text-xl-left">Энергопотребление</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <?php if ($electric_balance < 0) {$c2text = 'red';} else {$c2text = 'black';}?>
              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" style="color:<?=$c2text?>"><?=$electric_balance?>р.</h3>
              <i class="fas fa-ruble-sign"></i>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-3 grid-margin stretch-card">
        <a class="card" href="cab/membership">
          <div class="card-body">
            <p class="card-title text-md-center text-xl-left">Членские взносы</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <?php if ($membership_balance < 0) {$c3text = 'red';} else {$c3text = 'black';}?>
              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" style="color:<?=$c3text?>"><?=$membership_balance?>р.</h3>
              <i class="fas fa-ruble-sign"></i>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-3 grid-margin stretch-card">
        <a class="card" href="cab/target">
          <div class="card-body">
            <p class="card-title text-md-center text-xl-left">Целевые взносы</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <?php if ($target_balance < 0) {$c4text = 'red';} else {$c4text = 'black';}?>
              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" style="color:<?=$c4text?>"><?=$target_balance?>р.</h3>
              <i class="fas fa-ruble-sign"></i>
            </div>
          </div>
        </a>
      </div>
	  <div class="col-md-3 grid-margin stretch-card">
        <a class="card" href="cab/loans">
          <div class="card-body">
            <p class="card-title text-md-center text-xl-left">Договоры займа</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">              
              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" style="color:black"><?=$loan_balance?>р.</h3>
              <i class="fas fa-ruble-sign"></i>
            </div>
          </div>
        </a>
      </div>
    </div>
	
	<div class="row">
		<div id="accordion" style="width: 100%;">
		  <div class="card">
			<div class="card-header" id="headingOne">
			  <h5 class="mb-0">
				<button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" style="width: 100%; text-align: left;">
				  Порядок обработки персональных данных 152-ФЗ
				</button>
			  </h5>
			</div>

			<div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
			  <div class="card-body">
				<p>Предоставляя свои персональные данные Покупатель даёт согласие на обработку, хранение и использование своих персональных данных на основании ФЗ № 152-ФЗ «О персональных данных» от 27.07.2006 г. в следующих целях:</p>
				<ul>
					<li>Регистрации Пользователя на сайте</li>
					<li>Осуществление пользовательской поддержки</li>
					<li>Выполнение садовым некоммерческим товариществом обязательств перед его членами</li>
					<li>Проведения аудита и прочих внутренних исследований с целью повышения качества работы сайта.</li>
				</ul>
				<p>Под персональными данными подразумевается любая информация личного характера, позволяющая установить личность Покупателя такая как:</p>
				<ul>
					<li>Фамилия, Имя, Отчество</li>
					<li>Дата рождения</li>
					<li>Контактный телефон</li>
					<li>Адрес электронной почты</li>
					<li>Почтовый адрес</li>
				</ul>
				<p>Персональные данные членов СНТ хранятся исключительно на электронных носителях и обрабатываются с использованием автоматизированных систем, за исключением случаев, когда неавтоматизированная обработка персональных данных необходима в связи с исполнением требований законодательства.</p>
				<p>Продавец обязуется не передавать полученные персональные данные третьим лицам, за исключением следующих случаев:</p>
				<ul>
					<li>По запросам уполномоченных органов государственной власти РФ только по основаниям и в порядке, установленным законодательством РФ</li>
					<li>Партнерам, которые работают с СНТ для предоставления услуг, или тем из них, которые помогают СНТ реализовывать услуги его членам. Мы предоставляем третьим лицам минимальный объем персональных данных, необходимый только для оказания требуемой услуги или проведения необходимой транзакции.</li>
				</ul>
				<p>Продавец оставляет за собой право вносить изменения в одностороннем порядке в настоящие правила, при условии, что изменения не противоречат действующему законодательству РФ. Изменения условий настоящих правил вступают в силу после их публикации на Сайте.</p>
			  </div>
			</div>
		  </div>
		  <div class="card">
			<div class="card-header" id="headingTwo">
			  <h5 class="mb-0">
				<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo" style="width: 100%; text-align: left;">
				  Условия возврата денежных средств, Действия при возникновении проблем с оплатой
				</button>
			  </h5>
			</div>
			<div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
			  <div class="card-body">
				<p>Возврат денежных средств</p>
				<p>1. После произведенной оплаты член СНТ вправе потребовать полной или частичной возврата уплаченных СНТ денежных средств. Данное требование оформляется письменно в произвольной форме.</p>
				<p>2. В случае письменного заявления члена СНТ о возврате внесенной пердоплаты, возврат денежных средств производиться на следующих условиях: - Просьба направлена не позднее 48 часов после внесения предоплаты</p>
				<p>3. Возврат денежных средств производится СНТ в течении 30 (тридцати) календарных дней.</p>
				<p>4 Возврат денежных средств осуществляется тем же способом, которым была произведена оплата:<br>
				- на банковскую карту, с которой была произведена оплата<br>
				- на расчетный счет, с которого была произведена оплата.</p>
				<p>5 Для возврата денежных средств на банковскую карту Заказчику необходимо заполнить «Заявление о возврате денежных средств», которое высылается на электронный адрес <a href="mailto:info@tworiver.ru">info@tworiver.ru</a></p>
				<p>6. После получения письменного заявления с приложением копии паспорта и чеков/квитанций, СНТ производит возврат в срок до 30 (тридцати) рабочих дней со дня получения 3аявления на расчетный счет Заказчика, указанный в заявлении. В этом случае, сумма возврата будет равняться сумме, указанной в заявлении.</p>
				<p>Срок рассмотрения Заявления и возврата денежных средств члену СНТ начинает исчисляться с момента получения СНТ Заявления и рассчитывается в рабочих днях без учета праздников/выходных дней.</p>

			  </div>
			</div>
		  </div>
		  <div class="card">
			<div class="card-header" id="headingThree">
			  <h5 class="mb-0">
				<button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" style="width: 100%; text-align: left;">
				  Способы оплаты
				</button>
			  </h5>
			</div>
			<div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
			  <div class="card-body">
				<p>После нажатия на кнопку ОНЛАЙН ОПЛАТА, Вы будете перенаправлены на платежный шлюз ПАО "Сбербанк" для ввода реквизитов Вашей карты. Пожалуйста, приготовьте Вашу пластиковую карту заранее. Дополнительно нужно email. Соединение с платежным шлюзом и передача информации осуществляется в защищенном режиме с использованием протокола шифрования SSL. В случае если Ваш банк поддерживает технологию безопасного проведения интернет-платежей Verified By Visa или MasterCard Secure Code для проведения платежа также может потребоваться ввод специального пароля. Способы и возможность получения паролей для совершения интернет-платежей Вы можете уточнить в банке, выпустившем карту. Настоящий сайт поддерживает 256-битное шифрование. Конфиденциальность сообщаемой персональной информации обеспечивается ПАО "Сбербанк". Введенная информация не будет предоставлена третьим лицам за исключением случаев, предусмотренных законодательством РФ. Проведение платежей по банковским картам осуществляется в строгом соответствии с требованиями платежных систем Visa Int. и MasterCard Europe Sprl.</p>
			  </div>
			</div>
		  </div>
		</div>
	</div>
	
	
    <!--div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card border-bottom-0">
          <div class="card-body pb-0">
            <p class="card-title"></p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <h4 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0">Потребление за <span id="graphMonthName"></span></h4>
              <h4 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0">
                <div class="btn-group">
                  <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Выбрать месяц</button>
                  <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 44px, 0px);">
                    <?php
                      $month = date("n");
                      $year = date("Y");
                      for ($i = 12; $i >= 1; $i--) {
                       if ($month == 0) { $month = 12; $year = $year -1;}
                    ?>
                    <a class="dropdown-item" href="#" onclick="graphGetMonth('<?=$month?>','<?=$year?>'); return false;"><?=$core->getMonthName($month)?> <?=$year?></a>
                    <?php
                        $month = $month -1;
                      }
                    ?>
                  </div>
                </div>
              </h4>
            </div>
          </div>
          <canvas id="indications" class="w-100"></canvas>
        </div>
      </div>

    </div-->


  <!-- content-wrapper ends -->
  <!-- partial:partials/_footer.html -->
  <?php include ('tpl/cab/tpl_footer.tpl'); ?>
  <!-- partial -->
</div>
<!-- main-panel ends -->

