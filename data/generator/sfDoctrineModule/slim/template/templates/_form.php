[?php use_stylesheets_for_form($form) ?]
[?php use_javascripts_for_form($form) ?]

[?php echo form_tag_for($form, '@<?php echo $this->params['route_prefix'] ?>') ?]
  
  <div class="form-group">
    [?php echo $form ?]
  </div>
  
  [?php include_partial('<?php echo $this->getModuleName() ?>/form_actions', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?]
</form>
