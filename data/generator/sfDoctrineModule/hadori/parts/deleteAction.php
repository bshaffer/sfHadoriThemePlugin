  public function executeDelete(sfWebRequest $request)
  {
    if ($this->getRoute()->getObject()->delete())
    {
      $this->getUser()->setFlash('notice', 'The item was deleted successfully.');
    }

    $this->redirect(<?php echo $this->urlFor('list') ?>);
  }
