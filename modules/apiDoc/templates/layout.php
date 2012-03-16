<!DOCTYPE html>
<html>
<head>
  <?php include_title() ?>
  <?php include_http_metas() ?>
  <?php include_metas() ?>
</head>
<body onload="prettyPrint()">

<div class="navbar">
  <div class="navbar-inner">
    <div class="container">
      <span class="brand">API Doc</span>

      <div class="nav-collapse">
        <ul class="nav">
          <li><a href="<?php echo url_for('@apiMethods') ?>">Methods</a></li>
          <li><a href="<?php echo url_for('@apiFormatters') ?>">Formatters</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid">
  <?php echo $sf_content ?>
</div> <!-- /container -->
</body>
</html>
