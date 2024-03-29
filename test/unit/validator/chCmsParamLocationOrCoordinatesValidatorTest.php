<?php
/**
 * test file for chCmsParamLocationOrCoordinatesValidator generated by chCmsApiPlugin
 */

include dirname(__FILE__) . '/../../bootstrap/unit.php';

$t = new lime_test(13, new lime_output_color());

$t->diag('test empty value');
$v = new chCmsParamLocationOrCoordinatesValidator();
try
{
  $v->clean(null);
  $t->fail();
}
catch (sfValidatorError $e)
{
  $t->pass('empty values throw an exception');
  $t->is($e->getMessage(), 'You must provide a "%location%" or a "%coordinates%" field.', 'exception embed expected message');
  $t->is($e->getCode(), 'required', 'exception embed expected code "required"');
}

$t->diag('test valid value');
$t->is($v->clean(array('coord' => array(
                    'lat' => 19.134567, 'long' => -12.23456))), array(
                    'coord' => array(
                      'lat' => 19.134567, 'long' => -12.23456)), 'default coordinates option and output are ok');

$t->is($v->clean(array('location' => array(
                    'lat' => 19.134567, 'long' => -12.23456))), array(
                    'location' => array(
                      'lat' => 19.134567, 'long' => -12.23456),
                    'coord' => array(
                      'lat' => 19.134567, 'long' => -12.23456)), 'default location option and output are ok');

$t->diag('override options');
$v = new chCmsParamLocationOrCoordinatesValidator(array('output' => 'foo', 'coordinates' => 'bar', 'location' => 'baz'));
$t->is($v->clean(array('bar' => array(
                    'lat' => 19.134567, 'long' => -12.23456))), array(
                    'bar' => array(
                      'lat' => 19.134567, 'long' => -12.23456),
                    'foo' => array(
                      'lat' => 19.134567, 'long' => -12.23456)), 'coordinates option and output are ok');

$t->is($v->clean(array('baz' => array(
                    'lat' => 19.134567, 'long' => -12.23456))), array(
                    'baz' => array(
                      'lat' => 19.134567, 'long' => -12.23456),
                    'foo' => array(
                      'lat' => 19.134567, 'long' => -12.23456)), 'location option and output are ok');


$t->diag('test not valid value');
try
{
  $v->clean("%bad param%");
  $t->fail('invalid data should throw an exception');
}
catch (sfValidatorError $e)
{
  $t->fail('invalid data throws an API error, validator error encountered');
}
catch (chCmsApiErrorException $e)
{
  $t->pass('invalid parameter throw an exception');
  $t->is($e->getMessage(), 'You must provide a "baz" or a "bar" field.', 'exception embed expected message');
  $t->is($e->getCode(), '400', 'exception embed expected code "400"');
}
try
{
  $v->clean(array(
                'bar' => array('lat' => 19.134567, 'long' => -12.23456),
                'baz' => array('lat' => 19.134567, 'long' => -12.23456)
              ));
  $t->fail('invalid data should throw an exception');
}
catch (sfValidatorError $e)
{
  $t->fail('invalid data throws an API error, validator error encountered');
}
catch (chCmsApiErrorException $e)
{
  $t->pass('invalid parameter throw an exception');
  $t->is($e->getMessage(), 'You must provide only one of "baz" or "bar" field.', 'exception embed expected message');
  $t->is($e->getCode(), '400', 'exception embed expected code "400"');
}
