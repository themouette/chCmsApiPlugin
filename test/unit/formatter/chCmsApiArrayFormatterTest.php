<?php
include dirname(__FILE__) . '/../../bootstrap/unit.php';

$t = new ApiFormatterTester(39, new lime_output_color());

class TestArrayFormatter extends chCmsApiArrayFormatter
{
  public function initialize()
  {
    $this->setDefaultFormatFields(array('foo_bar'));
  }

  public function getFieldFormatter($output_name, $formatter = null)
  {
    return parent::getFieldFormatter($output_name, $formatter);
  }
}

$obj = array( 'foo' => 'FOO', 'bar' => 'BAR', 'foo_bar' => 'FOO_BAR');
$formatter = new TestArrayFormatter();

$t->diag('test for chCmsApiArrayFormatter object');
$t->diag('check field formatter conversion');

list($output_name, $propertyFormatter) = $formatter->getFieldFormatter(1);
$t->is($output_name, 1, 'numeric key without propertyFormatter returns a numeric key');
$t->isa_ok($propertyFormatter, 'chCmsApiPropertyFormatter', 'numeric key without propertyFormatter returns propertyFormatter');
$t->is($propertyFormatter->getFieldName(), 1, 'numeric key without propertyFormatter returns propertyFormatter');

list($output_name, $propertyFormatter) = $formatter->getFieldFormatter(1, 3);
$t->is($output_name, 3, 'numeric key with numeric formatter returns a numeric key');
$t->isa_ok($propertyFormatter, 'chCmsApiPropertyFormatter', 'numeric key with numeric formatter returns propertyFormatter');
$t->is($propertyFormatter->getFieldName(), 3, 'numeric key with numeric formatter returns propertyFormatter');

list($output_name, $propertyFormatter) = $formatter->getFieldFormatter(1, 'toto');
$t->is($output_name, 'toto', 'numeric key with propertyFormatter returns propertyFormatter key');
$t->isa_ok($propertyFormatter, 'chCmsApiPropertyFormatter', 'numeric key with propertyFormatter returns propertyFormatter');
$t->is($propertyFormatter->getFieldName(), 'toto', 'numeric key without propertyFormatter returns propertyFormatter');

list($output_name, $propertyFormatter) = $formatter->getFieldFormatter('toto', 'titi');
$t->is($output_name, 'toto', 'non numeric key with propertyFormatter returns given key');
$t->isa_ok($propertyFormatter, 'chCmsApiPropertyFormatter', 'provided propertyFormatter is used');
$t->is($propertyFormatter->getFieldName(), 'titi', 'provided propertyFormatter uses right field');

$f = new chCmsApiPropertyFormatter('titi');
list($output_name, $propertyFormatter) = $formatter->getFieldFormatter('toto', $f);
$t->is($output_name, 'toto', 'non numeric key with propertyFormatter returns given key');
$t->is($propertyFormatter, $f, 'provided propertyFormatter is used');
$t->is($propertyFormatter->getFieldName(), 'titi', 'provided propertyFormatter uses right field');

list($output_name, $propertyFormatter) = $formatter->getFieldFormatter('toto', array('titi', 'tata'));
$t->is($output_name, 'toto', 'array property returns given key');
$t->isa_ok($propertyFormatter, 'chCmsApiCollectionPropertyFormatter', 'array property returns a collection formatter');
$t->is_deeply($propertyFormatter->getFormatter()->getFormatFields(), array('titi', 'tata'), 'array property returns expected formatter');




$t->diag('check default parameters and overrides');
$formatter = new TestArrayFormatter();
$t->is_deeply($formatter->getFormatFields(), array('foo_bar'), 'default parameteres are set');
$t->is_deeply($formatter->getFormatFields(array('foo', 'bar')), array('foo_bar', 'foo', 'bar'), 'method override parameters are returned');
$t->is_deeply($formatter->getFormatFields(), array('foo_bar'), 'default parameters are not overriden by method');

$formatter = new TestArrayFormatter(array('foo', 'bar'));
$t->is_deeply($formatter->getFormatFields(), array('foo', 'bar'), 'constructor parameters are set');

$t->diag('check format');
$formatter = new TestArrayFormatter();
$t->compare_object($formatter->format($obj),
              array('foo_bar' => 'FOO_BAR'),
              'format with default value');
$t->compare_object($formatter->format($obj, array('foo')),
              array('foo_bar' => 'FOO_BAR', 'foo' => 'FOO'),
              'format with extend parameters by method');
$formatter = new TestArrayFormatter(array('foo', 'bar'));
$t->compare_object($formatter->format($obj),
              array('foo' => 'FOO', 'bar' => 'BAR'),
              'format with overriden parameters by constructor');

$t->diag('check formatCollection');
$formatter = new TestArrayFormatter();
$collection = array(
  array('foo_bar' => 'foo_bar1', 'foo' => 'FOO1', 'bar' => 'BAR1'),
  array('foo_bar' => 'foo_bar2', 'foo' => 'FOO2', 'bar' => 'BAR2'),
  array('foo_bar' => 'foo_bar3', 'foo' => 'FOO3', 'bar' => 'BAR3'),
  array('foo_bar' => 'foo_bar4', 'foo' => 'FOO4', 'bar' => 'BAR4'));
$t->compare_collection($formatter->formatCollection($collection),
  array(
    array('foo_bar' => 'foo_bar1'),
    array('foo_bar' => 'foo_bar2'),
    array('foo_bar' => 'foo_bar3'),
    array('foo_bar' => 'foo_bar4'),),
  'collection are formatted as expected');

$t->compare_collection($formatter->formatCollection($collection, array('bar')),
  array(
    array('foo_bar' => 'foo_bar1', 'bar' => 'BAR1'),
    array('foo_bar' => 'foo_bar2', 'bar' => 'BAR2'),
    array('foo_bar' => 'foo_bar3', 'bar' => 'BAR3'),
    array('foo_bar' => 'foo_bar4', 'bar' => 'BAR4')),
  'formatter fields can be extended by method');

$t->compare_collection($formatter->formatCollection($collection),
  array(
    array('foo_bar' => 'foo_bar1'),
    array('foo_bar' => 'foo_bar2'),
    array('foo_bar' => 'foo_bar3'),
    array('foo_bar' => 'foo_bar4'),),
  'formatter fields where not overriden');

$formatter = new TestArrayFormatter(array('foo', 'bar'));
$t->compare_collection($formatter->formatCollection($collection),
  array(
    array('foo' => 'FOO1', 'bar' => 'BAR1'),
    array('foo' => 'FOO2', 'bar' => 'BAR2'),
    array('foo' => 'FOO3', 'bar' => 'BAR3'),
    array('foo' => 'FOO4', 'bar' => 'BAR4')),
  'formatter fields can be overriden by method');

$t->diag('with subcollections');
$obj = array('a', 'b', 'c', 'd', 'foo_bar' => array(
                1 => array('e', 'f', 'g', null),
                3 => array('h', 'i', 'j', 'k')));
$formatter = new TestArrayFormatter(array(0,
                    'foo_bar' => new chCmsApiCollectionPropertyFormatter('foo_bar', new TestArrayFormatter(array(0, 3)))));
$t->is_deeply($formatter->getFormatFields(), array(0, 'foo_bar'), 'fields are kept');
$fields = $formatter->getOption('fields');
$t->ok(is_array($obj['foo_bar']), '"foo_bar" is an array');
$t->is_deeply($obj['foo_bar'][1], array('e', 'f', 'g', null), 'subcollection "key 1" is what expected');
$t->is_deeply($obj['foo_bar'][3], array('h', 'i', 'j', 'k'), 'subcollection "key 3" is what expected');
$t->is_deeply($fields['foo_bar']->getFormatter()->getFormatFields(), array(0, 3), 'subcollection "formatter" embed expected fields');
$t->compare_object($formatter->format($obj),
              array('a', 'foo_bar' => array(
                  0 => array('e', 3 => null),
                  1 => array('h', 3 => 'k'))),
              'fields can be extended by merge');

$t->diag('with subcollections, using merge');
$obj = array('a', 'b', 'c', 'd', 'foo_bar' => array(
                1 => array('e', 'f', 'g'),
                3 => array('h', 'i', 'j', 'k')));
$formatter = new TestArrayFormatter(array(0,
                    'foo_bar' => new chCmsApiCollectionPropertyFormatter('foo_bar', new TestArrayFormatter(array(0, 3)))));
$formatter->mergeFormatFields(array(1));
$t->is_deeply($formatter->getFormatFields(), array(0, 'foo_bar', 1), 'merged fields are persistants');
$t->compare_object($formatter->format($obj),
              array('a', 'b', 'foo_bar' => array(
                  array('e', 3 => null),
                  array('h', 3 => 'k'))),
              'fields can be extended by merge');

$t->diag('with subcollections, merging at runtime');
$obj = array('a', 'b', 'c', 'd', 'foo_bar' => array(
                1 => array('e', 'f', 'g'),
                3 => array('h', 'i', 'j', 'k')));
$formatter = new TestArrayFormatter(array(0,
                    'foo_bar' => new chCmsApiCollectionPropertyFormatter('foo_bar', new TestArrayFormatter(array(0, 3)))));
$t->compare_object($formatter->format($obj, array(1)),
              array('a', 'b', 'foo_bar' => array(
                  array('e', 3 => null),
                  array('h', 3 => 'k'))),
              'fields can be extended at runtime');

$t->diag('using chCmsApiCollectionPropertyFormatter formatter');
$formatter = new TestArrayFormatter(array(
      0,
      1,
      'foo' => new chCmsApiCollectionPropertyFormatter('foo_bar', new TestArrayFormatter(array(0,3)), array('keep_keys' => true))));
$t->compare_object($formatter->format($obj, array(1)),
              array('a', 'b', 'foo' => array(
                  1 => array('e', 3 => null),
                  3 => array('h', 3 => 'k'))),
              'subcollection property formatters can be passed');


