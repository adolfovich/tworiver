<nav class="navbar navbar-default" role="navigation">
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
				<li><a href="/">Новости</a></li>
				<li><a href="contacts.php">Контакты</a></li>
				
				<?php 
				if ($is_auth == 1) { 
					echo '<li><a href="user.php">Личный кабинет</a></li>';
				
					if ($_COOKIE["user_is_admin"] == 1) {
						echo '<li><a href="admin.php">Админ. панель</a></li>';
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
					<button type="submit" class="btn btn-default navbar-btn">Выход</button>
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