<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

$t = new ApiFormatterTester(9, new lime_output_color());

$obj = new chCmsApiFormatterTestObject();
$obj->setFooBar(array(
  1 => new chCmsApiFormatterTestObject(array('foo' => 'foo', 'bar' => 'bar')),
  3 => new chCmsApiFormatterTestObject(array('foo' => 'bar_foo', 'bar' => 'bar_bar')),));

$t->diag('constructor');
$formatter = new chCmsApiCollectionPropertyFormatter('foo_bar', array('foo'));
$t->is($formatter->getOption('field_name'), 'foo_bar', 'field_name option is set by constructor');
$t->is_deeply($formatter->getFormatter()->getFormatFields(), array('foo'), 'formatter option is set by constructor');

$formatter = new chCmsApiCollectionPropertyFormatter('foo_bar', array('foo'), array('field_name' => 'bar', 'formatter' => array('bar')));
$t->is_deeply($formatter->getOption('field_name'), 'foo_bar', 'field_name option cannot be overriden');
$t->is_deeply($formatter->getFormatter()->getFormatFields(), array('foo'), 'formatter option is set by constructor');

$t->diag('format');
$t->compare_collection($formatter->format($obj), array(
    array('foo' => 'foo',),
    array('foo' => 'bar_foo',)), 'formatter format right field');

$formatter = new chCmsApiCollectionPropertyFormatter('foo_bar', array('foo'));
$t->compare_collection($formatter->format($obj), array(
    array('foo' => 'foo',),
    array('foo' => 'bar_foo',)), 'formatter format right field');

$formatter = new chCmsApiCollectionPropertyFormatter('fooBar', array('foo'));
$t->compare_collection($formatter->format($obj), array(
    array('foo' => 'foo',),
    array('foo' => 'bar_foo',)), 'formatter format right field');

$formatter = new chCmsApiCollectionPropertyFormatter('FooBar', array('foo'));
$t->compare_collection($formatter->format($obj), array(
    array('foo' => 'foo',),
    array('foo' => 'bar_foo',)), 'formatter format right field');

$t->diag('set option "keep_keys" to true');
$formatter->setOption("keep_keys", true);
$t->compare_collection($formatter->format($obj), array(
    1 => array('foo' => 'foo',),
    3 => array('foo' => 'bar_foo',)), 'formatter format right field');
