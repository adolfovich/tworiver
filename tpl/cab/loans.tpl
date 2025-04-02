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
            <h4 class="font-weight-bold mb-0">Личный кабинет: участок №<?=$user_data['uchastok']?> - Договоры займа</h4>
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

                <div class="col-md-6 grid-margin stretch-card">
                  <div class="card border-bottom-0">
                    <div class="card-body">
                      <p class="card-title">Сумма займов</p>
                      <p class="" style="font-size: 2em;"><span style="color:black"><?=$loans?></span> р.</p>
                    </div>
                  </div>
                </div>
				
				<div class="col-md-6 grid-margin stretch-card">
                  <div class="card border-bottom-0">
                    <div class="card-body">
                      <p class="card-title">Остаток по займам</p>
                      <p class="" style="font-size: 2em;"><span style="color:black"><?=$loan_balance?></span> р.</p>
                    </div>
                  </div>
                </div>

            </div>
          </div>
        </div>
      </div>
    </div>

    <?php if (!$loans) { ?>
      <div class="row" >
        <div class="col-md-12 grid-margin stretch-card">
          <div class="card border-bottom-0">
            <div class="card-body">
              <p class="card-title"></p>
              <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
                <h4>Договоры не найдены</h4>
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
                    <th style="vertical-align: middle; text-align: center;">Номер договора</th>
                    <th style="vertical-align: middle; text-align: center;">Сумма</th>
					<th style="vertical-align: middle; text-align: center;">Остаток</th>
                    <th style="vertical-align: middle; text-align: center;">Комментарий</th>
                  </tr>
                  <?php foreach ($loans_list as $loan_list) { ?>
                    <tr>
                      <td class="text-center"><?=date("d.m.Y H:i", strtotime($loan_list['agreement_date']))?></td>
                      <td class="text-center"><?=$loan_list['agreement_num']?></td>
                      <td class="text-center"><?=$loan_list['amount']?></td>
					  <td class="text-center"><?=$loan_list['amount'] - $loan_list['payouts']?></td>
                      <td class="text-center"><?=$loan_list['comment']?></td>
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
