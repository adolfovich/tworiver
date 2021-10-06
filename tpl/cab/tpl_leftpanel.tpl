<nav class="sidebar sidebar-offcanvas" id="sidebar">

  <ul class="nav">
    <li class="nav-item <?php if (!isset($url[1])) echo 'active'?>">
      <a class="nav-link <?php if (!isset($url[1])) echo 'active'?>" href="/cab">
        <i class="fa fa-home menu-icon"></i>
        <span class="menu-title">Главная</span>
      </a>
    </li>
    <li class="nav-item <?php if (isset($url[1]) && $url[1] == 'electricpower') {echo 'active';}?>">
      <a class="nav-link <?php if (isset($url[1]) && $url[1] == 'electricpower') {echo 'active';}?>" href="/cab/electricpower">
        <i class="fa fa-bolt  menu-icon"></i>
        <span class="menu-title">Энергопотребление</span>
      </a>
    </li>

    <li class="nav-item <?php if (isset($url[1]) && $url[1] == 'membership') {echo 'active';}?>">
      <a class="nav-link <?php if (isset($url[1]) && $url[1] == 'membership') {echo 'active';}?>" href="/cab/membership">
        <i class="fa fa-user menu-icon"></i>
        <span class="menu-title">Членские взносы</span>
      </a>
    </li>
    <li class="nav-item <?php if (isset($url[1]) && $url[1] == 'target') {echo 'active';}?>">
      <a class="nav-link" href="/cab/target">
        <i class="fa fa-globe menu-icon"></i>
        <span class="menu-title">Целевые взносы</span>
      </a>
    </li>
    <li class="nav-item <?php if (isset($url[1]) && $url[1] == 'reports') {echo 'active';}?>">
      <a class="nav-link" href="/cab/reports">
        <i class="fa fa-file menu-icon"></i>
        <span class="menu-title">Отчеты</span>
      </a>
    </li>
    <li class="nav-item ">
      <a class="nav-link" href="/">
        <i class="fa fa-globe menu-icon"></i>
        <span class="menu-title">Вернуться на сайт</span>
      </a>
    </li>

    <?php if ($user_data['is_admin']) { ?>
    <li class="nav-item <?php if (isset($url[1]) && $url[1] == 'admin') {echo 'active';}?>">
      <a class="nav-link" href="/cab/admin">
        <i class="fa fa-user-secret  menu-icon"></i>
        <span class="menu-title">Админ панель</span>
      </a>
      <ul class="nav" style="margin-top: 0;">
        <li class="nav-item <?php if (isset($url[1]) && $url[1] == 'admin_users') {echo 'active';}?>">
          <a class="nav-link" href="/cab/admin_users">
            <i class="fa fa-users menu-icon"></i>
            <span class="menu-title">Пользователи</span>
          </a>
        </li>
        <li class="nav-item <?php if (isset($url[1]) && $url[1] == 'admin_contributions') {echo 'active';}?>">
          <a class="nav-link" href="/cab/admin_contributions">
            <i class="fa fa-money menu-icon"></i>
            <span class="menu-title">Взносы</span>
          </a>
        </li>

        <li class="nav-item <?php if (isset($url[1]) && $url[1] == 'admin_opjournal') {echo 'active';}?>">
          <a class="nav-link" href="/cab/admin_opjournal">
            <i class="fa fa-book menu-icon"></i>
            <span class="menu-title">Журнал операций</span>
          </a>
        </li>

        <li class="nav-item <?php if (isset($url[1]) && $url[1] == 'admin_opjournal') {echo 'active';}?>">
          <a class="nav-link" href="/cab/admin_rates">
            <i class="fa fa-bar-chart menu-icon"></i>
            <span class="menu-title">Тарифы</span>
          </a>
        </li>


        <li class="nav-item <?php if (isset($url[1]) && $url[1] == 'admin_settings') {echo 'active';}?>">
          <a class="nav-link" href="/cab/admin_settings">
            <i class="fa fa-cog menu-icon"></i>
            <span class="menu-title">Настройки</span>
          </a>
        </li>
      </ul>
    </li>
    <?php } ?>
  </ul>
</nav>
