<div class="[?php echo $helper->activeFilters() ? 'active':'inactive' ?]">
  [?php if ($form->hasGlobalErrors()): ?]
    [?php echo $form->renderGlobalErrors() ?]
  [?php endif; ?]

  <form action="[?php echo url_for('<?php echo $this->getUrlForAction('collection') ?>', array('action' => 'filter', 'for_action' => $sf_params->get('for_action', $sf_params->get('action')))) ?]" method="post">
    <table cellspacing="0">
      <tfoot>
        <tr>
          <td>
            [?php echo $form->renderHiddenFields() ?]
            [?php echo link_to(<?php echo $this->renderTextInBlock('Reset') ?>, '<?php echo $this->getUrlForAction('collection') ?>', array('action' => 'filter'), array('query_string' => '_reset&for_action=' . $sf_params->get('for_action', $sf_params->get('action')), 'method' => 'post')) ?]
            <input type="submit" value="<?php echo $this->renderText('Filter') ?>" />
          </td>
        </tr>
      </tfoot>
      <tbody>
        [?php foreach ($configuration->getFormFilterFields($form) as $name => $field): ?]
        [?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) continue ?]

          <div class="[?php echo $name ?]">
              [?php echo $form[$name]->renderLabel($field->getConfig('label')) ?]
          </div>

          <div>
              [?php echo $form[$name]->renderError() ?]
              [?php echo $form[$name]->render() ?]

              [?php if ($help = $field->getConfig('help') || $help = $form[$name]->renderHelp()): ?]
                <div class="help">[?php echo $help ?]</div>
              [?php endif; ?]
          </div>

        [?php endforeach; ?]
      </tbody>
    </table>
  </form>
</div>
