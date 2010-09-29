<?php


abstract class sfSlimThemeGeneratorConfiguration extends sfModelGeneratorConfiguration
{
  abstract public function getExportDisplay();

  abstract public function getExportTitle();

  abstract public function getExportActions();

  abstract public function getShowDisplay();

  abstract public function getShowTitle();

  abstract public function getShowActions();

  public function getFormClass()
  {
    throw new sfException('Deprecated');
  }

  protected function compile()
  {
    // Load security.yml
    if (isset($this->configuration['use_security_yaml_credentials']) && $this->configuration['use_security_yaml_credentials'] && sfContext::hasInstance())
    {
      $path = sfConfig::get('sf_app_module_dir').'/'.sfContext::getInstance()->getRequest()->getParameter('module').'/config/security.yml';
      include(sfContext::getInstance()->getConfigCache()->checkConfig($path));
    }
    
    parent::compile();
    
    // Set Legend in configuration
    $this->configuration['list']['legend'] = $this->getLegendItems();
    
    // ===================================
    // = Add for exporting configuration =
    // ===================================
    $this->configuration['credentials']['export'] = array();
    $this->configuration['export'] = array(
        'fields'  => array(),
        'title'   => $this->getExportTitle(),
        'actions' => $this->getExportActions());
  
    $config = $this->getConfig();
    foreach (array_keys($config['default']) as $field)
    {
      $formConfig = array_merge($config['default'][$field], isset($config['form'][$field]) ? $config['form'][$field] : array());

      $this->configuration['export']['fields'][$field]   = new sfModelGeneratorConfigurationField($field, array_merge(array('label' => ucwords(sfInflector::humanize(sfInflector::underscore($field)))), $config['default'][$field], isset($config['export'][$field]) ? $config['export'][$field] : array()));
    }

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
    $this->configuration['show'] = array(
        'fields'  => array(),
        'title'   => $this->getShowTitle(),
        'actions' => $this->getShowActions());
  
    $config = $this->getConfig();
    foreach (array_keys($config['default']) as $field)
    {
      $formConfig = array_merge($config['default'][$field], isset($config['form'][$field]) ? $config['form'][$field] : array());

      $this->configuration['show']['fields'][$field]   = new sfModelGeneratorConfigurationField($field, array_merge(array('label' => sfInflector::humanize(sfInflector::underscore($field))), $config['default'][$field], isset($config['show'][$field]) ? $config['show'][$field] : array()));
    }

    foreach ($this->getShowDisplay() as $field)
    {
      list($field, $flag) = sfModelGeneratorConfigurationField::splitFieldWithFlag($field);

      $this->configuration['show']['fields'][$field] = new sfModelGeneratorConfigurationField($field, array_merge(
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

    
    $this->configuration['show']['display'] = array();
    foreach ($this->getShowDisplay() as $name)
    {
      list($name, $flag) = sfModelGeneratorConfigurationField::splitFieldWithFlag($name);
      if (!isset($this->configuration['show']['fields'][$name]))
      {
        throw new InvalidArgumentException(sprintf('The field "%s" does not exist.', $name));
      }
      $field = $this->configuration['show']['fields'][$name];
      $field->setFlag($flag);
      $this->configuration['show']['display'][$name] = $field;
    }
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
    
    if ('_back' == $action && !isset($parameters['route']))
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
    if ($this->getUseSecurityYamlCredentials()) 
    {
      $actionAction = isset($parameters['action']) ? $parameters['action'] : (strpos($action, '_') === 0 ? substr($action, 1) : $action);
      if(isset($this->security[$actionAction]['credentials']))
      {
        $parameters['credentials'] = $this->security[$actionAction]['credentials'];
      }
      elseif($this->security['all']['credentials'] && (!isset($this->security[$actionAction]['is_secure']) || $this->security[$actionAction]['is_secure']))
      {
        // If "All" credentials are set and the route is secure, set the credential accordingly
        $parameters['credentials'] = $this->security['all']['credentials'];
      }
    }
    
    return $parameters;
  }
  
  public function addCredentialCondition($generator, $content, $params)
  {
    if (isset($params['credentials']))
    {
      $credentials = $generator->asPhp($this->cleanCredentials($params['credentials']));

      return <<<EOF
[?php if (\$sf_user->hasCredential($credentials)): ?]
$content
[?php endif; ?]

EOF;
    }
    return $content;
  }
  
  public function cleanCredentials($credentials)
  {
    if (is_array($credentials))
    {
      foreach ($credentials as $i => &$credential)
      {
        $credential = $this->cleanCredentials($credential);
      }
    }
    else
    {
      $credentials = $this->cleanCredential($credentials);
    }

    return $credentials;
  }

  
  // Use constants as credentials
  public function cleanCredential($credential)
  {
    if (strpos($credential, '::') !== 0
      && class_exists(substr($credential, 0, strpos($credential, '::')))) 
    {
      return constant($credential);
    }
    return $credential;
  }
}