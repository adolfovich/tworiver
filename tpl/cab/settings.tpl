<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h4 class="font-weight-bold mb-0">Настройки пользователя</h4>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card border-bottom-0">
          <div class="card-body pb-0">
            <p class="card-title"></p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center mb-4">
                <form class="col-sm-12" action="?&action=save_settings" method="POST">

                  <div class="input-group mb-3">
                    <label for="setting_email" class="col-sm-3 col-form-label">Адрес электронной почты</label>
                    <input type="text" class="form-control" id="setting_email" name="setting_email" value="<?=$user_email?>">
                  </div>

                  <div class="input-group mb-3">
                    <label for="setting_new_pass" class="col-sm-3 col-form-label">Новый пароль</label>
                    <div class="input-group-append">
                      <span class="input-group-text" style="cursor: pointer;" id="for_setting_new_pass" onClick="showPass()"><i class="fa fa-eye"></i></span>
                    </div>
                    <input type="password" class="form-control pass" id="setting_new_pass" name="setting_new_pass" value="<?php if(isset($form['setting_new_pass'])) echo $form['setting_new_pass']; ?>">
                  </div>

                  <div class="input-group mb-3">
                    <label for="setting_new_pass2" class="col-sm-3 col-form-label">Повтор пароля</label>
                    <input type="password" class="form-control pass" id="setting_new_pass2" name="setting_new_pass2" value="<?php if(isset($form['setting_new_pass2'])) echo $form['setting_new_pass2']; ?>">
                  </div>

                  <button type="submit" class="btn btn-primary">Сохранить</button>
                </form>
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

<script>
  function showPass() {
    var passFields = $(".pass");
    //console.log(passFields[0].type);
    if (passFields[0].type == 'password') {
      $(".pass").prop("type", "text");
      $("#for_setting_new_pass").html('<i class="fa fa-eye-slash"></i>');
    } else {
      $(".pass").prop("type", "password");
      $("#for_setting_new_pass").html('<i class="fa fa-eye"></i>');
    }
  }
</script>
