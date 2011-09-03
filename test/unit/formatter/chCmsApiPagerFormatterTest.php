<?php
include dirname(__FILE__) . '/../../bootstrap/unit.php';

$t = new ApiFormatterTester(3, new lime_output_color());

$t->diag('constructor');

$objectFormatter = new chCmsApiObjectFormatter(array('foo', 'bar'));
$formatter = new chCmsApiPagerFormatter($objectFormatter);
$t->is($formatter->getFormatter(), $objectFormatter, 'getFormatter access expected property');
$t->isa_ok($formatter->getCollectionFormatter(), 'chCmsApiCollectionPropertyFormatter', 'getCollectionFormatter access expected property');

$t->diag('test format method');
$pager = new chCmsApiFormatterTestObject(array(
  'another_prop'=> 'foo',
  'last_page'   => 2,
  'page'        => 1,
  'first_index' => 1,
  'last_index'  => 5,
  'nb_results'  => 9,
  'results'     => array(
    new chCmsApiFormatterTestObject(array('foo' => 'FOO0', 'bar' => 'BAR0')),
    new chCmsApiFormatterTestObject(array('foo' => 'FOO1', 'bar' => 'BAR1')),
    new chCmsApiFormatterTestObject(array('foo' => 'FOO2', 'bar' => 'BAR2')),
    new chCmsApiFormatterTestObject(array('foo' => 'FOO3', 'bar' => 'BAR3')),
    new chCmsApiFormatterTestObject(array('foo' => 'FOO4', 'bar' => 'BAR4')),
  )));
$t->compare_object($formatter->format($pager), array(
  'last_page'   => 2,
  'page'        => 1,
  'first_index' => 1,
  'last_index'  => 5,
  'total'       => 9,
  'results'     => array(
    array('foo' => 'FOO0', 'bar' => 'BAR0'),
    array('foo' => 'FOO1', 'bar' => 'BAR1'),
    array('foo' => 'FOO2', 'bar' => 'BAR2'),
    array('foo' => 'FOO3', 'bar' => 'BAR3'),
    array('foo' => 'FOO4', 'bar' => 'BAR4'),
  )), 'formatted result is a pager');
