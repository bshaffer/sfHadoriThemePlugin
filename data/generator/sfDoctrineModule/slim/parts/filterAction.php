  public function executeFilter(sfWebRequest $request)
  {
    $this->setPage(1);
    
    if ($request->hasParameter('_reset'))
    {
      $this->setFilters($this->configuration->getFilterDefaults());

      $this->redirect($this->getRedirect());
    }

    $this->filters = $this->configuration->getFilterForm($this->getFilters());

    $this->filters->bind($request->getParameter($this->filters->getName()));
    if ($this->filters->isValid())
    {
      $this->setFilters($this->filters->getValues());
      
      $this->redirect($this->getRedirect());
    }

    $this->pager = $this->getPager();
    $this->sort = $this->getSort();

    $this->setTemplate($request->getParameter('for_action', 'index'));
  }

  protected function getRedirect()
  {
    $redirectTo = $this->getRequest()->getParameter('for_action', 'index');
    
    return $this->helper->getRouteForAction($redirectTo);
  }