<?php
/**
 * This file declare the BaseObjectFormatter class.
 *
 * @package chCmsApiPlugin
 * @subpackage formatter
 * @author Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright (c) Carpe Hora SARL 2011
 * @since 2011-07-08
 */

/**
 * the base formatter object.
 * To declare a formatter, just extend this class and setDefaultFormatFields
 * in the initialize method.
 */
class chCmsApiObjectFormatter extends BasechCmsApiFormatter implements ArrayAccess
{
  /**
   * constructor for object formatter
   *
   * @return void
   */
  public function __construct($fields = array(), $options = array())
  {
    $options['fields'] = array();
    parent::__construct($options);

    $this->setDefaultFormatFields($fields);

    $this->initialize();
  }

  /**
   * parse a field configuration
   * if no formatter is given, $output_name field name is used.
   * if $formatter is not a formatter, then it is converted to chCmsApiPropertyFormatter
   *
   * @param string                        $output_name  fieldname to use as output
   * @param BasechCmsApiFormatter|string  $formatter     formatter to use
   * @return array
   **/
  protected function getFieldFormatter($output_name, $formatter = null)
  {
    // if no formatter is given, assume field is the same as output.
    if (is_null($formatter))
    {
      $formatter = $output_name;
    }

    if (is_array($formatter))
    {
      $formatter = new chCmsApiCollectionPropertyFormatter($output_name, $formatter);
    }

    // convert values to formatter
    if (!($formatter instanceof BasechCmsApiFormatter))
    {
      // in case output name is numeric, use the formatter value.
      if (is_numeric($output_name))
      {
        $output_name = $formatter;
      }

      $formatter = new chCmsApiPropertyFormatter($formatter);
    }

    // in case a fomatter is given but is not a property extractor, wrap it
    if (!($formatter instanceof chCmsApiPropertyFormatter))
    {
      $formatter = new chCmsApiFormatterPropertyFormatter($output_name, $formatter);
    }

    return array($output_name, $formatter);
  }

  /**
   * add $output_name field to formatted result using $formatter.
   *
   * @param string                        $output_name  fieldname to use as output
   * @param BasechCmsApiFormatter|string  $formatter     formatter to use
   * @return chCmsApiObjectFormatter
   **/
  public function setField($output_name, $formatter = null)
  {
    list($output_name, $formatter) = $this->getFieldFormatter($output_name, $formatter);

    $this->options['fields'][$output_name] = $formatter;

    return $this;
  }

  /**
   * set format for this instance
   * to ease implementation, if a value key is numeric, then
   * it is assumed that value is the field to format.
   * it allows to declare fields as following :
   * <code>
   *  array('foo', 'foo_bar' => 'foo');
   * </code>
   *
   * @param array $format set fields
   * @return BaseObjectFormatter
   **/
  public function setFormatFields($fields)
  {
    foreach ($fields as $key => $formatter)
    {
      $this->setField($key, $formatter);
    }
    return $this;
  }

  /**
   * set format for this instance
   *
   * @param array $format set fields
   * @return BaseObjectFormatter
   **/
  public function setDefaultFormatFields($fields)
  {
    if (!count($this->options['fields']))
    {
      $this->setFormatFields($fields);
    }

    return $this;
  }

  /**
   * retriev options['fields']
   *
   * @param array|null $extension fields to extend current fields with
   * @return array
   **/
  public function getFormatFields($extension = array())
  {
    $fields = $this->mergeFieldsArray($extension);
    $this->setDefaultFormatFields($fields);
    return array_keys($fields);
  }

  /**
   * add fields to format
   *
   * @return chCmsApiObjectFormatter
   **/
  public function mergeFormatFields($fields)
  {
    $this->setFormatFields($this->mergeFieldsArray($fields));

    return $this;
  }

  /**
   * merge fields array
   *
   * @return array
   **/
  protected function mergeFieldsArray($fields = array())
  {
    $preparedFields = array();

    foreach ($fields as $output_name => $formatter)
    {
      list($output_name, $formatter) = $this->getFieldFormatter($output_name, $formatter);
      $preparedFields[$output_name] = $formatter;
    }

    return $this->getOption('fields') + $preparedFields;
  }

  /**
   * declare default fields to render here
   **/
  public function initialize()
  {
  }

  /**
   * format object according to formatter configuration
   *
   * @return stdObject
   **/
  public function format($object, $fields = array())
  {
    if (is_array($object) || ($object instanceof PropelCollection))
    {
      return $this->formatCollection($object, $fields);
    }

    $fields = $this->mergeFieldsArray($fields);
    $ret = new stdClass();

    foreach ($fields as $key => $formatter)
    {
      $ret->$key = $formatter->format($object);
    }

    return $ret;
  }

  /**
   * format object
   *
   * @param BaseObject|array        $object object to format
   * @param array|null              $fields     fields to use
   * @return array
   **/
  public function formatObject($object, $fields = array())
  {
    return $this->format($object, $fields);
  }

  /**
   * format a collection
   *
   * @param PropelCollection|array  $collection collection to format
   * @param array|null              $fields     fields to use
   * @return array
   **/
  public function formatCollection($collection, $fields = array(), $options = array())
  {
    $objectFormatter = clone($this);
    $objectFormatter->mergeFormatFields($fields);
    $formatter = new chCmsApiCollectionFormatter($objectFormatter, $options);
    return $formatter->format($collection);
  }

	/**
	 * @see        http://www.php.net/ArrayAccess
	 */
	public function offsetExists($offset)
	{
		return isset($this->options['fields'][$offset]) || array_key_exists($offset, $this->options['fields']);
	}

	/**
	 * @see        http://www.php.net/ArrayAccess
	 */
	public function offsetSet($offset, $value)
	{
		$this->formatField[$offset] = $value;
	}

	/**
	 * @see        http://www.php.net/ArrayAccess
	 */
	public function offsetGet($offset)
	{
		return $this->options['fields'][$offset];
	}

	/**
	 * @see        http://www.php.net/ArrayAccess
	 */
	public function offsetUnset($offset)
	{
		unset($this->options['fields'][$offset]);
	}
} // END OF BaseObjectFormatter
