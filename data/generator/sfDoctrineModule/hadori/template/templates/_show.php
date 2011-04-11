<div class="info-block">
  <dl>
<?php foreach ($this->get('show_display') as $name => $field): ?>
<?php echo $this->startCredentialCondition($field->getOptions()) ?>
    <dt><?php echo $this->renderHtmlText($field->getOption('label', '', true)) ?></dt>
    <dd><?php echo $this->renderField($field) ?></dd>
<?php echo $this->endCredentialCondition($field->getOptions()) ?>

<?php endforeach; ?>
  </dl>
</div>
