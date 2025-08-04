<?php

$v_array = array_reverse($v_array);



foreach ($v_array as $v) {
    $v = explode(":", $v);
    //var_dump($v);
    $v_log = explode(";", $v[2]);
    ?>
    <div class="media mb-4">
        <div class="media-body">
            <h5 class="mt-0"><span style="font-size: 1.3em;"><?=$v[0]?></span> <span style="font-weight: 100;">(<?=$v[1]?>)</span></h5>
            <?php
            foreach ($v_log as $v2) {
                echo '> '.$v2."<br/>";
            }
            ?>
        </div>
    </div>
    <?php
}

  foreach ($changelog as $log) {
    if ($log['date']) {
      $date = '('.date("d.m.Y", strtotime($log['date'])).')';
    } else {
      $date = '';
    }
?>
<div class="media mb-4">
          <div class="media-body">
            <h5 class="mt-0"><span style="font-size: 1.3em;"><?=$log['version']?></span> <span style="font-weight: 100;"><?=$date?></span></h5>
            <?=$log['description']?>
          </div>
        </div>
<?php
  }
?>
