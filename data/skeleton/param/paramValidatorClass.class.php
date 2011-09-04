<?php
/**
 * This file declare the ##paramValidatorClass## class.
 *
 * @package ##package##
 * @subpackage param-validator
 * @author Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright (c) Carpe Hora SARL 2011
 * @since 2011-09-04
 */

/**
 * param validator
 */
class ##paramValidatorClass## extends ##BaseParamValidator##
{
  /**
   * use this validator in your chCmsApiAction as follow :
   * <code>
   *  $this->validateRequest(new ##paramValidatorClass##(array()));
   * </code>
   * then you can access your params through request as usual
   */
  public function configure()
  {
    /*
    $this->setValidator('page',   new chCmsPageParamValidator(array('max' => $this->getOption('max_page', null))));
    $this->setValidator('max',    new chCmsParamListLengthValidator(array('default' => $this->getOption('list_length', 10))));
    $this->setValidator('term',   new sfValidatorString(array('min_length' => 0, 'required' => false, 'empty_value' => "%")));
    */
  }
} // END OF ##paramValidatorClass##
