<?php
/**
 * This file declare the PluginChCmsApiCollectionFormatter class.
 *
 * @package     chCmsApiPlugin
 * @subpackage  formatter
 * @author      Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright   (c) Carpe Hora SARL 2011
 * @since       2011-09-03
 */

/**
 * the collection formatter
 */
class PluginChCmsApiCollectionFormatter extends BasechCmsApiFormatter
{
  /**
   * constructor.
   * possible options are :
   *  - keep_keys : should the returned collection keep original keys
   *
   * @param BasechCmsApiFormatter|array $formatter the formatter to use
   * @param array                       $options   options
   * @return void
   **/
  public function __construct($formatter, $options = array())
  {
    if (!($formatter instanceof BasechCmsApiFormatter))
    {
      $formatter = new chCmsApiObjectFormatter($formatter);
    }
    parent::__construct(array_merge($options, array('formatter' => $formatter)));
  }

  /**
   * return current formatter
   *
   * @return BasechCmsApiFormatter
   **/
  public function getFormatter()
  {
    return $this->getOption('formatter');
  }

  /**
   * format given collection
   *
   * @param array $collection the collection to format
   * @return array
   */
  public function format($collection)
  {
    $ret = array();
    $formatter = $this->getFormatter();

    if (!$collection)
    {
      return $ret;
    }

    if ($this->getOption('keep_keys', false))
    {
      foreach ($collection as $key => $object)
      {
        $ret[$key] = $formatter->format($object);
      }
    }
    else
    {
      foreach ($collection as $key => $object)
      {
        $ret[] = $formatter->format($object);
      }
    }

    return $ret;
  }
} // END OF PluginChCmsApiCollectionFormatter