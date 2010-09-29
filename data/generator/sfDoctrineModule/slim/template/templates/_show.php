<div id="information">
  <dl>
    <?php foreach ($this->configuration->getValue('show.display') as $name => $field): ?>
      [?php if($value = <?php echo $this->renderField($field) ?>): ?]
      <dt><?php echo $field->getConfig('label', '', true) ?></dt>
      <dd>[?php echo $value ?]</dd>
      [?php endif ?]
    <?php endforeach; ?>
  </dl>
</div>
