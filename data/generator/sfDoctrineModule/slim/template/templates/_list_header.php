<?php foreach ($this->get('list_display') as $name => $field): ?>
<?php echo $this->startCredentialCondition($field->getConfig()) ?>
<th>
<?php if ($field->isReal()): ?>
  [?php if ($helper->isActiveSort('<?php echo $name ?>')): ?]
    [?php echo link_to('<?php echo $field->getConfig('label', '', true) ?>', <?php echo $this->urlFor('list') ?>, array('query_string' => 'sort=<?php echo $name ?>&sort_direction='.$helper->toggleSortDirection())) ?]
    [?php echo image_tag(sfConfig::get('sf_admin_module_web_dir').'/images/'.$sort[1].'.png', array('alt' => $sort[1], 'title' => $sort[1])) ?]
  [?php else: ?]
    [?php echo link_to('<?php echo $field->getConfig('label', '', true) ?>', <?php echo $this->urlFor('list') ?>, array('query_string' => 'sort=<?php echo $name ?>&sort_direction=asc')) ?]
  [?php endif; ?]
<?php else: ?>
  <?php echo $field->getConfig('label', '', true) ?>
<?php endif; ?>
</th>
<?php echo $this->endCredentialCondition($field->getConfig()) ?>
<?php endforeach; ?>
