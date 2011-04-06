[?php echo form_tag('@<?php echo $this->params['route_prefix'] ?>_export') ?]
<table>
  <tr><th><?php echo $this->renderHtmlText('Include in Export') ?></th><th><?php echo $this->renderHtmlText('Field') ?></th><th><?php echo $this->renderHtmlText('Label (optional)') ?></th></tr>
<?php foreach ($this->get('export_display') as $name => $field): ?>
  <tr>
    <td><input name="include[<?php echo $name ?>]" type="checkbox" checked /></td>
    <td><?php echo $this->renderHtmlText($field->getOption('label', '', true)) ?></td>
    <td>
      <input name="export[<?php echo $name ?>]" type="textbox" size="20">
    </td>
  </tr>
<?php endforeach ?>

  </table>
  <input type="submit" value="<?php echo $this->renderHtmlText('Export') ?>" />
</form>
