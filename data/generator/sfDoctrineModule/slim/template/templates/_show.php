<div id="information">
  <table>
    <?php foreach ($this->configuration->getValue('show.display') as $name => $field): ?>
      <tr>
        <td width="180"><strong><?php echo $field->getConfig('label', '', true) ?></strong></td>
        <td>[?php echo <?php echo $this->renderField($field) ?>?]</td>
      </tr>
    <?php endforeach; ?>
  </table>
</div>
