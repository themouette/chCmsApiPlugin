<?php
/**
 * This file declare the PluginApiFormatterTester class.
 *
 * @package     chCmsApiPlugin
 * @subpackage  test
 * @author      Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright   (c) Carpe Hora SARL 2011
 * @since       2011-08-29
 */

/**
 * the formatter tester class
 */
class PluginApiFormatterTester extends lime_test
{
  /**
   * return the validator class name
   *
   * @return String
   */
  protected function getClassname()
  {
    throw new Exception('define me');
  }

  /**
   * instanciate a formatter for testing
   *
   * @param array|null $options the options overrides
   * @return BasechCmsApiFormatter
   */
  protected function getFormatter($options = null, $fields = array())
  {
    if (is_null($options))
    {
      $options = $this->getDefaultFormatterOptions();
    }

    $class = $this->getClassname();

    return new $class($fields, $options);
  }

  /**
   * Return the default options to instanciate a new validator.
   *
   * @return array
   */
  protected function getDefaultFormatterOptions($options = array())
  {
    return array();
  }

  /**
   * execute every method starting with "test"
   *
   * @return void
   */
  public function run()
  {
    $methods = get_class_methods($this);
    foreach ($methods as $method)
    {
      if (0 === strpos($method, 'test'))
      {
        $this->diag('runs '.$method);
        call_user_func_array(array($this, $method), $this->getTestArgs($method));
      }
    }
  }

  /**
   * return test arguments for $method
   *
   * @return array
   */
  protected function getTestArgs($method)
  {
    return array();
  }

  /**
   * link to addPlannedTests
   *
   * @return PluginApiFormatterTester
   */
  protected function expect($number_expected)
  {
    return $this->addPlannedTests($number_expected);
  }

  /**
   * add tests to planned tests
   *
   * @return PluginApiFormatterTester
   */
  protected function addPlannedTests($planned)
  {
    $this->results['stats']['plan']+= $planned;

    return $this;
  }

  /**
   * compare 2 collections
   *
   * @param stdClass  $test_collection      the collection to test
   * @param stdClass  $reference_collection the refence collection to compare with
   * @param string    $test_message         the test description
   * @return void
   **/
  public function compare_collection($test_collection, $reference_collection, $test_message = null)
  {
    try
    {
      $this->internal_compare_collection($test_collection, $reference_collection);
      $this->pass($test_message);
      return true;
    }
    catch (PluginApiFormatterTesterException $e)
    {
      $this->fail($test_message);
      return false;
    }
  }

  /**
   * compare 2 objects
   *
   * @param stdClass  $test_object      the object to test
   * @param stdClass  $reference_object the refence object to compare with
   * @param string    $test_message     the test description
   * @return void
   **/
  public function compare_object($test_object, $reference_object, $test_message = null)
  {
    try
    {
      $this->internal_compare_object($test_object, $reference_object);
      $this->pass($test_message);
      return true;
    }
    catch (PluginApiFormatterTesterException $e)
    {
      $this->fail($test_message);
      return false;
    }
  }

  /**
   * compare 2 objects
   *
   * @param stdClass  $test_object      the object to test
   * @param stdClass  $reference_object the refence object to compare with
   * @param string    $test_message     the test description
   * @return void
   **/
  public function internal_compare_object($test_object, $reference_object)
  {
    if (is_scalar($reference_object))
    {
      if ($test_object !== $reference_object)
      {
        $this->set_last_test_errors(array(
          sprintf("  > error while comparing two objects:"),
          sprintf("           got: %s", str_replace("\n", '', var_export($test_object, true))),
          sprintf("      expected: %s", str_replace("\n", '', var_export($reference_object, true))))
        );
        throw new PluginApiFormatterTesterException();
      }
      return;
    }

    if (!is_array($reference_object))
    {
      $reference_object = get_object_vars($reference_object);
    }

    foreach ($test_object as $key => $value)
    {
      if (is_array($value))
      {
        $this->internal_compare_collection($value, $reference_object[$key]);
      }
      else if ($value instanceof stdClass)
      {
        $this->internal_compare_object($value, $reference_object[$key]);
      }
      else if (!(array_key_exists($key, $reference_object) && ($value === $reference_object[$key])) &&
              !(is_numeric($key) && (($num_key = (int) $key) || true) && // convert numeric key
                array_key_exists($num_key, $reference_object) && ($value === $reference_object[$num_key])))
      {
        $this->set_last_test_errors(array(
          sprintf("  > comparing object \"%s\"'s key (numeric : %s) :", $key, is_numeric($key) ? $num_key : 'NULL'),
          sprintf("           got: %s", str_replace("\n", '', var_export($value, true))),
          sprintf("      expected: %s", str_replace("\n", '', array_key_exists($key, $reference_object)
                                                              ? var_export($reference_object[$key], true)
                                                              : ((is_numeric($key) && array_key_exists((int) $key, $reference_object))
                                                                  ? var_export($reference_object[(int) $key], true)
                                                                  : '"undefined"'))))
        );
        throw new PluginApiFormatterTesterException();
      }
    }
  }

  /**
   * compare 2 collections
   *
   * @param stdClass  $test_collection      the collection to test
   * @param stdClass  $reference_collection the refence collection to compare with
   * @return void
   **/
  protected function internal_compare_collection($test_collection, $reference_collection)
  {
    if (!is_array($test_collection) || !is_array($reference_collection))
    {
      $this->set_last_test_errors(array(
        sprintf("  > collections should be arrays :"),
        sprintf("           got: %s", str_replace("\n", '', var_export($test_collection, true))),
        sprintf("      expected: %s", str_replace("\n", '', var_export($reference_collection, true))))
      );
      throw new PluginApiFormatterTesterException();
    }

    if (count($test_collection) != count($reference_collection))
    {
      $this->set_last_test_errors(array(
        sprintf("  > collections should have the same count :"),
        sprintf("           got: %s", str_replace("\n", '', var_export($test_collection, true))),
        sprintf("      expected: %s", str_replace("\n", '', var_export($reference_collection, true))))
      );
      throw new PluginApiFormatterTesterException();
    }

    foreach ($test_collection as $key => $test_object)
    {
      if (!isset($reference_collection[$key]))
      {
        $this->set_last_test_errors(array(
          sprintf("  > unknown property %s :", $key),
          sprintf("           got: %s", str_replace("\n", '', var_export($test_collection, true))),
          sprintf("      expected: %s", str_replace("\n", '', var_export($reference_collection, true))))
        );
        throw new PluginApiFormatterTesterException();
      }
      $this->internal_compare_object($test_object, $reference_collection[$key]);
    }
  }
} // END OF PluginApiFormatterTester