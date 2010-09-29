[?php use_helper('I18N', 'Date') ?]
<div>
<?php if (sfConfig::get('app_admin_include_flashes')): ?>
  [?php include_partial('global/flashes') ?]  
<?php endif ?>
  
  <h1>[?php echo <?php echo $this->getI18NString('show.title') ?> ?]</h1>

  <div>
    [?php include_partial('<?php echo $this->getModuleName() ?>/show', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'configuration' => $configuration, 'helper' => $helper)) ?]
    <div class="form_actions">
      [?php include_partial('<?php echo $this->getModuleName() ?>/show_actions', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'helper' => $helper)) ?]
    </div>
  </div>
</div>
