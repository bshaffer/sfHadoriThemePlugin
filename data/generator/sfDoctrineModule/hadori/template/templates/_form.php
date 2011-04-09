[?php include_javascripts_for_form($form) ?]
[?php include_stylesheets_for_form($form) ?]

<div class="admin-form">
[?php echo form_tag_for($form, '@<?php echo $this->params['route_prefix'] ?>') ?]
  [?php echo $form->renderGlobalErrors() ?]
  [?php echo $form->renderHiddenFields() ?]
  
<?php $formClass = $this->getFormClass() ?>
<?php $form = new $formClass() ?>
<?php foreach ($form as $name => $config): if($config->isHidden()) continue ?>
  <div class="<?php echo $this->getFormFieldContainerClass($form, $name) ?>">
    [?php echo $form['<?php echo $name ?>']->renderRow(<?php echo $this->getFormFieldAttributes($form, $name) ?>) ?]
  </div>

<?php endforeach; ?>
  <p class="actions">
  [?php if ($form->isNew()): ?]
<?php foreach ($this->get('new_actions') as $name => $params): ?>
    <?php echo $this->linkTo($name, $params) ?>
  
<?php endforeach; ?>
  [?php else: ?]
<?php foreach ($this->get('edit_actions') as $name => $params): ?>
    <?php echo $this->linkTo($name, $params) ?>
  
<?php endforeach; ?>
  [?php endif; ?]
  </p>
</form>
</div>