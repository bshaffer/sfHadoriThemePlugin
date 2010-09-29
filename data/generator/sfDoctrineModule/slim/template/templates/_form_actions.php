<p class="form_buttons">
<?php foreach (array('new', 'edit') as $action): ?>
<?php if ('new' == $action): ?>
[?php if ($form->isNew()): ?]
<?php else: ?>
[?php else: ?]
<?php endif; ?>
<?php foreach ($this->configuration->getValue($action.'.actions') as $name => $params): ?>
  <?php echo $this->configuration->addCredentialCondition($this, $this->linkTo($name, $params), $params) ?>
<?php endforeach; ?>
<?php endforeach; ?>
[?php endif; ?]
</p>
