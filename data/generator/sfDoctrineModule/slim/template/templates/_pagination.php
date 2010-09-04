<div class="sf_admin_pagination">

  [?php $img = image_tag(sfConfig::get('sf_admin_module_web_dir').'/images/first.png', array('alt' => __('First page', array(), 'sf_admin'), 'title' => __('First page', array(), 'sf_admin'))) ?]
  [?php echo link_to_current($img, array('page' => 1)) ?]

  [?php $img = image_tag(sfConfig::get('sf_admin_module_web_dir').'/images/previous.png', array('alt' => __('Previous page', array(), 'sf_admin'), 'title' => __('Previous page', array(), 'sf_admin'))) ?]
  [?php echo link_to_current($img, array('page' => $pager->getPreviousPage())) ?]

  [?php foreach ($pager->getLinks() as $page): ?]
    [?php if ($page == $pager->getPage()): ?]
      [?php echo $page ?]
    [?php else: ?]
      [?php echo link_to_current($page, array('page' => $page)) ?]
    [?php endif; ?]
  [?php endforeach; ?]

  [?php $img = image_tag(sfConfig::get('sf_admin_module_web_dir').'/images/next.png', array('alt' => __('Next page', array(), 'sf_admin'), 'title' => __('Next page', array(), 'sf_admin'))) ?]
  [?php echo link_to_current($img, array('page' => $pager->getNextPage())) ?]

  [?php $img = image_tag(sfConfig::get('sf_admin_module_web_dir').'/images/last.png', array('alt' => __('Last page', array(), 'sf_admin'), 'title' => __('Last page', array(), 'sf_admin'))) ?]
  [?php echo link_to_current($img, array('page' => $pager->getLastPage())) ?]
  
</div>