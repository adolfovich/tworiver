<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h4 class="font-weight-bold mb-0"><a href="admin">Панель администратора</a> - <a href="admin_users">Пользователи</a> - <a href="admin_user?id=<?=$contract['user']?>">Участок №<?=$contract_user_data['uchastok']?></a> - Договор №<?=$contract_num?> от <?=$contract_date_start?></h4>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card border-bottom-0">
          <div class="card-body pb-0">
            <p class="card-title">Данные договора</p>

            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <form class="col-sm-12" action="?id=<?=$contract['id']?>&action=save_contract" method="POST">
                <div class="form-group row">
                  <label for="contractNum" class="col-sm-3 col-form-label">Номер договора</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="contractNum" name="num" value="<?=$contract['num']?>">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="contractDateStart" class="col-sm-3 col-form-label">Дата начала договора</label>
                  <div class="col-sm-9">
                    <input type="date" class="form-control" id="contractDateStart" name="date_start" value="<?php if ($contract['date_start']) echo date('Y-m-d', strtotime($contract['date_start']));?>">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="contractDateEnd" class="col-sm-3 col-form-label">Дата окончания договора</label>
                  <div class="col-sm-9">
                    <input type="date" class="form-control" id="contractDateEnd" name="date_end" value="<?php if ($contract['date_end']) echo date('Y-m-d', strtotime($contract['date_end']));?>">
                  </div>
                </div>

                <input name="id" type="hidden" value="<?=$contract['id']?>">

                <button type="submit" class="btn btn-primary">Сохранить</button>
              </form>
            </div>
            <br>
            <br>
            <p class="card-title">Счетчики</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col" class="text-center">Модель</th>
                    <th scope="col" class="text-center">Номер</th>
                    <th scope="col" class="text-center">Номера пломб</th>
                    <th scope="col" class="text-center">Номер модема</th>
                    <th scope="col" class="text-center">Дата установки</th>
                    <th scope="col" class="text-center">Дата вывода<br>из эксплуатации</th>
                    <th scope="col" class="text-center"></th>
                  </tr>
                </thead>
                <tbody class="list" id="usersResult">
                  <?php foreach ($counters as $counter) { ?>
                    <tr>
                      <td><?=$counter['model']?></td>
                      <td><?=$counter['num']?></td>
                      <td><?=$counter['plomb']?></td>
                      <td><?=$counter['modem_num']?></td>
                      <?php if ($counter['install_date']) {$install_date = date("d.m.Y", strtotime($counter['install_date']));} else {$install_date = '<center>---</center>';} ?>
                      <td><?=$install_date?></td>
                      <?php if ($counter['dismantling_date']) {$dismantling_date = date("d.m.Y", strtotime($counter['dismantling_date']));} else {$dismantling_date = '<center>---</center>';} ?>
                      <td><?=$dismantling_date?></td>
                      <td>
                        <?php if (!$counter['dismantling_date']) { ?>
                        <a href="#" class="btn btn-outline-secondary">Замена</a>
                        <a href="#" class="btn btn-outline-secondary" onclick="loadModal('modal_disable_counter', params = 'counter=<?=$counter['id']?>')">Вывести</a>
                      <?php } else {?>
                        <center>Выведен из эксплуатации</center>
                      <?php } ?>
                      </td>
                    </td>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <div class="row">
              <div class="col">
                <div class="form-group row">
                  <form class="col-sm-12">
                    <a href="#" class="btn btn-primary" onclick="loadModal('modal_add_counter', params = 'contract=<?=$contract['id']?>')">Добавить счетчик</a>
                  </form>
                </div>

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
