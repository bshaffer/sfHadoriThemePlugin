<tr id="<?php echo $this->getModuleName() ?>_[?php echo $<?php echo $this->getSingularName() ?>['id'] ?]" class="[?php echo $odd ?]">
[?php if ($checkbox): ?>
  <td>
    <input type="checkbox" name="ids[]" value="[?php echo $<?php echo $this->getSingularName() ?>->getPrimaryKey() ?]" class="checkbox" />
  </td>
[?php endif; ?]
<?php foreach ($this->get('list_display') as $name => $field): ?>
<?php echo $this->addCredentialCondition(sprintf(<<<EOF
  <td class="%s">
    [?php echo %s ?]
  </td>

EOF
, $name, $this->renderField($field)), $field->getConfig()) ?>
<?php endforeach; ?>
<?php if ($this->get('list_object_actions')): ?>
    <td>
<?php foreach ($this->get('list_object_actions', array()) as $name => $params): ?>
      <?php echo $this->addCredentialCondition($this->linkTo($name, $params), $params) ?>
  
<?php endforeach; ?>
    </td>
<?php endif; ?>
</tr>