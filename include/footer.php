<?php include_once "changelog.php"; ?>

<nav class="navbar-fixed-bottom" style="background: #ddd;">
	<div class="navbar-inner">
		<div class="container" style="padding: 20px;">
			<div class="col-md-4">
				Система управления СНТ "Двуречье" <?php echo date("Y"); ?><br>&copy; Все права защищены. 
			</div>
			<div class="col-md-4" style="text-align: center;">
				<a href="#changelog" data-toggle="modal">Версия <?php echo $version; ?></a>
			</div>
			<div class="col-md-4" style="text-align: right;">
				<!-- Yandex.Metrika informer -->
				<a href="https://metrika.yandex.ru/stat/?id=46189488&amp;from=informer"
				target="_blank" rel="nofollow"><img src="https://informer.yandex.ru/informer/46189488/3_0_FFFFFFFF_EFEFEFFF_0_pageviews"
				style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" class="ym-advanced-informer" data-cid="46189488" data-lang="ru" /></a>
				<!-- /Yandex.Metrika informer -->

				<!-- Yandex.Metrika counter -->
				<script type="text/javascript" >
					(function (d, w, c) {
						(w[c] = w[c] || []).push(function() {
							try {
								w.yaCounter46189488 = new Ya.Metrika({
									id:46189488,
									clickmap:true,
									trackLinks:true,
									accurateTrackBounce:true,
									webvisor:true,
									trackHash:true
								});
							} catch(e) { }
						});

						var n = d.getElementsByTagName("script")[0],
							s = d.createElement("script"),
							f = function () { n.parentNode.insertBefore(s, n); };
						s.type = "text/javascript";
						s.async = true;
						s.src = "https://mc.yandex.ru/metrika/watch.js";

						if (w.opera == "[object Opera]") {
							d.addEventListener("DOMContentLoaded", f, false);
						} else { f(); }
					})(document, window, "yandex_metrika_callbacks");
				</script>
				<noscript><div><img src="https://mc.yandex.ru/watch/46189488" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
				<!-- /Yandex.Metrika counter -->
			</div>
		</div>
	</div>
</nav>

<!-- HTML-код модального окна -->
<div id="changelog" class="modal fade">
  <div class="modal-dialog">
	<div class="modal-content">
	  <!-- Заголовок модального окна -->
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h4 class="modal-title">История версий</h4>
	  </div>
	  <!-- Основное содержимое модального окна -->
	  <div class="modal-body">
		<?php echo $changelog; ?>
	  </div>
	  <!-- Футер модального окна -->
	  <div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>											
	  </div>
	</div>
  </div>
</div>