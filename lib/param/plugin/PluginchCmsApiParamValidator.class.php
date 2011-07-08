<?php
/**
 * This file declare the PluginchCmsApiParamValidator class.
 *
 * @package chCmsApiPlugin
 * @subpackage api
 * @author Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright (c) Carpe Hora SARL 2011
 * @since 2011-07-06
 */

/**
 * base class for api parameters validation
 */
class PluginchCmsApiParamValidator extends BaseForm
{
  /**
   * set up the form
   *
   * @return void
   **/
  public function setup()
  {
    $this->validatorSchema->setOption('allow_extra_fields', true);
    $this->disableLocalCSRFProtection();
  }

  /**
   * return error code attached to this request
   *
   * @return String
   **/
  public function getErrorCode()
  {
    if ($this->isValid())
    {
      return null;
    }
    return 'ERROR_CODE';
  }

  /**
   * return error message attached to this request
   *
   * @return String
   **/
  public function getErrorMessage()
  {
    if ($this->isValid())
    {
      return null;
    }
    return $this->getFormattedErrors();
  }

  /**
   * return error parameters associated to this request
   *
   * @return array
   **/
  public function getErrorParameters()
  {
    if ($this->isValid())
    {
      return null;
    }
    return array();
  }
} // END OF PluginchCmsApiParamValidator
