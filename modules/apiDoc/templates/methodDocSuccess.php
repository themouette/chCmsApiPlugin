<?php if (isset($route_name)): ?>
  <h2>« <?php echo $route_name ?> » documentation</h2>
<?php endif; ?>

<p class="well">
  <?php if (!empty($ROUTE_DESCRIPTION)): ?>
    <?php echo nl2br($ROUTE_DESCRIPTION) ?>
    <br>
  <?php endif; ?>
  <?php if (!empty($DESCRIPTION)): ?>
    <?php echo nl2br($DESCRIPTION) ?>
    <br>
  <?php endif; ?>
</p>


<h2 id="formal_url">URL</h2>

<pre><?php echo implode('|', $HTTP_METHODS) ?> <?php echo $FORMAL_URL ?></pre>


<h2 id="formats">Supported formats</h2>

<p>
  <?php echo implode(', ', $SUPPORTED_FORMATS) ?>.
  Default is <?php echo $DEFAULT_FORMAT ?>.
</p>


<?php if (isset($AUTH_REQUIRED)): ?>
<h2 id="auth">Authentication required</h2>

<p><?php echo $AUTH_REQUIRED ? 'Yes.' : 'No.' ?></p>
<?php endif; ?>


<h2 id="parameters">Query Parameters</h2>

<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th style="width: 35%">Name</th>
      <th>Value</th>
    </tr>
  </thead>

  <tbody>
    <?php $required_params = array(); ?>
    <?php foreach ($PARAMS as $name => $data): ?>

    <?php $required_params[$name] = !empty($data['default']) ? $data['default'] : '' ?>

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

    <?php if (empty($PARAMS)): ?>
    <tr>
      <td colspan="2">No parameters.</td>
    </tr>
    <?php endif; ?>
  </tbody>
</table>


<?php if (!empty($RESULT)): ?>
  <h2 id="results">Result</h2>

  <pre class="prettyprint linenums"><?php echo $RESULT ?></pre>
<?php else: ?>
  <h2>Sandbox</h2>
  <?php
  include_partial('apiDoc/sandbox_form', array(
    'test_url'            => str_replace(':sf_format', 'json', $FORMAL_URL),
    'default_method'      => $HTTP_METHODS[0],
    'default_parameters'  => $required_params
  ));
  ?>
<?php endif; ?>
