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
        $filterChain->execute();
      }
      catch (chCmsError406Exception $e)
      {
        $response = $this->getContext()->getResponse();
        $response->setStatusCode('406');
        $response->setContent($e->getMessage());
      }
    }
    else
    {
      $filterChain->execute();
    }
  }
} // END OF PluginChCmsApiFilter
