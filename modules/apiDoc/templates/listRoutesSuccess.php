<h2>API Methods</h2>

<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th>Name</th>
      <th>URL</th>
      <th>Description</th>
    </tr>
  </thead>

  <tbody>
    <?php foreach ($apiMethods->getRawValue() as $id => $data): ?>
    <tr>
      <td>
        <?php
        echo link_to($id, 'api_method_doc', array(
          'route' => $id
        ), array(
          'title' => sprintf('See the documentation for the "%s" API method.', $id)
        ));
        ?>
      </td>

      <td>
        <?php
        echo sprintf('%s %s', implode(', ', $data['HTTP_METHODS']), $data['FORMAL_URL']);
        ?>
      </td>

      <td>
        <?php echo $data['ROUTE_DESCRIPTION'] ? $data['ROUTE_DESCRIPTION'] : 'NA' ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
