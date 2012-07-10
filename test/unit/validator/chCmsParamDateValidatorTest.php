<?php
/**
 * test file for chCmsParamDateValidator generated by chCmsApiPlugin
 */

include dirname(__FILE__) . '/../../../../../test/bootstrap/unit.php';
class chCmsParamDateValidatorTest extends chCmsValidatorUnitTest
{
  function getClassname()
  {
    return 'chCmsParamDateValidator';
  }

  function ValidDateData()
  {
    return array(
      array('2005-08-15T15:52:01+0000', new DateTime('2005-08-15T15:52:01', new DateTimeZone('UTC'))),
      array('2012-06-26T23:48:02+02:00', new DateTime('2012-06-26T23:48:02', new DateTimeZone('Europe/Paris'))),
    );
  }

  function testValidDate($date, $expected)
  {
    $this->expect(2);
    $v = $this->getValidator(array());
    $this->assert_clean_ok($v, $date, $expected);
    $this->isa_ok($v->clean($date), 'DateTime');
  }

  function InvalidDateData()
  {
    return array(
      array('2005-08-05', 'Invalid date "2005-08-05".'),
      array('2012-06-26T23:48:02.018+02:00', 'Invalid date "2012-06-26T23:48:02.018+02:00".'),
      array('2012-06-26T23:48:02', 'Invalid date "2012-06-26T23:48:02".'),
    );
  }

  function testInvalidDate($date, $message)
  {
    $this->expect(1);
    $v = $this->getValidator(array());
    $this->assert_clean_not_ok($v, $date, 'invalid', $message);
  }

  function WithFormatData()
  {
    return array(
      array('2005-08-15T15:52:01+0000', DateTime::ISO8601, new DateTime('2005-08-15T15:52:01', new DateTimeZone('UTC'))),
      array('2012-06-26T23:48:02+02:00', DateTime::ATOM, new DateTime('2012-06-26T23:48:02', new DateTimeZone('Europe/Paris'))),
      array('Monday, 15-Aug-05 15:52:01 UTC', DateTime::RFC850, new DateTime('2005-08-15T15:52:01', new DateTimeZone('UTC'))),
    );
  }

  function testWithFormat($date, $format, $expected)
  {
    $this->expect(1);
    $v = $this->getValidator(array('format' => $format));
    $this->assert_clean_ok($v, $date, $expected);
  }

  function testRequired()
  {
    $this->expect(1);
    $v = $this->getValidator(array('required' => true));
    $this->assert_clean_not_ok($v, null, 'required', '');
  }

  function testDefaultValue()
  {
    $this->expect(1);
    $v = $this->getValidator(array('default' => '2005-08-15T15:52:01+0000'));
    $this->assert_clean_ok($v, null, new DateTime('2005-08-15T15:52:01', new DateTimeZone('UTC')));
  }

  protected function compare_validator_result($value, $expected, $message = null)
  {
    $this->is($value->getTimestamp(), $expected->getTimestamp(), $message);

    return $this;
  }
}

new chCmsParamDateValidatorTest();
