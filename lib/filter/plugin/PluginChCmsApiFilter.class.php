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

        $this->validateRequestParamters();

        $response->setContentTypeForFormat($request->getRequestFormat());

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
    $options  = $route->getOptions();

    // set default options
    $options = array_merge(array(
          'param_validator' => false), $options);

    // check there is avalidator
    if (  ! ($options['param_validator']) ||
          ! (is_callable(array($options['param_validator'], 'processApiRequest'))))
    {
      // no validator to call
      // return
      return ;
    }

    $paramValidator->processApiRequest($request);

  }

  /**
   * format error into a user friendly array
   * @todo use a filter event to allow extension
   *
   * @return  array
   * @author Julien Muetton <julien_muetton@carpe-hora.com>
   **/
  protected function getErrorArray(chCmsError406Exception $e)
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
