<?php
/**
 * This file declare the chCmsApiGenerateValidatorTask class.
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
class chCmsApiGenerateValidatorTask extends sfGeneratorBaseTask
{
  public function configure()
  {
    $this->addArguments(array(
      new sfCommandArgument('name', sfCommandArgument::REQUIRED, 'The validator name'),
    ));

    $this->addOptions(array(
      new sfCommandOption('base-class', null, sfCommandOption::PARAMETER_REQUIRED, 'The base class to extend from', 'sfValidator'),
      new sfCommandOption('plugin', null, sfCommandOption::PARAMETER_REQUIRED, 'The plugin to generate class in', null),
    ));

    $this->namespace = 'chCms';
    $this->name = 'generate-validator';

    $this->briefDescription = 'Generates a param validator';

    $this->detailedDescription = <<<EOF
The [chCms:generate-validator|INFO] task creates a validator for given model.
It is possible to provide [base-class|INFO] and [plugin|INFO] options:

  [./symfony chCms:generate-validator myAction --plugin=myPlugin --base-class=myOtherModelValidator|INFO]

will generate plugins/myPlugin/lib/validator/plugin/PluginMyActionValidator.class.php and
plugins/myPlugin/lib/validator/myActionValidator.class.php files.

myActionValidator extends from PluginMyActionValidator, and PluginMyActionValidator extends from myOtherModelValidator.
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // prepare generation
    if (isset($options['plugin']) && $options['plugin'])
    {
      $options['base-class'] = $this->generatePluginValidator($arguments, $options);
    }

    $this->generateValidator($arguments, $options);

    $this->generateTestSuite($arguments, $options);
  }

  protected function generatePluginValidator($arguments, $options)
  {
    $className = $this->getValidatorClassname(sprintf('Plugin%s', ucfirst($arguments['name'])));
    $path = sprintf('%s/plugin', $this->getGenerationLibPath($arguments, $options));

    $skeleton = $this->getSkeletonPath('validatorClass.class.php');

    $this->getFilesystem()->mkdirs($path);
    $dest = sprintf('%s/%s.class.php', $path, $className);
    $this->getFilesystem()->copy($skeleton, $dest);
    $this->getFilesystem()->replaceTokens($dest, '##', '##', array(
        'validatorClass' => $className,
        'BaseValidator'  => $options['base-class'],
        'package'        => (isset($options['plugin']) && $options['plugin']) ? $options['plugin'] : 'lib',
      ));

    $this->logSection('generate', sprintf('generate class %s extending %s', $className, $options['base-class']));
    return $className;
  }

  protected function generateValidator($arguments, $options)
  {
    $className = $this->getValidatorClassname($arguments['name']);
    $path = $this->getGenerationLibPath($arguments, $options);
    if (isset($options['plugin']) && $options['plugin'])
    {
      $skeleton = $this->getSkeletonPath('validator.class.php');
    }
    else
    {
      $skeleton = $this->getSkeletonPath('validatorClass.class.php');
    }

    $this->getFilesystem()->mkdirs($path);
    $dest = sprintf('%s/%s.class.php', $path, $className);
    $this->getFilesystem()->copy($skeleton, $dest);
    $this->getFilesystem()->replaceTokens($dest, '##', '##', array(
        'validatorClass' => $className,
        'BaseValidator'  => $options['base-class'],
        'package'        => (isset($options['plugin']) && $options['plugin']) ? $options['plugin'] : 'lib'
      ));

    $this->logSection('generate', sprintf('generate class %s extending %s', $className, $options['base-class']));
  }

  protected function generateTestSuite($arguments, $options)
  {
    $className = $this->getValidatorClassname($arguments['name']);
    $path = $this->getGenerationTestPath($arguments, $options);

    $skeleton = $this->getSkeletonPath('test/unit/validator/validatorTest.php');
    $this->getFilesystem()->mkdirs($path);
    $dest = sprintf('%s/%sTest.php', $path, $className);
    $this->getFilesystem()->copy($skeleton, $dest);
    $this->getFilesystem()->replaceTokens($dest, '##', '##', array(
        'validatorClass' => $className,
      ));

    $this->logSection('generate', sprintf('generate test file %s for %s', $dest, $className));
  }

  protected function getValidatorClassname($name)
  {
    if (!preg_match('#Validator#', $name))
    {
      return sprintf('%sValidator', $name);
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
    $path = sprintf('%s/lib/validator', $this->getGenerationPath($arguments, $options));

    return $path;
  }

  protected function getGenerationTestPath($arguments, $options)
  {
    $path = sprintf('%s/test/unit/validator', $this->getGenerationPath($arguments, $options));

    return $path;
  }

  protected function getSkeletonPath($skeleton)
  {
    // first check in /data/generator/skeleton/validator
    $dir = sfConfig::get('sf_data_dir') . '/skeleton/validator';
    if (file_exists($path = sprintf('%s/%s', $dir, $skeleton)))
    {
      return $path;
    }

    return realpath(sprintf('%s/../../data/skeleton/validator/%s', dirname(__FILE__), $skeleton));
  }
} // END OF chCmsApiGenerateValidatorTask

