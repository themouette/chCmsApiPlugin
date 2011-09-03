<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

$t = new lime_test(6, new lime_output_color());

$obj = new chCmsApiFormatterTestObject();
$obj->setFoo('foo');
$obj->setFooBar('foo_bar');

$t->diag('constructor');
$formatter = new chCmsApiPropertyFormatter('foo');
$t->is_deeply($formatter->getOption('field_name'), 'foo', 'field_name option is set by constructor');

$formatter = new chCmsApiPropertyFormatter('foo', array('field_name' => 'bar'));
$t->is_deeply($formatter->getOption('field_name'), 'foo', 'field_name option cannot be overriden');

$t->diag('format');
$t->is($formatter->format($obj), 'foo', 'formatter format right field');

$formatter = new chCmsApiPropertyFormatter('foo_bar');
$t->is($formatter->format($obj), 'foo_bar', 'formatter format field camelcase');

$formatter = new chCmsApiPropertyFormatter('fooBar');
$t->is($formatter->format($obj), 'foo_bar', 'formatter format field camelcase');

$formatter = new chCmsApiPropertyFormatter('FooBar');
$t->is($formatter->format($obj), 'foo_bar', 'formatter format field camelcase');
