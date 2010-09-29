<?php

/**
* 
*/
class sfSlimThemeGenerator extends sfThemeGenerator
{
  public function linkTo($action, $params)
  {
    $action = strpos($action, '_') === 0 ? substr($name, 1) : $action;
    
    $method = sprintf('linkTo%s', sfInflector::camelize($action));
    
    return method_exists($this, $method) ? $this->$method($params) : $this->getLinkToAction($action, $params, true);
  }
  
  public function linkToNew($params)
  {
    return '[?php echo $helper->linkToNew('.$this->asPhp($params).') ?]';
  }
  
  public function linkToExport($params)
  {
    return '[?php echo $helper->linkToNew('.$this->asPhp($params).') ?]';
  }
  
  public function linkToDelete($params)
  {
    return '[?php echo $helper->linkToDelete($form->getObject(), '.$this->asPhp($params).') ?]';
  }
  
  public function linkToList($params)
  {
    return '[?php echo $helper->linkToList('.$this->asPhp($params).') ?]';
  }
  
  public function linkToSave($params)
  {
    return '[?php echo $helper->linkToSave($form->getObject(), '.$this->asPhp($params).') ?]';
  }
  
  public function linkToSaveAndAdd($params)
  {
    '[?php echo $helper->linkToSaveAndAdd($form->getObject(), '.$this->asPhp($params).') ?]';
  }
  
  public function renderField($field)
  {
    if (!$field instanceof sfModelGeneratorConfigurationField) 
    {
      return $field;
    }
    
    $html = $this->getColumnGetter($field->getName(), true);

    if ($renderer = $field->getRenderer())
    {
      $html = sprintf("$html ? call_user_func_array(%s, array_merge(array(%s), %s)) : '&nbsp;'", $this->asPhp($renderer), $html, $this->asPhp($field->getRendererArguments()));
    }
    else if ($field->isComponent())
    {
      return sprintf("get_component('%s', '%s', array('type' => 'list', '%s' => \$%s))", $this->getModuleName(), $field->getName(), $this->getSingularName(), $this->getSingularName());
    }
    else if ($field->isPartial())
    {
      return sprintf("get_partial('%s/%s', array('type' => 'list', '%s' => \$%s))", $this->getModuleName(), $field->getName(), $this->getSingularName(), $this->getSingularName());
    }
    else if ('Date' == $field->getType())
    {
      $html = sprintf("false !== strtotime($html) ? format_date(%s, \"%s\") : '&nbsp;'", $html, $field->getConfig('date_format', 'f'));
    }
    else if ('Boolean' == $field->getType())
    {
      $html = sprintf("get_partial('%s/list_field_boolean', array('value' => %s))", $this->getModuleName(), $html);
    }

    if ($field->isLink())
    {
      $html = sprintf("link_to(%s, '%s', \$%s)", $html, $this->getUrlForAction('edit'), $this->getSingularName());
    }

    return $html;
  }
  
  /**
   * Override this to rename base files
   */
  public function generate($params = array())
  {
    $this->validateParameters($params);

    $this->modelClass = $this->params['model_class'];

    // generated module name
    $this->setModuleName($this->params['moduleName']);
    $this->setGeneratedModuleName('auto'.ucfirst($this->params['moduleName']));

    // theme exists?
    $theme = isset($this->params['theme']) ? $this->params['theme'] : 'default';
    $this->setTheme($theme);
    $themeDir = $this->generatorManager->getConfiguration()->getGeneratorTemplate($this->getGeneratorClass(), $theme, '');
    if (!is_dir($themeDir))
    {
      throw new sfConfigurationException(sprintf('The theme "%s" does not exist.', $theme));
    }

    // configure the model
    $this->configure();

    $this->configuration = $this->loadConfiguration();

    // generate files
    $this->generatePhpFiles($this->generatedModuleName, sfFinder::type('file')->relative()->in($themeDir));

    // move helper file
    if (file_exists($file = $this->generatorManager->getBasePath().'/'.$this->getGeneratedModuleName().'/lib/helper.php'))
    {
      @rename($file, $this->generatorManager->getBasePath().'/'.$this->getGeneratedModuleName().'/lib/'.$this->moduleName.'GeneratorHelper.class.php');
    }

    return "require_once(sfConfig::get('sf_module_cache_dir').'/".$this->generatedModuleName."/actions/actions.class.php');";
  }
  
  /**
   * Loads the configuration for this generated module.
   */
  protected function loadConfiguration()
  {
    try
    {
      $this->generatorManager->getConfiguration()->getGeneratorTemplate($this->getGeneratorClass(), $this->getTheme(), '../parts/configuration.php');
    }
    catch (sfException $e)
    {
      return null;
    }

    $config = $this->getGeneratorManager()->getConfiguration();
    if (!$config instanceof sfApplicationConfiguration)
    {
      throw new LogicException('The sfModelGenerator can only operates with an application configuration.');
    }

    $basePath = $this->getGeneratedModuleName().'/lib/'.$this->getModuleName().'GeneratorConfiguration.class.php';
    $this->getGeneratorManager()->save($basePath, $this->evalTemplate('../parts/configuration.php'));

    require_once $this->getGeneratorManager()->getBasePath().'/'.$basePath;

    $class = 'Base'.ucfirst($this->getModuleName()).'GeneratorConfiguration';

    foreach ($config->getLibDirs($this->getModuleName()) as $dir)
    {
      if (!is_file($configuration = $dir.'/'.$this->getModuleName().'GeneratorConfiguration.class.php'))
      {
        continue;
      }

      require_once $configuration;
      $class = $this->getModuleName().'GeneratorConfiguration';
      break;
    }

    // validate configuration
    foreach ($this->config as $context => $value)
    {
      if (!$value)
      {
        continue;
      }

      throw new InvalidArgumentException(sprintf('Your generator configuration contains some errors for the "%s" context. The following configuration cannot be parsed: %s.', $context, $this->asPhp($value)));
    }

    return new $class();
  }
}
