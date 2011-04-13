<?php

/**
 * route collection for hadori routes
 *
 * @package    sfHadoriThemePlugin
 * @subpackage routing
 * @author     Brent Shaffer <bshafs@gmail.com>
 */
class sfHadoriRouteCollection extends sfDoctrineRouteCollection
{
  public function __construct(array $options)
  {
    $options = array_merge(array(
      'segment_names'        => array('edit' => 'edit', 'new' => 'new', 'create' => 'new', 'update' => 'edit'),
      'with_export'          => true,
    ), $options);

    parent::__construct($options);
  }
  protected function generateRoutes()
  {
    parent::generateRoutes();

    if ($this->options['with_export'])
    {
      $routeName = $this->options['name'].'_export';

      $this->routes[$routeName] = $this->getRouteForExport();
    }
  }

  protected function getRouteForExport()
  {
    $url = sprintf(
      '%s/export.:sf_format',
      $this->options['prefix_path']
    );

    $params = array(
      'module' => $this->options['module'],
      'action' => 'export',
      'sf_format' => 'html'
    );

    $requirements = array('sf_method' => array('get', 'post'));

    $options = array(
      'model' => $this->options['model'],
      'type'  => 'list',
    );

    return new $this->routeClass(
      $url,
      $params,
      $requirements,
      $options
    );
  }

  protected function getRouteForCreate()
  {
    $url = sprintf(
      '%s/%s.:sf_format',
      $this->options['prefix_path'],
      $this->options['segment_names']['create']
    );

    $params = array_merge(array(
      'module' => $this->options['module'],
      'action' => $this->getActionMethod('create'),
      'sf_format' => 'html'
    ), $this->options['default_params']);

    $requirements = array_merge($this->options['requirements'], array('sf_method' => 'post'));

    $options = array(
      'model' => $this->options['model'],
      'type' => 'object'
    );

    return new $this->routeClass(
      $url,
      $params,
      $requirements,
      $options
    );
  }

  protected function getRouteForUpdate()
  {
    $url = sprintf(
      '%s/:%s/%s.:sf_format',
      $this->options['prefix_path'],
      $this->options['column'],
      $this->options['segment_names']['update']
    );

    $params = array_merge(array(
      'module' => $this->options['module'],
      'action' => $this->getActionMethod('update'),
      'sf_format' => 'html'
    ), $this->options['default_params']);

    $requirements = array_merge($this->options['requirements'], array('sf_method' => 'put'));

    $options = array(
      'model' => $this->options['model'],
      'type' => 'object',
      'method' => $this->options['model_methods']['object']
    );

    return new $this->routeClass(
      $url,
      $params,
      $requirements,
      $options
    );
  }
}