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
    return 'INVALID_PARAMETERS';
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
    return 'given parameters are invalid.';
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

    $errors = array();

    if ($this->hasGlobalErrors())
    {
      $errors['global'] = array();

      // global errors
      foreach ($this->getGlobalErrors() as $validator_error)
      {
        $errors['global'][] = $validator_error->getMessage();
      }
    }

    // iterate over fields
    foreach ($this->getErrorSchema() as $field_name => $error_obj)
    {
      if ($error_obj instanceof sfValidatorErrorSchema)
      {
        $errors[$field_name] = array();
        foreach ($error_obj as $error)
        {
          $errors[$field_name][] = $error->getMessage();
        }
      }
      else
      {
        $errors[$field_name] = $error_obj->getMessage();
      }
    }

    return $errors;
  }
} // END OF PluginchCmsApiParamValidator
