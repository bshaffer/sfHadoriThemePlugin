<div class="admin-form">
[?php echo form_tag_for($form, '@<?php echo $this->params['route_prefix'] ?>') ?]
  [?php echo $form->renderGlobalErrors() ?]
  [?php echo $form->renderHiddenFields() ?]
  
<?php $formClass = $this->getModelClass().'Form' ?>
<?php foreach ($this->configuration->getFormFields(new $formClass(), 'Form') as $fieldsetName => $fields): ?>
  <fieldset class="<?php echo $fieldsetName == 'NONE' ? 'form-group' : 'form-group-'.$fieldsetName ?>">
<?php foreach ($fields as $name => $config): ?>
      [?php echo $form['<?php echo $name ?>']->renderError() ?]
      [?php echo $form['<?php echo $name ?>']->renderLabel() ?]
      [?php echo $form['<?php echo $name ?>']->render() ?]

<?php endforeach; ?>
  </fieldset>  
<?php endforeach; ?>

  <p class="form_buttons">
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