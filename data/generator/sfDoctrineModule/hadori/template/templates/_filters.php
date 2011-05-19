<form action="[?php echo url_for(<?php echo $this->urlFor('collection', false) ?>, array('action' => 'filter')) ?]" method="post" class="filter-form">
  <fieldset id="filters" class="collapsible">
    <legend><?php echo $this->renderHtmlText('Filters') ?></legend>

    <div class="inner">
      [?php if ($form->hasGlobalErrors()): ?]
        [?php echo $form->renderGlobalErrors() ?]
      [?php endif; ?]
      
      [?php echo $form->renderHiddenFields(); ?]
      
      [?php foreach ($form->getFormFieldSchema()->getHiddenFields() as $key => $field): ?]
        <input type="hidden" name="include[[?php echo $field->getName() ?]]" value="1"/>
      [?php endforeach ?]

      <select class="filter-select">
        <option value=""><?php echo $this->renderHtmlText('-- Add Filter --') ?></option>
        [?php foreach ($form as $name => $field): if($field->isHidden()) continue; ?]
          <option value="[?php echo $name ?]">[?php echo $field->renderLabel() ?]</option>
        [?php endforeach ?]
      </select>

      [?php echo link_to(<?php echo $this->renderPhpText('Reset') ?>, <?php echo $this->urlFor('collection', false) ?>, array('action' => 'filter'), array('query_string' => '_reset', 'method' => 'post', 'class' => 'reset')) ?]

      <input type="submit" value="<?php echo $this->renderHtmlText('Filter') ?>" class="button" />

      <table>
        [?php foreach ($form as $name => $field): ?]
          [?php if ($field->isHidden()) continue ?]
          <tr class="[?php echo $name ?] [?php echo $helper->isActiveFilter($name) || $field->hasError() ? 'active' : 'inactive' ?]">
            <td>
              <input type="checkbox" name="include[[?php echo $name ?]]" class="filter-include" [?php echo $helper->isActiveFilter($name) || $field->hasError()  ? 'checked' : ''?]/>
              [?php echo $field->renderLabel() ?]
            </td>

            <td>
              <div class="filter-input">
                [?php echo $field->render() ?]

                [?php if ($help = $field->renderHelp()): ?]
                  <div class="help">[?php echo $help ?]</div>
                [?php endif; ?]
              </div>
            </td>
            
            <td>[?php echo $field->renderError() ?]</td>
          </tr>
        [?php endforeach; ?]
      </table>

    </div>
  </fieldset>
</form>