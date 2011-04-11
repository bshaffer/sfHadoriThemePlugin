[?php if ($pager->haveToPaginate()): ?]
  <div class="pagination">
    [?php if($pager->getFirstPage() != $pager->getPage()): ?]
      [?php echo link_to(<?php echo $this->renderPhpText('First') ?>, <?php echo $this->urlFor('list', false) ?>, array('page' => 1), array('class' => 'first_page')) ?]
    [?php else: ?]
      <span class="disabled first_page"><?php echo $this->renderHtmlText('First') ?></span>
    [?php endif ?]
    
    [?php foreach ($pager->getLinks() as $page): ?]
      [?php if ($page == $pager->getPage()): ?]
        <span class="current">[?php echo $page ?]</span>
      [?php else: ?]
        [?php echo link_to($page, <?php echo $this->urlFor('list', false) ?>, array('page' => $page)) ?]
      [?php endif; ?]
    [?php endforeach; ?]

    [?php if($pager->getLastPage() != $pager->getPage()): ?]
      [?php echo link_to(<?php echo $this->renderPhpText('Last') ?>, <?php echo $this->urlFor('list', false) ?>, array('page' => $pager->getLastPage()), array('class' => 'last_page')) ?]
    [?php else: ?]
      <span class="disabled last_page"><?php echo $this->renderHtmlText('Last') ?></span>
    [?php endif ?]

  </div>
[?php endif; ?]

[?php echo $helper->getChoiceFormatter()->format(sprintf('[0] |[1] 1 result|(1,+Inf] %s results', $pager->getNbResults()), $pager->getNbResults()) ?]
[?php if ($pager->haveToPaginate()): ?]
  [?php echo strtr('(page %%page%%/%%nb_pages%%)', array('%%page%%' => $pager->getPage(), '%%nb_pages%%' => $pager->getLastPage())) ?]
[?php endif; ?]