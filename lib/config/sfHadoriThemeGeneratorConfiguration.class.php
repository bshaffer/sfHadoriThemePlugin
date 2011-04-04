<?php


abstract class sfHadoriThemeGeneratorConfiguration extends sfThemeGeneratorConfiguration
{
  protected 
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

  /**
   * Gets the fields that represents the filters.
   *
   * If no filter.display parameter is passed in the configuration,
   * all the fields from the form are returned (dynamically).
   *
   * @param sfForm $form The form with the fields
   */
  public function getFormFilterFields(sfForm $form)
  {
    $formFields = array();
    $fields     = $this->getFilterFields();
    
    foreach ($form->getWidgetSchema()->getPositions() as $name)
    {
      if (isset($fields[$name])) 
      {
        $formFields[$name] = $fields[$name];
      }
    }

    return $formFields;
  }
  
  public function getFormFields(sfForm $form, $context)
  {
    $fields = parent::getFormFields($form, 'Form');

    // Unset hidden fields
    foreach ($fields as $fieldsetName => &$fieldset) 
    {
      foreach ($fieldset as $name => $field) 
      {
        if (!$field) 
        {
          unset($fieldset[$name]);
        }
        
        if (isset($form[$name])) 
        {
          if($form[$name]->isHidden())
          {
            unset($fieldset[$name]);
          }
        }
      }
    }
    
    return $fields;
  }
  
  public function getFormClass()
  {
    throw new sfException('Deprecated');
  }

  protected function compile()
  {
    // inheritance rules:
    // new|edit < form < default
    // list < default
    // filter < default
    
    $defaults = sfYaml::load(dirname(__FILE__).'/config/generator.yml');
    
    $configDefaults = $defaults['generator']['param']['config'];
    
    if ($this->hasExporting()) {
      $configDefaults['list']['actions']['export'] = null;
    }
    
    $this->configuration = Doctrine_Lib::arrayDeepMerge($configDefaults, $this->array_filter_recursive(array(
      'default' => $this->getFieldsDefault(),
      'list'   => array(
        'fields'         => array(),
        'layout'         => $this->getListLayout(),
        'title'          => $this->getListTitle(),
        'actions'        => $this->getListActions(),
        'object_actions' => $this->getListObjectActions(),
        'params'         => $this->getListParams(),
      ),
      'filter' => array(
        'fields'  => array(),
      ),
      'form'   => array(
        'fields'  => array(),
      ),
      'new'    => array(
        'fields'  => array(),
        'title'   => $this->getNewTitle(),
        'actions' => $this->getNewActions() ? $this->getNewActions() : $this->getFormActions(),
      ),
      'edit'   => array(
        'fields'  => array(),
        'title'   => $this->getEditTitle(),
        'actions' => $this->getEditActions() ? $this->getEditActions() : $this->getFormActions(),
      ),
      'show'   => array(
        'fields'  => array(),
        'title'   => $this->getShowTitle(),
        'actions' => $this->getShowActions(),
      ),
      'export'   => array(
        'fields'  => array(),
        'title'   => $this->getExportTitle(),
        'actions' => $this->getExportActions(),
      ),
    )));
    
    $config = $this->configuration;

    foreach (array_keys($config['default']) as $field)
    {
      $formConfig = array_merge($config['default'][$field], isset($config['form'][$field]) ? $config['form'][$field] : array());

      $this->configuration['list']['fields'][$field]   = new sfModelGeneratorConfigurationField($field, array_merge(array('label' => sfInflector::humanize(sfInflector::underscore($field))), $config['default'][$field], isset($config['list'][$field]) ? $config['list'][$field] : array()));
      $this->configuration['filter']['fields'][$field] = new sfModelGeneratorConfigurationField($field, array_merge($config['default'][$field], isset($config['filter'][$field]) ? $config['filter'][$field] : array()));
      $this->configuration['new']['fields'][$field]    = new sfModelGeneratorConfigurationField($field, array_merge($formConfig, isset($config['new'][$field]) ? $config['new'][$field] : array()));
      $this->configuration['edit']['fields'][$field]   = new sfModelGeneratorConfigurationField($field, array_merge($formConfig, isset($config['edit'][$field]) ? $config['edit'][$field] : array()));
      $this->configuration['show']['fields'][$field]   = new sfModelGeneratorConfigurationField($field, array_merge($formConfig, isset($config['show'][$field]) ? $config['show'][$field] : array()));
      $this->configuration['export']['fields'][$field] = new sfModelGeneratorConfigurationField($field, array_merge(array('label' => ucwords(sfInflector::humanize(sfInflector::underscore($field)))), $config['default'][$field], isset($config['export'][$field]) ? $config['export'][$field] : array()));
    }

    // "virtual" fields for list
    foreach ($this->getListDisplay() as $field)
    {
      list($field, $flag) = sfModelGeneratorConfigurationField::splitFieldWithFlag($field);

      $this->configuration['list']['fields'][$field] = new sfModelGeneratorConfigurationField($field, array_merge(
        array('type' => 'Text', 'label' => sfInflector::humanize(sfInflector::underscore($field))),
        isset($config['default'][$field]) ? $config['default'][$field] : array(),
        isset($config['list'][$field]) ? $config['list'][$field] : array(),
        array('flag' => $flag)
      ));
    }

    // form actions
    foreach (array('edit', 'new') as $context)
    {
      foreach ($this->configuration[$context]['actions'] as $action => $parameters)
      {
        $this->configuration[$context]['actions'][$action] = $this->fixActionParameters($action, $parameters);
      }
    }

    // list actions
    foreach ($this->configuration['list']['actions'] as $action => $parameters)
    {
      $this->configuration['list']['actions'][$action] = $this->fixActionParameters($action, $parameters);
    }

    // list batch actions
    $this->configuration['list']['batch_actions'] = array();
    foreach ($this->getListBatchActions() as $action => $parameters)
    {
      $parameters = $this->fixActionParameters($action, $parameters);

      $action = 'batch'.ucfirst(0 === strpos($action, '_') ? substr($action, 1) : $action);

      $this->configuration['list']['batch_actions'][$action] = $parameters;
    }

    // list object actions
    foreach ($this->configuration['list']['object_actions'] as $action => $parameters)
    {
      $this->configuration['list']['object_actions'][$action] = $this->fixActionParameters($action, $parameters);
    }

    // list field configuration
    $this->configuration['list']['display'] = array();
    foreach ($this->getListDisplay() as $name)
    {
      list($name, $flag) = sfModelGeneratorConfigurationField::splitFieldWithFlag($name);
      if (!isset($this->configuration['list']['fields'][$name]))
      {
        throw new InvalidArgumentException(sprintf('The field "%s" does not exist.', $name));
      }
      $field = $this->configuration['list']['fields'][$name];
      $field->setFlag($flag);
      $this->configuration['list']['display'][$name] = $field;
    }

    // parse the %%..%% variables, remove flags and add default fields where
    // necessary (fixes #7578)
    $this->parseVariables('list', 'params');
    $this->parseVariables('edit', 'title');
    $this->parseVariables('list', 'title');
    $this->parseVariables('new', 'title');

    // action credentials
    $this->configuration['credentials'] = array(
      'list'   => array(),
      'new'    => array(),
      'create' => array(),
      'edit'   => array(),
      'update' => array(),
      'delete' => array(),
    );
    foreach ($this->getActionsDefault() as $action => $params)
    {
      if (0 === strpos($action, '_'))
      {
        $action = substr($action, 1);
      }

      $this->configuration['credentials'][$action] = isset($params['credentials']) ? $params['credentials'] : array();
      $this->configuration['credentials']['batch'.ucfirst($action)] = isset($params['credentials']) ? $params['credentials'] : array();
    }
    $this->configuration['credentials']['create'] = $this->configuration['credentials']['new'];
    $this->configuration['credentials']['update'] = $this->configuration['credentials']['edit'];

    // ===================================
    // = Add for exporting configuration =
    // ===================================
    $this->configuration['credentials']['export'] = array();

    foreach ($this->getExportDisplay() as $field)
    {
      list($field, $flag) = sfModelGeneratorConfigurationField::splitFieldWithFlag($field);

      $this->configuration['export']['fields'][$field] = new sfModelGeneratorConfigurationField($field, array_merge(
        array('type' => 'Text', 'label' => ucwords(sfInflector::humanize(sfInflector::underscore($field)))),
        isset($config['default'][$field]) ? $config['default'][$field] : array(),
        isset($config['export'][$field]) ? $config['export'][$field] : array(),
        array('flag' => $flag)
      ));
    }
    
    // export actions
    foreach ($this->configuration['export']['actions'] as $action => $parameters)
    {
      $this->configuration['export']['actions'][$action] = $this->fixActionParameters($action, $parameters);
    }

    
    $this->configuration['export']['display'] = array();
    foreach ($this->getExportDisplay() as $name)
    {
      list($name, $flag) = sfModelGeneratorConfigurationField::splitFieldWithFlag($name);
      if (!isset($this->configuration['export']['fields'][$name]))
      {
        throw new InvalidArgumentException(sprintf('The field "%s" does not exist.', $name));
      }
      $field = $this->configuration['export']['fields'][$name];
      $field->setFlag($flag);
      $this->configuration['export']['display'][$name] = $field;
    }
    
    // ==============================
    // = Add for show configuration =
    // ==============================
    $this->configuration['credentials']['show'] = array();
  
    foreach ($this->getShowDisplay() as $field)
    {
      list($field, $flag) = sfModelGeneratorConfigurationField::splitFieldWithFlag($field);

      $this->configuration['show']['display'][$field] = new sfModelGeneratorConfigurationField($field, array_merge(
        array('type' => 'Text', 'label' => sfInflector::humanize(sfInflector::underscore($field))),
        isset($config['default'][$field]) ? $config['default'][$field] : array(),
        isset($config['show'][$field]) ? $config['show'][$field] : array(),
        array('flag' => $flag)
      ));
    }
    
    // show actions
    foreach ($this->configuration['show']['actions'] as $action => $parameters)
    {
      $this->configuration['show']['actions'][$action] = $this->fixActionParameters($action, $parameters);
    }

    // $this->configuration['show']['display'] = array();
    // // foreach ($this->getShowDisplay() as $name)
    // // {
    // //   list($name, $flag) = sfModelGeneratorConfigurationField::splitFieldWithFlag($name);
    // //   if (!isset($this->configuration['show']['fields'][$name]))
    // //   {
    // //     throw new InvalidArgumentException(sprintf('The field "%s" does not exist.', $name));
    // //   }
    // //   $field = $this->configuration['show']['fields'][$name];
    // //   $field->setFlag($flag);
    // //   $this->configuration['show']['display'][$name] = $field;
    // // }
    
    $this->parseVariables('show', 'title');
    $this->parseVariables('export', 'title');
  }
  
  protected function fixActionParameters($action, $parameters)
  {
    $parameters = parent::fixActionParameters($action, $parameters);

    if ('_export' == $action && !isset($parameters['action']))
    {
      $parameters['action'] = 'export';
    }

    if ('_show' == $action && !isset($parameters['action']))
    {
      $parameters['action'] = 'show';
    }
    
    if ('_cancel' == $action && !isset($parameters['route']))
    {
      $parameters['route'] = '@homepage';
    }
    
    if ('_edit' == $action && !isset($parameters['action']))
    {
      $parameters['action'] = 'edit';
    }
    
    // ===========================
    // = Automate Credential Fix =
    // ===========================

    // Synch with security.yml
    if ($this->loadSecurityCredentials()) 
    {
      $actionAction = isset($parameters['action']) ? $parameters['action'] : (strpos($action, '_') === 0 ? substr($action, 1) : $action);
      if(isset($this->security[$actionAction]['credentials']))
      {
        $parameters['credentials'] = $this->security[$actionAction]['credentials'];
      }
      elseif(isset($this->security[$actionAction]['is_secure']) && $this->security[$actionAction]['is_secure'])
      {
        $parameters['credentials'] = true;
      }
      elseif(isset($this->security['all']['credentials']) && $this->security['all']['credentials'])
      {
        // If "All" credentials are set and the route is secure, set the credential accordingly
        $parameters['credentials'] = $this->security['all']['credentials'];
      }
    }
    
    return $parameters;
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
  
  public function getConfigValue($config, $default = null)
  {
    if (isset($this->configuration[$config])) 
    {
      return $this->configuration[$config];
    }
    
    return $default;
  }
  
  public function array_filter_recursive($input) 
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
