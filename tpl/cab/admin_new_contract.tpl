<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h4 class="font-weight-bold mb-0"><a href="admin">Панель администратора</a> - <a href="admin_users">Пользователи</a> - <a href="admin_user?id=<?=$contract['user']?>">Участок №<?=$contract_user_data['uchastok']?></a> - Новый договор</h4>
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
              <form class="col-sm-12" action="?user_id=<?=$contract_user_data['id']?>" method="POST">
                <input type="hidden" name="user_id" value="<?=$contract_user_data['id']?>" />
                <div class="form-group row">
                  <label for="contractNum" class="col-sm-3 col-form-label">Номер договора</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="contractNum" name="num" value="<?php if(isset($_POST['num'])) echo $_POST['num'];?>">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="contractDateStart" class="col-sm-3 col-form-label">Дата начала договора</label>
                  <div class="col-sm-9">
                    <input type="date" class="form-control" id="contractDateStart" name="dateStart" value="<?php if(isset($_POST['dateStart'])) echo $_POST['dateStart'];?>">
                  </div>
                </div>

                <div class="form-group row">
                  <label for="contractDateEnd" class="col-sm-3 col-form-label">Дата окончания договора</label>
                  <div class="col-sm-9">
                    <input type="date" class="form-control" id="contractDateEnd" name="dateEnd" value="<?php if(isset($_POST['dateEnd'])) echo $_POST['dateEnd'];?>">
                  </div>
                </div>

                <button type="submit" name="saveNewContract" class="btn btn-primary">Сохранить</button>
              </form>
            </div>
            <br>
            <br>
            <p class="card-title">Счетчики</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <div class="alert alert-warning" role="alert">
                  Для добавления счетчиков нужно сохранить договор.
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
