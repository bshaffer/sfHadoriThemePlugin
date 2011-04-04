<tr>
<?php if ($this->get('list_batch_actions')): ?>
  <th class="batch checkboxes"><input type="checkbox" class="checkbox" /></th>

<?php endif; ?>
<?php foreach ($this->get('list_display') as $name => $field): ?>
<?php echo $this->startCredentialCondition($field->getConfig()) ?>
  <th>
<?php if ($field->isReal()): ?>
    [?php echo link_to('<?php echo $field->getConfig('label', '', true) ?>', <?php echo $this->urlFor('list') ?>, array('query_string' => 'sort=<?php echo $name ?>&sort_direction='.$helper->toggleSortDirection('<?php echo $name ?>'), 'class' => $helper->getSortDirection('<?php echo $name ?>'))) ?]
<?php else: ?>
    <?php echo $field->getConfig('label', '', true) ?>
<?php endif; ?>
  </th>
<?php echo $this->endCredentialCondition($field->getConfig()) ?>

<?php endforeach; ?>
<?php if ($this->get('list_object_actions')): ?>
  <th class="actions"><?php echo $this->renderText('Actions') ?></th>
<?php endif; ?>
</tr>
