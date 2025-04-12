<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h4 class="font-weight-bold mb-0">Панель администратора - Настройки системы</h4>
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
                  <?php foreach ($all_settings as $setting) { ?>

                  <div class="form-group row">
                    <label for="setting_<?=$setting['cfgname']?>" class="col-sm-3 col-form-label"><?=$setting['description']?></label>
                    <div class="col-sm-9">
                      <?php if ($setting['input_type'] == 'text') { ?>
                        <input type="text" class="form-control" id="setting_<?=$setting['cfgname']?>" name="<?=$setting['cfgname']?>" value="<?=$setting['data']?>" <?php if ($setting['cfgname'] == 'version') echo 'disabled'; ?> >
                      <?php } else if ($setting['input_type'] == 'select') { ?>
                        <select class="form-control" id="setting_<?=$setting['cfgname']?>" name="<?=$setting['cfgname']?>">
                          <?php $options = explode(";", $setting['options']); ?>
                          <?php foreach ($options as $value) { ?>
                            <?php $option = explode(",", $value); ?>
                            <?php if ($setting['data'] == $option[0]) { $selected_option = 'selected'; } else { $selected_option = ''; } ?>
                            <option value="<?=$option[0]?>" <?=$selected_option?>><?=$option[1]?></option>
                          <?php } ?>
                        </select>
                      <?php } ?>
                    </div>
                  </div>
                  <?php } ?>
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
