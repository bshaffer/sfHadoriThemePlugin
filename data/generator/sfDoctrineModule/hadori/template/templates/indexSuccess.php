[?php use_helper('Date'<?php echo $this->get('i18n') ? ', \'I18N\'' : ''?>) ?]
<div>
<?php if (sfConfig::get('app_hadori_include_flashes')): ?>
  [?php include_partial('global/flashes') ?]  
<?php endif ?>
    
  <h2><?php echo $this->renderHtmlText($this->get('list_title')) ?></h2>

<?php if ($this->configuration->hasFilterForm()): ?>
  <div class="filters form-container[?php echo $helper->isActiveFilter() ? ' active':'' ?]">
    [?php include_partial('<?php echo $this->getModuleName() ?>/filters', array('form' => $filters, 'helper' => $helper)) ?]
  </div>
<?php endif; ?>

  <div class="form-container<?php echo $this->configuration->hasFilterForm() ? ' with_filters' : '' ?>">
<?php if ($this->get('list_batch_actions')): ?>
    <form action="[?php echo url_for('<?php echo $this->getUrlForAction('collection') ?>', array('action' => 'batch')) ?]" method="post">
<?php endif; ?>
    [?php include_partial('<?php echo $this->getModuleName() ?>/list', array('pager' => $pager, 'helper' => $helper)) ?]
    <div class="actions">
<?php if ($batchActions = $this->get('list_batch_actions')): ?>
      <select name="batch_action">
        <option value=""><?php echo $this->renderHtmlText('Choose an action') ?></option>
<?php foreach ((array) $batchActions as $name => $params): ?>
        <?php echo $this->addCredentialCondition('<option value="'.$params['action'].'">'.$this->renderHtmlText($params['label']).'</option>', $params) ?>

<?php endforeach; ?>
      </select>
      <input type="submit" value="<?php echo $this->renderHtmlText('go') ?>" />
<?php endif; ?>

<?php foreach ($this->get('list_actions') as $name => $params): ?>
      <?php echo $this->linkTo($name, $params) ?>
        
<?php endforeach; ?>

    </div>
<?php if ($this->get('list_batch_actions')): ?>
    </form>
<?php endif; ?>
  </div>
</div>
