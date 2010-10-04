<tr id="<?php echo $this->getModuleName() ?>_[?php echo $<?php echo $this->getSingularName() ?>['id'] ?]" class="[?php echo $odd ?]">
[?php if ($checkbox): ?>
  <td>
    <input type="checkbox" name="ids[]" value="[?php echo $<?php echo $this->getSingularName() ?>->getPrimaryKey() ?]" class="checkbox" />
  </td>
[?php endif; ?]
<?php foreach ($this->get('list_display') as $name => $field): ?>
<?php echo $this->startCredentialCondition($field->getConfig()) ?>
  <td class="<?php echo $name ?>">
    [?php echo <?php echo $this->renderField($field) ?> ?]
  </td>
<?php echo $this->endCredentialCondition($field->getConfig()) ?>

<?php endforeach; ?>
<?php if ($this->get('list_object_actions')): ?>
    <td class="actions">
<?php foreach ($this->get('list_object_actions', array()) as $name => $params): ?>
      <?php echo $this->linkTo($name, $params) ?>
  
<?php endforeach; ?>
    </td>
<?php endif; ?>
</tr>