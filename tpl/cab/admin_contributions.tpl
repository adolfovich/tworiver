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
            <p class="card-title text-md-center text-xl-left">Общий долг по членским взносам</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" style="color:red"><?=number_format($membership_debt, 2, '.', ' ')?> р.</h3>
              <button type="button" class="btn btn-primary">Создать взнос</button>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <p class="card-title text-md-center text-xl-left">Общий долг по целевым взносам</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" style="color:red"><?=number_format($target_debt, 2, '.', ' ')?> р.</h3>
              <button type="button" class="btn btn-primary">Создать взнос</button>
            </div>
          </div>
        </div>
      </div>

    </div>


    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card border-bottom-0">
          <div class="card-body pb-0">
            <p class="card-title">Список взносов</p>
            <form class="form-inline">
              <div class="form-group mx-sm-3 mb-2 col-sm-12">
                <label for="searchUser" class="sr-only">Пользователь</label>
                <input type="text" class="form-control col-sm-12" id="searchUser" placeholder="участок/ФИО/телефон" onKeyup="searchСontribution(this.value)">
              </div>
            </form>

            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Дата</th>                    
                    <th scope="col">Участок(ФИО)</th>
                    <th scope="col">Тип</th>
                    <th scope="col">Коментарий</th>
                    <th scope="col">Сумма</th>
                  </tr>
                </thead>
                <tbody class="list" id="contributionsResult"></tbody>
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


<script>
  function searchСontribution(val) {
    //console.log(val);
    $.post(
        "../pages/ajax/searchСontribution.php",
        {
            string: val,
        },
        onAjaxSuccess
    );

    function onAjaxSuccess(data) {
      document.getElementById('contributionsResult').innerHTML = data;
    }
  }

  window.onload = function() {
    searchСontribution('');
  }
</script>
