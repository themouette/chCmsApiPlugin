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
          <li><a href="<?php echo url_for('@api_methods') ?>">Methods</a></li>
          <li><a href="<?php echo url_for('@api_formatters') ?>">Object Types</a></li>
          <li><a href="<?php echo url_for('@api_sandbox') ?>">Sandbox</a></li>
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="container-fluid">
  <?php if ($sf_user->hasFlash('notice')): ?>
    <div class="alert alert-info"><?php echo $sf_user->getFlash('notice') ?></div>
  <?php endif ?>

  <?php if ($sf_user->hasFlash('error')): ?>
    <div class="alert alert-error"><?php echo $sf_user->getFlash('error') ?></div>
  <?php endif ?>

  <?php echo $sf_content ?>
</div> <!-- /container -->
</body>
</html>
