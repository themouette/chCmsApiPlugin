<?php
include dirname(__FILE__) . '/../../bootstrap/unit.php';

class chCmsApiDateTimePropertyFormatterTest extends ApiFormatterTester
{
  public function testTimezoneBehavior()
  {
    $this->expect(3);

    $formatter = $this->getFormatter();
    $this->assert_format_ok($formatter, new DateTime('2010-01-01 10:24:16-04:00'),
                                                     '2010-01-01T10:24:16-0400',
                                        "Timezone is preserved.");
    $this->assert_format_ok($formatter, new DateTime('2011-11-31 00:00:00', new DateTimeZone('UTC')),
                                                     '2011-12-01T00:00:00+0000',
                                        "Timezone can be forced in date");
    $this->assert_format_ok($formatter, new DateTime('2011-11-31 00:00:00'),
                                                     '2011-12-01T00:00:00'.date('O'),
                                        "Default timezone is server's.");
  }

  public function testFormatFieldName()
  {
    $this->expect(1);

    $formatter = $this->getFormatter('foo');
    $this->is($formatter->getFieldName(), 'foo', 'first argument is fieldname');
  }

  public function testDefaultFormatIsISO8601()
  {
    $this->expect(2);

    $formatter = $this->getFormatter();
    $this->assert_format_ok($formatter, new DateTime('2010-01-01 10:24:16-04:00'),
                                                     '2010-01-01T10:24:16-0400');
    $this->assert_format_ok($formatter, new DateTime('2011-11-31 00:00:00+00:00'),
                                                     '2011-12-01T00:00:00+0000');
  }

  public function testFormatParameter()
  {
    $this->expect(3);

    $formatter = $this->getFormatter('date', array('format' => DateTime::ATOM));
    $this->assert_format_ok($formatter, new DateTime('2005-08-15T15:52:01+00:00'),
                                                     '2005-08-15T15:52:01+00:00',
                            'format is overridden by "format" option.');

    $formatter = $this->getFormatter('date', array('format' => DateTime::RFC822));
    $this->assert_format_ok($formatter, new DateTime('2005-08-15T15:52:01+00:00'),
                                                     'Mon, 15 Aug 05 15:52:01 +0000',
                            '"format" can take any DateTime format.');

    $formatter = $this->getFormatter('date', array('format' => 'l jS \of F Y h:i:s A'));
    $this->assert_format_ok($formatter, new DateTime('2005-08-15T15:52:01+00:00'),
                                                     'Monday 15th of August 2005 03:52:01 PM',
                            '"format" can take any [date] format, even text.');
  }

  protected function assert_format_ok($formatter, $value, $expected, $message = null)
  {
    if (is_null($message))
    {
      $message = sprintf('"%s" formats into "%s"',
                          $this->formatterValueToString($value),
                          $expected);
    }

    try
    {
      $obj = $this->getDefaultObject(array(
        $formatter->getFieldName() => $value));

      $this->is($formatter->format($obj), $expected, $message);
    }
    catch (Exception $e)
    {
      $this->fail(sprintf('formatter sent an exception %s', get_class($e)));
      $this->set_last_test_errors(array(
        sprintf('   code: %s', $e->getCode()),
        sprintf('message: %s', $e->getMessage())));
    }

    return $this;
  }

  /**
   * format a formatter value to string
   *
   * @return String
   */
  protected function formatterValueToString($value)
  {
    if ($value instanceof DateTime)
    {
      return $value->format('l jS \of F Y h:i:s A');
    }
    else
    {
      return $value;
    }
  }

  /**
   * Returns a new validator for $field.
   */
  protected function getFormatter($field = 'date', $options = null)
  {
    if (is_null($options))
    {
      $options = $this->getFormatterDefaultOptions();
    }
    return new chCmsApiDateTimePropertyFormatter($field, $options);
  }

  /**
   * Returns default options, overriden by $options if provided.
   */
  protected function getFormatterDefaultOptions($options = array())
  {
    return array_merge(array(), $options);
  }

  /**
   * transform an array into a simple test object
   */
  protected function getDefaultObject($fields = null)
  {
    return new chCmsApiFormatterTestObject($fields);
  }
}

$t = new chCmsApiDateTimePropertyFormatterTest();
$t->run();
