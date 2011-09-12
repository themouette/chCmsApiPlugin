<?php
/**
 * This file declare the chCmsApiGenerateParamValidatorTask class.
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
class chCmsApiGenerateParamValidatorTask extends sfGeneratorBaseTask
{
  public function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('name', sfCommandArgument::REQUIRED, 'The validator name'),
    ));

    $this->addOptions(array(
      new sfCommandOption('base-class', null, sfCommandOption::PARAMETER_REQUIRED, 'The base class to extend from', 'chCmsApiParamValidator'),
      new sfCommandOption('plugin', null, sfCommandOption::PARAMETER_REQUIRED, 'The plugin to generate class in', null),
    ));

    $this->namespace = 'chCms';
    $this->name = 'generate-param-validator';

    $this->briefDescription = 'Generates a param validator';

    $this->detailedDescription = <<<EOF
The [chCms:generate-validator|INFO] task creates a validator for given model.
It is possible to provide [base-class|INFO] and [plugin|INFO] options:

  [./symfony chCms:generate-validator myAction --plugin=myPlugin --base-class=myOtherModelParamValidator|INFO]

will generate plugins/myPlugin/lib/validator/plugin/PluginMyActionParamValidator.class.php and
plugins/myPlugin/lib/validator/myActionParamValidator.class.php files.

myActionParamValidator extends from PluginMyActionParamValidator, and PluginMyActionParamValidator extends from myOtherModelParamValidator.
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // prepare generation
    if (isset($options['plugin']) && $options['plugin'])
    {
      $options['base-class'] = $this->generatePluginParamValidator($arguments, $options);
    }

    $this->generateParamValidator($arguments, $options);

    $this->generateTestSuite($arguments, $options);
  }

  protected function generatePluginParamValidator($arguments, $options)
  {
    $className = $this->getParamValidatorClassname(sprintf('Plugin%s', ucfirst($arguments['name'])));
    $path = sprintf('%s/plugin', $this->getGenerationLibPath($arguments, $options));

    $skeleton = $this->getSkeletonPath('paramValidatorClass.class.php');

    $this->getFilesystem()->mkdirs($path);
    $dest = sprintf('%s/%s.class.php', $path, $className);
    $this->getFilesystem()->copy($skeleton, $dest);
    $this->getFilesystem()->replaceTokens($dest, '##', '##', array(
        'paramValidatorClass' => $className,
        'BaseParamValidator'  => $options['base-class'],
        'package'        => (isset($options['plugin']) && $options['plugin']) ? $options['plugin'] : 'lib',
      ));

    $this->logSection('generate', sprintf('generate class %s extending %s', $className, $options['base-class']));
    return $className;
  }

  protected function generateParamValidator($arguments, $options)
  {
    $className = $this->getParamValidatorClassname($arguments['name']);
    $path = $this->getGenerationLibPath($arguments, $options);

    if (isset($options['plugin']) && $options['plugin'])
    {
      $skeleton = $this->getSkeletonPath('paramValidator.class.php');
    }
    else
    {
      $skeleton = $this->getSkeletonPath('paramValidatorClass.class.php');
    }

    $this->getFilesystem()->mkdirs($path);
    $dest = sprintf('%s/%s.class.php', $path, $className);
    $this->getFilesystem()->copy($skeleton, $dest);
    $this->getFilesystem()->replaceTokens($dest, '##', '##', array(
        'paramValidatorClass' => $className,
        'BaseParamValidator'  => $options['base-class'],
        'package'        => (isset($options['plugin']) && $options['plugin']) ? $options['plugin'] : 'lib'
      ));

    $this->logSection('generate', sprintf('generate class %s extending %s', $className, $options['base-class']));
  }

  protected function generateTestSuite($arguments, $options)
  {
    $className = $this->getParamValidatorClassname($arguments['name']);
    $path = $this->getGenerationTestPath($arguments, $options);

    $skeleton = $this->getSkeletonPath('test/unit/param/paramValidatorTest.php');
    $this->getFilesystem()->mkdirs($path);
    $dest = sprintf('%s/%sTest.php', $path, $className);
    $this->getFilesystem()->copy($skeleton, $dest);
    $this->getFilesystem()->replaceTokens($dest, '##', '##', array(
        'paramValidatorClass' => $className,
      ));

    $this->logSection('generate', sprintf('generate test file %s for %s', $dest, $className));
  }

  protected function getParamValidatorClassname($name)
  {
    if (!preg_match('#ParamValidator$#', $name))
    {
      return sprintf('%sParamValidator', $name);
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

      return $path;
    }

    return sfConfig::get('sf_root_dir');
  }

  protected function getGenerationLibPath($arguments, $options)
  {
    $path = sprintf('%s/lib/param', $this->getGenerationPath($arguments, $options));

    return $path;
  }

  protected function getGenerationTestPath($arguments, $options)
  {
    $path = sprintf('%s/test/unit/param', $this->getGenerationPath($arguments, $options));

    return $path;
  }

  protected function getSkeletonPath($skeleton)
  {
    // first check in /data/generator/skeleton/validator
    $dir = sfConfig::get('sf_data_dir') . '/skeleton/param';
    if (file_exists($path = sprintf('%s/%s', $dir, $skeleton)))
    {
      return $path;
    }

    return realpath(sprintf('%s/../../data/skeleton/param/%s', dirname(__FILE__), $skeleton));
  }
} // END OF chCmsApiGenerateParamValidatorTask

