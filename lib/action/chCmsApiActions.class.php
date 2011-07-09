<?php
/**
 * This file declare the chCmsApiActions class.
 *
 * @package chCmsApiPlugin
 * @subpackage action
 * @author Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright (c) Carpe Hora SARL 2011
 * @since 2011-07-08
 */

/**
 * base action class for API actions
 */
abstract class chCmsApiActions extends chCmsActions
{
  /**
   * return the default error code if none provided
   *
   * @return String
   **/
  abstract public function getDefaultErrorCode();

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

    $this->renderText($this->formatResult($result));

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
          $errorMessage,
          is_null($errorCode) ? $this->getDefaultErrorCode() : $errorCode,
          $parameters);
  }

  /**
   * formate a result object/array into a string matching request format.
   *
   * @param Mixed $result the result to format.
   * @return String
   * @author Julien Muetton <julien_muetton@carpe-hora.com>
   **/
  protected function formatResult($result)
  {
    if (is_null($result))
    {
      return '';
    }
    return chCmsApiTools::formatResultForRequest($result, $this->getRequest(), $this->getResponse());
  }
} // END OF chCmsApiActions
