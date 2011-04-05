<?php

class sfHadoriGeneratorConfiguration extends sfThemeGeneratorConfiguration
{
  protected
    $doctrineDefaults = array(),
    $availableConfigs = array(
        'actions' => array(),
        'fields'  => array(),
        'list'    => array(),
        'filter'  => array(),
        'form'    => array(),
        'edit'    => array(),
        'show'    => array(),
        'new'     => array(),
        'export'  => array(),
      );

  protected function compile($configs)
  {
    $this->table   = Doctrine_Core::getTable($this->getOptionValue('model_class'));
    $configuration = Doctrine_Lib::arrayDeepMerge($this->getDefaultConfiguration(), $this->array_filter_recursive($configs));

    if ($configuration['list']['display'] === true) {
      $configuration['list']['display'] = array_slice($this->getAllFieldNames(false), 0, 5);
    }

    if ($configuration['show']['display'] === true) {
      $configuration['show']['display'] = $this->getAllFieldNames(false);
    }

    if ($configuration['export']['display'] === true) {
      $configuration['export']['display'] = $this->getAllFieldNames(false);
    }

    // create "sfHadoriField" object from supplied options for all "display" fields
    foreach ($configuration as $context => $config) {
      if (isset($config['display'])) {
        $display = array();
        foreach ($configuration[$context]['display'] as $key => $options) {
          $name = is_string($key) ? $key : (string) $options;
          $display[$name] = $this->createFieldFromOptions($name, $options);
        }
        $configuration[$context]['display'] = $display;
      }
    }

    // Add default options for special actions (syntax: "_name")
    foreach ($configuration as $context => $config) {
      if (is_array($config)) {
        foreach ($config as $actionType => $value) {
          if (strpos($actionType, 'actions') !== false) {
            $actions = array();
            foreach ($configuration[$context][$actionType] as $key => $options) {
              $name = is_string($key) ? $key : (string) $options;
              $actions[$name] = $this->fixActionOptions($name, $options);
            }
            $configuration[$context][$actionType] = $actions;
          }
        }
      }
    }

    $this->configuration = $configuration;

    // unset export if export is disabled
    if (!$this->hasExporting()) {
      unset($this->configuration['list']['actions']['export']);
    }
  }

  public function hasExporting()
  {
    return isset($this->configuration['export']) && false !== $this->configuration['export'];
  }

  public function hasFilterForm()
  {
    return !isset($this->configuration['filter']['class']) || false !== $this->configuration['filter']['class'];
  }

  public function hasExportFilterForm()
  {
    return !isset($this->configuration['export']['filter']['class']) || false !== $this->configuration['export']['filter']['class'];
  }

  public function hasSortable()
  {
    return isset($this->options['sortable']) && $this->options['sortable'];
  }

  protected function getDefaultConfiguration()
  {
    $defaults = sfYaml::load(dirname(__FILE__).'/config/generator.yml');

    $configDefaults = $defaults['generator']['param']['config'];

    // Defaults when sorting is enabled
    if ($this->hasSortable()) {
      $configDefaults['list']['object_actions']['_promote'] = null;
      $configDefaults['list']['object_actions']['_demote']  = null;
      $configDefaults['list']['sort'] = array('position', 'asc');
    }

    return $configDefaults;
  }

  protected function fixActionOptions($action, $options)
  {
    $options = Doctrine_Lib::arrayDeepMerge(array(
      'class' => (strpos($action, '_') === 0 ? substr($action, 1) : $action),
    ), $options);

    if (null === $options)
    {
      $options = array();
    }

    if ('_delete' == $action && !isset($options['confirm']))
    {
      $options['confirm'] = 'Are you sure?';
    }

    if (isset($options['label']))
    {
      $label = $options['label'];
    }
    else if ('_' != $action[0])
    {
      $label = $action;
    }
    else
    {
      $label = substr($action, 1);
    }

    $options['label'] = sfInflector::humanize($label);

    if (!isset($options['action'])) {
      switch ($action) {
        case '_export':
          $options['action'] = 'export';
          break;

        case '_show':
          $options['action'] = 'show';
          break;

        case '_cancel':
          $options['route'] = 'list';
          break;

        case '_edit':
          $options['action'] = 'edit';
          break;

        case '_promote':
          $options['action'] = 'promote';
          break;

        case '_demote':
          $options['action'] = 'demote';
          break;
      }
    }

    // ===========================
    // = Automate Credential Fix =
    // ===========================

    // Synch with security.yml
    if ($this->loadSecurityCredentials())
    {
      $actionAction = isset($options['action']) ? $options['action'] : (strpos($action, '_') === 0 ? substr($action, 1) : $action);
      if(isset($this->security[$actionAction]['credentials']))
      {
        $options['credentials'] = $this->security[$actionAction]['credentials'];
      }
      elseif(isset($this->security[$actionAction]['is_secure']) && $this->security[$actionAction]['is_secure'])
      {
        $options['credentials'] = true;
      }
      elseif(isset($this->security['all']['credentials']) && $this->security['all']['credentials'])
      {
        // If "All" credentials are set and the route is secure, set the credential accordingly
        $options['credentials'] = $this->security['all']['credentials'];
      }
    }

    return $options;
  }

  public function loadSecurityCredentials()
  {
    if ($this->getConfigValue('use_security_yaml_credentials', true))
    {
      $path = sfConfig::get('sf_app_module_dir').'/'.sfContext::getInstance()->getRequest()->getParameter('module').'/config/security.yml';
      if (file_exists($path))
      {
        include(sfContext::getInstance()->getConfigCache()->checkConfig($path));

        return true;
      }
    }
  }

  protected function createFieldFromOptions($name, $options)
  {
    $cleanName = $name;

    switch ($name[0]) {
      case '_':
      case '~':
      case '=':
        $cleanName = substr($name, 1);
        break;
    }

    $options = array_merge($this->getDefaultFieldConfiguration($cleanName), (array) $options);

    return new sfHadoriField($name, $options);
  }

  public function getDefaultFieldConfiguration($name)
  {
    if (!$this->doctrineDefaults) {
      $this->doctrineDefaults = $this->getDoctrineFieldDefaultConfigurations();
    }

    return isset($this->doctrineDefaults[$name]) ? $this->doctrineDefaults[$name] : array(
      'label' => sfInflector::humanize(sfInflector::underscore($name)),
      'type'  => 'Text');
  }

  public function getDoctrineFieldDefaultConfigurations()
  {
    $fields = array();

    foreach ($this->getColumns() as $name => $column)
    {
      if ($column->isForeignKey())
      {
        $type = 'ForeignKey';
      }
      else {
        switch ($column->getDoctrineType())
        {
          case 'enum':
            $type = 'Enum';
          case 'boolean':
            $type = 'Boolean';
          case 'date':
          case 'timestamp':
            $type = 'Date';
          case 'time':
            $type = 'Time';
          default:
            $type = 'Text';
        }
      }

      $fields[$name] = array_merge(array(
        'is_link'      => (Boolean) $column->isPrimaryKey(),
        'is_real'      => true,
        'is_partial'   => false,
        'is_component' => false,
        'type'         => $type,
        'label'        => sfInflector::humanize(sfInflector::underscore($name)),
      ));
    }

    foreach ($this->getManyToManyTables() as $tables)
    {
      $name = sfInflector::underscore($tables['alias']).'_list';

      $fields[$name] = array_merge(array(
        'is_link'      => false,
        'is_real'      => false,
        'is_partial'   => false,
        'is_component' => false,
        'type'         => 'Text',
        'label'        => sfInflector::humanize(sfInflector::underscore($name)),
      ));
    }

    return $fields;
  }
  
  public function getColumns()
  {
    $columns = array();

    foreach (array_keys($this->table->getColumns()) as $name)
    {
      $name = $this->table->getFieldName($name);
      $columns[$name] = new sfDoctrineColumn($name, $this->table);
    }
    
    return $columns;
  }
  
  public function getManyToManyTables()
  {
    $manyToManyTables = array();
    
    // get many to many tables
    foreach ($this->table->getRelations() as $relation)
    {
      if ($relation->getType() === Doctrine_Relation::MANY && isset($relation['refTable']))
      {
        $manyToManyTables[] = $relation;
      }
    }
    
    return $manyToManyTables;
  }
  
  public function getAllFieldNames($withM2M = true)
  {
    $names = array();
    foreach ($this->getColumns() as $name => $column)
    {
      $names[] = $name;
    }

    if ($withM2M)
    {
      foreach ($this->getManyToManyTables() as $tables)
      {
        $names[] = sfInflector::underscore($tables['alias']).'_list';
      }
    }

    return $names;
  }

  protected function array_filter_recursive($input)
  {
    foreach ($input as &$value)
    {
      if (is_array($value))
      {
        $value = $this->array_filter_recursive($value);
      }
    }

    return array_filter($input);
  }
}
