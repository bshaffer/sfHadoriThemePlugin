  public function executeFilter(sfWebRequest $request)
  {
    if ($request->hasParameter('_reset'))
    {
      $this->setFilters(<?php echo $this->asPhp($this->get('filter_default', array())) ?>);

      $this->redirect(<?php echo $this->urlFor('list') ?>);
    }

    $this->filters = new <?php echo $this->get('filter_class', $this->getModelClass().'FormFilter') ?>();
    
    $this->filters->bind(array_intersect_key($request->getParameter($this->filters->getName()), $request->getParameter('include')));
    if ($this->filters->isValid())
    {
      $this->setFilters($this->filters->getValues());
      
      $this->redirect(<?php echo $this->urlFor('list') ?>);
    }

    $this->pager  = $this->getPager();
    $this->sort   = $this->getSort();

    $this->setTemplate('index');
  }
  