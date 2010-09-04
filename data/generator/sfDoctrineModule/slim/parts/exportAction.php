  public function executeExport(sfWebRequest $request)
  { 
    // sorting
    if ($request->getParameter('sort') && $this->isValidSortColumn($request->getParameter('sort')))
    {
      $this->setSort(array($request->getParameter('sort'), $request->getParameter('sort_type')));
    }

    // pager
    if ($request->getParameter('page'))
    {
      $this->setPage($request->getParameter('page'));
    }

    $this->pager = $this->getPager();
    $this->sort = $this->getSort();
     
    if ($request->isMethod('POST')) 
    { 
      $exportManager = $this->helper->getExportManager();
      
      // Export
      $fields = array();
      
      foreach ($request->getParameter('export', array()) as $name => $field) 
      {
        if (isset($field['include']) && $field['include']) 
        {
          $fields[$name] = $field['label'] ? $field['label'] : ($field['default'] ? $field['default'] : sfInflector::humanize($name));
        }
      }

      $downloadManager = sfExportDownloadManager::create(sfConfig::get('app_export_format', 'pdf'), array('context' => $this->getContext())); 
      
      $filename = sprintf('%s.%s', $this->configuration->getExportFilename(), $downloadManager->getExtension());
      
      if(false === $downloadManager->export($exportManager, $this->getDataForExport($exportManager), $fields, $filename))
      {
        // There was an error when generating the download.  Redirect to the referer and set the error in a flash message
        $this->redirectReferer($downloadManager->getErrorMessage());
      }

      if($route = $downloadManager->getDownloadRoute())
      {
        $this->redirect($route);
      }
    }
  }
  
  protected function getDataForExport($exportManager)
  {
    $query = $this->pager
                  ->getQuery()
                  ->limit(9999999);

    return $query->execute();
  }
