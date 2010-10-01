  protected function getSort()
  {
    $sort = $this->getUser()->getAttribute('<?php echo $this->getModuleName() ?>.sort', <?php echo $this->asPhp($this->get('list_sort', array())) ?>);
    $this->helper->setSort($sort);
    return $sort;
  }

  protected function setSort(array $sort)
  {
    if (null !== $sort[0] && null === $sort[1])
    {
      $sort[1] = 'asc';
    }

    $this->getUser()->setAttribute('<?php echo $this->getModuleName() ?>.sort', $sort);
    
    $this->helper->setSort($sort);
    return $sort;
  }
