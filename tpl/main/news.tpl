<?php
foreach ($result_news as $news) {
  $time = strtotime($news['date_crate']);
  $month = $month_name[ date( 'n',$time ) ];
  $day   = date( 'j',$time );
  $year  = date( 'Y',$time );
  $news_date = "[$day $month $year]";
  if ($news['important'] == 1) {
    $news_class = "alert alert-danger";
  }
  else {
    $news_class = "";
  }
  ?>
  <hr>
  <!-- Title -->
  <h1 class="mt-4 <?=$news_class?>"><?=$news['header']?></h1>
  <!-- Date/Time -->
  <p><?=$news_date?></p>
  <hr>
  <!-- Preview Image -->
  <?php if ($news['img']) { ?>
    <a href="data:image/png;base64,<?=base64_encode($news['img'])?>" class="lightzoom">
      <img class="img-fluid rounded" style="width: 500px; float:left; margin-right: 20px; margin-bottom: 20px;" src="data:image/png;base64,<?=base64_encode($news['img'])?>">
    </a>
  <?php } ?>
  <p><?=$news['text']?></p>
  <?php
}
?>
