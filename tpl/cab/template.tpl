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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
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

  function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

  function payElectricOnline() {
    var payAmount = document.getElementById("electricAmount").value;

    $.post("/pages/cab/ajax/payElectricOnline.php", {user: '<?=$user_data["id"]?>', amount: payAmount}, onAjaxSuccess);
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

  function payMembershipOnline() {
    var payAmount = document.getElementById("electricAmount").value;

    $.post("/pages/cab/ajax/payMembershipOnline.php", {user: '<?=$user_data["id"]?>', amount: payAmount}, onAjaxSuccess);
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

    function loadModal(page, params = '') {

      if (params.length > 0) {
        params = '?'+params;
      }

      $.post("/pages/cab/ajax/"+page+".php"+params, {user: '<?=$user_data["id"]?>'}, onAjaxSuccess);
      function onAjaxSuccess(data)
      {
        //console.log(data);
        response = JSON.parse(data);
        document.getElementById('templateModalHeader').innerHTML = response.header;
        document.getElementById('templateModalBody').innerHTML = response.html;
        $('#templateModal').modal('show');
        //eval(response.script);
      }
    }

    function deleteIndications(counter) {
      date_from = document.getElementById('date_from').value;

      $.post("/pages/cab/ajax/delete_indications.php", {counter: counter, date_from: date_from}, onAjaxSuccess);
      function onAjaxSuccess(data)
      {
        response = JSON.parse(data);

        Swal.fire({
          icon: response.status,
          text: response.text
        })

        $('#templateModal').modal('hide');
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
        Swal.fire({
          icon: response.status,
          text: response.text
        }).then((result) => {
            if (typeof(response.redirect) != "undefined" && response.redirect !== null) {
              location.href = response.redirect;
            }
        })
        if (response.status = 'error') {
          $("#"+response.error_input).addClass( "is-invalid" );
        } else {
          $('#templateModal').modal('hide');
        }
      }

    }

    /*function disableCounter() {
      $(".forcheck").removeClass( "is-invalid" );
      formData = $("#add_counter").serialize();

      console.log(formData);
    }*/

    $(document).on('click','body',function(){
      var c_user = $.cookie("user");
      var inFifteenMinutes = new Date(new Date().getTime() + 15 * 60 * 1000);
      $.cookie("user", c_user, { path: '/cab', expires: inFifteenMinutes });
      $.cookie("user", c_user, { path: '/', expires: inFifteenMinutes });
    });


  </script>

  <script>
  <?php if (isset($swal_message)) {?>
    Swal.fire({
      icon: '<?=$swal_message["type"]?>',
      text: '<?=$swal_message["text"]?>'
    })
    <?php if (isset($swal_message["redirect"])) {?>
      .then((result) => {
        if (result.value) {
          location.href = '<?=$swal_message["redirect"]?>';
        }
      })
    <?php } ?>
  <?php } ?>



  </script>
</body>

</html>