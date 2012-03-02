<?php

/**
 * Base actions for the chCmsApiPlugin apiDoc module.
 *
 * @package     chCmsApiPlugin
 * @subpackage  apiDoc
 * @author      Kévin Gomez <kevin_gomez@carpe-hora.com>
 */
abstract class BaseapiDocActions extends sfActions
{
  public function preExecute()
  {
    $this->setLayout(__DIR__.'/../templates/layout');
  }

  /**
   * Executes listRoutes action
   *
   * @param sfRequest $request A request object
   */
  public function executeListRoutes(sfWebRequest $request)
  {
    $routes = $this->extractApiRoutes();

    $extractor = new chRouteDocumentationExtractor();
    $this->apiMethods = $this->routesToApiData($routes, $extractor);
  }

  /**
   * Executes listRoutes action
   *
   * @param sfRequest $request A request object
   */
  public function executeMethodDoc(sfWebRequest $request)
  {
    $routes = $this->context->getRouting()->getRoutes();

    // does the route exists?
    $this->route_name = $request->getParameter('route');
    $this->forward404Unless(isset($routes[$this->route_name]));

    // is the route public?
    $this->route = $routes[$this->route_name];
    $this->forward404Unless($this->isApiMethodPublic($routes[$this->route_name]));
    $options = $this->route->getOptions();

    // extract the data
    $extractor = new chDocumentationExtractor();
    $extractor->registerExtractor('param_validator', new chParamValidatorDocumentationExtractor());
    $extractor->registerExtractor('route', new chRouteDocumentationExtractor());

    $data = array_merge(
      $extractor->extract($options['param_validator']),
      $extractor->extract($this->route)
    );

    // expose the extracted data to the template
    sfConfig::set('sf_escaping_strategy', false);
    foreach ($data as $key => $value)
    {
      $this->{$key} = $value;
    }
  }


  protected function routesToApiData($routes, chExtractorInterface $extractor)
  {
    $data = array();
    foreach ($routes as $id => $route)
    {
      $data[$id] = $this->routeToApiData($route, $extractor);
    }

    return $data;
  }

  protected function routeToApiData($route, chExtractorInterface $extractor)
  {
    return $extractor->extract($route);
  }

  protected function extractApiRoutes()
  {
    $routes = array();
    $routing = $this->context->getRouting();

    foreach ($routing->getRoutes() as $id => $route)
    {
      $options = $route->getOptions();

      if (empty($options['param_validator']) || !$this->isApiMethodPublic($route))
      {
        continue;
      }

      $routes[$id] = $route;
    }

    return $routes;
  }

  protected function isApiMethodPublic($route)
  {
    $options = $route->getOptions();
    return !(array_key_exists('public_api', $options) && !$options['public_api']);
  }
}
