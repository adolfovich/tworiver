<style>
.table td { font-size: 0.9rem; }
.table td p{ font-size: 0.9rem; }
.table td { padding: 0.25rem; }
</style>

<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h4 class="font-weight-bold mb-0">Панель администратора - Пользователи</h4>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card border-bottom-0">
          <div class="card-body pb-0">
            <p class="card-title">Список пользователей</p>
            <form class="form-inline">
              <div class="form-group mx-sm-3 mb-2 col-sm-12">
                <label for="searchUser" class="sr-only">Password</label>
                <input type="text" class="form-control col-sm-12" id="searchUser" placeholder="участок/ФИО/телефон" onKeyup="searchUsers(this.value)">
              </div>
            </form>

            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Участок</th>
                    <th scope="col">ФИО</th>
                    <th scope="col">Телефон</th>
                    <th scope="col">Договора</th>
                    <th scope="col">Счетчики</th>
                    <!--th scope="col">Акты<br>сверок</th-->
                    <th scope="col">Баланс</th>
                    <!--th scope="col">SMS</th-->
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody class="list" id="usersResult"></tbody>
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
  function searchUsers(val) {
    //console.log(val);
    $.post(
        "../pages/ajax/searchUsers.php",
        {
            string: val,
        },
        onAjaxSuccess
    );

    function onAjaxSuccess(data) {
      document.getElementById('usersResult').innerHTML = data;
    }
  }
  
  window.onload = function() {
    searchUsers('');
  }
</script>
