  protected function getFilters()
  {
    $filters = $this->getUser()->getAttribute('<?php echo $this->getModuleName() ?>.filters', <?php echo $this->asPhp($this->get('filter_default', array())) ?>);
    $this->helper->setFilters($filters);
    return $filters;
  }

  protected function setFilters(array $filters)
  {
    $this->getUser()->setAttribute('<?php echo $this->getModuleName() ?>.filters', $filters);
    $this->helper->setFilters($filters);
    return $filters;
  }
