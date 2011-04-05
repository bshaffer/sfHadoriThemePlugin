  protected function getPager()
  {
    $pager = new sfDoctrinePager('<?php echo $this->getModelClass() ?>', <?php echo $this->asPhp($this->get('list_pager_max_per_page', 10)) ?>);
    $pager->setQuery($this->buildQuery());
    $pager->setPage($this->getRequest()->getParameter('page'));
    $pager->init();

    return $pager;
  }

  protected function buildQuery()
  {
<?php if ($this->configuration->hasFilterForm()): ?>
    if(!$this->filters) {
      $this->filters = new <?php echo $this->get('filter_class', $this->getModelClass().'FormFilter') ?>($this->getFilters());
    }

    $this->filters->setQuery($this->getBaseQuery());

    $query = $this->filters->buildQuery($this->getFilters());
<?php else: ?>
    $query = $this->getBaseQuery();
<?php endif; ?>

    if ($sort = $this->getSort())
    {
      $query->addOrderBy($sort[0] . ' ' . $sort[1]);
    }

    return $query;
  }

  protected function getBaseQuery()
  {
    return Doctrine_Core::getTable('<?php echo $this->getModelClass() ?>')->createQuery();
  }
