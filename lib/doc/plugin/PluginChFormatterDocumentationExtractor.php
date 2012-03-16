<?php
/**
 * This file declare the PluginChFormatterDocumentationExtractor class.
 *
 * @package     chCmsApiPlugin
 * @subpackage  doc
 * @author      Kévin Gomez <kevin_gomez@carpe-hora.com>
 * @copyright   (c) Carpe Hora SARL 2012
 * @since       2012-03-16
 */

/**
 * Class used extract documentation information from a formatter.
 */
class PluginChFormatterDocumentationExtractor extends AbstractDocumentationExtractor
{
  public function extract($formatter, $options = array())
  {
    if (!$formatter)
    {
      return array();
    }

    $formatter = $this->createFormatterObject($formatter);
    if (!$formatter instanceof BasechCmsApiFormatter)
    {
      return array();
    }

    return array(
      'FIELDS'      => $this->getFields($formatter),
      'DESCRIPTION' => $this->getDescription($formatter)
    );
  }

  protected function getFields($formatter)
  {
    if (!$formatter instanceof chCmsApiArrayFormatter)
    {
      return array();
    }

    $fields = $formatter->getOption('fields');
    if (!$fields)
    {
      return array();
    }

    $data_fields = array();
    foreach ($fields as $field => $fieldFormatter)
    {
      $data_fields[$field] = $this->getFieldFormatterData($fieldFormatter);
    }

    return $data_fields;
  }

  protected function getFieldFormatterData($fieldFormatter)
  {
    $data = array();

    if ($fieldFormatter instanceof chCmsApiDateTimePropertyFormatter)
    {
      $data = array(
        'TYPE' => 'datetime'
      );
    }

    if ($fieldFormatter instanceof chCmsApiCoordinatesPropertyFormatter)
    {
      $data = array(
        'TYPE' => 'coordinates'
      );
    }

    if (in_array(get_class($fieldFormatter), array('chCmsApiCollectionFormatter', 'chCmsApiCollectionPropertyFormatter')))
    {
      $data = array(
        'TYPE'    => 'collection',
        'SUBTYPE' => get_class($fieldFormatter->getFormatter())
      );
    }

    if ($type = $fieldFormatter->getOption('type'))
    {
      $data['TYPE'] = $type;
    }

    return array_merge(array(
      'DESCRIPTION' => $this->getDescription($fieldFormatter, false),
    ), $data);
  }

  protected function createFormatterObject($formatter)
  {
    if (is_object($formatter))
    {
      return $formatter;
    }

    return new $formatter();
  }
}