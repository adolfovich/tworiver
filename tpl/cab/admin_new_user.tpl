<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h4 class="font-weight-bold mb-0"><a href="admin">Панель администратора</a> - <a href="admin_users">Пользователи</a> - Новый пользователь</h4>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 grid-margin stretch-card">
        <div class="card border-bottom-0">
          <div class="card-body pb-0">
            <p class="card-title">Данные пользователя</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center mb-4">
                <form class="col-sm-12" action="" method="POST">
                  <div class="form-group row">
                    <label for="userName" class="col-sm-3 col-form-label">ФИО</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="userName" name="name" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="userEmail" class="col-sm-3 col-form-label">Email</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="userEmail" name="email" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="userPhone" class="col-sm-3 col-form-label">Телефон</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="userPhone" name="phone" value="<?php if (isset($_POST['phone'])) echo $_POST['phone']; ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="userUchastok" class="col-sm-3 col-form-label">Номер участка</label>
                    <div class="col-sm-9">
                      <input type="text" class="form-control" id="userUchastok" name="uchastok" value="<?php if (isset($_POST['uchastok'])) echo $_POST['uchastok']; ?>">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="userSMS" class="col-sm-3 col-form-label">Получать СМС</label>
                    <div class="col-sm-9">
                      <label class="switch">
                        <?php if (isset($_POST['send_monthly_sms']) && $_POST['send_monthly_sms'] == 1) $sms_checked = 'checked'; ?>
                        <input type="checkbox" class="form-control" id="userSMS" name="send_monthly_sms" value="1" <?=$sms_checked?> >
                        <span class="slider round"></span>
                      </label>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="userPass" class="col-sm-3 col-form-label">Пароль</label>
                    <div class="col-sm-9">
                      <input type="password" class="form-control" id="userPass" name="pass">
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="userRePass" class="col-sm-3 col-form-label">Повтор пароля</label>
                    <div class="col-sm-9">
                      <input type="password" class="form-control" id="userRePass" name="repass">
                    </div>
                  </div>
                  <button name="saveNewUser" type="submit" class="btn btn-primary">Сохранить</button>
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
              <div class="alert alert-warning" role="alert">
                  Для добавления договора нужно сохранить пользователя.
              </div>
            </div>
          </div>
        </div>
        <div class="card border-bottom-0">
          <div class="card-body pb-0">
            <p class="card-title">Акты</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center mb-4">
              <div class="alert alert-warning" role="alert">
                  Для добавления акта нужно сохранить пользователя.
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
