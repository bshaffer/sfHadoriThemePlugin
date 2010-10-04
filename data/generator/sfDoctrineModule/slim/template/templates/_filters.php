

<form action="[?php echo url_for(<?php echo $this->urlFor('collection', false) ?>, array('action' => 'filter')) ?]" method="post" class="filter-form">

  <fieldset id="filters" class="collapsible">
    <legend>Filters</legend>
    
    <div class="inner">

    [?php if ($form->hasGlobalErrors()): ?]
      [?php echo $form->renderGlobalErrors() ?]
    [?php endif; ?]

    <select class="filter-select">
      <option value="">-- Add Filter --</option>
          [?php foreach ($configuration->getFormFilterFields($form) as $name => $config): ?]
        <option value="[?php echo $name ?]">[?php echo $config['label'] ?]</option>
      [?php endforeach ?]
    </select>


      [?php echo link_to(<?php echo $this->renderTextInBlock('Reset') ?>, <?php echo $this->urlFor('collection', false) ?>, array('action' => 'filter'), array('query_string' => '_reset', 'method' => 'post', 'class' => 'reset')) ?]
      <input type="submit" value="<?php echo $this->renderText('Filter') ?>" class="button" />

      [?php echo $helper->renderHiddenFields($form) ?]
  
      <table>
      [?php foreach ($configuration->getFormFilterFields($form) as $name => $config): ?]
      [?php if ((isset($form[$name]) && $form[$name]->isHidden())) continue ?]
  
        <tr class="[?php echo $name ?] [?php echo $helper->isActiveFilter($name) ? 'active' : 'inactive' ?]">
          <td>
            <input type="checkbox" name="include[[?php echo $name ?]]" class="filter-include" [?php echo $helper->isActiveFilter($name) ? 'checked' : ''?]/>
            [?php echo $form[$name]->renderLabel($config['label']) ?]
          </td>
        
          <td>
            <div class="filter-input">
              [?php echo $form[$name]->renderError() ?]
              [?php echo $form[$name]->render() ?]
      
              [?php if ($help = (isset($config['help']) && $config['help']) || $help = $form[$name]->renderHelp()): ?]
                <div class="help">[?php echo $help ?]</div>
              [?php endif; ?]
            </div>
          </td>
        </tr>

    [?php endforeach; ?]
    </table>
    
    </div>
  </fieldset>
</form>