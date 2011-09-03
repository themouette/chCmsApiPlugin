<?php
include dirname(__FILE__) . '/../../bootstrap/unit.php';

$t = new ApiFormatterTester(6, new lime_output_color());

$t->diag('constructor');
$formatter = new chCmsApiCollectionFormatter(array('titi', 'tata'));
$t->isa_ok($formatter->getFormatter(), 'chCmsApiObjectFormatter', 'object formatter is created');
$t->is_deeply($formatter->getFormatter()->getFormatFields(), array('titi', 'tata'), 'array property returns expected formatter');

$formatter = new chCmsApiCollectionFormatter(new chCmsApiObjectFormatter(array('titi', 'tata')));
$t->isa_ok($formatter->getFormatter(), 'chCmsApiObjectFormatter', 'object formatter is created');
$t->is_deeply($formatter->getFormatter()->getFormatFields(), array('titi', 'tata'), 'array property returns expected formatter');

$t->diag('default : option "keep_keys" off');
$formatter = new chCmsApiCollectionFormatter(array('tata'));
$collection = array(
  1         => new chCmsApiFormatterTestObject(array('toto' => 'TOTO1', 'tata' => 'TATA1')),
  2         => new chCmsApiFormatterTestObject(array('toto' => 'TOTO2', 'tata' => 'TATA2')),
  'bill'    => new chCmsApiFormatterTestObject(array('toto' => 'TOTO3', 'tata' => 'TATA3')),
  'richard' => new chCmsApiFormatterTestObject(array('toto' => 'TOTO4', 'tata' => 'TATA4')),
);
$t->compare_collection($formatter->format($collection), array(
  array('tata' => 'TATA1'),
  array('tata' => 'TATA2'),
  array('tata' => 'TATA3'),
  array('tata' => 'TATA4'),
));

$t->diag('option "keep_keys" on');
$formatter = new chCmsApiCollectionFormatter(array('tata'), array('keep_keys' => true));
$t->compare_collection($formatter->format($collection), array(
  1 => array('tata' => 'TATA1'),
  2 => array('tata' => 'TATA2'),
  'bill'    => array('tata' => 'TATA3'),
  'richard' => array('tata' => 'TATA4'),
));

