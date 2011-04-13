<?php

/**
 * Handles theme configuration when spinning up a new module using this theme.  See sfThemeGeneratorPlugin
 *
 * @package    sfHadoriThemePlugin
 * @subpackage config
 * @author     Brent Shaffer <bshafs@gmail.com>
 */
class sfHadoriThemeConfiguration extends sfThemeConfiguration
{
  protected
    $theme = 'hadori';

  public function setup()
  {
    $this->askForApplication();

    $this->askForModel();

    $this->task->bootstrapSymfony($this->options['application'], $this->options['env']);

    $this->askForOption('module', null, sfInflector::underscore($this->options['model']));
  }

  public function filesToCopy()
  {
    $files = array(
      'MODULE_DIR/actions'              => 'THEME_DIR/skeleton/actions',
      'MODULE_DIR/config/generator.yml' => 'THEME_DIR/skeleton/config/generator.yml',
      'MODULE_DIR/templates'            => 'THEME_DIR/skeleton/templates',
      'APP_DIR/templates/_flashes.php'  => 'THEME_DIR/templates/_flashes.php',
    );

    if (sfConfig::get('app_hadori_include_assets', true)) {
      $files['MODULE_DIR/config/view.yml'] = 'THEME_DIR/skeleton/config/view.yml';
    }

    return $files;
  }

  public function initConstants()
  {
    parent::initConstants();

    $this->constants['CONFIG'] = sprintf(<<<EOF
    model_class:           %s
    theme:                 %s
    non_verbose_templates: true
    with_show:             true
    singular:              ~
    plural:                ~
    route_prefix:          %s
    with_doctrine_route:   true
    i18n:                  false
    sortable:              false
    actions_base_class:    sfActions
    use_security_yaml_credentials: false
EOF
      ,
      $this->options['model'],
      $this->theme,
      $this->options['module']
    );
  }

  public function routesToPrepend()
  {
    $primaryKey = Doctrine_Core::getTable($this->options['model'])->getIdentifier();
    $routes = array($this->options['module'] => sprintf(<<<EOF
  class: sfHadoriRouteCollection
  options:
    model:                %s
    module:               %s
    prefix_path:          /%s
    column:               %s
    with_wildcard_routes: true
    with_export:          true
EOF
      ,
      $this->options['model'],
      $this->options['module'],
      $this->options['module'],
      $primaryKey
    ));

    return $routes;
  }

  public function filterGeneratedFile($file)
  {
    switch (true)
    {
      // Rename class in actions.class.php
      case strpos($file, 'actions.class.php') !== false:
        $contents = file_get_contents($file);
        $search   = sprintf('auto%sActions', ucfirst($this->options['module']));
        $replace  = sprintf('%sActions', $this->options['module']);
        file_put_contents($file, str_replace($search, $replace, $contents));
        break;
    }
  }
}
