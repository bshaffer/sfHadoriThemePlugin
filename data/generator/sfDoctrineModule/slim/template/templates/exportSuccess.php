[?php use_helper('I18N', 'Date') ?]
<div id="sf_admin_container">
  <h1 class='export_header'>[?php echo <?php echo $this->getI18NString('export.title') ?> ?]</h1>

  [?php include_partial('global/flashes') ?]

  <div id="sf_admin_header">
    [?php include_partial('<?php echo $this->getModuleName() ?>/export_header', array('pager' => $pager)) ?]
  </div>

  <div id="sf_admin_export">
    [?php include_partial('<?php echo $this->getModuleName() ?>/exportForm', array('helper' => $helper, 'configuration' => $configuration)) ?]
  </div>
  
  <div class='help'>The table below represents the data that will be exported.  Use the filters to refine your export</div>

  <div id="sf_admin_content" class="grid_10 alpha<?php echo $this->configuration->hasFilterForm() ? ' with_filters' : '' ?>">
    [?php include_partial('<?php echo $this->getModuleName() ?>/filter_message', array('helper' => $helper)) ?]
    [?php include_partial('<?php echo $this->getModuleName() ?>/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper, 'checkboxes' => false)) ?]
    <ul class="sf_admin_actions">
      [?php include_partial('<?php echo $this->getModuleName() ?>/export_actions', array('helper' => $helper)) ?]
    </ul>
  </div> 

<?php if ($this->configuration->hasExportFilterForm()): ?>
  <div id="sf_admin_bar" class="sf_admin_filters [?php echo $helper->activeFilters() ? 'active':'' ?]">
    [?php include_partial('<?php echo $this->getModuleName() ?>/filters', array('form' => $filters, 'helper' => $helper, 'configuration' => $configuration)) ?]
  </div>
<?php endif; ?>

  <div id="sf_admin_footer">
    [?php include_partial('<?php echo $this->getModuleName() ?>/export_footer', array('pager' => $pager)) ?]
  </div>
</div>
