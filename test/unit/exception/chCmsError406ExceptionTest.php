<?php

$pluginDir = dirname(dirname(dirname(dirname(__FILE__))));
require $pluginDir . '/test/bootstrap/unit.php';

$t = new lime_test(7, new lime_output_color());

$t->diag('simply throw an exception and check params');

$e = new chCmsError406Exception('code', 'message', array('param' => 'ok'));
$t->is($e->getMessage(), 'message', 'message is what expected');
$t->is($e->getApiCode(), 'code', 'code is what expected');
$t->is_deeply($e->getParameters(), array('param' => 'ok'), 'parameters are what expected');
$t->is($e->getCode(), 406, 'http status is "406"');

$e = new chCmsError406Exception();
$t->is($e->getParameters(), null, 'parameters can be ommited');
$t->is($e->getMessage(), 'unknown error', '"message" can be ommited');
$t->is($e->getApiCode(), 'UNKNOWN_ERROR', '"code" can be ommited');
