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
        $this->setContentTypeFromRequest();

        $filterChain->execute();
      }
      catch (chCmsError406Exception $e)
      {
        $response = $this->getContext()->getResponse();
        $response->setStatusCode('406');
        $response->setContent(chCmsApiTools::formatResultForRequest($this->getErrorArray($e), $this->context->getRequest(), $response));
      }
    }
    else
    {
      $filterChain->execute();
    }
  }

  /**
   * set the response content type from request
   * @todo use a notifyUntil event to allow more types registration
   *
   * @return void
   **/
  protected function setContentTypeFromRequest()
  {
    chCmsApiTools::setResponseContentType($this->context->getRequest(), $this->context->getResponse());
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
