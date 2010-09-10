<p class="form_buttons">
<?php foreach (array('new', 'edit') as $action): ?>
<?php if ('new' == $action): ?>
[?php if ($form->isNew()): ?]
<?php else: ?>
[?php else: ?]
<?php endif; ?>
<?php foreach ($this->configuration->getValue($action.'.actions') as $name => $params): ?>
<?php if ('_delete' == $name): ?>
  <?php echo $this->configuration->addCredentialCondition($this, '[?php echo $helper->linkToDelete($form->getObject(), '.$this->asPhp($params).') ?]', $params) ?>

<?php elseif ('_list' == $name): ?>
  <?php echo $this->configuration->addCredentialCondition($this, '[?php echo $helper->linkToList('.$this->asPhp($params).') ?]', $params) ?>

<?php elseif ('_save' == $name): ?>
  <?php echo $this->configuration->addCredentialCondition($this, '[?php echo $helper->linkToSave($form->getObject(), '.$this->asPhp($params).') ?]', $params) ?>

<?php elseif ('_save_and_add' == $name): ?>
  <?php echo $this->configuration->addCredentialCondition($this, '[?php echo $helper->linkToSaveAndAdd($form->getObject(), '.$this->asPhp($params).') ?]', $params) ?>

<?php else: ?>
[?php if (method_exists($helper, 'linkTo<?php echo $method = ucfirst(sfInflector::camelize($name)) ?>')): ?]
  <?php echo $this->configuration->addCredentialCondition($this, '[?php echo $helper->linkTo'.$method.'($form->getObject(), '.$this->asPhp($params).') ?]', $params) ?>

[?php else: ?]
  <?php echo $this->configuration->addCredentialCondition($this, $this->getLinkToAction($name, $params, true), $params) ?>

[?php endif; ?]
<?php endif; ?>
<?php endforeach; ?>
<?php endforeach; ?>
[?php endif; ?]
</p>
