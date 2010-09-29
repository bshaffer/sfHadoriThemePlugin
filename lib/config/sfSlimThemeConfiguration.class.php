<?php 

class sfSlimThemeConfiguration extends sfThemeConfiguration
{
  protected
    $theme = 'slim';

  public function filesToCopy()
  {
    return array(
      'skeleton/actions'                => 'MODULE_DIR/actions',
      'skeleton/config'                 => 'MODULE_DIR/config',
      'skeleton/templates'              => 'MODULE_DIR/templates',
      'templates/_flashes.php'          => 'APP_DIR/templates/_flashes.php',
    );
  } 
  
  public function initConstants()
  {
    parent::initConstants();

    $this->constants['CONFIG'] = sprintf(<<<EOF
    model_class:           %s
    theme:                 %s
    non_verbose_templates: true
    with_show:             false
    singular:              ~
    plural:                ~
    route_prefix:          %s
    with_doctrine_route:   true
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
  class: sfDoctrineRouteCollection
  options:
    model:                %s
    module:               %s
    prefix_path:          /%s
    column:               %s
    with_wildcard_routes: true
EOF
      ,
      $this->options['model'], 
      $this->options['module'], 
      $this->options['module'],
      $primaryKey
    ));

    return $routes;
  }
}