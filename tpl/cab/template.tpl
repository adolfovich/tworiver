<?php
$currentmomth = date("m");
$currentyear = date("Y");
?>

<!DOCTYPE html>
<html lang="ru">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Личный кабинет</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="/vendors/base/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="/css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />

  <link rel="apple-touch-icon" sizes="57x57" href="/img/favicon/apple-icon-57x57.png">
  <link rel="apple-touch-icon" sizes="60x60" href="/img/favicon/apple-icon-60x60.png">
  <link rel="apple-touch-icon" sizes="72x72" href="/img/favicon/apple-icon-72x72.png">
  <link rel="apple-touch-icon" sizes="76x76" href="/img/favicon/apple-icon-76x76.png">
  <link rel="apple-touch-icon" sizes="114x114" href="/img/favicon/apple-icon-114x114.png">
  <link rel="apple-touch-icon" sizes="120x120" href="/img/favicon/apple-icon-120x120.png">
  <link rel="apple-touch-icon" sizes="144x144" href="/img/favicon/apple-icon-144x144.png">
  <link rel="apple-touch-icon" sizes="152x152" href="/img/favicon/apple-icon-152x152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="/img/favicon/apple-icon-180x180.png">
  <link rel="icon" type="image/png" sizes="192x192"  href="/img/favicon/android-icon-192x192.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="96x96" href="/img/favicon/favicon-96x96.png">
  <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon/favicon-16x16.png">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="msapplication-TileImage" content="/img/favicon/ms-icon-144x144.png">
  <meta name="theme-color" content="#ffffff">
  
  <script src="https://use.fontawesome.com/8077ddb7e0.js"></script>

  <style>
  
  .switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
  }

  .switch input {display:none;}

  .slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
  }

  .slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
  }

  input:checked + .slider {
  background-color: #2196F3;
  }

  input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
  }

  input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
  }

  /* Rounded sliders */
  .slider.round {
  border-radius: 34px;
  }

  .slider.round:before {
  border-radius: 50%;
  }
  </style>

</head>
<body>

  <div class="modal fade bd-example-modal-lg" id="templateModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title h4" id="templateModalHeader">Modal Header</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body" id="templateModalBody">

        </div>
      </div>
    </div>
  </div>
  
  <div class="modal fade bd-example-modal-lg" id="modalPay" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title h4" id="modalPayHeader">Modal Header</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body" id="modalPayBody">

        </div>
      </div>
    </div>
  </div>

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


          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
        </div>
      </div>
    </div>
  </div>

  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <?php include ('tpl/cab/tpl_header.tpl'); ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      <?php include ('tpl/cab/tpl_leftpanel.tpl'); ?>
      <!-- partial -->
      <?php include ($content); ?>
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="/vendors/base/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <script src="/vendors/chart.js/Chart.min.js"></script>
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="/js/off-canvas.js"></script>

  <script src="/js/hoverable-collapse.js"></script>

  <script src="/js/template.js"></script>
  <script src="/js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="/js/dashboard.js"></script>
  <!-- End custom js for this page-->
  <script src="/js/jquery.cookie.js"></script>

  <?php if (isset($_GET['payment']) && $_GET['payment'] == 'success') {
    $swal_message["type"] = 'success';
    $swal_message["text"] = 'Оплата успешно проведена';
    $swal_message["redirect"] = 'electricpower';
  } ?>

  <script>
  
  <?php if (isset($_GET['orderId'])) { ?>
	openModalPay(header = '', text = 'Оплата произведена. Зачисление произойдет в течение нескольких минут.')
	  
  <?php } ?>
  
	function setLocation(curLoc){
		try {
		  history.pushState(null, null, curLoc);
		  return;
		} catch(e) {}
		location.hash = '#' + curLoc;
	}

  function printAct(type)
  {
    dateFrom = document.getElementById('actDateFrom').value;
    dateTo = document.getElementById('actDateTo').value;

    console.log(dateFrom);

    if (new Date(dateFrom) > new Date('2020-06-24')) {
      url = '/pages/cab/forms/?act=electric&datefrom='+dateFrom+'&dateto='+dateTo;

      if (type == 'electric') {
        counter = document.getElementById('actCounter').value;
        url = url + '&counter='+counter;
      }

      window.open(url, '_blank');
      $('#templateModal').modal('hide');
    } else {
      Swal.fire({
        icon: 'error',
        text: 'Дата начала должна быть больше 24.06.2020'
      })
    }


  }

  function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}


  $('#upladIndications').submit(function(){
      event.preventDefault();
      $('#upladIndicationsButton').attr('disabled', 'disabled');
      $('#upladIndicationsButton').attr('value', 'Идет загрузка ...');

      if (window.FormData === undefined) {
          alert('В вашем браузере FormData не поддерживается')
      } else {
          var formData = new FormData();
          formData.append('startDate', $("#startDate").val());
          formData.append('endDate', $("#endDate").val());
          formData.append('csvFile', $("#csvFile")[0].files[0]);

          $.ajax({
              type: "POST",
              url: '/pages/cab/ajax/uploadIndications.php',
              cache: false,
              contentType: false,
              processData: false,
              data: formData,
              dataType : 'html',
              success: function(data){
                  console.log(data);
                  if (IsJsonString(data)) {
                      response = JSON.parse(data)
                      if (response.status === 'OK') {
                          $('#uploadIndicationsMsg').html('<p style="color:green;">'+response.msg+'</p>');
                          $("#csvFile").val('');
                      } else {
                          $('#uploadIndicationsMsg').html('<p style="color:red;">Ошибка: '+response.error+'</p>');
                      }
                  } else {
                      $('#uploadIndicationsMsg').html('<p style="color:red;">Ошибка: Неверный JSON</p>');
                  }
                  $('#upladIndicationsButton').removeAttr("disabled");
                  $('#upladIndicationsButton').attr('value', 'Загрузить');
              }
          });
      }
  });


  function payElectricOnline() {
    var payAmount = document.getElementById("electricAmount").value;

    $.post("/pages/cab/ajax/payElectricOnline.php", {user: '<?=$user_data["id"]?>', amount: payAmount}, onAjaxSuccess);
    function onAjaxSuccess(data)
    {
		console.log(data);
      if (IsJsonString(data)) {
        response = JSON.parse(data)
        if (response.status == 'OK') {
          location.href = response.url;
        } else {
          Swal.fire({
            icon: 'error',
            text: response.error
          })
        }
      } else {
        Swal.fire({
          icon: 'error',
          text: 'Ошибка JSON'
        })
      }

    }
  }

  function payMembershipOnline() {
    var payAmount = document.getElementById("electricAmount").value;

    $.post("/pages/cab/ajax/payMembershipOnline.php", {user: '<?=$user_data["id"]?>', amount: payAmount}, onAjaxSuccess);
    function onAjaxSuccess(data)
    {
		console.log(data);
      if (IsJsonString(data)) {
		  
        response = JSON.parse(data)
        if (response.status == 'OK') {
          location.href = response.url;
        } else {
          Swal.fire({
            icon: 'error',
            text: response.error
          })
        }
      } else {
        Swal.fire({
          icon: 'error',
          text: 'Ошибка JSON'
        })
      }

    }
  }

  function payTargetOnline() {
    var payAmount = document.getElementById("electricAmount").value;

    $.post("/pages/cab/ajax/payTargetOnline.php", {user: '<?=$user_data["id"]?>', amount: payAmount}, onAjaxSuccess);
    function onAjaxSuccess(data)
    {
      if (IsJsonString(data)) {
        response = JSON.parse(data)
        if (response.status == 'OK') {
          location.href = response.url;
        } else {
          Swal.fire({
            icon: 'error',
            text: response.error
          })
        }
      } else {
        Swal.fire({
          icon: 'error',
          text: 'Ошибка JSON'
        })
      }

    }
  }

  $(document).ready(function() {
    $('[name=numbers]').bind("change keyup input click", function() {
      if (this.value.match(/[^0-9\.]/g)) {
      this.value = this.value.replace(/[^0-9\.]/g, '');
      }
    });
  });

    
	function openModal(header, text) {
      
        document.getElementById('templateModalHeader').innerHTML = header;
        document.getElementById('templateModalBody').innerHTML = text;
		 $('#templateModal').modal('show');
        
    }
	
	
	function openModalPay(header, text) {      
        document.getElementById('modalPayHeader').innerHTML = header;
        document.getElementById('modalPayBody').innerHTML = text;
		 $('#modalPay').modal('show');        
    }
	
	$(document).ready(function(){

		$("#modalPay").on('hide.bs.modal', function () {
		   var url=document.location.href;
			var mainurl=url.split("?");
			setLocation(mainurl[0]);
		});

	});
	
	function loadModal(page, params = '') {

      if (params.length > 0) {
        params = '?'+params;
      }

      $.post("/pages/cab/ajax/"+page+".php"+params, {user: '<?=$user_data["id"]?>'}, onAjaxSuccess);
      function onAjaxSuccess(data)
      {
        response = JSON.parse(data);
        document.getElementById('templateModalHeader').innerHTML = response.header;
        document.getElementById('templateModalBody').innerHTML = response.html;
        $('#templateModal').modal('show');
      }
    }

    function uploadAct() {
      $(".forcheck").removeClass( "is-invalid" );

      files = document.getElementById('uploadActFile').files;

      var data1 = new FormData();
      $.each( files, function( key, value ){
          data1.append( key, value );
      });

      data1.append( 'uploadActDateFrom', document.getElementById('uploadActDateFrom').value );
      data1.append( 'uploadActDateTo', document.getElementById('uploadActDateTo').value );
      data1.append( 'uploadActType', document.getElementById('uploadActType').value );
      data1.append( 'uploadActUser', document.getElementById('uploadActUser').value );
      data1.append( 'uploadActComment', document.getElementById('uploadActComment').value );
      data1.append( 'uploadActVisible', document.getElementById('uploadActVisible').value );

      $.ajax({
        type: "POST",
        url: "/pages/cab/ajax/upload_act.php",
        data: data1,
        success: onAjaxSuccess,
        processData: false, // Не обрабатываем файлы (Don't process the files)
        contentType: false, // Так jQuery скажет серверу что это строковой запрос
      });
      function onAjaxSuccess(data)
      {
        console.log(data);
        response = JSON.parse(data);
        openModal(header = response.status, text = response.text).then((result) => {
            if (typeof(response.redirect) != "undefined" && response.redirect !== null) {
              location.href = response.redirect;
            }
        })
        if (response.status == 'error') {
          $("#"+response.error_input).addClass( "is-invalid" );
        } else {
          $('#templateModal').modal('hide');
        }
      }
    }

    function deleteIndications(counter) {
      date_from = document.getElementById('date_from').value;

      $.post("/pages/cab/ajax/delete_indications.php", {counter: counter, date_from: date_from}, onAjaxSuccess);
      function onAjaxSuccess(data)
      {
        response = JSON.parse(data);

		openModal(header = response.status, text = response.text)
		

        $('#templateModal').modal('hide');
      }
    }

    function printActModal() {
      $(".forcheck").removeClass( "is-invalid" );
      formData = $("#print_act").serialize();

      $.ajax({
        type: "POST",
        url: "/pages/cab/ajax/print_act.php",
        data: formData,
        success: onAjaxSuccess
      });

      function onAjaxSuccess(data)
      {
        response = JSON.parse(data);
        console.log(response);

        if (response.status != 'error') {
          window.open(response.link);
        } else (
			openModal(header = response.status, text = response.text).then((result) => {
              if (typeof(response.redirect) != "undefined" && response.redirect !== null) {
                location.href = response.redirect;
              }
          })
        )

        if (response.status == 'error') {
          $("#"+response.error_input).addClass( "is-invalid" );
        } else {
          $('#templateModal').modal('hide');
        }

      }
    }

    function addCounter() {
      $(".forcheck").removeClass( "is-invalid" );
      formData = $("#add_counter").serialize();
      $.ajax({
        type: "POST",
        url: "/pages/cab/ajax/add_counter.php",
        data: formData,
        success: onAjaxSuccess
      });
      function onAjaxSuccess(data)
      {
        response = JSON.parse(data);
        openModal(header = response.status, text = response.text).then((result) => {
            if (typeof(response.redirect) != "undefined" && response.redirect !== null) {
              location.href = response.redirect;
            }
        })
        if (response.status == 'error') {
          $("#"+response.error_input).addClass( "is-invalid" );
        } else {
          $('#templateModal').modal('hide');
        }
      }
    }

    function addOperation() {
      $(".forcheck").removeClass( "is-invalid" );
      formData = $("#add_operation").serialize();
      $.ajax({
        type: "POST",
        url: "/pages/cab/ajax/add_operation.php",
        data: formData,
        success: onAjaxSuccess
      });
      function onAjaxSuccess(data)
      {
        console.log(data);
        response = JSON.parse(data);

        openModal(header = response.status, text = response.text).then((result) => {
            if (typeof(response.redirect) != "undefined" && response.redirect !== null) {
              location.href = response.redirect;
            }
        })
        if (response.status == 'error') {
          $("#"+response.error_input).addClass( "is-invalid" );
        } else {
          $('#templateModal').modal('hide');
        }
      }
    }

    function openIndicationsModal(counterId, titleCounterNum = '', month = '', year = '')
    {
      date = new Date();

      var modalTitle = document.getElementById('indicationsModalTitle');
      var modalBody = document.getElementById('indicationsModalBody');
      if (month == '') {
        month = document.getElementById('indicationsMonth').value;
		
      } else {
        month = date.getMonth();
		month = month + 1;
      }
	  
	  //console.log(month);

      if (year == '') {
        var year = document.getElementById('indicationsYear').value;
      } else {
        year = date.getFullYear();
      }

      titleCounterNum = document.getElementById('indicationsModalCounterNum');


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

    $(document).on('click','body',function(){
      var c_user = $.cookie("user");
      var inFifteenMinutes = new Date(new Date().getTime() + 15 * 60 * 1000);
      $.cookie("user", c_user, { path: '/cab', expires: inFifteenMinutes });
      $.cookie("user", c_user, { path: '/', expires: inFifteenMinutes });
    });

    function changeRate() {
      $(".forcheck").removeClass( "is-invalid" );
      formData = $("#change_rate").serialize();
      $.ajax({
        type: "POST",
        url: "/pages/cab/ajax/change_rate.php",
        data: formData,
        success: onAjaxSuccess
      });
      function onAjaxSuccess(data)
      {
        response = JSON.parse(data);
        openModal(header = response.status, text = response.text).then((result) => {
            if (typeof(response.redirect) != "undefined" && response.redirect !== null) {
              location.href = response.redirect;
            }
        })
        if (response.status == 'error') {
          $("#"+response.error_input).addClass( "is-invalid" );
        } else {
          $('#templateModal').modal('hide');
        }
      }
    }


  </script>  
 
</body>

</html>
