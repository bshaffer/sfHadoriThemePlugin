<div>
<?php if (sfConfig::get('app_admin_include_flashes')): ?>
  [?php include_partial('global/flashes') ?]  
<?php endif ?>
  
  <h1>[?php echo <?php echo $this->renderText($this->get('show_title')) ?> ?]</h1>

  <div>
    [?php include_partial('<?php echo $this->getModuleName() ?>/show', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'helper' => $helper)) ?]
    <div class="form_actions">
<?php foreach ($this->get('show_actions') as $name => $params): ?>
      <?php echo $this->linkTo($name, $params) ?>
  
<?php endforeach; ?>
    </div>
  </div>
</div>
