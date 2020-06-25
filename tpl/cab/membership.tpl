<style>
.indicationsInput {
  color: #000 !important;
    height: 30px;
    margin-top: -2px;
}
</style>

<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h4 class="font-weight-bold mb-0">Личный кабинет: участок №<?=$user_data['uchastok']?> - Членские взносы</h4>
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

                <div class="col-md-4 grid-margin stretch-card">
                  <div class="card border-bottom-0">
                    <div class="card-body">
                      <p class="card-title">Текущий членский взнос</p>
                      <p class="" style="font-size: 2em;"><span class="text-danger"><?=$core->cfgRead('membership_rate')?></span> р/<?=$core->cfgRead('membership_period')?></p>
                    </div>
                  </div>
                </div>

                <div class="col-md-4 grid-margin stretch-card">
                  <div class="card border-bottom-0">
                    <div class="card-body">
                      <p class="card-title">Текущий баланс</p>
                      <?php if ($membership_balance < 0) {$c3text = 'red';} else {$c3text = 'black';}?>
                      <p class="" style="font-size: 2em;"><span style="color:<?=$c3text?>"><?=$membership_balance?></span> р.</p>
                    </div>
                  </div>
                </div>

                <div class="col-md-4 ">
                  <div class="mb-3" style="font-size: 2em; width: 100%;"><a href="#" class="btn btn-success btn-sm btn-block" onClick="loadModal('modal_pay_membership')">Оплатить</a></div>
                  <div class="mb-3" style="font-size: 2em; width: 100%;"><a href="#" class="btn btn-success btn-sm btn-block" onClick="loadModal('modal_receipt_membership')">Распечатать квитанцию</a></div>
                </div>

            </div>
          </div>
        </div>
      </div>
    </div>

    <?php if (!$memberchips) { ?>
      <div class="row" >
        <div class="col-md-12 grid-margin stretch-card">
          <div class="card border-bottom-0">
            <div class="card-body">
              <p class="card-title"></p>
              <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                <h4>Операций не найдено</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    <?php } else { ?>

      <div class="row" >
        <div class="col-md-12 grid-margin stretch-card">
          <div class="card border-bottom-0">
            <div class="card-body">
              <p class="card-title"></p>
              <div class="row">
                <table class="table table-bordered table-sm table-hover">
                  <tr>
                    <th style="vertical-align: middle; text-align: center;">Дата</th>
                    <th style="vertical-align: middle; text-align: center;">Операция</th>
                    <th style="vertical-align: middle; text-align: center;">Сумма</th>
                    <th style="vertical-align: middle; text-align: center;">Коментарий</th>
                  </tr>
                  <?php foreach ($memberchips as $memberchip) { ?>
                    <tr>
                      <td class="text-center"><?=date("d.m.Y H:i", strtotime($memberchip['date']))?></td>
                      <td class="text-center"><?=$memberchip['operation_name']?></td>
                      <td class="text-center"><?=$memberchip['amount']?></td>
                      <td class="text-center"><?=$memberchip['comment']?></td>
                    </tr>
                  <?php } ?>
              </table>
              </div>
            </div>
          </div>
        </div>
      </div>

    <?php } ?>

  <!-- content-wrapper ends -->
  <!-- partial:partials/_footer.html -->
  <?php include ('tpl/cab/tpl_footer.tpl'); ?>
  <!-- partial -->
</div>
