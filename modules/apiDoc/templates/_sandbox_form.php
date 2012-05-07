<?php use_javascript('/chCmsApiPlugin/js/sandbox.js') ?>


<?php $default_parameters = isset($default_parameters) ? $default_parameters : array(); ?>
<?php $default_method = isset($default_method) ? $default_method : 'GET'; ?>


<form id="sandbox-form" class="form-horizontal" action="" method="GET">
  <fieldset>
    <legend>API Method</legend>

    <div class="control-group">
      <label class="control-label" for="route">URL</label>

      <div class="controls">
        <div class="input-append">
          <?php if (!empty($routes)): ?>
          <select name="route" id="route">
            <option value="">-----</option>
            <?php foreach ($routes as $id => $route): ?>
              <option value="<?php echo $id ?>"<?php echo $id === $test_route ? ' selected' : '' ?>><?php echo $id ?></option>
            <?php endforeach; ?>
          </select><?php endif; ?><select name="method" id="method" class="span2">
            <?php foreach (array('GET', 'HEAD', 'POST', 'PUT', 'DELETE') as $method): ?>
              <option value="<?php echo $method ?>"<?php echo $method === $default_method ? ' selected' : '' ?>><?php echo $method ?></option>
            <?php endforeach; ?>
          </select><input type="text" value="<?php echo isset($test_url) ? $test_url : '' ?>" class="input-xlarge" id="url" name="url" placeholder="Query URL" /><button class="btn" id="send" type="button">Send</button>
        </div>
      </div>
    </div>
  </fieldset>

  <fieldset>
    <legend>Request Parameters</legend>

    <div class="control-group">
      <label class="control-label">Parameters</label>

      <div class="controls">
        <?php foreach ($default_parameters as $name => $value): ?>
          <?php // don't show parameters which are in the URL ?>
          <?php if (!empty($test_url) && strpos($test_url, ':'.$name) !== false) continue; ?>

          <p class="param_tuple">
            <input type="text" value="<?php echo $name ?>" placeholder="Name" class="key">
            <span> = </span>
            <input type="text" value="<?php echo $value ?>" placeholder="Value" class="value">
            <i class="icon-minus-sign" style="cursor:pointer"></i>
          </p>
        <?php endforeach; ?>

        <a href="#" class="btn" id="new_param_btn">
          <i class="icon-plus-sign"></i>
          New parameter
        </a>
      </div>
    </div>
  </fieldset>

  <fieldset>
    <legend>
      Results <span id="http_code"></span>
    </legend>

    <div class="control-group">
      <label class="control-label">Results</label>

      <div class="controls">
        <pre id="results" class="prettyprint linenums"></pre>
      </div>
    </div>
  </fieldset>
</form>
