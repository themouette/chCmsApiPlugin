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
  <p class="well">
    <?php echo nl2br($DESCRIPTION) ?>
  </p>


  <h2 id="url">URL</h2>

  <pre>
    <?php echo implode('|', $HTTP_METHODS) ?> <?php echo $FORMAL_URL ?>
  </pre>


  <h2 id="formats">Supported formats</h2>

  <p>
    <?php echo implode(', ', $SUPPORTED_FORMATS) ?>.
    Default is <?php echo $DEFAULT_FORMAT ?>.
  </p>


  <h2 id="auth">Authentication required</h2>

  <p><?php echo $AUTH_REQUIRED ? 'Yes.' : 'No.' ?></p>


  <h2 id="parameters">Query Parameters</h2>

  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>Name</th>
        <th>Value</th>
      </tr>
    </thead>

    <tbody>
      <?php foreach ($PARAMS as $name => $data): ?>
      <tr>
      <th><?php echo $name ?></th>
        <td>
          <ul>
            <?php foreach ($data as $option => $value): ?>
              <li><strong><?php echo $option ?></strong>: <?php echo $value ?></li>
            <?php endforeach; ?>
          </ul>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>


  <h2 id="results">Result</h2>

  <pre class="prettyprint linenums"><?php echo $RESULT ?></pre>
</div> <!-- /container -->
<script src="/chCmsApiPlugin/js/jquery.js"></script>
<script src="/chCmsApiPlugin/js/google-code-prettify/prettify.js"></script>
</body>
</html>
