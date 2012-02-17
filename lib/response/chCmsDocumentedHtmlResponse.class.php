<?php
/**
 * This file declare the chCmsDocumentedHtmlResponse class.
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
class chCmsDocumentedHtmlResponse
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
    $vars = $this->getVariablesForTemplate($results, $request, $response);
    extract($vars);

    ob_start();
    ob_implicit_flush(0);

    require sfConfig::get('app_chCmsApiPlugin_docTemplate');

    return ob_get_clean();
  }

  protected function getVariablesForTemplate($results, $request, $response)
  {
    $route = $request->getAttribute('sf_route');
    $paramValidator = $request->getParamValidator();
    $context_data = $request->getRequestContext();

    return array_merge(array(
      'RESULT'            => htmlentities($results, ENT_QUOTES),
      'HTTP_METHODS'      => $this->getHttpMethods($route),
      'FORMAL_URL'        => $this->getFormalUrl($route),
      'SUPPORTED_FORMATS' => $this->getFormats($route),
      'DEFAULT_FORMAT'    => $this->getDefaultFormat($route),
      'AUTH_REQUIRED'     => $context_data['is_secure'],
    ), $this->getVariablesFromParamValidator($paramValidator));
  }

  protected function getVariablesFromParamValidator($paramValidator)
  {
    if (!$paramValidator)
    {
      return array();
    }

    $params = array();
    foreach ($paramValidator->getValidatorSchema()->getFields() as $field => $validator)
    {
      $data = array(
        'required'  => $validator->getOption('required', true) ? 'yes' : 'no',
      );

      $default = $validator->getOption('default');
      if ($default !== null)
      {
        $data['default'] = $default;
      }

      $comment = $validator->getOption('comment');
      if (!empty($comment))
      {
        $data['comment'] = $comment;
      }

      $params[$field] = $data;
    }

    // retrieve the description from the param validator
    $description = $this->getDescriptionFromClass($paramValidator);
    $description = trim(str_replace(array('/**', '/*', '*/'), '', $description));
    $description = preg_replace('#^\*[ ]*#', '', $description);

    return array(
      'PARAMS'      => $params,
      'DESCRIPTION' => $description,
    );
  }

  protected function getDescriptionFromClass($class, $try_parent = true)
  {
    $rClass = $class instanceof ReflectionClass ? $class : new ReflectionClass($class);

    if ($try_parent)
    {
      return $this->getDescriptionFromClass($rClass->getParentClass(), false);
    }

    return $rClass->getDocComment();
  }

  protected function getHttpMethods($route)
  {
    $requirements = $route->getRequirements();

    return array_map('strtoupper', $requirements['sf_method']);
  }

  protected function getFormalUrl($route)
  {
    return $route->getPattern();
  }

  protected function getDefaultFormat($route)
  {
    $defaults = $route->getDefaults();

    return $defaults['sf_format'];
  }

  protected function getFormats($route)
  {
    $requirements = $route->getRequirements();
    $formats_regex = $requirements['sf_format'];

    $formats = str_replace(array('(?:', ')'), '', $formats_regex);
    return explode('|', $formats);
  }
}
