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
            <h4 class="font-weight-bold mb-0">Панель администратора - Журнал операций</h4>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-12 grid-margin stretch-card">
        <div class="card border-bottom-0">
          <div class="card-body pb-0">


            <form class="form-inline" id="opJournalForm">
              <div class="form-group mx-sm-2 mb-1">
                <label for="number" class="sr-only">Участок</label>
                <input name="number" type="text" class="form-control" id="number" placeholder="Участок">
              </div>
              <div class="form-group mx-sm-3 mb-2">
                <label for="optype" class="sr-only">Тип операции</label>
                <select name="optype" id="optype" class="form-control">
                  <option selected disabled>Тип операции</option>
                  <option value="0">Все</option>
                  <?php foreach ($types as $type) { ?>
                    <option value="<?=$type['id']?>"><?=$type['name']?></option>
                  <?php } ?>
                </select>
              </div>

              <div class="form-group mx-sm-3 mb-2">
                <label for="balancetype" class="sr-only">Тип баланса</label>
                <select name="balancetype" id="balancetype" class="form-control">
                  <option selected disabled>Тип баланса</option>
                  <option value="0">Все</option>
                  <option value="1">Электричество</option>
                  <option value="2">Членские взносы</option>
                  <option value="3">Целевые взносы</option>
                </select>
              </div>

              <div class="form-group mx-sm-1 mb-1" style="max-width: 15px;">
                <label for="number" class="sr-only">C</label>
                <input type="text" readonly class="form-control-plaintext" value="С">
              </div>
              <div class="form-group mx-sm-3 mb-2">
                <label for="start_date" class="sr-only">Дата начала</label>
                <input name="start_date" type="date" class="form-control" id="start_date" placeholder="Дата начала">
              </div>
              <div class="form-group mx-sm-1 mb-1" style="max-width: 25px;">
                <label for="number" class="sr-only">ПО</label>
                <input type="text" readonly class="form-control-plaintext" value="ПО">
              </div>
              <div class="form-group mx-sm-3 mb-2">
                <label for="end_date" class="sr-only">Дата окончания</label>
                <input name="end_date" type="date" class="form-control" id="end_date" placeholder="Дата окончания">
              </div>

              <div class="form-group mx-sm-3 mb-2">
                <label for="comment" class="sr-only">Комментарий</label>
                <input name="comment" type="text" class="form-control" id="comment" placeholder="Коментарий">
              </div>

              <button type="submit" class="btn btn-primary mb-2" onClick="loadAdminJournal(); return false;"><i class="fa fa-search" aria-hidden="true"></i></button>
              &nbsp;
              <a class="btn btn-primary mb-2" onClick="printData(); return false;" style="color:#fff;"><i class="fa fa-print" aria-hidden="true"></i></a>
              &nbsp;
              <!--a class="btn btn-primary mb-2" onClick="" style="color:#fff;" ><i class="fa fa-file-excel-o" aria-hidden="true"></i></a-->
              &nbsp;
              <button type="submit" class="btn btn-success mb-2" onClick="loadModal('modal_add_operation', 'operation_type='+document.getElementById('optype').value+'&area_number='+document.getElementById('number').value); return false;">Добавить операцию</button>
            </form>

            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <table class="table align-items-center table-flush" id="for-print">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">ID операции</th>
                    <th scope="col">Дата</th>
                    <th scope="col">Участок</th>
                    <th scope="col">Тип баланса</th>
                    <th scope="col">Тип операции</th>
                    <th scope="col">Сумма</th>
                    <th scope="col">Коментарий</th>
                  </tr>
                </thead>
                <tbody class="list" id="journalResult">
                  <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;"> Выберите параметры </td>
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

<script>

    const tableToPrint = document.getElementById('for-print');

    function printData(tableToPrint) {
        Popup($(tableToPrint).html());
    }

    function Popup(data) {
        const mywindow = window.open('', 'for-print', 'height=600, width=1000');
        // стили таблицы
        mywindow.document.write('<link rel="stylesheet" href="/css/style.css" type="text/css" />');
        mywindow.document.write(tableToPrint.outerHTML);
        mywindow.document.close(); // для IE >= 10
        mywindow.focus();          // для IE >= 10
        mywindow.print();
        mywindow.close();
        return true;
    }

    /*$(document).on('click', '#printTable', function () {
        printData();
        return false;
    });*/

</script>

<script>
  function loadAdminJournal()
  {
    formData = $('#opJournalForm').serialize()
    //console.log(formData);

    $.post(
        "../pages/cab/ajax/getOperationsJournal.php",
        formData,
        onAjaxSuccess
    );

    function onAjaxSuccess(data) {
      //console.log(data);
      response = JSON.parse(data);
      if (response.status == 'error') {
        Swal.fire({
          icon: 'error',
          text: response.text
        })
      } else {
        document.getElementById("journalResult").innerHTML = response.html;
      }
    }
  }
</script>
