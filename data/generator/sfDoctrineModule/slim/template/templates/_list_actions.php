<?php if ($actions = $this->configuration->getValue('list.actions')): ?>
<?php foreach ($actions as $name => $params): ?>
<?php if ('_new' == $name): ?>
<?php echo $this->configuration->addCredentialCondition($this, $this->linkToNew($params), $params)."\n" ?>
<?php elseif ('export' == $params['class_suffix']): ?>
<?php echo $this->configuration->addCredentialCondition($this, $this->linkToExport($params), $params)."\n" ?>
<?php else: ?>
<?php echo $this->configuration->addCredentialCondition($this, $this->getLinkToAction($name, $params, false), $params)."\n" ?>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
