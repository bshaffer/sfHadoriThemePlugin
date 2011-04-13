<?php

/**
 * class to handle generation of theme cache files.
 *
 * @package    sfHadoriThemePlugin
 * @subpackage generator
 * @author     Brent Shaffer <bshafs@gmail.com>
 */
class sfHadoriGenerator extends sfThemeGenerator
{
  public function getUrlForAction($action)
  {
    return sprintf('%s%s', $this->get('route_prefix'), in_array($action, array('list', 'index')) ? '' : '_'.$action);
  }

  public function linkToSave($params)
  {
    return '<input class="greyButton" type="submit" value="'.$this->renderHtmlText($params['label']).'" />';
  }

  public function linkToSaveAndAdd($params)
  {
    $link = <<<EOF
  <input class="greyButton" type="submit" value="%s" name="_save_and_add" />
EOF;

    return sprintf($link, $this->renderHtmlText($params['label']));
  }

  public function linkToObjectList($class, $html, $params)
  {
    $listRoute = sfInflector::tableize($class);
    $routes    = sfContext::getInstance()->getRouting()->getRoutes();

    if (isset($routes[$listRoute]) && $routes[$listRoute] instanceof sfDoctrineRoute) {
      $options = $routes[$listRoute]->getOptions();
      if ($options['model'] == $class) {
        $html = sprintf("link_to(%s, '%s', %s)", $html, $listRoute, $html);
      }
    }

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

  public function getField($name, $config)
  {
    return new sfHadoriField($name, $config['Type'], $config);
  }

  public function renderField($name, $config = null, $inBlock = true)
  {
    if ($name instanceof sfHadoriField)
    {
      $field = $name;
    }
    else
    {
      $field = $this->getField($name, $config);
    }

    $html = $this->getColumnGetter($field->getName(), true);

    if ($field->isComponent())
    {
      $html = sprintf("get_component('%s', '%s', array('type' => 'list', '%s' => \$%s))", $this->getModuleName(), $field->getName(), $this->getSingularName(), $this->getSingularName());
    }
    else if ($field->isPartial())
    {
      $html = sprintf("get_partial('%s/%s', array('type' => 'list', '%s' => \$%s))", $this->getModuleName(), $field->getName(), $this->getSingularName(), $this->getSingularName());
    }
    else if ('Date' == $field->getType())
    {
      $html = sprintf("false !== strtotime($html) ? date(%s, strtotime(%s)) : %s", $this->asPhp($field->getOption('date_format', 'Y-m-d')), $html, $html);
    }
    else if ('Boolean' == $field->getType())
    {
      $ternary = sprintf('var_export(%s)', $html);
      $html = $inBlock ? 
        sprintf('<div class="<?php echo %s ?>"><?php echo %s ?></div>', $ternary, $ternary) : 
        sprintf("content_tag('div', %s, array('class' => (%s)))", $ternary, $ternary);
      $inBlock = false;
    }
    else
    {
      // Render Object Link (if possible)
      $table = Doctrine_Core::getTable($this->get('model_class'));
      if ($table->hasRelation($field->getName())) {
        $relation = $table->getRelation($field->getName());
        if ($relation->getType() == Doctrine_Relation::MANY) {
          // This is a foreign alias.  Link To list
          $html = $this->linkToObjectList($relation['class'], $html, $field->getOptions());
        }
        else {
          $html = $this->linkToObject($relation['class'], $html, $field->getOptions());
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

  protected function getPhpFilesToGenerate($themeDir)
  {
    $finder = sfFinder::type('file')->relative();
    
    if (!$this->configuration->hasExporting())
    {
      $finder->discard('*export*');
    }
    
    return $finder->in($themeDir);
  }
  
  protected function getConfigurationClass()
  {
    return 'sfHadoriGeneratorConfiguration';
  }
}
