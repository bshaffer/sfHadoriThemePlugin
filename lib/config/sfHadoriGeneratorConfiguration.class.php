<?php


abstract class sfHadoriGeneratorConfiguration extends sfThemeGeneratorConfiguration
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

  protected function compile()
  {
    // inheritance rules:
    // new|edit < form < default
    // list < default
    // filter < default

    $defaults = sfYaml::load(dirname(__FILE__).'/config/generator.yml');

    $configDefaults = $defaults['generator']['param']['config'];

    // Defaults when exporting is enabled
    if ($this->hasExporting()) {
      $configDefaults['list']['actions']['_export'] = null;
    }

    // Defaults when sorting is enabled
    if ($this->hasSortable()) {
      $configDefaults['list']['object_actions']['_promote'] = null;
      $configDefaults['list']['object_actions']['_demote']  = null;
      $configDefaults['list']['sort'] = array('position', 'asc');
    }

    $this->configuration = Doctrine_Lib::arrayDeepMerge($configDefaults, $this->array_filter_recursive(array(
      'list'   => array(
        'fields'         => array(),
        'layout'         => $this->getListLayout(),
        'title'          => $this->getListTitle(),
        'actions'        => $this->getListActions(),
        'object_actions' => $this->getListObjectActions(),
        'params'         => $this->getListParams(),
        'display'        => $this->getListDisplay(),
      ),
      'filter' => array(),
      'form'   => array(),
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
        'display' => $this->getShowDisplay(),
      ),
      'export'   => array(
        'fields'  => array(),
        'title'   => $this->getExportTitle(),
        'actions' => $this->getExportActions(),
        'display' => $this->getExportDisplay(),
      ),
    )));

    if ($this->configuration['list']['display'] === true) {
      $this->configuration['list']['display'] = array_slice($this->getAllFieldNames(false), 0, 5);
    }

    if ($this->configuration['show']['display'] === true) {
      $this->configuration['show']['display'] = $this->getAllFieldNames(false);
    }

    // create "sfHadoriField" object from supplied options for all "display" fields
    foreach ($this->configuration as $context => $config) {
      if (isset($config['display'])) {
        $display = array();
        foreach ($this->configuration[$context]['display'] as $key => $options) {
          $name = is_string($key) ? $key : (string) $options;
          $display[$name] = $this->createFieldFromOptions($name, $options);
        }
        $this->configuration[$context]['display'] = $display;
      }
    }

    // Add default options for special actions (syntax: "_name")
    foreach ($this->configuration as $context => $config) {
      if (is_array($config)) {
        foreach ($config as $actionType => $value) {
          if (strpos($actionType, 'actions') !== false) {
            $actions = array();
            foreach ($this->configuration[$context][$actionType] as $key => $options) {
              $name = is_string($key) ? $key : (string) $options;
              $actions[$name] = $this->fixActionOptions($name, $options);
            }
            $this->configuration[$context][$actionType] = $actions;
          }
        }
      }
    }
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
    $configuration = $this->getDefaultFieldsConfiguration();

    return isset($configuration[$name]) ? $configuration[$name] : array(
      'label' => sfInflector::humanize(sfInflector::underscore($name)),
      'type'  => 'Text');
  }

  public function getConfigValue($config, $default = null)
  {
    if (isset($this->configuration[$config]))
    {
      return $this->configuration[$config];
    }

    return $default;
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

  protected function getConfig()
  {
    return array(
      'default' => $this->getDefaultFieldsConfiguration(),
      'list'    => $this->getFieldsList(),
      'filter'  => $this->getFieldsFilter(),
      'form'    => $this->getFieldsForm(),
      'new'     => $this->getFieldsNew(),
      'edit'    => $this->getFieldsEdit(),
    );
  }
}
