<h2>API Formatters</h2>

<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>Name</th>
      <th>Description</th>
    </tr>
  </thead>

  <tbody>
    <?php foreach ($formatters->getRawValue() as $class => $data): ?>
    <tr>
      <td><?php echo link_to($class, 'api_formatter_doc', array('formatter' => $class)) ?></td>

      <td>
        NA
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
