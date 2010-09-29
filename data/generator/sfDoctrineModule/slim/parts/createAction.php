  public function executeCreate(sfWebRequest $request)
  {
    $this->form =     $this->form = new <?php echo $this->get('form_class', $this->getModelClass().'Form') ?>();
    $this-><?php echo $this->getSingularName() ?> = $this->form->getObject();

    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }
