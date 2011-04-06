<div id="information">
  [?php if (!$pager->getNbResults()): ?]
    <p><?php echo $this->renderHtmlText('No Results') ?></p>
  [?php else: ?]
    <table cellspacing="0">
      <thead>
        [?php include_partial('<?php echo $this->getModuleName() ?>/list_header', array('helper' => $helper)) ?]
      </thead>
      <tbody>
        [?php foreach ($pager->getResults() as $i => $<?php echo $this->getSingularName() ?>): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?]
          [?php include_partial('<?php echo $this->getModuleName() ?>/list_row', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'helper' => $helper, 'odd' => $odd, 'checkbox' => <?php echo $this->asPhp($this->get('list_batch_actions') != false) ?>)) ?]
        [?php endforeach; ?]
      </tbody>
    </table>
  [?php endif; ?]

  [?php include_partial('<?php echo $this->getModuleName() ?>/pagination', array('pager' => $pager, 'helper' => $helper)) ?]
</div>
