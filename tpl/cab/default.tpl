<style>
  a.card:hover {
    text-decoration: none;
    box-shadow: rgb(122, 122, 134) 4px 8px 9px -2px;
    transition: 0.5s;
  }

  a.card {
    transition: 0.5s;
  }
</style>


<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h4 class="font-weight-bold mb-0">Личный кабинет: участок №<?=$user_data['uchastok']?></h4>
          </div>

        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-3 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <p class="card-title text-md-center text-xl-left">Общий баланс</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <?php if ($total_balance < 0) {$c1text = 'red';} else {$c1text = 'black';}?>
              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" style="color:<?=$c1text?>"><?=$total_balance?>р.</h3>
              <i class="fas fa-ruble-sign"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-3 grid-margin stretch-card">
        <a class="card" href="cab/electricpower">
          <div class="card-body">
            <p class="card-title text-md-center text-xl-left">Энергопотребление</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <?php if ($electric_balance < 0) {$c2text = 'red';} else {$c2text = 'black';}?>
              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" style="color:<?=$c2text?>"><?=$electric_balance?>р.</h3>
              <i class="fas fa-ruble-sign"></i>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-3 grid-margin stretch-card">
        <a class="card" href="cab/membership">
          <div class="card-body">
            <p class="card-title text-md-center text-xl-left">Членские взносы</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <?php if ($membership_balance < 0) {$c3text = 'red';} else {$c3text = 'black';}?>
              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" style="color:<?=$c3text?>"><?=$membership_balance?>р.</h3>
              <i class="fas fa-ruble-sign"></i>
            </div>
          </div>
        </a>
      </div>
      <div class="col-md-3 grid-margin stretch-card">
        <a class="card" href="cab/target">
          <div class="card-body">
            <p class="card-title text-md-center text-xl-left">Целевые взносы</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <?php if ($target_balance < 0) {$c4text = 'red';} else {$c4text = 'black';}?>
              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" style="color:<?=$c4text?>"><?=$target_balance?>р.</h3>
              <i class="fas fa-ruble-sign"></i>
            </div>
          </div>
        </a>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card border-bottom-0">
          <div class="card-body pb-0">
            <p class="card-title"></p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <h4 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0">Потребление за <span id="graphMonthName"></span></h4>
              <h4 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0">
                <div class="btn-group">
                  <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Выбрать месяц</button>
                  <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 44px, 0px);">
                    <?php
                      $month = date("n");
                      $year = date("Y");
                      for ($i = 12; $i >= 1; $i--) {
                       if ($month == 0) { $month = 12; $year = $year -1;}
                    ?>
                    <a class="dropdown-item" href="#" onclick="graphGetMonth('<?=$month?>','<?=$year?>'); return false;"><?=$core->getMonthName($month)?> <?=$year?></a>
                    <?php
                        $month = $month -1;
                      }
                    ?>
                  </div>
                </div>
              </h4>
            </div>
          </div>
          <canvas id="indications" class="w-100"></canvas>
        </div>
      </div>

    </div>


  <!-- content-wrapper ends -->
  <!-- partial:partials/_footer.html -->
  <?php include ('tpl/cab/tpl_footer.tpl'); ?>
  <!-- partial -->
</div>
<!-- main-panel ends -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>

<script>

window.onload = function()
{
  graphGetMonth(<?=date("n")?>, <?=date("Y")?>)
}

function graphGetMonth(month, year)
{
  //console.log(year+'-'+month);

  $.post(
    "/pages/cab/ajax/getMonthGraph.php", {
      month: month,
      year: year
    },
    onAjaxSuccess
  );

  function onAjaxSuccess(data) {
    //console.log(data);
    result = JSON.parse(data);
    //console.log(result.labels);
    var new_data = {
        type: 'line',
        data: {
          labels: result.labels,
            datasets: [
              {
                label: 'Т2',
                data: result.data1,
                backgroundColor: [
                  'rgba(153, 102, 255, 1)'
                ],
                borderColor: [
                  'rgba(153, 102, 255, 1)'
                ],
                borderWidth: 1
              },
              {
                label: 'Т1',
                data: result.data2,
                backgroundColor: [
                  'rgba(255, 206, 86, 1)'
                ],
                borderColor: [
                  'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
              }

            ]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    }
    //console.log(new_data);
    document.getElementById('graphMonthName').innerHTML = result.month;
    var ctx = document.getElementById('indications').getContext('2d');
    var myChart = new Chart(ctx, new_data);
  }
}

</script>
