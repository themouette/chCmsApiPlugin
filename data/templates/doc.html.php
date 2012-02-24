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
        <th style="width: 35%">Name</th>
        <th>Value</th>
      </tr>
    </thead>

    <tbody>
      <?php foreach ($PARAMS as $name => $data): ?>
      <tr>
        <td>
          <!-- required -->
          <?php if (isset($data['required'])): ?>
            <?php if ($data['required']): ?>
              <span class="label label-important pull-left">Required</span>
            <?php else: ?>
              <span class="label label-info pull-left">Optionnal</span>
            <?php endif; ?>

            <?php unset($data['required']); ?>
          <?php endif; ?>

          <!-- default value -->
          <?php if (!empty($data['default'])): ?>
            <span class="label pull-right">Default is "<?php echo $data['default']; ?>"</span>
            <?php unset($data['default']); ?>
          <?php endif; ?>

          <span style="margin-left: 10px"><?php echo $name ?></span>
        </td>
        <td>
          <!-- option comment -->
          <?php if (!empty($data['comment'])): ?>
            <?php echo $data['comment']; ?>
            <?php unset($data['comment']); ?>
          <?php endif; ?>

          <!-- other stuff -->
          <ul>
            <?php $total = 0; ?>
            <?php foreach ($data as $option => $value): ?>
              <?php if (!$value) continue;?>

              <li><strong><?php echo $option ?></strong>: <?php echo var_export($value, true)?></li>
              <?php $total += 1; ?>
            <?php endforeach; ?>
          </ul>

          <?php if (!$total): ?>
            No more details available.
          <?php endif; ?>
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
