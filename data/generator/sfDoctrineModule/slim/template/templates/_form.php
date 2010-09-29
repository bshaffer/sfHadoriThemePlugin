<div class="admin-form">
[?php echo form_tag_for($form, '@<?php echo $this->params['route_prefix'] ?>') ?]
  [?php echo $form->renderGlobalErrors() ?]
  [?php echo $form->renderHiddenFields() ?]
  
<?php $formClass = $this->getModelClass().'Form' ?>
<?php foreach ($this->configuration->getFormFields(new $formClass(), 'Form') as $fieldsetName => $fields): ?>
  <fieldset class="<?php echo $fieldsetName == 'NONE' ? 'form-group' : 'form-group-'.$fieldsetName ?>">
<?php foreach ($fields as $name => $config): ?>
      [?php echo $form[<?php echo $name ?>]->renderError() ?]
      [?php echo $form[<?php echo $name ?>]->renderLabel() ?]
      [?php echo $form[<?php echo $name ?>]->render() ?]

<?php endforeach; ?>
  </fieldset>  
<?php endforeach; ?>

  [?php include_partial('<?php echo $this->getModuleName() ?>/form_actions', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'form' => $form, 'helper' => $helper)) ?]
</form>
</div>