  public function executeExport(sfWebRequest $request)
  { 
    // sorting
    if ($request->getParameter('sort'))
    {
      $this->setSort(array($request->getParameter('sort'), $request->getParameter('sort_direction')));
    }

    $this->pager = $this->getPager();
     
    if ($request->isMethod('post')) 
    { 
      $manager = new <?php echo $this->get('export_manager_class', 'sfExportManager') ?>($this->getResponse());
      
      $fields = array_intersect_key($request->getParameter('export'), $request->getParameter('include'));
      
      if(false === $manager->export($this->pager->getQuery()->limit(9999999)->execute(), $fields, <?php echo $this->asPhp($this->get('export_filename', $this->getModelClass().'Export')) ?>))
      {
        // There was an error when generating the download.  Redirect to the referer and set the error in a flash message
        $this->redirectReferer($manager->getErrorMessage());
      }

      if($route = $manager->getDownloadRoute())
      {
        $this->redirect($route);
      }
      
      return sfView::NONE;
    }
  }
