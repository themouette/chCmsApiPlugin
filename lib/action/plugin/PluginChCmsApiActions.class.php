<?php
/**
 * This file declare the PluginChCmsApiActions class.
 *
 * @package     chCmsApiPlugin
 * @subpackage  action
 * @author      Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright   (c) Carpe Hora SARL 2011
 * @since       2011-07-08
 */

/**
 * base action class for API actions
 */
abstract class PluginChCmsApiActions extends sfActions
{
  /**
   * return the default error code if none provided
   *
   * @return String
   **/
  abstract public function getDefaultErrorCode();

  /**
   * validate request against $paramValidator.
   * $paramValidator should match chCmsApiParamValidator inteface.
   *
   * @param chCmsApiParamValidator|sfForm $paramValidator validator for this request.
   * @return void
   **/
  public function validateRequest($paramValidator)
  {
    $request = $this->getRequest();
    $paramValidator->bind($request->getParameterHolder()->getAll());
    if (!$paramValidator->isValid())
    {
      $this->forward406($paramValidator->getErrorMessage(), $paramValidator->getErrorCode(), $paramValidator->getErrorParameters());
    }

    // replace request parameters.
    // to access raw parameters, extract it before.
    $request->getParameterHolder()->add($paramValidator->getValues());
  }

  /**
   * render given result in requested format
   *
   * @param mixed $result     result to return
   * @param int   $statusCode response status code
   * @return sfView::NONE
   **/
  protected function renderApi($result, $statusCode = 200)
  {
    // set status code
    $response = $this->getResponse();
    $response->setStatusCode($statusCode);
    $response->setApiResultContent($this->preprocessResult($result), $this->getRequest());
    $response->send();
    throw new sfStopException();
  }

  /**
   * throws a 406 error if $condition is true
   *
   * @param boolean $condition    condition to check.
   * @param Integer $errorCode    error code to return to funambol.
   * @parram String  $errorMessage error message to ease debugging.
   * @param array   $parameters   extra parameters to return to funambol.
   * @return void
   * @author Julien Muetton <julien_muetton@carpe-hora.com>
   **/
  public function forward406If($condition, $errorMessage, $errorCode = null, $parameters = null)
  {
    if (!$condition)
    {
      return ;
    }

    return $this->forward406($errorMessage, $errorCode, $parameters);
  }

  /**
   * throws a 406 error unless $condition is true
   *
   * @param boolean $condition    condition to check.
   * @param Integer $errorCode    error code to return to funambol.
   * @param String  $errorMessage error message to ease debugging.
   * @param array   $parameters   extra parameters to return to funambol.
   * @return void
   * @author Julien Muetton <julien_muetton@carpe-hora.com>
   **/
  public function forward406Unless($condition, $errorMessage, $errorCode = null, $parameters = null)
  {
    return $this->forward406If(!$condition, $errorMessage, $errorCode, $parameters);
  }

  /**
   * throws a 406 error.
   *
   * @param Integer $errorCode    error code to return to funambol.
   * @param String  $errorMessage error message to ease debugging.
   * @param array   $parameters   extra parameters to return to funambol.
   * @return void
   * @author Julien Muetton <julien_muetton@carpe-hora.com>
   **/
  public function forward406($errorMessage, $errorCode = null, $parameters = null)
  {
    throw new chCmsError406Exception(
          is_null($errorCode) ? $this->getDefaultErrorCode() : $errorCode,
          $errorMessage,
          $parameters);
  }

  /**
   * returns form errors as api error.
   *
   * @return void
   */
  public function renderInvalidForm($errorMessage, $form, $errorCode = null)
  {
    // extract error schema
    $schema = $this->extractErrorSchema($form->getErrorSchema());
    throw new chCmsError406Exception(
          is_null($errorCode) ? $this->getDefaultErrorCode() : $errorCode,
          $errorMessage,
          $errors);
  }

  protected function extractErrorSchema($errors)
  {
    $schema = array();
    $i18n = $this->getContext()->getI18n();
    foreach ($errors as $field => $validator)
    {
      if ($validator instanceof sfValidatorErrorSchema)
      {
        $schema[$field] = $this->extractErrorSchema($validator);
      }
      else
      {
        $schema[$field] = $i18n->__($validator->getMessageFormat(), $validator->getArguments());
      }
    }
    return $schema;
  }

  /**
   * formate a result object/array into a string matching request format.
   *
   * @param Mixed $result the result to format.
   * @return String
   * @author Julien Muetton <julien_muetton@carpe-hora.com>
   **/
  protected function preprocessResult($result)
  {
    return $result;
  }
} // END OF PluginChCmsApiActions
