[?php echo form_tag('@<?php echo $this->params['route_prefix'] ?>_export') ?]
<table>
  <tr><th>Include in Export</th><th>Field</th><th>Label (optional)</th></tr>
  <tr>
    <td><input name="export[export_date][include]" type="checkbox" checked /></td>
    <td>Export Date</td>
    <td>
      <input name="export[export_date][default]" value="Export Date" type="hidden" />
      <input name="export[export_date][label]" type="textbox" size="20">
    </td>
  </tr>
  <?php $fields = $this->configuration->getValue('export.display') ?>
  [?php if($helper->activeFilter('occurs_in_range')): ?]
  <?php foreach (array('export_start_date' => "Export Start Date", 'export_end_date' => "Export End Date") as $name => $label): ?>
    <?php if (isset($fields[$name])): $label = $fields[$name]->getConfig('label', '', true); unset($fields[$name]); endif?>
    <tr>
      <td><input name="export[<?php echo $name ?>][include]" type="checkbox" checked /></td>
      <td><?php echo $label ?></td>
      <td>
        <input name="export[<?php echo $name ?>][default]" value="<?php echo $label ?>" type="hidden" />
        <input name="export[<?php echo $name ?>][label]" type="textbox" size="20">
      </td>
    </tr>
  <?php endforeach ?>
  [?php endif ?]
<?php foreach ($fields as $name => $field): ?>
  <tr>
    <td><input name="export[<?php echo $name ?>][include]" type="checkbox" checked /></td>
    <td><?php echo $field->getConfig('label', '', true) ?></td>
    <td>
      <input name="export[<?php echo $name ?>][default]" value="<?php echo $field->getConfig('label', '', true) ?>" type="hidden" />
      <input name="export[<?php echo $name ?>][label]" type="textbox" size="20">
    </td>
  </tr>
<?php endforeach ?>
  </table>
  <input type="submit" value="Export" />
</form>
