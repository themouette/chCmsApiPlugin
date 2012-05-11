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
  protected $api_tools;


  public function preExecute()
  {
    $this->api_tools = new chCmsApiTools();
    $this->setLayout(__DIR__.'/../templates/layout');
  }

  /**
   * Executes listRoutes action
   *
   * @param sfRequest $request A request object
   */
  public function executeListRoutes(sfWebRequest $request)
  {
    $routes = $this->api_tools->extractApiRoutes($this->context->getRouting());
    $extractor = new chRouteDocumentationExtractor($this->getExtractorOptions(array(
      'context' => $this->context
    )));
    $this->apiMethods = array();

    foreach ($routes as $id => $route)
    {
      $this->apiMethods[$id] = $extractor->extract($route);
    }
    ksort($this->apiMethods);
  }

  /**
   * Executes listFormatters action
   *
   * @param sfRequest $request A request object
   */
  public function executeListFormatters(sfWebRequest $request)
  {
    $this->formatters = $this->api_tools->extractApiFormatters();
  }

  /**
   * Executes methodDoc action
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
    $this->forward404Unless($this->api_tools->isApiMethodPublic($this->route));
    $route_options = $this->route->getOptions();

    // extractors options
    $options = $this->getExtractorOptions(array(
      'context' => $this->context
    ));

    // extract the data
    $extractor = new chDocumentationExtractor();
    $extractor->registerExtractor('param_validator', new chParamValidatorDocumentationExtractor($options));
    $extractor->registerExtractor('route', new chRouteDocumentationExtractor($options));

    try
    {
      $data = array_merge(
        $extractor->extract($route_options['param_validator']),
        $extractor->extract($this->route)
      );
    }
    catch (Exception $e)
    {
      $this->getUser()->setFlash('error', 'Impossible to generate the documentation for the "'.$this->route_name.'" method.');
      $this->redirect('api_methods');
    }

    // expose the extracted data to the template
    sfConfig::set('sf_escaping_strategy', false);
    $this->expose($data);
  }

  /**
   * Executes formatterDoc action
   *
   * @param sfRequest $request A request object
   */
  public function executeFormatterDoc(sfWebRequest $request)
  {
    $this->formatter = $request->getParameter('formatter');
    $this->forward404Unless($this->api_tools->isFormatterValid($this->formatter));

    // extract the data
    $extractor = new chFormatterDocumentationExtractor($this->getExtractorOptions());
    try
    {
      $data = $extractor->extract($this->formatter);
    }
    catch (Exception $e)
    {
      $this->getUser()->setFlash('error', 'Impossible to generate the documentation for the "'.$this->formatter.'" formatter.');
      $this->redirect('api_formatters');
    }

    // expose the extracted data to the template
    sfConfig::set('sf_escaping_strategy', false);
    $this->expose($data);
  }

  /**
   * Executes sandbox action
   *
   * @param sfRequest $request A request object
   */
  public function executeSandbox(sfWebRequest $request)
  {
    $this->routes = $this->api_tools->extractApiRoutes($this->context->getRouting());
    $this->test_route = $request->getParameter('route', '');
  }


  /**
   * Expose the given array in the view
   *
   * @author Kevin Gomez <kevin_gomez@carpe-hora.com>
   */
  protected function expose(array $data)
  {
    foreach ($data as $key => $value)
    {
      $this->{$key} = $value;
    }
  }

  protected function getExtractorOptions($options = array())
  {
    $description_parser = sfConfig::get('app_chCmsApiPlugin_descriptionParser', 'Markdown_Parser');

    return array_merge(array(
      'description_parser' => new $description_parser
    ), $options);
  }
}
