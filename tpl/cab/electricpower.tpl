

<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h4 class="font-weight-bold mb-0">Личный кабинет: участок №<?=$user_data['uchastok']?> - Энергопотребление</h4>
          </div>
        </div>
      </div>
    </div>

    <div class="row" >
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card border-bottom-0">
          <div class="card-body">
            <p class="card-title"></p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">

              <?php foreach ($current_tarifs as $tarif) { ?>
                <div class="col-md-3 grid-margin stretch-card">
                  <div class="card border-bottom-0">
                    <div class="card-body">
                      <p class="card-title">Тариф: <?=$tarif['name']?></p>
                      <p class="" style="font-size: 1.5em;"><span class="text-danger"><?=$tarif['price']?></span> <span style="white-space: nowrap;">р/кВт*ч</span></p>
                    </div>
                  </div>
                </div>
              <?php } ?>
              <div class="col-md-3 grid-margin stretch-card">
                <div class="card border-bottom-0">
                  <div class="card-body">
                    <p class="card-title">Текущий баланс</p>
                    <?php if ($electric_balance < 0) {$c3text = 'red';} else {$c3text = 'black';}?>
                    <p class="" style="font-size: 1.5em;"><span style="color:<?=$c3text?>"><?=$electric_balance?></span> р</p>
                  </div>
                </div>
              </div>
              <div class="col-md-3 ">
			  <?php if ($core->cfgRead('pay_enable') == 1) { ?>
                <div class="mb-3" style="font-size: 2em; width: 100%;"><a href="#" class="btn btn-success btn-sm btn-block" onClick="loadModal('modal_pay_electric')">Оплатить</a></div>
			  <?php } else { ?>
				<div class="mb-3" style="font-size: 2em; width: 100%;"><a href="#" class="btn btn-secondary btn-sm btn-block" disabled>Оплата отключена</a></div>
			  <?php } ?>
                <div class="mb-3" style="font-size: 2em; width: 100%;"><a href="#" class="btn btn-primary btn-sm btn-block" onClick="loadModal('modal_receipt_electric')">Распечатать квитанцию</a></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php if (!$contracts) { ?>
      <div class="row" >
        <div class="col-md-12 grid-margin stretch-card">
          <div class="card border-bottom-0">
            <div class="card-body">
              <p class="card-title"></p>
              <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                <h4>Договоров не найдено</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php } else { ?>
      <?php foreach ($contracts as $contract) { ?>
      <?php $counters = getCounters($contract['id']); ?>
      <div class="row" >
        <div class="col-md-12 grid-margin stretch-card">
          <div class="card border-bottom-0">
            <div class="card-body">
              <p class="card-title">Договор на электропотребление №<?=$contract['num']?> от <?=date("d.m.Y", strtotime($contract['date_start']))?></p>
              <div class="row">
                <?php if (!$counters) { ?>
                  <div class="col-md-12 grid-margin stretch-card">
                    <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                      <p>Счетчиков не найдено</p>
                    </div>
                  </div>
                <?php } else { ?>
                  <?php foreach ($counters as $counter) { ?>
                  <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                      <div class="card-body">
                        <p class="card-title text-md-center text-xl-left">Электросчетчик</p>
                        <div class="row">
                          <div class="col-md-4">
                            <img class="img-fluid" src="/img/electrosm.png" style="max-width: 100px;"/>
                          </div>
                          <div class="col-md-8">
                            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                              <table class="table-sm">
                                <tr>
                                  <td>Марка:</td>
                                  <td><?=$counter['model']?></td>
                                </tr>
                                <tr>
                                  <td>Номер:</td>
                                  <td><?=$counter['num']?></td>
                                </tr>
                                <tr>
                                  <td>Пломба №:</td>
                                  <td><?=$counter['plomb']?></td>
                                </tr>
                                <tr>
                                  <td>Установлен:</td>
                                  <td><?=date("d.m.Y", strtotime($counter['install_date']))?></td>
                                </tr>
                              </table>
                            </div>
                          </div>
                        </div>
                        <div class="row" style="margin-top: 10px;">
                          <div class="col-md-6">
                            <a href="#" style="margin-top: 10px;" class="btn btn-primary btn-sm btn-block" onclick="openIndicationsModal(<?=$counter['id']?>); return false;">Показания</a>
                          </div>
                          <div class="col-md-6">
                            <a href="#" style="margin-top: 10px;" class="btn btn-success btn-sm btn-block" onclick="loadModal('modal_act_electric', 'counter=<?=$counter['id']?>'); return false;">Акт сверки</a>
                          </div>

                        </div>
                      </div>
                    </div>
                  </div>
                  <?php } ?>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php } ?>
    <?php } ?>

    <?php if (!$acts) { ?>
      <div class="row" >
        <div class="col-md-12 grid-margin stretch-card">
          <div class="card border-bottom-0">
            <div class="card-body">
              <p class="card-title">Акты</p>
              <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                <h4>Актов не найдено</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php } else { ?>
      <div class="row" >
        <div class="col-md-4 grid-margin stretch-card">
          <div class="card border-bottom-0">
            <div class="card-body">
              <p class="card-title">Акты</p>
              <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                <ul class="list-group col-sm-12">
                <?php foreach ($acts as $act) {?>
                  <li class="list-group-item">
                    <div class="row">
                      <div class="col-sm-11">
                        <?=$act['comment']?>
                      </div>
                      <div class="col-sm-1">
                        <a href="/<?=$act['path']?>" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>
                      </div>
                    </div>
                  </li>
                <?php } ?>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>


      <div class="row" >
        <div class="col-md-12 grid-margin stretch-card">
          <div class="card border-bottom-0">
            <div class="card-body">
              <p class="card-title">Журнал операций</p>
			  
			  <form class="form-inline" method="GET">
				  <div class="form-group mb-2">
					С<span style="width: 10px;"></span><input type="date" class="form-control" name="datefrom" id="datefrom" value="<?=$opStartDate?>">
				  </div>
				  <div class="form-group mb-2" style="padding-left: 10px;">
					ПО<span style="width: 10px;"></span><input type="date" class="form-control" name="dateto" id="dateto" value="<?=$opEndDate?>">
				  </div>
				  <button type="submit" class="btn btn-primary mb-2" style="margin-left: 10px;">Поиск</button>
				</form>
				
				<?php if (!$operations) { ?>
				
					<div class="row" style="margin-top: 20px;">
						<div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
							<h4>Операций за период не найдено</h4>
						</div>	
					</div>					
				
				<?php } else { ?>
				
					<div class="row">
						<table class="table table-bordered table-sm table-hover">
						  <tr>
							<th style="vertical-align: middle; text-align: center;">Дата</th>
							<th style="vertical-align: middle; text-align: center;">Операция</th>
							<th style="vertical-align: middle; text-align: center;">Сумма</th>
							<th style="vertical-align: middle; text-align: center;">Комментарий</th>
						  </tr>
						  <?php foreach ($operations as $operation) { ?>
							<tr>
							  <td class="text-center"><?=date("d.m.Y H:i", strtotime($operation['date']))?></td>
							  <td class="text-center"><?=$operation['operation_name']?></td>
							  <td class="text-center"><?=$operation['amount']?></td>
							  <td class="text-center"><?=$operation['comment']?></td>
							</tr>
						  <?php } ?>
						</table>
				    </div>
				
				<?php } ?>
				
				</div>
          </div>
        </div>
      </div>

  <!-- content-wrapper ends -->
  <!-- partial:partials/_footer.html -->
  <?php include ('tpl/cab/tpl_footer.tpl'); ?>
  <!-- partial -->
</div>

<?php if ($showOperations == 1) {?>
	<script>
		window.location.hash="datefrom";
	</script>
<?php } ?>