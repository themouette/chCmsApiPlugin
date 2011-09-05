<?php
include dirname(__FILE__) . '/../../bootstrap/unit.php';

$t = new ApiFormatterTester(2, new lime_output_color());

$f = new chCmsApiPassFormatter();
$var = 'test';
$t->is($f->format($var), $var, 'string is unchanged');
$var = array('toto', 'titi' => 'tata');
$t->is_deeply($f->format($var), $var, 'array is unchanged');
