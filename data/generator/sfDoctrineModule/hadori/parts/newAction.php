  public function executeNew(sfWebRequest $request)
  {
    $this->form = new <?php echo $this->get('form_class', $this->getModelClass().'Form') ?>();
    $this-><?php echo $this->getSingularName() ?> = $this->form->getObject();
  }
