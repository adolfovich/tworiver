<style>
.indicationsInput {
  color: #000 !important;
    height: 30px;
    margin-top: -2px;
}
</style>

<!-- Modal indications-->
<div class="modal fade bd-example-modal-lg" id="indicationsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <span id="indicationsModalCounterNum" style="display:none;"></span>
        <h5 class="modal-title" id="indicationsModalTitle">Показания по счетчику № за &nbsp;</h5>
          <div class="form-row">
            <div class="form-group col-md-6">
              <select id="indicationsMonth" class="form-control indicationsInput" onChange="indicationsLoad(document.getElementById('indicationsModalCounterNum').innerHTML, document.getElementById('indicationsMonth').value, document.getElementById('indicationsYear').value);">
                <?php for ($i = 1; $i <= 12; $i++) { ?>
                  <?php if ($i == $currentmomth) {$selected = 'selected';} else {$selected = '';} ?>
                  <option value="<?=$i?>" <?=$selected?>><?=$core->getMonthName($i)?></option>
                <?php } ?>
              </select>
            </div>
            <div class="form-group col-md-6">
              <select id="indicationsYear" class="form-control indicationsInput"  onChange="indicationsLoad(document.getElementById('indicationsModalCounterNum').innerHTML, document.getElementById('indicationsMonth').value, document.getElementById('indicationsYear').value);">
                <?php for ($i = $currentyear - 10; $i < $currentyear; $i++) { ?>
                  <option value="<?=$i?>"><?=$i?></option>
                <?php } ?>
                <option selected><?=$currentyear?></option>
              </select>
            </div>
          </div>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="indicationsModalBody">
        <div class="container mt-3">
          <!-- Nav tabs -->
          <ul class="nav nav-tabs">
            <li class="nav-item">
              <a class="nav-link active" data-toggle="tab" href="#t1">T1</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#t2">T2</a>
            </li>
          </ul>

          <!-- Tab panes -->
          <div class="tab-content">
            <div id="t1" class="container tab-pane active"><br>
              <h3>T1</h3>
              <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
            </div>
            <div id="t2" class="container tab-pane fade"><br>
              <h3>T2</h3>
              <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
      </div>
    </div>
  </div>
</div>

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
                <div class="mb-3" style="font-size: 2em; width: 100%;"><a href="#" class="btn btn-success btn-sm btn-block" onClick="loadModal('modal_pay_electric')">Оплатить</a></div>
                <div class="mb-3" style="font-size: 2em; width: 100%;"><a href="#" class="btn btn-success btn-sm btn-block" onClick="loadModal('modal_receipt_electric')">Распечатать квитанцию</a></div>
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
                          <a href="#" class="btn btn-success btn-sm btn-block" onclick="openIndicationsModal(<?=$counter['id']?>); return false;">Показания</a>
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

  <!-- content-wrapper ends -->
  <!-- partial:partials/_footer.html -->
  <?php include ('tpl/cab/tpl_footer.tpl'); ?>
  <!-- partial -->
</div>

<script>
  function openIndicationsModal(counterId)
  {
    var modalTitle = document.getElementById('indicationsModalTitle');
    var modalBody = document.getElementById('indicationsModalBody');
    var month = document.getElementById('indicationsMonth').value;
    var year = document.getElementById('indicationsYear').value;
    var titleCounterNum = document.getElementById('indicationsModalCounterNum');
    titleCounterNum.innerHTML = counterId;
    indicationsLoad(counterId, month, year);
    $("#indicationsModal").modal('show');
  }

  function indicationsLoad(counterId, month, year)
  {
    var modalBody = document.getElementById('indicationsModalBody');
    $.post(
      "/pages/cab/ajax/getIndications.php",
      {
        counterId: counterId,
        month: month,
        year: year
      },
      onAjaxSuccess
    );

    function onAjaxSuccess(data)
    {
      modalBody.innerHTML = data;
    }
  }

</script>
