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
   * Executes listFormatters action
   *
   * @param sfRequest $request A request object
   */
  public function executeListFormatters(sfWebRequest $request)
  {
    $this->formatters = $this->extractApiFormatters();
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
    $this->forward404Unless($this->isApiMethodPublic($routes[$this->route_name]));
    $options = $this->route->getOptions();

    // extract the data
    $extractor = new chDocumentationExtractor();
    $extractor->registerExtractor('param_validator', new chParamValidatorDocumentationExtractor());
    $extractor->registerExtractor('route', new chRouteDocumentationExtractor());

    try
    {
      $data = array_merge(
        $extractor->extract($options['param_validator']),
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
    $this->forward404Unless(class_exists($this->formatter));
    $this->forward404Unless($this->isFormatterValid($this->formatter));

    // extract the data
    $extractor = new chDocumentationExtractor();
    $extractor->registerExtractor('formatter', new chFormatterDocumentationExtractor());
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
    $this->routes = $this->extractApiRoutes();
    $this->test_route = $request->getParameter('route', '');
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

  protected function extractApiFormatters()
  {
    $formatters = array();

    $classes = sfFinder::type('file')
      ->name('*Formatter{,.class}.php')
      ->in(sfConfig::get('sf_root_dir'));
    foreach ($classes as $file)
    {
      $class = explode(DIRECTORY_SEPARATOR, $file);
      $class = explode('.', $class[count($class) - 1]);
      $class = $class[0];

      if (!$this->isFormatterValid($class))
      {
        continue;
      }

      $formatters[$class] = array();
    }

    ksort($formatters, SORT_LOCALE_STRING);

    return $formatters;
  }

  /**
   * Tells if a given formatter is valid (ie: can be displayed in the doc).
   *
   * @param string $formatter The formatter name.
   *
   * @return bool
   * @author Kevin Gomez <kevin_gomez@carpe-hora.com>
   */
  protected function isFormatterValid($formatter)
  {
    // skip Plugin* classes
    if (substr($formatter, 0, 6) === 'Plugin')
    {
      return false;
    }

    try
    {
      $rClass = new ReflectionClass($formatter);
      // a formatter must inherit from BasechCmsApiFormatter
      if ($rClass->isAbstract() || !$rClass->isSubclassOf('BasechCmsApiFormatter'))
      {
        return false;
      }
    }
    catch (ReflectionException $e)
    {
      return false;
    }

    return true;
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
}
