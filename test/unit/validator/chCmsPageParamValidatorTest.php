<?php

require dirname(dirname(__FILE__)) . '/../bootstrap/unit.php';

$t = new lime_test(5, new lime_output_color());

$t->diag('test chCmsPageParamValidator validator');


$v = new chCmsPageParamValidator(array('max' => 10));
foreach (array(5 => 5, 10 => 10, 20 => 10) as $val => $expect)
{
  try
  {
    $t->is($v->clean($val), $expect, sprintf('%d is cleaned as expected (max 10)', $val));
  }
  catch (sfValidatorError $e)
  {
    $t->fail(sprintf('%s was not validated', $val));
  }
}

foreach (array('toto') as $value)
{
  try
  {
    $v->clean($value);
    $t->fail(sprintf('%s passed as valid list length', var_export($value, true)));
  }
  catch (sfValidatorError $e)
  {
    $t->pass(sprintf('%s is not a valid list length', var_export($value, true)));
  }
}

try
{
  $t->is($v->clean(null), 1, '"null" returns first page');
}
catch (sfValidatorError $e)
{
  $t->fail('"null" was not validated');
}
