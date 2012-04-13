<h2>Sandbox</h2>

<?php
include_partial('apiDoc/sandbox_form', array(
  'routes'      => $routes,
  'test_route'  => $test_route,
));
?>


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