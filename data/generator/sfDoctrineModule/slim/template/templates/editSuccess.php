[?php use_helper('I18N', 'Date') ?]
<div>
<?php if (sfConfig::get('app_admin_include_flashes')): ?>
  [?php include_partial('global/flashes') ?]  
<?php endif ?>
  
  <h1><?php echo $this->renderText($this->get('edit_title')) ?></h1>

  <div class="form-container">
    [?php include_partial('<?php echo $this->getModuleName() ?>/form', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'form' => $form, 'helper' => $helper)) ?]
  </div>
</div>
