[?php use_helper('Date'<?php echo $this->get('i18n') ? ', \'I18N\'' : ''?>) ?]
<div>
<?php if (sfConfig::get('app_hadori_include_flashes')): ?>
  [?php include_partial('global/flashes') ?]  
<?php endif ?>
  
  <h1><?php echo $this->renderHtmlText($this->get('export_title')) ?></h1>

  <div>
    [?php include_partial('<?php echo $this->getModuleName() ?>/export_form', array('helper' => $helper)) ?]
  </div>

<?php if ($help = $this->get('export_help')): ?>
  <div class='help'><?php echo $this->renderHtmlText($help) ?></div>

<?php endif ?>
  <div class="export-preview">
    [?php include_partial('<?php echo $this->getModuleName() ?>/list', array('pager' => $pager, 'helper' => $helper)) ?]
    <ul class="actions">
<?php foreach ($this->configuration->getValue('export.actions') as $name => $params): ?>
      <?php echo $this->linkTo($name, $params) ?>
  
<?php endforeach; ?>    
    </ul>
  </div> 

<?php if ($this->configuration->hasExportFilterForm()): ?>
  <div class="filters[?php echo $helper->isActiveFilter() ? ' active':'' ?]">
    [?php include_partial('<?php echo $this->getModuleName() ?>/filters', array('form' => $filters, 'helper' => $helper)) ?]
  </div>
<?php endif; ?>
</div>
