<?php
/**
 * This file declare the PluginChParamValidatorDocumentationExtractor class.
 *
 * @package     chCmsApiPlugin
 * @subpackage  doc
 * @author      Kévin Gomez <kevin_gomez@carpe-hora.com>
 * @copyright   (c) Carpe Hora SARL 2012
 * @since       2012-02-17
 */

/**
 * Class used extract documentation information from a param validator.
 */
class PluginChParamValidatorDocumentationExtractor extends AbstractDocumentationExtractor
{
  public function extract($paramValidator, $options = array())
  {
    if (!$paramValidator)
    {
      return array();
    }

    $paramValidator = $this->createValidatorObject($paramValidator);
    if (!$paramValidator instanceof chCmsApiParamValidator)
    {
      return array();
    }

    $params = array();
    foreach ($paramValidator->getValidatorSchema()->getFields() as $field => $validator)
    {
      $params[$field] = array_map(array($this, 'prepareObjects'), array_filter($validator->getOptions(), array($this, 'filterObjects')));
    }

    return array(
      'PARAMS'      => $params,
      'DESCRIPTION' => $this->getDescription($paramValidator),
    );
  }

  public function prepareObjects($var)
  {
    if (!$var || is_scalar($var))
    {
      return $var;
    }

    if (is_array($var))
    {
      return implode(', ', array_filter($var, array($this,'filterObjects')));
    }

    return var_export($var, true);
  }

  public function filterObjects($var)
  {
    return !is_object($var);
  }

  protected function createValidatorObject($paramValidator)
  {
    if (is_object($paramValidator))
    {
      return $paramValidator;
    }

    // class name
    return new $paramValidator;
  }
}