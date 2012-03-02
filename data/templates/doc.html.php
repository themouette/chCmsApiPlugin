<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link href="/chCmsApiPlugin/css/bootstrap.min.css" rel="stylesheet">
  <link href="/chCmsApiPlugin/css/bootstrap-responsive.min.css" rel="stylesheet">
  <link href="/chCmsApiPlugin/js/google-code-prettify/prettify.css" rel="stylesheet">
  <title>API Doc</title>
</head>
<body onload="prettyPrint()">

<div class="navbar">
  <div class="navbar-inner">
    <div class="container">
      <a class="brand" href="#">API Doc</a>

      <div class="nav-collapse">
        <ul class="nav">
          <li class="active"><a href="#url">URL</a></li>
          <li><a href="#formats">Formats</a></li>
          <li><a href="#auth">Authentication</a></li>
          <li><a href="#parameters">Parameters</a></li>
          <li><a href="#results">Results</a></li>
        </ul>
      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>

<div class="container-fluid">
  <?php require_once sfConfig::get('app_chCmsApiPlugin_docMethodTemplate'); ?>
</div> <!-- /container -->

<script src="/chCmsApiPlugin/js/jquery.js"></script>
<script src="/chCmsApiPlugin/js/google-code-prettify/prettify.js"></script>
</body>
</html>
