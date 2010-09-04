  protected function getFilters()
  {
    $filters = $this->getUser()->getAttribute('<?php echo $this->getModuleName() ?>.filters', $this->configuration->getFilterDefaults(), 'admin_module');
    $this->helper->setFilters($filters);
    return $filters;
  }

  protected function setFilters(array $filters)
  {
    return $this->getUser()->setAttribute('<?php echo $this->getModuleName() ?>.filters', $filters, 'admin_module');
  }
