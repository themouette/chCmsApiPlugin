<?php

require(dirname(__FILE__).'/../../../../../test/bootstrap/unit.php');

$t = new lime_test(19, new lime_output_color());
require_once(dirname(__FILE__).'/../../../lib/api/chCmsApiObjectFormatter.class.php');

$t->info('formatObject');
$t->info(' > simple case');
$obj = array('a', 'b', 'c', 'd', 'e');
$res = chCmsApiObjectFormatter::formatObject($obj, array(1,3));
// expect array(1 => 'b', 3 => 'd')
$t->is(2, count($res), 'there is the right number of results');
$t->ok(isset($res[1]), 'key 1 is filtered');
$t->ok(isset($res[3]), 'key 3 is filtered');
$t->is($res[1], 'b', 'key 1 is what expected');
$t->is($res[3], 'd', 'key 1 is what expected');

$t->info(' > with arrays');
$obj = array('a', 'b', 'c', 'd', array(
                1 => array('e', 'f', 'g'),
                3 => array('h', 'i', 'j', 'k')));
$res = chCmsApiObjectFormatter::formatObject($obj, array(1,3,4 => array(1,3)));
// expect array(1 => 'b', 3 => 'd', 4 => array(
//                        1 => array(1 => 'f', 3 => null),
//                        3 => array(1 => 'i', 3 => 'k')))
$t->is(3, count($res), 'there is the right number of results');
$t->ok(isset($res[1]), 'key 1 is filtered');
$t->ok(isset($res[3]), 'key 3 is filtered');
$t->ok(isset($res[4]), 'key 4 is filtered');
$t->is($res[1], 'b', 'key 1 is what expected');
$t->is($res[3], 'd', 'key 1 is what expected');
$t->isa_ok($res[4], 'array', 'key 4 is an array');
$t->is(2, count($res[4]), 'there is the right number of results');
$t->ok(isset($res[4][1]), 'key 4 => 1 is what expected');
$t->ok(isset($res[4][3]), 'key 4 => 3 is what expected');
$t->isa_ok($res[4][1], 'array', 'subkey 1 is an array');
$t->isa_ok($res[4][3], 'array', 'subkey 3 is an array');
$t->is_deeply($res[4][1], array(1 => 'f', 3 => null), 'subkey 1 is what expected');
$t->is_deeply($res[4][3], array(1 => 'i', 3 => 'k'), 'subkey 3 is what expected');

