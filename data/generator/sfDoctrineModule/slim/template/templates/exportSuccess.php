[?php use_helper('I18N', 'Date') ?]
<div>
<?php if (sfConfig::get('app_admin_include_flashes')): ?>
  [?php include_partial('global/flashes') ?]  
<?php endif ?>
  
  <h1 class='export_header'>[?php echo <?php echo $this->getI18NString('export.title') ?> ?]</h1>

  <div>
    [?php include_partial('<?php echo $this->getModuleName() ?>/exportForm', array('helper' => $helper, 'configuration' => $configuration)) ?]
  </div>
  
  <div class='help'>The table below represents the data that will be exported.  Use the filters to refine your export</div>

  <div class="<?php echo $this->configuration->hasFilterForm() ? ' with_filters' : '' ?>">
    [?php include_partial('<?php echo $this->getModuleName() ?>/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper, 'checkboxes' => false)) ?]
    <ul class="actions">
      [?php include_partial('<?php echo $this->getModuleName() ?>/export_actions', array('helper' => $helper)) ?]
    </ul>
  </div> 

<?php if ($this->configuration->hasExportFilterForm()): ?>
  <div class="filters [?php echo $helper->activeFilters() ? 'active':'' ?]">
    [?php include_partial('<?php echo $this->getModuleName() ?>/filters', array('form' => $filters, 'helper' => $helper, 'configuration' => $configuration)) ?]
  </div>
<?php endif; ?>
</div>
