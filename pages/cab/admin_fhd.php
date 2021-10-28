<?php



if (isset($form['fhd_delete_id'])) {
  $db->query("DELETE FROM reports_fhd WHERE id = ?i", $form['fhd_delete_id']);
  $deleted = 1;
}

if (isset($form['fhd_upload'])) {
  //var_dump($_FILES);

  $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
  $file_name = explode('.', $_FILES["fhd_file"]['name']);
  //var_dump(end($file_name));
  $rand_name = substr(str_shuffle($permitted_chars), 0, 10);

  $uploaddir = $_SERVER['DOCUMENT_ROOT'].'/uploads/';
  $uploadfile = $uploaddir . $rand_name.'.'.end($file_name);

  if (move_uploaded_file($_FILES["fhd_file"]['tmp_name'], $uploadfile)) {

      $insert = [
        'name' => $form['fhd_name'],
        'date' => $form['fhd_date'],
        'path' => 'uploads/'.$rand_name.'.'.end($file_name)
      ];

      $db->query("INSERT INTO reports_fhd SET ?u", $insert);

      //echo "Файл корректен и был успешно загружен.\n ".$uploadfile;
      /*if ($form['uploadActDateFrom'] == '') $form['uploadActDateFrom'] = '1970-01-01';
      if (!isset($form['uploadActVisible'])) $form['uploadActVisible'] = 0;
      $insert = [
        'user' => $form['uploadActUser'],
        'date_start' => $form['uploadActDateFrom'],
        'date_end' => $form['uploadActDateTo'],
        'comment' => $form['uploadActComment'],
        'path' => 'uploads/'.$rand_name.'.'.$file_name[1],
        'type' => $form['uploadActType'],
        'visible' => $form['uploadActVisible']
      ];
      $db->query("INSERT INTO acts SET ?u", $insert);*/

  }
}

if (isset($_COOKIE["admin_fhd"])) {
  $year = $_COOKIE["admin_fhd"];
} else {
  $year = date("Y");
}


include('tpl/cab/admin_fhd.tpl');
