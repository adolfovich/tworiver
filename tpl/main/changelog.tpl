<?php
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
