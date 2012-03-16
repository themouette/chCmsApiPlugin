<h2>« <?php echo $formatter ?> » documentation</h2>

<?php if (!empty($DESCRIPTION)): ?>
<p class="well">
  <?php echo nl2br($DESCRIPTION) ?>
</p>
<?php endif; ?>

<h3 id="fields">Fields</h3>

<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th style="width: 35%">Name</th>
      <th>Type</th>
      <th>Description</th>
    </tr>
  </thead>

  <tbody>
    <?php foreach ($FIELDS as $name => $data): ?>
    <tr>
      <td>
        <?php echo $name ?>
      </td>
      <td>
        <?php if (!empty($data['TYPE']) && !empty($data['SUBTYPE'])): ?>
          <?php $subtype = link_to($data['SUBTYPE'], 'api_formatter_doc', array('formatter' => $data['SUBTYPE'])); ?>
          <?php echo sprintf('%s[%s]', $data['TYPE'], $subtype) ?>
        <?php elseif (!empty($data['TYPE'])): ?>
          <?php echo $data['TYPE'] ?>
        <?php else: ?>
          NA
        <?php endif; ?>
      </td>
      <td>
        <?php if (!empty($data['DESCRIPTION'])): ?>
          <?php echo $data['DESCRIPTION'] ?>
        <?php else: ?>
          NA
        <?php endif; ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>