<?php if ($actions = $this->configuration->getValue('show.actions')): ?>
<?php foreach ($actions as $name => $params): ?>
<?php if ('_edit' == $name): ?>
  <?php echo $this->configuration->addCredentialCondition($this, '[?php echo $helper->linkToEdit($'.$this->getSingularName().', '.$this->asPhp($params).') ?]', $params) ?>
<?php else: ?>
  <?php echo $this->configuration->addCredentialCondition($this, $this->getLinkToAction($name, $params, false), $params)."\n" ?>
<?php endif ?>
<?php endforeach; ?>
<?php endif; ?>
