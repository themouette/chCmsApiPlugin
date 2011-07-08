<?php
include dirname(__FILE__) . '/../../bootstrap/unit.php';

$t = new lime_test(12, new lime_output_color());

class TestObjectFormatter extends BaseObjectApiFormatter
{
  function initialize()
  {
    $this->setDefaultFormatFields(array('toto'));
  }
}

$t->diag('test for BaseObjectApiFormatter object');
$t->diag('check default parameters and overrides');
$formatter = new TestObjectFormatter();
$t->is_deeply(array('toto'), $formatter->getFormatFields(), 'default parameteres are set');
$t->is_deeply(array('foo', 'bar'), $formatter->getFormatFields(array('foo', 'bar')), 'method override parameters are returned');
$t->is_deeply(array('toto'), $formatter->getFormatFields(), 'default parameteres are not overriden by method');

$formatter = new TestObjectFormatter(array('foo', 'bar'));
$t->is_deeply(array('foo', 'bar'), $formatter->getFormatFields(), 'constructor parameters are set');

$t->diag('check formatObject');
$obj = array('toto' => 'toto', 'foo' => 'FOO', 'bar' => 'BAR');
$formatter = new TestObjectFormatter();
$t->is_deeply($formatter->formatObject($obj),
              array('toto' => 'toto'),
              'format with default value');
$t->is_deeply($formatter->formatObject($obj, array('foo', 'bar')),
              array('foo' => 'FOO', 'bar' => 'BAR'),
              'format with overriden parameters by method');
$formatter = new TestObjectFormatter(array('foo', 'bar'));
$t->is_deeply($formatter->formatObject($obj),
              array('foo' => 'FOO', 'bar' => 'BAR'),
              'format with overriden parameters by constructor');

$t->diag('check formatCollection');
$formatter = new TestObjectFormatter();
$collection = array(
  array('toto' => 'toto1', 'foo' => 'FOO1', 'bar' => 'BAR1'),
  array('toto' => 'toto2', 'foo' => 'FOO2', 'bar' => 'BAR2'),
  array('toto' => 'toto3', 'foo' => 'FOO3', 'bar' => 'BAR3'),
  array('toto' => 'toto4', 'foo' => 'FOO4', 'bar' => 'BAR4'));
$t->is_deeply($formatter->formatCollection($collection),
  array(
    array('toto' => 'toto1'),
    array('toto' => 'toto2'),
    array('toto' => 'toto3'),
    array('toto' => 'toto4'),),
  'collection are formatted as expected');

$t->is_deeply($formatter->formatCollection($collection, array('foo', 'bar')),
  array(
    array('foo' => 'FOO1', 'bar' => 'BAR1'),
    array('foo' => 'FOO2', 'bar' => 'BAR2'),
    array('foo' => 'FOO3', 'bar' => 'BAR3'),
    array('foo' => 'FOO4', 'bar' => 'BAR4')),
  'formatter fields can be overriden by method');

$t->is_deeply($formatter->formatCollection($collection),
  array(
    array('toto' => 'toto1'),
    array('toto' => 'toto2'),
    array('toto' => 'toto3'),
    array('toto' => 'toto4'),),
  'formatter fields where not overriden');

$formatter = new TestObjectFormatter(array('foo', 'bar'));
$t->is_deeply($formatter->formatCollection($collection),
  array(
    array('foo' => 'FOO1', 'bar' => 'BAR1'),
    array('foo' => 'FOO2', 'bar' => 'BAR2'),
    array('foo' => 'FOO3', 'bar' => 'BAR3'),
    array('foo' => 'FOO4', 'bar' => 'BAR4')),
  'formatter fields can be overriden by method');

$t->diag('with subcollections');
$obj = array('a', 'b', 'c', 'd', 'toto' => array(
                1 => array('e', 'f', 'g'),
                3 => array('h', 'i', 'j', 'k')));
$t->is_deeply($formatter->formatObject($obj, array(0, 'toto' => array(0, 3))),
              array('a', 'toto' => array(
                  1 => array('e', 3 => null),
                  3 => array('h', 3 => 'k'))),
              'subcollections are treated as expected');



