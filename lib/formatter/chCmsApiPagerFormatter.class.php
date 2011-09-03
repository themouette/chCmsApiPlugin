<?php
/**
 * This file declare the chCmsApiPagerFormatter class.
 *
 * @package chCmsApiPlugin
 * @subpackage formatter
 * @author Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright (c) Carpe Hora SARL 2011
 * @since 2011-09-03
 */

/**
 * pager formatter
 */
class chCmsApiPagerFormatter extends chCmsApiObjectFormatter
{
  /**
   * constructor
   *
   * @return void
   */
  public function __construct($objectFormatter, $options = array())
  {
    if (!($objectFormatter instanceof chCmsApiCollectionPropertyFormatter))
    {
      $objectFormatter = new chCmsApiCollectionPropertyFormatter('results', $objectFormatter, $options);
    }
    parent::__construct(array(
      'page'        => new chCmsApiPropertyFormatter('page'),
      'last_page'   => new chCmsApiPropertyFormatter('last_page'),
      'first_index' => new chCmsApiPropertyFormatter('first_index'),
      'last_index'  => new chCmsApiPropertyFormatter('last_index'),
      'results'     => $objectFormatter,
      'total'       => new chCmsApiPropertyFormatter('nb_results'),
    ), $options);
  }

  /**
   * set an option for both formatter and subformatter
   *
   * @param string $name   the option name
   * @param mixed  $value  the value
   * @return chCmsApiCollectionFormatter
   **/
  public function setOption($name, $value)
  {
    $this->getFormatter()->setOption($name, $value);
    return parent::setOption($name, $value);
  }

  /**
   * return the subcollection object formatter
   *
   * @return chCmsApiObjectFormatter
   **/
  public function getFormatter()
  {
    return $this->getCollectionFormatter()->getFormatter();
  }

  /**
   * return the collection formatter
   *
   * @return chCmsApiCollectionFormatter
   **/
  public function getCollectionFormatter()
  {
    $fields = $this->getOption('fields');
    return $fields['results'];
  }

} // END OF chCmsApiPagerFormatter
