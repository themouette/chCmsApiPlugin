<?php

include dirname(__FILE__) . '/../../bootstrap/unit.php';

$t = new ApiFormatterTester(3, new lime_output_color());

$t->diag('constructor');
$obj = new chCmsApiFormatterTestObject(array('lat' => 1, 'long' => 2, 'latitude' => 3, 'longitude' => 4));
$formatter = new chCmsApiCoordinatesPropertyFormatter();
$t->isa_ok($formatter->format($obj), 'stdClass', 'returns a "stdClass"');
$t->compare_object($formatter->format($obj), array('lat' => 3, 'long' => 4), 'default fields are "latitude" and "longitude"');

$formatter = new chCmsApiCoordinatesPropertyFormatter(array('latitude_field'  => 'lat', 'longitude_field' => 'long'));
$t->compare_object($formatter->format($obj), array('lat' => 1, 'long' => 2), 'default fields can be overriden');
