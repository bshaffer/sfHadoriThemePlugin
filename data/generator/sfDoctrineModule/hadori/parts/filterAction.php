  public function executeFilter(sfWebRequest $request)
  {
    if ($request->hasParameter('_reset'))
    {
      $this->setFilters(<?php echo $this->asPhp($this->get('filter_default', array())) ?>);
      $this->redirect(<?php echo $this->urlFor('list') ?>);
    }

    $this->filters = new <?php echo $this->get('filter_class', $this->getModelClass().'FormFilter') ?>();
    $filters = array_intersect_key($request->getParameter($this->filters->getName()), $request->getParameter('include', array()));

    $this->filters->bind($filters);

    if ($this->filters->isValid())
    {
      $this->setFilters($this->filters->getValues());
      $this->redirect(<?php echo $this->urlFor('list') ?>);
    }

    $this->pager = $this->getPager();
    $this->helper->setFilters($filters);
    $this->setTemplate('index');
  }
