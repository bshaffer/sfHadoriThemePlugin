<?php

/**
*
*/
class sfHadoriThemeGenerator extends sfThemeGenerator
{
  public function renderTextAsBlock($text)
  {
    if (strpos($text, '<?php') !== 0) {
      $text = sprintf('<?php echo \'%s\' ?>', $text);
    }

    return $text;
  }

  public function renderWildcardString($string)
  {
    preg_match_all('/%%([^%]+)%%/', $string, $matches, PREG_PATTERN_ORDER);

    if (count($matches[1])) {
      $string = $this->escapeString($string);

      foreach ($matches[1] as $name)
      {
        $getter  = $this->getColumnGetter($name, true);
        $string  = str_replace("%%$name%%", sprintf("'.%s.'", $getter), $string);
      }

      $string = $this->renderTextAsBlock($string);
    }
    else {
      $string = $this->renderText($string);
    }

    return $string;
  }

  public function getUrlForAction($action)
  {
    return sprintf('%s%s', $this->get('route_prefix'), in_array($action, array('list', 'index')) ? '' : '_'.$action);
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
  <input class="greyButton" type="submit" value="%s" name="_save_and_add" />
EOF;

    return sprintf($link, $params['label']);
  }

  public function linkToDelete($params)
  {
    $attributes = array_merge(
      array(
        'method' => 'delete',
        'confirm' => !empty($params['confirm']) ? $params['confirm'] : $params['confirm'],
        'title' => 'Delete ' . $this->getClassLabel()
        ), $params['attributes']);

    return $this->renderLinkToBlock($params['label'], $this->getUrlForAction('delete'), $attributes, true);
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

  public function linkToObjectList($class, $html, $params)
  {
    $varname  = '$'.sfInflector::tableize($class);
    $linkHtml = $this->linkToObject($class, $varname, $params);

    $html = <<<EOF
    <ul>
      <?php foreach($html as $varname): ?>
        <li><?php echo $linkHtml ?></li>
      <?php endforeach ?>
    </ul>
EOF;

    return $html;
  }

  public function linkToObject($class, $html, $params)
  {
    $showRoute = sfInflector::tableize($class) . '_show';
    $routes    = sfContext::getInstance()->getRouting()->getRoutes();

    if (isset($routes[$showRoute]) && $routes[$showRoute] instanceof sfDoctrineRoute) {
      $options = $routes[$showRoute]->getOptions();
      if ($options['model'] == $class) {
        $html = sprintf("link_to(%s, '%s', %s)", $html, $showRoute, $html);
      }
    }

    return $html;
  }

  public function getClassLabel()
  {
    return $this->get('class_label', $this->getModelClass());
  }

  public function getField($name, $config)
  {
    return new sfModelGeneratorConfigurationField($name, $config);
  }

  public function renderField($name, $config = null, $inBlock = true)
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
      $html = sprintf("get_component('%s', '%s', array('type' => 'list', '%s' => \$%s))", $this->getModuleName(), $field->getName(), $this->getSingularName(), $this->getSingularName());
    }
    else if ($field->isPartial())
    {
      $html = sprintf("get_partial('%s/%s', array('type' => 'list', '%s' => \$%s))", $this->getModuleName(), $field->getName(), $this->getSingularName(), $this->getSingularName());
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
    else
    {
      $table = Doctrine_Core::getTable($this->get('model_class'));
      if ($table->hasRelation($field->getName())) {
        $relation = Doctrine_Core::getTable($this->get('model_class'))->getRelation($field->getName());
        if ($relation->getType() == Doctrine_Relation::MANY) {
          // This is a foreign alias.  Link To list
          $html = $this->linkToObjectList($relation['class'], $html, $field->getConfig());
          $inBlock  = false;
        }
        else {
          $html = $this->linkToObject($relation['class'], $html, $field->getConfig());
        }
      }
    }

    if ($field->isLink())
    {
      $html = sprintf("link_to(%s, '%s', \$%s)", $html, $this->getUrlForAction('edit'), $this->getSingularName());
    }

    if ($inBlock) {
      $html = sprintf("<?php echo %s ?>", $html);
    }

    return $html;
  }

  public function getFormFieldAttributes(sfForm $form, $name)
  {
    $attributes = array();

    if (isset($form[$name]))
    {
      $widget = $form->getWidget($name);

      switch (true)
      {
        case $widget instanceof sfWidgetFormInputCheckbox:
          $attributes['class'] = 'checkbox';
          break;

        case $widget  instanceof sfWidgetFormChoice:
          $attributes['class'] = 'selectfield';
          break;
      }
    }

    return $attributes ? $this->asPhp($attributes) : '';
  }

  public function getFormFieldContainerClass(sfForm $form, $name)
  {
    $class = array('form-element');

    $attributes = array();

    if (isset($form[$name]))
    {
      $widget = $form->getWidget($name);
      $validator = $form->getValidator($name);

      switch (true)
      {
        case $validator->getOption('required') === true:
          $class[] = 'required';
          break;

        default:
          $class[] = sfInflector::underscore(str_replace('sfWidgetForm', '', get_class($widget)));
          break;
      }
    }

    return implode(' ', $class);
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
