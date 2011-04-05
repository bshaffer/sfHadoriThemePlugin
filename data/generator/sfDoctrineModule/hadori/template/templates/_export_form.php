[?php echo form_tag('@<?php echo $this->params['route_prefix'] ?>_export') ?]
<table>
  <tr><th>Include in Export</th><th>Field</th><th>Label (optional)</th></tr>
<?php foreach ($this->get('export_display') as $name => $field): ?>
  <tr>
    <td><input name="include[<?php echo $name ?>]" type="checkbox" checked /></td>
    <td><?php echo $field->getOption('label', '', true) ?></td>
    <td>
      <input name="export[<?php echo $name ?>]" type="textbox" size="20">
    </td>
  </tr>
<?php endforeach ?>

  </table>
  <input type="submit" value="Export" />
</form>
