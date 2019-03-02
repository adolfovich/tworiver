<nav class="navbar navbar-default" role="navigation" style="position: fixed; z-index: 999; width: 100%; top: 0;">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">СНТ "Двуречье"</a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<?php
					if (basename($_SERVER['SCRIPT_NAME']) == 'index.php') {
						echo '<li class="active"><a href="/">Главная</a></li>';
					}
					else {
						echo '<li><a href="/">Главная</a></li>';
					}
					if (basename($_SERVER['SCRIPT_NAME']) == 'news.php') {
						echo '<li class="active"><a href="/news.php">Новости</a></li>';
					}
					else {
						echo '<li><a href="/news.php">Новости</a></li>';
					}
					if (basename($_SERVER['SCRIPT_NAME']) == 'contacts.php') {
						echo '<li class="active"><a href="contacts.php">Контакты</a></li>';
					}
					else {
						echo '<li><a href="contacts.php">Контакты</a></li>';
					}

					if ($is_auth == 1) {
						if (basename($_SERVER['SCRIPT_NAME']) == 'reports.php') {
							echo '<li class="active"><a href="reports.php">ФХД</a></li>';
						}
						else {
							echo '<li><a href="reports.php">ФХД</a></li>';
						}
						if (basename($_SERVER['SCRIPT_NAME']) == 'user.php') {
							echo '<li class="active"><a href="user.php">Личный кабинет</a></li>';
						}
						else {
							echo '<li><a href="user.php">Личный кабинет</a></li>';
						}
						if ($_COOKIE["user_is_admin"] == 1) {
							if (basename($_SERVER['SCRIPT_NAME']) == 'admin.php' || basename($_SERVER['SCRIPT_NAME']) == 'admin_users.php' || basename($_SERVER['SCRIPT_NAME']) == 'admin_indications.php'  || basename($_SERVER['SCRIPT_NAME']) == 'admin_payments.php') {
								echo '<li class="active"><a href="admin.php">Админ. панель</a></li>';
							}
							else {
								echo '<li><a href="admin.php">Админ. панель</a></li>';
							}

						}
					}
				?>



			</ul>



			<ul class="nav navbar-nav navbar-right">
				<?php
				if ($is_auth == 1) {
				?>
				<form method="POST" class="navbar-form navbar-right" role="form">
					<div class="form-group">
						<?php echo $_COOKIE["user_name"]; ?>
					</div>
					<div class="form-group">
						<input name="logout" type="hidden" value="1">
					</div>
					<button type="submit" class="btn btn-default navbar-btn"><i class="fa fa-sign-out" aria-hidden="true"></i></button>
				</form>
				<?php
				}
				else {
				?>
				<form method="POST" class="navbar-form navbar-right" role="form">
					<div class="form-group">
						<input name="auth_login" type="text" placeholder="Email" class="form-control">
					</div>
					<div class="form-group">
						<input name="auth_pass" type="password" placeholder="Пароль" class="form-control">
						<input name="auth" type="hidden" value="1">
					</div>
					<button type="submit" class="btn btn-default navbar-btn">Войти в Личный кабинет</button>
				 </form>
				<?php } ?>
			</ul>
		</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>
