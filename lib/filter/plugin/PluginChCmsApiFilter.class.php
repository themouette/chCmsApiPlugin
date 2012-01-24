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
  /** debug timers */
  protected $timers;

  /**
   * excute the filter
   **/
  public function execute($filterChain)
  {
    // get the current action instance
    $actionInstance = $this->context->getController()->getActionStack()->getLastEntry()->getActionInstance();

    if ($actionInstance instanceof chCmsApiActions)
    {
      $this->initTimer('chCmsApiFilter');

      try
      {
        $response = $this->getContext()->getResponse();
        $request  = $this->getContext()->getRequest();

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
        if ($this->getContext()->getUser()->isAuthenticated())
        {
          $response->setStatusCode('403');
          $response->setApiResultContent(array('error' => array(
            'code'    => 'INSUFFICIENT_CREDENTIAL',
            'message' => $e->getMessage()
                            ? $e->getMessage()
                            : 'this ressource is protected') ), $request);
        }
        else
        {
          $response->setStatusCode('401');
          $response->setApiResultContent(array('error' => array(
            'code'    => 'AUTHENTICATION_REQUIRED',
            'message' => $e->getMessage()
                            ? $e->getMessage()
                            : 'this ressource is protected') ), $request);
        }
      }

      $this->endTimer('chCmsApiFilter');
    }
    else
    {
      $filterChain->execute();
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
} // END OF PluginChCmsApiFilter
