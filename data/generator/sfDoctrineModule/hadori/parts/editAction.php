  public function executeEdit(sfWebRequest $request)
  {
    $this-><?php echo $this->getSingularName() ?> = $this->getRoute()->getObject();
    $this->form = new <?php echo $this->get('form_class', $this->getModelClass().'Form') ?>($this-><?php echo $this->getSingularName() ?>);
  }
