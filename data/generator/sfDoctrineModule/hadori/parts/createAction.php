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
