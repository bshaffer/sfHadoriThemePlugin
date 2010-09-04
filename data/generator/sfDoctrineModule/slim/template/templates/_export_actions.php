<?php if ($actions = $this->configuration->getValue('export.actions')): ?>
<?php foreach ($actions as $name => $params): ?>
<?php if ('_list' == $name): ?>
  <?php echo $this->configuration->addCredentialCondition($this, '[?php echo $helper->linkToList('.$this->asPhp($params).') ?]', $params) ?>
<?php else: ?>
<li class="sf_admin_action_<?php echo $params['class_suffix'] ?>">
  <?php echo $this->configuration->addCredentialCondition($this, $this->getLinkToAction($name, $params, false), $params)."\n" ?>
</li>
<?php endif ?>
<?php endforeach; ?>
<?php endif; ?>
