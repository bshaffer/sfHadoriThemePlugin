<div id="information">
  <dl>
<?php foreach ($this->get('show_display') as $name => $field): ?>
    <dt><?php echo $field->getConfig('label', '', true) ?></dt>
    <dd>[?php echo <?php echo $this->renderField($field) ?> ?]</dd>
<?php endforeach; ?>
  </dl>
</div>
