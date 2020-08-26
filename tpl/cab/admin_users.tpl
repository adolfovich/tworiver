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

            <p class="card-title">
              <div class="row">
                <div class="col-md-6">
                  Список пользователей
                </div>
                <div class="col-md-6 text-right">
                  <a href="admin_new_user" class="btn btn-primary">Добавить пользователя</a>
                </div>
              </div>
            </p>
            <form class="form-inline">
              <div class="form-group mb-2 col-sm-10">
                <label for="searchUser" class="sr-only"></label>
                <input type="text" class="form-control col-sm-12" id="searchUser" placeholder="участок/ФИО/телефон" onKeyup="searchUsers()">
              </div>
              <div class="form-group mb-2 col-sm-2">
                <label for="searchUser" class="sr-only">Сортировка</label>
                <select class="form-control col-sm-12" id="sortUser" onChange="searchUsers()">
                  <option value="0" disabled selected>Сортировка по</option>
                  <option value="area">Номер участка</option>
                  <option value="name">ФИО</option>
                  <option value="balance">Баланс</option>
                </select>
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
                    <th scope="col">Баланс</th>
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
  function searchUsers() {
    val = document.getElementById('searchUser').value;
    sort = document.getElementById('sortUser').value;
    $.post(
        "../pages/ajax/searchUsers.php",
        {
          string: val,
          sorting: sort,
        },
        onAjaxSuccess
    );

    function onAjaxSuccess(data) {
      //console.log(data);
      document.getElementById('usersResult').innerHTML = data;
    }
  }

  window.onload = function() {
    searchUsers('');
  }
</script>
