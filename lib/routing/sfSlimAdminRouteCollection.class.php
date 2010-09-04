<?php 

class sfSlimAdminRouteCollection extends sfDoctrineRouteCollection
{
  protected function generateRoutes()
  {
    parent::generateRoutes();
 
    if (!isset($this->options['with_export']) || $this->options['with_export'])
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
      'type' => 'list',
    );
 
    return new $this->routeClass(
      $url,
      $params,
      $requirements,
      $options
    );
  }
}