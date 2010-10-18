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
