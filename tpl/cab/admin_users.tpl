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
				  <a class="btn btn-primary" onClick="printData(); return false;" style="color:#fff;"><i class="fa fa-print" aria-hidden="true"></i></a>
				  <a class="btn btn-primary" id="exportcsv" onClick="exportCSV(); return false;" style="color:#fff;" ><i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
                </div>
              </div>
			  <div class="row" style="padding-top: 5px;">
				<div class="col-md-12 text-right">
                  <a href="#" onClick="loadModal('modal_upload_indications'); return false;" class="btn btn-primary">Загрузка показаний</a>
				</div>
			  </div>
            </p>
            <form class="form-inline">
              <div class="form-group mb-2 col-sm-8">
                <label for="searchUser" class="sr-only"></label>
                <input type="text" class="form-control col-sm-12" id="searchUser" placeholder="участок/ФИО/телефон" onKeyup="searchUsers()">
              </div>
              <div class="form-group mb-2 col-sm-2">
                <label for="searchUser" class="sr-only">Сортировка</label>
                <select class="form-control col-sm-12" id="sortUser" onChange="searchUsers()">
                  
                  <option value="area" selected>Номер участка</option>
                  <option value="name">ФИО</option>
                  <option value="balance">Баланс</option>
                </select>
              </div>
			  <div class="form-group mb-2 col-sm-2">
                <label for="searchUser" class="sr-only">Баланс</label>
                <select class="form-control col-sm-12" id="balanceType" onChange="searchUsers()">                  
                  <option value="1" selected>Электричество</option>
                  <option value="2">Членские</option>
                  <option value="3">Целевые</option>
                </select>
              </div>
            </form>
			
			

            <div class="d-flex flex-wrap justify-content-between justify-content-md-center justify-content-xl-between align-items-center">
              <table class="table align-items-center table-flush dataTable" id="for-print">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">Участок</th>
                    <th scope="col">ФИО</th>
                    <th scope="col">Телефон</th>
                    <th scope="col">Договора</th>
                    <th scope="col">Счетчики</th>
					<th scope="col">Модем</th>
                    <th scope="col">
						Баланс<br>
						<span id="balance_name"></span>
					</th>
					<th scope="col">Показания</th>
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
	
  function searchUsers() {
    val = document.getElementById('searchUser').value;
    sort = document.getElementById('sortUser').value;
	btype = document.getElementById('balanceType').value;
	bname = $( "#balanceType option:selected" ).text();
    $.post(
        "../pages/ajax/searchUsers.php",
        {
          string: val,
          sorting: sort,
		  balance: btype,
        },
        onAjaxSuccess
    );

    function onAjaxSuccess(data) {
      //console.log(data);
      document.getElementById('usersResult').innerHTML = data;
	  $('#balance_name').html(bname);
    }
  }

  window.onload = function() {
    searchUsers('');
  }
</script>
