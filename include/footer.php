<?php include_once "changelog.php"; ?>

<nav class="navbar-fixed-bottom" style="background: #ddd;">
	<div class="navbar-inner">
		<div class="container" style="padding: 20px;">Система управления СНТ "Двуречье" <?php echo date("Y"); ?> &copy; Все права защищены. <a href="#changelog" data-toggle="modal">Версия <?php echo $version; ?></a></div>
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