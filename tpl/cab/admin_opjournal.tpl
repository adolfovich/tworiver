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
                <input name="start_date" type="date" class="form-control" id="start_date" placeholder="Дата начала" value="<?= date("Y-m")?>-01">
              </div>
              <div class="form-group mx-sm-1 mb-1" style="max-width: 25px;">
                <label for="number" class="sr-only">ПО</label>
                <input type="text" readonly class="form-control-plaintext" value="ПО">
              </div>
              <div class="form-group mx-sm-3 mb-2">
                <label for="end_date" class="sr-only">Дата окончания</label>
                <input name="end_date" type="date" class="form-control" id="end_date" placeholder="Дата окончания" value="<?= date("Y-m-d")?>">
              </div>

              <div class="form-group mx-sm-3 mb-2">
                <label for="comment" class="sr-only">Комментарий</label>
                <input name="comment" type="text" class="form-control" id="comment" placeholder="Комментарий">
              </div>

              <button type="submit" class="btn btn-primary mb-2" onClick="loadAdminJournal(); return false;" id="searchButton"><i class="fa fa-search" aria-hidden="true"></i></button>
              &nbsp;
              <a class="btn btn-primary mb-2" onClick="printData(); return false;" style="color:#fff;"><i class="fa fa-print" aria-hidden="true"></i></a>
              &nbsp;
              <a class="btn btn-primary mb-2" id="exportcsv" onClick="exportCSV(); return false;" style="color:#fff;" ><i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
              &nbsp;
              <button type="submit" class="btn btn-success mb-2" onClick="loadModal('modal_add_operation', 'operation_type='+document.getElementById('optype').value+'&area_number='+document.getElementById('number').value); return false;">Добавить операцию</button>
            </form>

            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <table class="table align-items-center table-flush dataTable" id="for-print">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">ID операции</th>
                    <th scope="col">Дата</th>
                    <th scope="col">Участок</th>
                    <th scope="col">Тип баланса</th>
                    <th scope="col">Тип операции</th>
                    <th scope="col">Сумма</th>
                    <th scope="col">Комментарий</th>
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

function exportCSV() {
  var titles = [];
  var data = [];

  /*
   * Get the table headers, this will be CSV headers
   * The count of headers will be CSV string separator
   */
  $('.dataTable th').each(function() {
    titles.push($(this).text());
  });

  /*
   * Get the actual data, this will contain all the data, in 1 array
   */
  $('.dataTable td').each(function() {
    data.push($(this).text());
  });

  /*
   * Convert our data to CSV string
   */
  var CSVString = prepCSVRow(titles, titles.length, '');
  CSVString = prepCSVRow(data, titles.length, CSVString);

  /*
   * Make CSV downloadable
   */
  var downloadLink = document.createElement("a");
  var blob = new Blob(["\ufeff", CSVString]);
  var url = URL.createObjectURL(blob);
  downloadLink.href = url;
  downloadLink.download = "data.csv";

  /*
   * Actually download CSV
   */
  document.body.appendChild(downloadLink);
  downloadLink.click();
  document.body.removeChild(downloadLink);
};

   /*
* Convert data array to CSV string
* @param arr {Array} - the actual data
* @param columnCount {Number} - the amount to split the data into columns
* @param initial {String} - initial string to append to CSV string
* return {String} - ready CSV string
*/
function prepCSVRow(arr, columnCount, initial) {
  var row = ''; // this will hold data
  var delimeter = ';'; // data slice separator, in excel it's `;`, in usual CSv it's `,`
  var newLine = '\r\n'; // newline separator for CSV row

  /*
   * Convert [1,2,3,4] into [[1,2], [3,4]] while count is 2
   * @param _arr {Array} - the actual array to split
   * @param _count {Number} - the amount to split
   * return {Array} - splitted array
   */
  function splitArray(_arr, _count) {
    var splitted = [];
    var result = [];
    _arr.forEach(function(item, idx) {
      if ((idx + 1) % _count === 0) {
        splitted.push(item);
        result.push(splitted);
        splitted = [];
      } else {
        splitted.push(item);
      }
    });
    return result;
  }
  var plainArr = splitArray(arr, columnCount);
  // don't know how to explain this
  // you just have to like follow the code
  // and you understand, it's pretty simple
  // it converts `['a', 'b', 'c']` to `a,b,c` string
  plainArr.forEach(function(arrItem) {
    arrItem.forEach(function(item, idx) {
      row += item + ((idx + 1) === arrItem.length ? '' : delimeter);
    });
    row += newLine;
  });
  return initial + row;
}
















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
function setLoadOnButton(button) {
  button.prop('disabled', true);
  butonOLdImage = button.html();
  button.html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>');
}
function unSetLoadOnButton(button) {
  button.prop('disabled', false);
  butonOLdImage = button.html();
  button.html('<i class="fa fa-search" aria-hidden="true"></i>');
}
  function loadAdminJournal()
  {
    setLoadOnButton($('#searchButton'));
    formData = $('#opJournalForm').serialize()
    //console.log(formData);

    $.post(
        "../pages/cab/ajax/getOperationsJournal.php",
        formData,
        onAjaxSuccess
    );

    function onAjaxSuccess(data) {
      unSetLoadOnButton($('#searchButton'));
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
