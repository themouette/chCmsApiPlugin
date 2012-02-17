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
   * the current sf_user
   */
  protected static $user;

  public function __construct($options = array())
  {
    $options = array_merge(array('error_status_code' => 400), $options);
    parent::__construct(array(), $options);
  }

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

    // do not iterate over global errors array.
    // all errors are global as no field are defined.

    $errors = array();
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

  /**
   * is there an error for $paramName
   *
   * @return bool
   **/
  public function hasParameterError($paramName, $error_type = null)
  {
    $error_schema = $this->getErrorSchema();
    if (!isset($error_schema[$paramName]))
    {
      return false;
    }

    $error = $error_schema[$paramName];

    if (  is_null($error_type) ||
        (($error instanceof sfValidatorErrorSchema) && isset($error[$error_type])) ||
          $error_type == $error->getCode()
        )
    {
      return true;
    }

    return false;
  }

  /**
   * overrid the bind function
   *
   * @param array $values A merged array of values and files
   * @return void
   */
  protected function doBind(array $values)
  {
    try
    {
      parent::doBind($values);
    }
    catch (chCmsApiErrorException $e)
    {
      $e->setParameters($values);
      throw $e;
    }
  }

  /**
   * handle API request parameters processing
   * bind all request parameters
   * you can provide one of the following options :
   *  - error_status_code : http status for error responses (400)
   *
   * @param sfRequest $request  the current sf_request
   * @param array     $options  the options override
   *
   * @return void
   * @throw chCmsApiErrorException
   */
  public function processApiRequest($request, $options = array())
  {
    $options = array_merge($this->getOptions(), $options);

    $request->setOriginalApiParameters($request->getOriginalApiParameters());

    // bind with all the request paramters
    $this->bind($request->getOriginalApiParameters());

    if (!$this->isValid())
    {
      $this->throwApiError();
    }

    // replace request parameters.
    // to access raw parameters, use getOriginalApiParameters
    $request->getParameterHolder()->add($this->getValues());
  }

  /**
   * throw an API error
   *
   * @return void
   */
  protected function throwApiError()
  {
    // request is invalid, throw new error
    throw new chCmsApiErrorException(
      $this->getErrorCode(),
      $this->getErrorMessage(),
      $this->options['error_status_code'],
      $this->getErrorParameters()
    );
  }

  /**
   * setter for User
   */
  public static function setUser($User)
  {
    chCmsApiParamValidator::$user = $User;
  }

  /**
   * getter for user
   * @return sfUser
   */
  public function getUser()
  {
    return chCmsApiParamValidator::$user;
  }

  /**
   * set the current user into the param validator
   *
   * @return void
   */
  public static function listenToLoadFactoryEvent($sf_event)
  {
    return chCmsApiParamValidator::setUser($sf_event->getSubject()->getUser());
  }
} // END OF PluginchCmsApiParamValidator
