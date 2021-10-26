<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h4 class="font-weight-bold mb-0">Панель администратора - ФХД</h4>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card border-bottom-0">
          <div class="card-body">
            <p class="card-title">Финансовая и хозяйственная деятельность</p>
            <form class="form-inline">
              <div class="form-group mx-sm-3 mb-2 col-sm-12">
                <label for="searchUser" >Год &nbsp&nbsp&nbsp</label>
                <!--input type="text" class="form-control col-sm-12" id="searchUser" placeholder="" onKeyup="searchUsers(this.value)"-->
                <select class="form-control col-sm-10" id="searchUser" onchange="searchFhd(this.value)">
                  <?php for ($i = 0; $i <= 10; $i++) { ?>
                    <?php if ((date('Y') - $i) == $year) {
                      $selected = 'selected';
                    } else {
                      $selected = '';
                    } ?>
                    <option value="<?=(date('Y') - $i)?>" <?=$selected?>><?=(date('Y') - $i)?></option>
                  <?php } ?>
                </select>
                <a class="btn btn-primary" style="margin-left: 5px; color: #fff;" role="button" onClick="loadModal('modal_fhd_upload'); return false;">Загрузить</a>
              </div>
            </form>

            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <table class="table align-items-center table-flush">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Период</th>
                    <th scope="col">Наименование</th>
                    <th scope="col" class="text-center">Действия</th>
                  </tr>
                </thead>
                <tbody class="list" id="reportsResult"></tbody>
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

  function searchFhd(val) {
    //console.log(val);
    document.cookie = "admin_fhd="+val;
    $.post(
        "../pages/ajax/searchReport.php",
        {
          type: 'fhd',
          year: val,
          role: 'admin'
        },
        onAjaxSuccess
    );

    function onAjaxSuccess(data) {
      //console.log(data);
      document.getElementById('reportsResult').innerHTML = data;
    }
  }

  window.onload = function() {
    searchFhd('<?=$year?>');
  }

  <?php if (isset($deleted)) { ?>
    Swal.fire({
      icon: 'success',
      text: 'Удалено'
    })
  <?php } ?>
</script>
