<?php
/**
 * This file declare the PluginPluginChCmsDocumentedHtmlResponse class.
 *
 * @package     chCmsApiPlugin
 * @subpackage  response
 * @author      Kévin Gomez <kevin_gomez@carpe-hora.com>
 * @copyright   (c) Carpe Hora SARL 2012
 * @since       2012-02-17
 */

/**
 * Class used to self-document an API route.
 */
class PluginChCmsDocumentedHtmlResponse
{
  /**
   * Format the given results, and insert the documentation.
   *
   * @param mixed         $results  The results to return.
   * @param sfWebRequest  $request  The request object.
   * @param sfWebResponse $response The response object.
   *
   * @return string The page in HTML.
   * @author Kevin Gomez <kevin_gomez@carpe-hora.com>
   */
  public function format($results, $request, $response)
  {
    $vars = $this->getVariablesForTemplate($results, $request);
    extract($vars);

    ob_start();
    ob_implicit_flush(0);

    require sfConfig::get('app_chCmsApiPlugin_docLayoutTemplate');

    return ob_get_clean();
  }

  protected function getVariablesForTemplate($results, $request)
  {
    $extractor = $this->getExtractor();

    return array_merge(
      array('RESULT' => htmlentities($results, ENT_QUOTES)),
      $extractor->extractFromRequest($request)
    );
  }

  protected function getExtractor()
  {    
    $extractor = new chDocumentationExtractor();
    $extractor->registerExtractor('param_validator', new chParamValidatorDocumentationExtractor());
    $extractor->registerExtractor('route', new chRouteDocumentationExtractor());

    return $extractor;
  }
}