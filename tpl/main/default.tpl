<div class="row">
  <div class="col-lg-8">
    <!-- Title -->
    <h1 class="mt-4">Историческая справка</h1>
    <hr>
    <!-- Preview Image -->
    <img class="img-fluid rounded" src="img/history.jpg" alt="СНТ Двуречье" style="width: 450px; float: left; margin: 0 10px 0 0;">
    <!-- Post Content -->
    <p>Урочище Широкая Балка находится в исторически и географически значимом месте. На территории бывшего античного царства Боспор, существовавшего более 2000 лет назад.</p>
    <p>СНТ «Двуречье» расположенное почти в самом начале Широкой Балки знаменательно историческими памятниками.</p>
    <p>На восточной стороне СНТ расположены развалины некогда богатой усадьбы 19 века на ее территории так же находится античный колодец, который использовался на протяжении 2000 лет и в настоящее время находится в рабочем состоянии. Другой подобный колодец, но гораздо большего диаметра, находится на северо-востоке, ближе к новому кладбищу.</p>
    <p>К западу, также интересное место. По крутой старой дороге можно подняться к карьеру, где добывают строительный камень, который отправляли в Новороссийск, Анапу и Кабардинку. Карьер был открыт в правлении Николая II.</p>
    <p>Если знать дорогу, от карьера можно дойти до родника, где так же была усадьба вдовы офицера Кавказских войн.
И еще много всего происходило на этой благодатной земле в средние века.</p>
  </div>
  <div class="col-md-4">
    <?php
    if ($core->login()) {
    ?>
    <!-- User Widget -->
    <div class="card my-4">
      <h5 class="card-header">Участок №<?=$user_info['uchastok']?></h5>
      <div class="card-body">
        <?php if ($user_info['total_balance'] < 0) $tb_color = 'color: red;';?>
        <p><b>Общий баланс:</b> <span style="<?=$tb_color?>"><?=$user_info['total_balance']?></span></p>

        <?php if ($user_info['balans'] < 0) $eb_color = 'color: red;';?>
        <p><b>Энергопотребление:</b> <span style="<?=$eb_color?>"><?=$user_info['balans']?></span></p>

        <?php if ($user_info['membership_balans'] < 0) $mb_color = 'color: red;';?>
        <p><b>Членские взносы:</b> <span style="<?=$mb_color?>"><?=$user_info['membership_balans']?></span></p>

        <?php if ($user_info['target_balans'] < 0) $tab_color = 'color: red;';?>
        <p><b>Целевые взносы:</b> <span style="<?=$tab_color?>"><?=$user_info['target_balans']?></span></p>

        <a href="cab" class="btn btn-outline-dark btn-block">Перейти в кабинет</a>
      </div>
    </div>
    <?php
    }
    ?>

    <!-- News Widget -->
        <div class="card my-4">
          <h5 class="card-header">Новости</h5>
          <div class="card-body">
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
              if ($news['discussed'] == 1) {
                if ($core->login()) {
                  $result_count_comment = $db->getOne("SELECT COUNT(*) FROM news_comments WHERE news = ".$news['id']." AND is_del = 0");
                  $button = '<a class="btn btn-outline-info" href="news?news='.$news['id'].'#comments"> Обсуждение <span class="badge pull-right" style="margin-top: 3px; ">('.$result_count_comment.')</span></a>';
                }
              }
            ?>
              <div style="padding: 10px; border-radius: 10px;">
                <h4 class="<?=$news_class?>" ><?=$news['header']?></h4>
                <span class="news_date"><?=$news_date?></span>
                <p><?=$news['preview']?></p>
                <a class="btn btn-outline-secondary" href="news?id=<?=$news['id']?>"> Подробнее </a>
                <?php if(isset($button)) echo $button;?>
              </div>
            <?php
            }
            ?>
          </div>
        </div>
  </div>
</div>
