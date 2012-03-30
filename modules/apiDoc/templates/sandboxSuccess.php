<?php use_javascript('/chCmsApiPlugin/js/sandbox.js') ?>


<h2>Sandbox</h2>

<form id="sandbox-form" class="form-horizontal" action="" method="GET">
  <fieldset>
    <legend>API Method</legend>

    <div class="control-group">
      <label class="control-label" for="route">URL</label>

      <div class="controls">
        <div class="input-append">
          <select name="route" id="route">
            <option value="">-----</option>
            <?php foreach ($routes as $id => $route): ?>
              <option value="<?php echo $id ?>"<?php echo $id === $test_route ? ' selected' : '' ?>><?php echo $id ?></option>
            <?php endforeach; ?>
          </select><input type="text" class="input-xlarge" id="url" name="url" placeholder="Query URL" /><select name="method" id="method" class="span1">
            <?php foreach (array('GET', 'HEAD', 'POST', 'PUT', 'DELETE') as $method): ?>
              <option value="<?php echo $method ?>"><?php echo $method ?></option>
            <?php endforeach; ?>
          </select><button class="btn" id="send" type="button">Send</button>
        </div>
      </div>
    </div>
  </fieldset>

  <fieldset>
    <legend>Request Parameters</legend>

    <!--<div class="control-group">
      <label class="control-label">Parameters</label>

      <div class="controls">
        <p id="parameters-message" class="alert alert-info">
          Choose an API Method and its required parameters will automatically be
          inserted here.
        </p>

      </div>
    </div>-->

    <div class="control-group">
      <label class="control-label">Parameters</label>

      <div class="controls">
        <a href="#" class="btn" id="new_param_btn">
          <i class="icon-plus-sign"></i>
          New parameter
        </a>
      </div>
    </div>
  </fieldset>

  <fieldset>
    <legend>Results</legend>

    <div class="control-group">
      <label class="control-label">HTTP Code</label>

      <div class="controls">
        <span id="http_code">NA</span>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label">Results</label>

      <div class="controls">
        <pre id="results" class="prettyprint linenums"></pre>
      </div>
    </div>
  </fieldset>
</form>


<?php
$data_routes = array();
foreach ($routes as $id => $route)
{
  $data_routes[$id] = array(
    'url' => $route->getPattern(),
  );
}
?>
<script type="text/javascript">
var Routes = <?php echo json_encode($data_routes); ?>;
</script>
