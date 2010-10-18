<?php

/**
* 
*/
class sfHadoriThemeGenerator extends sfThemeGenerator
{ 
  public function getUrlForAction($action)
  {
    return sprintf('%s%s', $this->getSingularName(), in_array($action, array('list', 'index')) ? '' : '_'.$action);
  }
  
  public function linkToNew($params)
  {
    $attributes = array_merge(array('title' => 'Add A New ' . $this->getClassLabel()), $params['attributes']);
    return $this->renderLinkToBlock($params['label'], $this->getUrlForAction('new'), $attributes);
  }
  
  public function linkToShow($params)
  {
    $attributes = array_merge(array('title' => 'View ' . $this->getClassLabel()), $params['attributes']);

    return $this->renderLinkToBlock($params['label'], $this->getUrlForAction('show'), $attributes, true);
  }
  
  public function linkToEdit($params)
  {
    $attributes = array_merge(array('title' => 'Edit ' . $this->getClassLabel()), $params['attributes']);
    
    return $this->renderLinkToBlock($params['label'], $this->getUrlForAction('edit'), $attributes, true);
  }
  
  public function linkToExport($params)
  {
    $attributes = array_merge(array('title' => 'Export ' . $this->getClassLabel() . ' Data'), $params['attributes']);

    return $this->renderLinkToBlock($params['label'], $this->getUrlForAction('export'), $attributes);
  }
  
  public function linkToSave($params)
  {
    return '<input class="greyButton" type="submit" value="'.$params['label'].'" />';
  }
  
  public function linkToSaveAndAdd($params)
  {
    $link = <<<EOF
[?php if(!$%s->isNew()): ?]
  <input class="greyButton" type="submit" value="%s" name="_save_and_add" />
[?php endif ?]
EOF;
    
    return sprintf($link, $this->getSingularName(), $params['label']);
  }

  public function linkToDelete($params)
  {
    $attributes = array_merge(
      array(
        'method' => 'delete', 
        'confirm' => !empty($params['confirm']) ? $params['confirm'] : $params['confirm'], 
        'title' => 'Delete ' . $this->getClassLabel()
        ), $params['attributes']);
    
    $link = <<<EOF
[?php if(!$%s->isNew()): ?]
  %s
[?php endif ?]
EOF;
    
    return sprintf($link, $this->getSingularName(), $this->renderLinkToBlock($params['label'], $this->getUrlForAction('delete'), $attributes, true));
  }
  
  public function linkToList($params)
  {
    $attributes = array_merge(array('title' => 'Back to ' . $this->getClassLabel() . ' List'), $params['attributes']);

    return $this->renderLinkToBlock($params['label'], $this->getUrlForAction('list'), $attributes);
  }

  public function linkToCancel($params)
  {
    $attributes = array_merge(array('title' => 'Back to ' . $this->getClassLabel() . ' List'), $params['attributes']);

    return $this->renderLinkToBlock($params['label'], $this->getUrlForAction('list'), $attributes);
  }
  
  public function getClassLabel()
  {
    return $this->get('class_label', $this->getModelClass());
  }
    
  public function getField($name, $config)
  {
    return new sfModelGeneratorConfigurationField($name, $config);
  }
  
  public function renderField($name, $config = null)
  {
    if ($name instanceof sfModelGeneratorConfigurationField) 
    {
      $field = $name;
    }
    else 
    {
      $field = $this->getField($name, $config);
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
      $html = sprintf("false !== strtotime($html) ? date(%s, strtotime(%s)) : '&nbsp;'", $this->asPhp($field->getConfig('date_format', 'Y-m-d')), $html);
    }
    else if ('Boolean' == $field->getType())
    {
      $ternary = $html." ? 'true' : 'false'";
      $html = sprintf("content_tag('div', %s, array('class' => (%s)))", $ternary, $ternary);
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
    $finder = sfFinder::type('file')->relative();
    
    if (!$this->configuration->hasExporting()) 
    {
      $finder->discard('*export*');
    }
    
    $this->generatePhpFiles($this->generatedModuleName, $finder->in($themeDir));

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
    $this->configToOptions($this->config);
    $this->configToOptions($this->params);

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

    $generatorConfiguration = new $class();
    $generatorConfiguration->validateConfig($this->config);

    $this->configToOptions($generatorConfiguration->getConfiguration());
    
    return $generatorConfiguration;
  }

  protected function renderLinkToBlock($label, $url, $attributes = array(), $forObject = false)
  {
    if ($forObject) 
    {
      return sprintf('[?php echo link_to(%s, %s, $%s, %s) ?]', 
        $this->asPhp($label), 
        $this->asPhp($url), 
        $this->getSingularName(), 
        $this->asPhp($attributes));
    }

    return sprintf('[?php echo link_to(%s, %s, %s) ?]', 
      $this->asPhp($label), 
      $this->asPhp('@'.$url), 
      $this->asPhp($attributes));    
  } 
}
