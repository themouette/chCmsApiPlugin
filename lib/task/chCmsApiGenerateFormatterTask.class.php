<?php
/**
 * This file declare the chCmsApiGenerateFormatterTask class.
 *
 * @package chCmsApiPlugin
 * @subpackage task
 * @author Julien Muetton <julien_muetton@carpe-hora.com>
 * @copyright (c) Carpe Hora SARL 2011
 * @since 2011-09-04
 */

/**
 * task generating formatter classes
 */
class chCmsApiGenerateFormatterTask extends sfGeneratorBaseTask
{
  public function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('name', sfCommandArgument::REQUIRED, 'The formatter name'),
    ));

    $this->addOptions(array(
      new sfCommandOption('base-class', null, sfCommandOption::PARAMETER_REQUIRED, 'The base class to extend from', 'chCmsApiObjectFormatter'),
      new sfCommandOption('plugin', null, sfCommandOption::PARAMETER_REQUIRED, 'The plugin to generate class in', null),
    ));

    $this->namespace = 'chCms';
    $this->name = 'generate-formatter';

    $this->briefDescription = 'Generates a formatter';

    $this->detailedDescription = <<<EOF
The [chCms:generate-formatter|INFO] task creates a formatter for given model.
It is possible to provide [base-class|INFO] and [plugin|INFO] options:

  [./symfony chCms:generate-formatter myModel --plugin=myPlugin --base-class=myOtherModelFormatter|INFO]

will generate plugins/myPlugin/lib/formatter/plugin/PluginMyModelFormatter.class.php and
plugins/myPlugin/lib/formatter/myModelFormatter.class.php files.

myModelFormatter extends from PluginMyModelFormatter, and PluginMyModelFormatter extends from myOtherModelFormatter.
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $options['path'] = $this->getGenerationPath($arguments, $options);
    $this->logSection('generate', sprintf('base path is %s', $options['path']));

    // prepare generation
    if (isset($options['plugin']) && $options['plugin'])
    {
      $options['base-class'] = $this->generatePluginFormatter($arguments, $options);
    }

    $this->generateFormatter($arguments, $options);
  }

  protected function generatePluginFormatter($arguments, $options)
  {
    $className = $this->getFormatterClassname(sprintf('Plugin%s', ucfirst($arguments['name'])));
    $path = sprintf('%s/plugin', $options['path']);

    $skeleton = $this->getSkeletonPath('FormatterClass.class.php');

    $this->getFilesystem()->mkdirs($path);
    $dest = sprintf('%s/%s.class.php', $path, $className);
    $this->getFilesystem()->copy($skeleton, $dest);
    $this->getFilesystem()->replaceTokens($dest, '##', '##', array(
        'FormatterClass' => $className,
        'BaseFormatter'  => $options['base-class'],
        'package'        => (isset($options['plugin']) && $options['plugin']) ? $options['plugin'] : 'lib'
      ));

    $this->logSection('generate', sprintf('generate class %s extending %s', $className, $options['base-class']));
    return $className;
  }

  protected function generateFormatter($arguments, $options)
  {
    $className = $this->getFormatterClassname($arguments['name']);
    $path = $options['path'];
    if (isset($options['plugin']) && $options['plugin'])
    {
      $skeleton = $this->getSkeletonPath('formatter.class.php');
    }
    else
    {
      $skeleton = $this->getSkeletonPath('FormatterClass.class.php');
    }

    $this->getFilesystem()->mkdirs($path);
    $dest = sprintf('%s/%s.class.php', $path, $className);
    $this->getFilesystem()->copy($skeleton, $dest);
    $this->getFilesystem()->replaceTokens($dest, '##', '##', array(
        'FormatterClass' => $className,
        'BaseFormatter'  => $options['base-class'],
        'package'        => (isset($options['plugin']) && $options['plugin']) ? $options['plugin'] : 'lib'
      ));

    $this->logSection('generate', sprintf('generate class %s extending %s', $className, $options['base-class']));
  }

  protected function getFormatterClassname($name)
  {
    if (!preg_match('#Formatter$#', $name))
    {
      return sprintf('%sFormatter', $name);
    }
    return $name;
  }

  protected function getGenerationPath($arguments, $options)
  {
    // prepare the path
    if (isset($options['plugin']) && $options['plugin'])
    {
      $path = sprintf('%s/%s', sfConfig::get('sf_plugins_dir'), $options['plugin']);

      if (!file_exists($path) || !is_dir($path))
      {
        throw new InvalidArgumentException(sprintf('Unable to find plugin "%s"', $options['plugin']));
      }
    }
    else
    {
      $path = sfConfig::get('sf_lib_dir');
    }

    $path = sprintf('%s/lib/formatter', $path);

    return $path;
  }

  protected function getSkeletonPath($skeleton)
  {
    // first check in /data/generator/skeleton/formatter
    $dir = sfConfig::get('sf_data_dir') . '/skeleton/formatter';
    if (file_exists($path = sprintf('%s/%s', $dir, $skeleton)))
    {
      return $path;
    }

    return realpath(sprintf('%s/../../data/skeleton/formatter/%s', dirname(__FILE__), $skeleton));
  }
} // END OF chCmsApiGenerateFormatterTask
