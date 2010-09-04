<?php if ($actions = $this->configuration->getValue('list.actions')): ?>
<?php foreach ($actions as $name => $params): ?>
<?php if ('_new' == $name): ?>
<?php echo $this->configuration->addCredentialCondition($this, '[?php echo $helper->linkToNew('.$this->asPhp($params).') ?]', $params)."\n" ?>
<?php elseif ('export' == $params['class_suffix']): ?>
<?php echo $this->configuration->addCredentialCondition($this, '[?php echo $helper->linkToExport('.$this->asPhp($params).') ?]', $params)."\n" ?>
<?php else: ?>
<li class="sf_admin_action_<?php echo $params['class_suffix'] ?>">
  <?php echo $this->configuration->addCredentialCondition($this, $this->getLinkToAction($name, $params, false), $params)."\n" ?>
</li>
<?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>
