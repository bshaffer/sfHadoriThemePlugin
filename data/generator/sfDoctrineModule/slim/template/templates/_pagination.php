
[?php if ($pager->haveToPaginate()): ?]
  <div class="sf_admin_pagination">

    [?php $img = image_tag(sfConfig::get('sf_admin_module_web_dir').'/images/first.png', array('alt' => <?php echo $this->renderTextInBlock('First page') ?>, 'title' => <?php echo $this->renderTextInBlock('First page') ?>)) ?]
    [?php echo link_to_current($img, array('page' => 1)) ?]

    [?php $img = image_tag(sfConfig::get('sf_admin_module_web_dir').'/images/previous.png', array('alt' => <?php echo $this->renderTextInBlock('Previous page') ?>, 'title' => <?php echo $this->renderTextInBlock('Previous page') ?>)) ?]
    [?php echo link_to_current($img, array('page' => $pager->getPreviousPage())) ?]

    [?php foreach ($pager->getLinks() as $page): ?]
      [?php if ($page == $pager->getPage()): ?]
        [?php echo $page ?]
      [?php else: ?]
        [?php echo link_to_current($page, array('page' => $page)) ?]
      [?php endif; ?]
    [?php endforeach; ?]

    [?php $img = image_tag(sfConfig::get('sf_admin_module_web_dir').'/images/next.png', array('alt' => <?php echo $this->renderTextInBlock('Next page') ?>, 'title' => <?php echo $this->renderTextInBlock('Next page') ?>)) ?]
    [?php echo link_to_current($img, array('page' => $pager->getNextPage())) ?]

    [?php $img = image_tag(sfConfig::get('sf_admin_module_web_dir').'/images/last.png', array('alt' => <?php echo $this->renderTextInBlock('Last page') ?>, 'title' => <?php echo $this->renderTextInBlock('Last page') ?>)) ?]
    [?php echo link_to_current($img, array('page' => $pager->getLastPage())) ?]
  
  </div>
[?php endif; ?]

[?php echo format_number_choice('[0] no result|[1] 1 result|(1,+Inf] %1% results', array('%1%' => $pager->getNbResults()), $pager->getNbResults(), 'sf_admin') ?]
[?php if ($pager->haveToPaginate()): ?]
  [?php echo strtr('(page %%page%%/%%nb_pages%%)', array('%%page%%' => $pager->getPage(), '%%nb_pages%%' => $pager->getLastPage())) ?]
[?php endif; ?]