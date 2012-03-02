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
      <a class="brand" href="#">API Doc</a>
    </div>
  </div>
</div>

<div class="container-fluid">
  <?php echo $sf_content ?>
</div> <!-- /container -->
</body>
</html>
