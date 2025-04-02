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
            <p class="card-title text-md-center text-xl-left">Договор займа №<?=$loan['agreement_num']?></p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
				<h4 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" >Сумма по договору <?=number_format($loan['amount'], 2, '.', ' ')?> р.</h4>
				<br>
				<br>
				<h4 class="mb-0 mb-md-2 mb-xl-0 order-md-1 order-xl-0" >Остаток по договору <?=number_format($loan_balance, 2, '.', ' ')?> р.</h4>
              
            </div>
          </div>
        </div>
      </div>
	  
	  <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <p class="card-title text-md-center text-xl-left"></p>
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              
			  <?php if ($loan_balance > 0) { ?>
				<button type="button" class="btn btn-primary" onClick="loadModal('modal_add_loan_payout', 'load_id=<?=$loan['id']?>')">Добавить выплату</button>
			  <?php } else { ?>
				<button type="button" class="btn btn-primary" disabled>Добавить выплату</button><br>
				<button type="button" class="btn btn-success">Займ выплачен</button>
			  <?php }?>
			  
			  <!--button type="button" class="btn btn-primary" onClick="">Закрыть договор</button-->
            </div>
          </div>
        </div>
      </div>
	  
    </div>


    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card border-bottom-0">
          <div class="card-body pb-0">
            <p class="card-title">Список выплат</p>
                        
            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <table class="table align-items-center table-flush" style="margin-bottom: 20px;">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">ID</th>
					<th scope="col">Дата</th>
					<th scope="col">Выплата</th>
                    <th scope="col"></th>                    
                  </tr>
                </thead>
                <tbody class="list" id="loansResult">
					<?php 
						foreach ($loan_payments as $loan_payment) {
							echo "<tr>";
								echo "<td>".$loan_payment['id']."</td>";
								echo "<td>".date("d.m.Y", strtotime($loan_payment['payment_date']))."</td>";
								echo "<td>".$loan_payment['payment_amount']."р.</td>";
								echo '<td><a href="#" onclick="delLoanPayment('.$loan_payment['id'].'); return false;" style="color: red; font-size: 20px;"><i class="fa fa-trash" aria-hidden="true"></i></a></td>';
							echo "</tr>";
						}
					?>
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


<script>
  function addLoanPayout() {
    $(".forcheck").removeClass( "is-invalid" );
    formData = $("#add_loan_payout").serialize();
    $.ajax({
      type: "POST",
      url: "/pages/cab/ajax/add_loan_payout.php",
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
  

  
</script>
