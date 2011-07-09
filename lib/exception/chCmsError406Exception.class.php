<?php
/**
 * This file declare the chCmsError406Exception class.
 *
 * @package chCmsApiPlugin
 * @subpackage exception
 * @author Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright (c) Carpe Hora SARL 2011
 * @since 2011-07-08
 */

/**
 * 406 error exception
 */
class chCmsError406Exception extends sfException
{
  /**
   * exception constructor
   *
   * @param string  $message  the error message
   * @param mixed   $code     the error code
   * @param array   $parameters   extra param to return
   **/
  public function __construct($message, $code, $parameters = null)
  {
    parent::__construct($message);
    $this->parameters = $parameters;
    $this->api_code = $code;
  }

  /**
   * extra parameters bound to the exception (will be returned)
   * @access protected
   * @var array
   */
   protected $parameters;

   /**
    * the exception error code
    * @access protected
    * @var mixed
    */
    protected $api_code;

   /**
    * getter for api_code
    * @access public
    *
    * @return mixed the api_code value
    */
   public function getApiCode()
   {
     return $this->api_code;
   }

   /**
    * setter for api_code
    * @access public
    *
    * @return mixed the api_code value
    */
   public function setApiCode($api_code)
   {
     return $this->api_code = $error_code;
   }


  /**
   * getter for parameters
   * @access public
   *
   * @return array the parameters value
   */
  public function getParameters()
  {
    return $this->parameters;
  }

  /**
   * setter for parameters
   * @access public
   *
   * @return array the parameters value
   */
  public function setParameters($parameters)
  {
    return $this->parameters = $parameters;
  }

} // END OF chCmsError406Exception
