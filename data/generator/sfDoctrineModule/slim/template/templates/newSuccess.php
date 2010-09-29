[?php use_helper('I18N', 'Date') ?]
<div>
<?php if (sfConfig::get('app_admin_include_flashes')): ?>
  [?php include_partial('global/flashes') ?]  
<?php endif ?>

  <h1>[?php echo <?php echo $this->getI18NString('new.title') ?> ?]</h1>

  <div>
    [?php include_partial('<?php echo $this->getModuleName() ?>/form', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?]
  </div>
</div>
