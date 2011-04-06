<div class="info-block">
  <dl>
<?php $first = true; foreach ($this->get('show_display') as $name => $field): ?>
    <dt<?php echo $first ? ' class="first"' : '' ?>><?php echo $this->renderHtmlText($field->getOption('label', '', true)) ?></dt>
    <dd<?php echo $first ? ' class="first"' : '';$first=false ?>><?php echo $this->renderField($field) ?></dd>

<?php endforeach; ?>
  </dl>
</div>
