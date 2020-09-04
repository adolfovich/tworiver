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

      <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <p class="card-title text-md-center text-xl-left">Текущий тариф на электроэнергию</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <table class="table">
                <tbody>
                <?php foreach($rates as $rate) { ?>
                  <tr>
                    <td><?=$rate['name']?></td>
                    <td><?=$rate['price']?> р./КвЧ</td>
                    <td><button type="button" class="btn btn-primary" onClick="loadModal('modal_change_ep_rate', 'rate=<?=$rate['id']?>')">Изменить</button></td>
                  </tr>
                <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <p class="card-title text-md-center text-xl-left">Текущий тариф по членским взносам</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <table class="table">
                <tbody>
                  <tr>
                    <td>Ежемесячный</td>
                    <td><?=$membership_rate?> р./<?=$membership_rate_period?></td>
                    <td><button type="button" class="btn btn-primary" onClick="">Изменить</button></td>
                  </tr>
                </tbody>
              </table>
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
