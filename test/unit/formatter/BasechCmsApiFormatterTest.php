<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

$t = new lime_test(10, new lime_output_color());

class TestchCmsApiFormatter extends BasechCmsApiFormatter
{
  public function format($object) {return $object;}
}

$t->diag('check options');
$prop = new TestchCmsApiFormatter();
$t->is_deeply($prop->getOptions(), array(), 'options are empty for start');
$t->is($prop->setOption('test', 'joe'), $prop, 'setOption is fluid');
$t->is($prop->getOption('test'), 'joe', 'option can be accessed');
$t->is($prop->getOption('opt', 'foo'), 'foo', 'default is returned if option does not exist');
$t->is($prop->getOption('opt'), null, 'default is null as default');
$t->is($prop->getOption('test', 'foo'), 'joe', 'default is not used if prop exists');
$t->is_deeply($prop->getOptions(), array('test' => 'joe'), 'options can be accessed');
$t->is($prop->setOptions(array('foo' => 'bar')), $prop, 'setOptions is fluid');
$t->is_deeply($prop->getOptions(), array('foo' => 'bar'), 'options where reseted');

$prop = new TestchCmsApiFormatter(array('test' => 'joe'));
$t->is_deeply($prop->getOptions(), array('test' => 'joe'), 'options can be set by constructor');

