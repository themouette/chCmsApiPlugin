<?php
/**
 * This file declare the PluginChCmsApiFilter class.
 *
 * @package chCmsApiPlugin
 * @subpackage filter
 * @author Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright (c) Carpe Hora SARL 2011
 * @since 2011-07-08
 */

/**
 * the api filter, trap exceptions
 */
class PluginChCmsApiFilter extends sfFilter
{
  /**
   * excute the filter
   **/
  public function execute($filterChain)
  {
    // get the current action instance
    $actionInstance = $this->context->getController()->getActionStack()->getLastEntry()->getActionInstance();

    if ($actionInstance instanceof chCmsApiActions)
    {
      try
      {
        $response = $this->getContext()->getResponse();
        $request  = $this->getContext()->getRequest();

        $response->setContentTypeForFormat($request->getRequestFormat());

        $this->validateRequestParamters();

        $filterChain->execute();
      }
      catch (chCmsApiErrorException $e)
      {
        $response->setStatusCode($e->getCode());
        $response->setApiResultContent($this->getErrorArray($e), $request);
      }
      catch (chCmsError401Exception $e)
      {
        $response->setStatusCode('401');
        if ($this->getContext()->getUser()->isAuthenticated())
        {
          $response->setApiResultContent(array('error' => array(
            'code'    => 'INSUFFICIENT_CREDENTIAL',
            'message' => 'this ressource is protected') ), $request);
        }
        else
        {
          $response->setApiResultContent(array('error' => array(
            'code'    => 'AUTHENTICATION_REQUIRED',
            'message' => 'this ressource is protected, please sign in') ), $request);
        }
      }
    }
    else
    {
      $filterChain->execute();
    }
  }

  /**
   * execute the request parameter validation
   * and update request parameters
   *
   * @return void
   * @throw chCmsApiErrorException if paramters are invalid
   */
  protected function validateRequestParamters()
  {
    $response = $this->getContext()->getResponse();
    $request  = $this->getContext()->getRequest();
    $route    = $request->getAttribute('sf_route');

    // check there is a validator
    if ($paramValidator = $this->getParamValidatorForRoute($route))
    {
      $request->setParamValidator($paramValidator);
      $paramValidator->processApiRequest($request);
    }
  }

  /**
   * create the param_validator object
   *
   * @return chCmsApiParamValidator
   */
  protected function getParamValidatorForRoute($route)
  {
    $options  = $route->getOptions();

    // set default options
    $options = array_merge(array(
          'param_validator'       => false,
          'param_validator_args'  => array()), $options);

    switch (true)
    {
      case is_object($options['param_validator']) && is_callable(array($options['param_validator'], 'processApiRequest')):
        // object is already instanciate
        return $options['param_validator'];

      case (bool) $options['param_validator']:
        // class name and arguments
        $r = new ReflectionClass($options['param_validator']);
        return $r->newInstanceArgs($options['param_validator_args']);

      default:
        // no validator to call
        // return
        return false;
    }
  }

  /**
   * format error into a user friendly array
   * @todo use a filter event to allow extension
   *
   * @return  array
   * @author Julien Muetton <julien_muetton@carpe-hora.com>
   **/
  protected function getErrorArray(chCmsApiErrorException $e)
  {
    $result = array(
      'code'    => $e->getApiCode(),
      'message' => $e->getMessage());

    if (!is_null($e->getParameters()))
    {
      $result['parameters'] = $e->getParameters();
    }

    return array('error' => $result);
  }
} // END OF PluginChCmsApiFilter
