<?php

/**
 * Class to handle the theme configuration.
 *
 * @package    sfHadoriThemePlugin
 * @subpackage generator
 * @author     Brent Shaffer <bshafs@gmail.com>
 */
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
    $depth = array(1 => array('list', 'filter', 'form', 'edit', 'show', 'new', 'export'), 2 => array('actions', 'fields'));
    $this->table   = Doctrine_Core::getTable($this->getOptionValue('model_class'));
    $configuration = $this->arrayDeepMerge($this->getDefaultConfiguration(), $configs, $depth);

    // Default values for list display
    if ($configuration['list']['display'] === true) {
      $configuration['list']['display'] = array_slice($this->getAllFieldNames(false), 0, 5);
    }

    // Default values for show display
    if ($configuration['show']['display'] === true) {
      $configuration['show']['display'] = $this->getAllFieldNames(false);
    }

    // Default values for export display
    if ($configuration['export']['display'] === true) {
      $configuration['export']['display'] = $this->getAllFieldNames(false);
    }

    // create "sfHadoriField" object from supplied options for all configurations of type "field"
    foreach ($configuration as $context => $config) {
      if (isset($config['display'])) {
        $display = array();
        foreach ($configuration[$context]['display'] as $key => $options) {
          $name = is_string($key) ? $key : (string) $options;

          // Merge in options if the field is defined in "fields"
          $options = isset($configuration['fields'][$name]) ? array_merge($configuration['fields'][$name], (array)$options) : $options;
          $display[$name] = $this->createFieldFromOptions($name, $options);
        }
        $configuration[$context]['display'] = $display;
      }
    }

    // merge in default "actions" configuration
    foreach ($configuration as $context => $config) {
      if (is_array($config)) {
        foreach ($config as $actionType => $value) {
          if (strpos($actionType, 'actions') !== false) {

            // if action names are set as values (i.e. [edit, new, save]) change them to values
            $configuredActions = array();
            foreach ($value as $action => $options) {
              if (is_int($action) && is_string($options)) {
                $configuredActions[(string)$options] = null;
              } else {
                $configuredActions[$action] = $options;
              }
            }

            // Merge in actions for configurations defined in "actions"
            $actions = Doctrine_Lib::arrayDeepMerge(array_intersect_key($configuration['actions'], $configuredActions), $this->filterNullValues($configuredActions));

            // set "label" and "action" if not set in configuration
            foreach ($actions as $actionName => $actionConfig) {
              $actions[$actionName] = $this->getActionsConfig($actionName, $actionConfig);
            }

            // set "credentials" if security.yml is defined and "use_security_yaml_credentials" is true
            if ($this->loadSecurityCredentials()) {
              foreach ($actions as $actionName => $actionConfig) {
                $actions[$actionName] = $this->addSecurityCredentials($actionName, $actionConfig);
              }
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
    $defaults = sfYaml::load(dirname(__FILE__).'/../config/config/generator.yml');

    $configDefaults = $defaults['generator']['param']['config'];

    // Defaults when sorting is enabled
    if ($this->hasSortable()) {
      $configDefaults['list']['object_actions']['promote'] = null;
      $configDefaults['list']['object_actions']['demote']  = null;
      $configDefaults['list']['sort'] = array('position', 'asc');
    }

    return $configDefaults;
  }

  public function addSecurityCredentials($name, $options = array())
  {
    $actionAction = isset($options['action']) ? $options['action'] : $name;
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

    return $options;
  }

  protected function getActionsConfig($action, $options)
  {
    if (!isset($options['class'])) {
      $options['class'] = $action;
    }

    if (!isset($options['label'])) {
      $options['label'] = sfInflector::humanize($action);
    }

    return $options;
  }

  protected function loadSecurityCredentials()
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
            break;
          case 'boolean':
            $type = 'Boolean';
            break;
          case 'date':
          case 'timestamp':
            $type = 'Date';
            break;
          case 'time':
            $type = 'Time';
            break;
          default:
            $type = 'Text';
            break;
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

  protected function getColumns()
  {
    $columns = array();

    foreach (array_keys($this->table->getColumns()) as $name)
    {
      $name = $this->table->getFieldName($name);
      $columns[$name] = new sfDoctrineColumn($name, $this->table);
    }

    return $columns;
  }

  protected function getManyToManyTables()
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

  protected function getAllFieldNames($withM2M = true)
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

  protected function filterNullValues($input, $depth = null)
  {
    if ($depth === 0) {
      return $input;
    }

    foreach ($input as $key => &$value)
    {
      if (is_array($value))
      {
        $nextDepth = null === $depth ? $depth : $depth - 1;
        $value = $this->filterNullValues($value, $nextDepth);
      }
      elseif($value === null) {
        unset($input[$key]);
      }
    }

    return $input;
  }

  protected function arrayDeepMerge($array1, $array2, $depth = null)
  {
    if ($depth === 0) {
      return $array2;
    }

    $merged = array();

    if (is_array($array1) && is_array($array2))
    {
        if ($array2 === array()) {
          return $array2;
        }

        foreach (array_unique(array_merge(array_keys($array1),array_keys($array2))) as $key)
        {
            $isKey0 = array_key_exists($key, $array1);
            $isKey1 = array_key_exists($key, $array2);

            if ($isKey0 && $isKey1 && is_array($array1[$key]) && is_array($array2[$key]))
            {
                $nextDepth = null;

                if (null !== $depth) {
                  if (is_array($depth)) {
                    foreach ($depth as $keyDepth => $keys) {
                      if (in_array($key, $keys)) {
                        $nextDepth = $keyDepth;
                      }
                    }
                  }
                  else {
                    $nextDepth = $depth - 1;
                  }
                }

                $merged[$key] = $this->arrayDeepMerge($array1[$key], $array2[$key], $nextDepth);
            } else if ($isKey0 && $isKey1 && $array2[$key] === null) {
                $merged[$key] = $array1[$key];
            }else if ($isKey0 && $isKey1) {
                $merged[$key] = $array2[$key];
            } else if ( ! $isKey1) {
                $merged[$key] = $array1[$key];
            } else if ( ! $isKey0) {
                $merged[$key] = $array2[$key];
            }
        }

        return $merged;
    } else {
        return $array2;
    }
  }
}
