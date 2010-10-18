  protected function processForm(sfForm $form)
  {
    $form->bind($this->getRequest()->getParameter($form->getName()), $this->getRequest()->getFiles($form->getName()));

    if ($form->isValid())
    {
      $<?php echo $this->getSingularName() ?> = $form->save();

      $this->dispatcher->notify(new sfEvent($this, 'admin.save_object', array('object' => $<?php echo $this->getSingularName() ?>)));
      
      return $<?php echo $this->getSingularName() ?>;
    }
    
    return false;
  }
