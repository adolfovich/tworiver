<style>
  .table td {
    padding: 4px !important;
  }
</style>

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
            <p class="card-title text-md-center text-xl-left">Сумма по займам</p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <h3 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" ><?=number_format($loans_sum, 2, '.', ' ')?> р.</h3>
              <button type="button" class="btn btn-primary" onClick="loadModal('modal_add_loan_agreement')">Новый договор займа</button>
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
                <input type="text" class="form-control col-sm-12" id="searchUser" placeholder="участок/ФИО/телефон" onKeyup="searchLoans(this.value)">
              </div>
            </form>

            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <table class="table align-items-center table-flush" style="margin-bottom: 20px;">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Договор</th>
					<th scope="col">Дата</th>
                    <th scope="col">Участок(ФИО)</th>
                    <th scope="col">Сумма займа</th>
					<th scope="col">Возвращено</th>
                    <th scope="col">Комментарий</th>                    
                  </tr>
                </thead>
                <tbody class="list" id="loansResult"></tbody>
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
  function addLoan() {
    $(".forcheck").removeClass( "is-invalid" );
    formData = $("#add_loan").serialize();
    $.ajax({
      type: "POST",
      url: "/pages/cab/ajax/add_loan.php",
      data: formData,
      success: onAjaxSuccess
    });
    function onAjaxSuccess(data)
    {
      console.log(data);
      response = JSON.parse(data);
      /*Swal.fire({
        icon: response.status,
        text: response.text
      }).then((result) => {
          if (typeof(response.redirect) != "undefined" && response.redirect !== null) {
            location.href = response.redirect;
          }
      })*/
	  
	  alert(response.text);
	  if (typeof(response.redirect) != "undefined" && response.redirect !== null) {
            location.href = response.redirect;
          }
	  
      if (response.status == 'error') {
        $("#"+response.error_input).addClass( "is-invalid" );
      } else {
        $('#templateModal').modal('hide');
      }
    }
  }
  

  function searchLoans(val) {
    //console.log(val);
    $.post(
        "../pages/ajax/searchLoans.php",
        {
            string: val,
        },
        onAjaxSuccess
    );

    function onAjaxSuccess(data) {
      document.getElementById('loansResult').innerHTML = data;
    }
  }

  window.onload = function() {
    searchLoans('');
  }
</script>
