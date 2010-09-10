<td>
<?php foreach ($this->configuration->getValue('list.object_actions') as $name => $params): ?>
<?php if ('_show' == $name): ?>
  <?php echo $this->configuration->addCredentialCondition($this, '[?php echo $helper->linkToShow($'.$this->getSingularName().', '.$this->asPhp($params).') ?]', $params) ?>

<?php elseif ('_delete' == $name): ?>
  <?php echo $this->configuration->addCredentialCondition($this, '[?php echo $helper->linkToDelete($'.$this->getSingularName().', '.$this->asPhp($params).') ?]', $params) ?>

<?php elseif ('deactivate' == $params['class_suffix']): ?>
  <?php echo $this->configuration->addCredentialCondition($this, '[?php echo $helper->linkToDeactivate($'.$this->getSingularName().', '.$this->asPhp($params).') ?]', $params) ?>

<?php elseif ('_edit' == $name): ?>
  <?php echo $this->configuration->addCredentialCondition($this, '[?php echo $helper->linkToEdit($'.$this->getSingularName().', '.$this->asPhp($params).') ?]', $params) ?>

<?php else: ?>
  <?php echo $this->configuration->addCredentialCondition($this, $this->getLinkToAction($name, $params, true), $params) ?>
<?php endif; ?>
<?php endforeach; ?>
</td>
