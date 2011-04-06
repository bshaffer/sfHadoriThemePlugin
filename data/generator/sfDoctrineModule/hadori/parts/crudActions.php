  public function executeNew(sfWebRequest $request)
  {
    $this->form = new <?php echo $this->get('form_class', $this->getModelClass().'Form') ?>();
    $this-><?php echo $this->getSingularName() ?> = $this->form->getObject();
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->form = new <?php echo $this->get('form_class', $this->getModelClass().'Form') ?>();
    $this-><?php echo $this->getSingularName() ?> = $this->form->getObject();

    if($this->processForm($this->form))
    {
      $this->getUser()->setFlash('notice', 'The item was created successfully');

      $this->redirect($request->hasParameter('_save_and_add') ? <?php echo $this->urlFor('new') ?> : <?php echo $this->urlFor('list') ?>);
    }

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this-><?php echo $this->getSingularName() ?> = $this->getRoute()->getObject();
    $this->form = new <?php echo $this->get('form_class', $this->getModelClass().'Form') ?>($this-><?php echo $this->getSingularName() ?>);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this-><?php echo $this->getSingularName() ?> = $this->getRoute()->getObject();
    $this->form = new <?php echo $this->get('form_class', $this->getModelClass().'Form') ?>($this-><?php echo $this->getSingularName() ?>);

    if($this->processForm($this->form))
    {
      $this->getUser()->setFlash('notice', 'The item was updated successfully');

      $this->redirect($request->hasParameter('_save_and_add') ? <?php echo $this->urlFor('new') ?> : <?php echo $this->urlFor('list') ?>);
    }

    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    if ($this->getRoute()->getObject()->delete())
    {
      $this->getUser()->setFlash('notice', 'The item was deleted successfully.');
    }

    $this->redirect(<?php echo $this->urlFor('list') ?>);
  }

  public function executeShow(sfWebRequest $request)
  {
    $this-><?php echo $this->getSingularName() ?> = $this->getRoute()->getObject();
  }
