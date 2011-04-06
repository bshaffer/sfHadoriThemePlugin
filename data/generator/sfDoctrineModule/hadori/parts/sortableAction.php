  public function executePromote(sfWebRequest $request)
  {
    $<?php echo $this->getSingularName() ?> = Doctrine::getTable('<?php echo $this->getModelClass() ?>')->findOneById($request->getParameter('id'));

    $this->setSort(array('position', 'asc'));
    $<?php echo $this->getSingularName() ?>->promote();
    $this->redirect(<?php echo $this->urlFor('list') ?>);
  }

  public function executeDemote(sfWebRequest $request)
  {
    $<?php echo $this->getSingularName() ?> = Doctrine::getTable('<?php echo $this->getModelClass() ?>')->findOneById($request->getParameter('id'));

    $<?php echo $this->getSingularName() ?>->demote();
    $this->setSort(array('position', 'asc'));
    $this->redirect(<?php echo $this->urlFor('list') ?>);
  }
