<?php foreach ($this->get('list_display') as $name => $field): ?>
<?php echo $this->startCredentialCondition($field->getConfig()) ?>
<th>
<?php if ($field->isReal()): ?>
  [?php if ($helper->isActiveSort('<?php echo $name ?>')): ?]
    [?php echo link_to('<?php echo $field->getConfig('label', '', true) ?>', <?php echo $this->urlFor('list') ?>, array('query_string' => 'sort=<?php echo $name ?>&sort_direction='.$helper->toggleSortDirection(), 'class' => $helper->getSortDirection())) ?]
  [?php else: ?]
    [?php echo link_to('<?php echo $field->getConfig('label', '', true) ?>', <?php echo $this->urlFor('list') ?>, array('query_string' => 'sort=<?php echo $name ?>&sort_direction=asc')) ?]
  [?php endif; ?]
<?php else: ?>
  <?php echo $field->getConfig('label', '', true) ?>
<?php endif; ?>
</th>
<?php echo $this->endCredentialCondition($field->getConfig()) ?>
<?php endforeach; ?>
