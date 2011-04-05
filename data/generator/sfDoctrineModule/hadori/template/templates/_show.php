<div class="info-block">
  <dl>
<?php foreach ($this->get('show_display') as $name => $field): ?>
    <dt><?php echo $field->getOption('label', '', true) ?></dt>
    <dd><?php echo $this->renderField($field) ?></dd>
<?php endforeach; ?>
  </dl>
</div>
