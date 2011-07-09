<?php

$pluginDir = dirname(dirname(dirname(dirname(__FILE__))));
require $pluginDir . '/test/bootstrap/unit.php';

$t = new lime_test(4, new lime_output_color());

$t->diag('simply throw an exception and check params');

$e = new chCmsError406Exception('message', 'code', array('param' => 'ok'));
$t->is($e->getMessage(), 'message', 'message is what expected');
$t->is($e->getApiCode(), 'code', 'code is what expected');
$t->is_deeply($e->getParameters(), array('param' => 'ok'), 'parameters are what expected');

$e = new chCmsError406Exception('message', 'CODE');
$t->is($e->getParameters(), null, 'parameters can be ommited');
