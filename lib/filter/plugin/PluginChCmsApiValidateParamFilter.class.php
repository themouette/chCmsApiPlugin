<?php
/**
 * This file declare the PluginChCmsApiValidateParamFilter class.
 *
 * @package chCmsApiPlugin
 * @subpackage lib-filter
 * @author Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright (c) Carpe Hora SARL 2012
 * @since 2012-01-24
 */

/**
 * plugin class
 */
class PluginChCmsApiValidateParamFilter extends sfFilter
{
  /** debug timers */
  protected $timers;

  /**
   * excute the filter
   **/
  public function execute($filterChain)
  {
    // get the current action instance
    $actionInstance = $this->context->getController()->getActionStack()->getLastEntry()->getActionInstance();

    $response = $this->getContext()->getResponse();
    $request  = $this->getContext()->getRequest();

    if ($actionInstance instanceof chCmsApiActions)
    {
      try
      {
        $this->initTimer('Api request parsing');

        $this->validateRequestParameters();

        $this->endTimer('Api request parsing');
      }
      catch(Exception $e)
      {
        $this->endTimer('Api request parsing');

        throw $e;
      }
    }

    $this->initTimer('Api action processing');
    $filterChain->execute();
    $this->endTimer('Api action processing');
  }

  /**
   * execute the request parameter validation
   * and update request parameters
   *
   * @return void
   * @throw chCmsApiErrorException if paramters are invalid
   */
  protected function validateRequestParameters()
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
   * get the dedicated timer
   *
   * @param String $name timer name.
   * @return sfTimer
   */
  protected function initTimer($name)
  {
    $is_debug = sfConfig::get('sf_debug');
    if ($is_debug)
    {
      return $this->timer[$name] = sfTimerManager::getTimer($name);
    }
  }

  /**
   * closes a dedicated timer
   *
   * @param String $name timer name.
   */
  protected function endTimer($name)
  {
    $is_debug = sfConfig::get('sf_debug');
    if ($is_debug)
    {
      return $this->timer[$name]->addTime();
    }
  }
} // END OF PluginChCmsApiValidateParamFilter
