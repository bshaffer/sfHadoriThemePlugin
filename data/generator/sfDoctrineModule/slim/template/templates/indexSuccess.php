[?php use_helper('I18N', 'Date') ?]
<div id="sf_admin_container">
  <h2 class="list_header">[?php echo <?php echo $this->getI18NString('list.title') ?> ?]</h2>

  [?php include_partial('global/flashes') ?]

<?php if ($this->configuration->hasFilterForm()): ?>
  <div class="filters form-container">
    [?php include_partial('<?php echo $this->getModuleName() ?>/filters', array('form' => $filters, 'helper' => $helper, 'configuration' => $configuration)) ?]
  </div>
<?php endif; ?>

  <div class="form-container <?php echo $this->configuration->hasFilterForm() ? ' with_filters' : '' ?>">
    [?php include_partial('<?php echo $this->getModuleName() ?>/filter_message', array('helper' => $helper)) ?]
<?php if ($this->configuration->getValue('list.batch_actions')): ?>
    <form action="[?php echo url_for('<?php echo $this->getUrlForAction('collection') ?>', array('action' => 'batch')) ?]" method="post">
<?php endif; ?>
    [?php include_partial('<?php echo $this->getModuleName() ?>/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?]
    <ul>
      [?php include_partial('<?php echo $this->getModuleName() ?>/list_batch_actions', array('helper' => $helper)) ?]
      [?php include_partial('<?php echo $this->getModuleName() ?>/list_actions', array('helper' => $helper)) ?]
    </ul>
<?php if ($this->configuration->getValue('list.batch_actions')): ?>
    </form>
<?php endif; ?>
  </div>
</div>
