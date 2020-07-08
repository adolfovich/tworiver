<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h4 class="font-weight-bold mb-0">Панель администратора</h4>
          </div>

        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <p class="card-title text-md-center text-xl-left">Общий долг по электроэнергии</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" style="color:red"><?=number_format($electric_debt, 2, '.', ' ')?> р.</h3>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <p class="card-title text-md-center text-xl-left">Общий долг по членским взносам</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" style="color:red"><?=number_format($membership_debt, 2, '.', ' ')?> р.</h3>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <p class="card-title text-md-center text-xl-left">Общий долг по целевым взносам</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" style="color:red"><?=number_format($target_debt, 2, '.', ' ')?> р.</h3>
            </div>
          </div>
        </div>
      </div>

    </div>
    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card border-bottom-0">
          <div class="card-body pb-0">
            <p class="card-title">Список должников</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <div class="container">
                <div class="row mb-3">
                  <div class="col-md-4 ">
                    <h4 class="text-center mb-3">Электроэнергия</h4>
                    <?php foreach ($electric_debtors as $electric_debtor) { ?>
                      <?php if (!$electric_debtor['is_del']) { ?>
                      <?php $name = explode(" ", $electric_debtor['name']); ?>
                      <p><a href="/cab/admin_user?id=<?=$electric_debtor['id']?>" style="color: #000"><?=$name[0]?> <?=mb_substr($name[1], 0, 1)?>.<?=mb_substr($name[2], 0, 1)?>. №<?=$electric_debtor['uchastok']?>: <b><?=number_format($electric_debtor['balance'], 2, '.', ' ')?>р.</b></a></p>
                      <?php } ?>
                    <?php } ?>
                  </div>
                  <div class="col-md-4 ">
                    <h4 class="text-center mb-3">Членские взносы</h4>
                    <?php foreach ($membership_debtors as $membership_debtor) { ?>
                      <?php if (!$membership_debtor['is_del']) { ?>
                      <?php $name = explode(" ", $membership_debtor['name']); ?>
                      <p><a href="/cab/admin_user?id=<?=$membership_debtor['id']?>" style="color: #000"><?=$name[0]?> <?=mb_substr($name[1], 0, 1)?>.<?=mb_substr($name[2], 0, 1)?>. №<?=$membership_debtor['uchastok']?>: <b><?=number_format($membership_debtor['balance'], 2, '.', ' ')?>р.</b></a></p>
                      <?php } ?>
                    <?php } ?>
                  </div>
                  <div class="col-md-4 ">
                    <h4 class="text-center mb-3">Целевые взносы</h4>
                    <?php foreach ($target_debtors as $target_debtor) { ?>
                      <?php if (!$target_debtor['is_del']) { ?>
                      <?php $name = explode(" ", $target_debtor['name']); ?>
                      <p><a href="/cab/admin_user?id=<?=$target_debtor['id']?>" style="color: #000"><?=$name[0]?> <?=mb_substr($name[1], 0, 1)?>.<?=mb_substr($name[2], 0, 1)?>. №<?=$target_debtor['uchastok']?>: <b><?=number_format($target_debtor['balance'], 2, '.', ' ')?>р.</b></a></p>
                      <?php } ?>
                    <?php } ?>
                  </div>
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
