<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

$t = new ApiFormatterTester(2, new lime_output_color());

$t->diag('constructor');
$obj = new chCmsApiFormatterTestObject(array('lat' => 1, 'long' => 2, 'latitude' => 3, 'longitude' => 4));
$formatter = new chCmsApiDummyPropertyFormatter('foo');
$t->is($formatter->format($obj), 'foo', 'return given value');

$formatter = new chCmsApiDummyPropertyFormatter();
$t->is($formatter->format($obj), null, 'default vallue is null');

