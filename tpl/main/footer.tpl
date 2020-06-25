  </div>
  <!-- /.container -->

  <!-- Footer -->
  <footer class="py-5 bg-dark">
    <div class="container">
      <p class="m-0 text-center text-white">Copyright &copy; <?=$core->cfgRead('siteName')?> <?=date("Y")?>. <a href="changelog" title="Посмотреть список изменений">Версия <?=$core->cfgRead('version')?><a></p>
    </div>
    <!-- /.container -->
  </footer>

  <!-- Bootstrap core JavaScript -->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="js/lightzoom/style.css" type="text/css">
  <script type="text/javascript" src="js/lightzoom/lightzoom.js"></script>
  <script type="text/javascript">jQuery('.lightzoom').lightzoom({speed: 400, viewTitle: true, isOverlayClickClosing: true, isWindowClickClosing: true});</script>
</body>

</html>
