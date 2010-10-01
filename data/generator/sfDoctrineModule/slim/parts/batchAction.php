  public function executeBatch(sfWebRequest $request)
  {
    if (!$ids = $request->getParameter('ids'))
    {
      $this->getUser()->setFlash('error', 'You must at least select one item.');
    }
    elseif (!$action = $request->getParameter('batch_action'))
    {
      $this->getUser()->setFlash('error', 'You must select an action to execute on the selected items.');
    }
    else 
    {
      $method = 'execute'.ucfirst($action);
      $this->$method($request);  
    }

    $this->redirect(<?php echo $this->urlFor('list') ?>);
  }

  protected function executeBatchDelete(sfWebRequest $request)
  {
    $ids = $request->getParameter('ids');

    $records = Doctrine_Core::getTable('<?php echo $this->getModelClass() ?>')->findById($ids);

    $records->delete();

    $this->getUser()->setFlash('notice', 'The selected items have been deleted.');
    $this->redirect(<?php echo $this->urlFor('list') ?>);
  }
