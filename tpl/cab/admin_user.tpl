<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h4 class="font-weight-bold mb-0"><a href="admin">Панель администратора</a> - <a href="admin_users">Пользователи</a> - участок №<?=$curr_user_data['uchastok']?> <?=$curr_user_data['name']?></h4>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-2 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <p class="card-title text-md-center text-xl-left">Общий баланс</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <?php if ($curr_count_balance < 0) {$c1text = 'red';} else {$c1text = 'black';}?>
              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" style="color:<?=$c1text?>"><?=$curr_count_balance?>р.</h3>
              <i class="fas fa-ruble-sign"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-2 grid-margin stretch-card">
        <div class="card" href="cab/electricpower">
          <div class="card-body">
            <p class="card-title text-md-center text-xl-left">Энергопотребление</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <?php if ($curr_electric_balance < 0) {$c2text = 'red';} else {$c2text = 'black';}?>
              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" style="color:<?=$c2text?>"><?=$curr_electric_balance?>р.</h3>
              <i class="fas fa-ruble-sign"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-2 grid-margin stretch-card">
        <div class="card" href="cab/membership">
          <div class="card-body">
            <p class="card-title text-md-center text-xl-left">Членские взносы</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <?php if ($curr_membership_balance < 0) {$c3text = 'red';} else {$c3text = 'black';}?>
              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" style="color:<?=$c3text?>"><?=$curr_membership_balance?>р.</h3>
              <i class="fas fa-ruble-sign"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-2 grid-margin stretch-card">
        <div class="card" href="cab/target">
          <div class="card-body">
            <p class="card-title text-md-center text-xl-left">Целевые взносы</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <?php if ($curr_target_balance < 0) {$c4text = 'red';} else {$c4text = 'black';}?>
              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" style="color:<?=$c4text?>"><?=$curr_target_balance?>р.</h3>
              <i class="fas fa-ruble-sign"></i>
            </div>
          </div>
        </div>
      </div>
	  <div class="col-md-2 grid-margin stretch-card">
        <a class="card" href="cab/loan">
          <div class="card-body">
            <p class="card-title text-md-center text-xl-left">Займ</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <?php if ($curr_target_balance < 0) {$c4text = 'red';} else {$c4text = 'black';}?>
              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" style="color:<?=$c4text?>"><?=$curr_target_balance?>р.</h3>
              <i class="fas fa-ruble-sign"></i>
            </div>
          </div>
        </a>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 grid-margin stretch-card">
        <div class="card border-bottom-0">
          <div class="card-body pb-0">
            <p class="card-title">Данные пользователя</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center mb-4">
                <form class="col-sm-12" action="?id=<?=$curr_user_data['id']?>&action=save_user" method="POST">
                  <div class="form-group row">
                    <label for="userName" class="col-sm-3 col-form-label">ФИО</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="userName" name="name" value="<?=$curr_user_data['name']?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="userEmail" class="col-sm-3 col-form-label">Email</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="userEmail" name="email" value="<?=$curr_user_data['email']?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="userPhone" class="col-sm-3 col-form-label">Телефон</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="userPhone" name="phone" value="<?=$curr_user_data['phone']?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="userUchastok" class="col-sm-3 col-form-label">Номер участка</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="userUchastok" name="uchastok" value="<?=$curr_user_data['uchastok']?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="userSMS" class="col-sm-3 col-form-label">Получать СМС</label>
                    <div class="col-sm-9">
                      <label class="switch">
                        <?php if ($curr_user_data['send_monthly_sms']) $sms_checked = 'checked'; ?>
                        <input type="checkbox" class="form-control" id="userSMS" name="send_monthly_sms" value="1" <?=$sms_checked?> >
                        <span class="slider round"></span>
                      </label>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="userPass" class="col-sm-3 col-form-label">Новый пароль</label>
                    <div class="col-sm-9">
                      <input type="password" class="form-control" id="userPass" name="pass">
                    </div>
                  </div>
                  <button type="submit" class="btn btn-primary">Сохранить</button>
                </form>
            </div>
          </div>
        </div>
      </div>


      <div class="col-md-6 grid-margin ">
        <div class="card border-bottom-0">
          <div class="card-body pb-0">
            <p class="card-title">Договора и счетчики</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center mb-4">
              <ul class="list-group col-sm-12">
                <?php if ($curr_user_contracts ) { ?>
                  <?php foreach ($curr_user_contracts as $contract) {?>
                    <?php if (!$contract['num']) $contract['num'] = 'Б/Н'; ?>
                    <?php
                      if ($contract['date_start'] != '0000-00-00') {
                        $contract['date_start'] = date("d.m.Y", strtotime($contract['date_start']));
                      } else {
                        $contract['date_start'] = '--.--.----';
                      }
                    ?>

                    <li class="list-group-item bg-secondary text-light">
                      <div class="row">
                        <div class="col-sm-8">
                          Договор №<?=$contract['num']?> от <?=$contract['date_start']?>
                          <?php if ($contract['date_end']) { ?>
                           - <?=date("d.m.Y", strtotime($contract['date_end']))?>
                           <?php if (strtotime($contract['date_end']) < time()) { ?>
                           <b>(ЗАКРЫТ)</b>
                           <?php } ?>
                          <?php } ?>
                        </div>
                        <div class="col-sm-4 text-right">
                          <a href="admin_edit_contract?id=<?=$contract['id']?>" class="btn btn-light btn-sm">Изменить</a>
                        </div>
                      </div>
                    </li>
                    <?php $contract_counters = $db->getAll("SELECT * FROM counters WHERE contract_id = ?i AND dismantling_date IS NULL",$contract['id'] ); ?>
					<?php //var_dump($db->parse("SELECT * FROM counters WHERE contract_id = ?i AND dismantling_date IS NULL",$contract['id'] ));?>
                    <?php if (!$contract_counters) { ?>
                      <li class="list-group-item text-center">Нет счетчиков</li>
                    <?php } else { ?>
                      <?php foreach ($contract_counters as $contract_counter) { ?>
                        <li class="list-group-item">
                          <?=$contract_counter['model']?> №<?=$contract_counter['num']?> (<?=$contract_counter['plomb']?>) <br>
                          <button type="button" class="btn btn-danger" onClick="loadModal('modal_delete_indications', 'counter=<?=$contract_counter['id']?>')"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Удалить показания</button>
                          <button type="button" class="btn btn-primary" onClick="openIndicationsModal(<?=$contract_counter['id']?>, 1, 1); return false;">Просмотр показаний</button>
                        </li>
                      <?php } ?>
                    <?php } ?>
                  <?php } ?>
                <?php } else { ?>
                  <a href="admin_new_contract?user_id=<?=$curr_user_data['id']?>" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Добавить договор</a>
                <?php } ?>
                <a href="admin_new_contract?user_id=<?=$curr_user_data['id']?>" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> Добавить договор</a>

              </ul>
            </div>
          </div>
        </div>
        <div class="card border-bottom-0">
          <div class="card-body pb-0">
            <p class="card-title">Акты</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center mb-4">
              <ul class="list-group col-sm-12">
              <?php foreach ($curr_user_acts as $act) { ?>
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
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center mb-4">
              <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <div class="btn-group" role="group">
                  <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Распечатать акт
                  </button>
                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <a class="dropdown-item" href="#" onClick="loadModal('modal_print_act', 'type=electric&user_id=<?=$curr_user_data['id']?>')">Электричество</a>
                    <a class="dropdown-item" href="#">Членские взносы</a>
                    <a class="dropdown-item" href="#">Целевые взносы</a>
                  </div>
                </div>
                <button type="button" class="btn btn-primary" onClick="loadModal('modal_upload_act', 'user_id=<?=$curr_user_data['id']?>')">Загрузить акт</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  <!-- content-wrapper ends -->
  <!-- partial:partials/_footer.html -->
  <?php include ('tpl/cab/tpl_footer.tpl'); ?>
  <!-- partial -->
</div>
