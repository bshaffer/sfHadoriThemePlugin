<div id="information">
  [?php if (!$pager->getNbResults()): ?]
    <p><?php echo $this->renderText('No Results') ?></p>
  [?php else: ?]
    <table cellspacing="0">
      <thead>
        <tr>
<?php if ($this->get('list_batch_actions')): ?>
          <th id="sf_admin_list_batch_actions"><input id="sf_admin_list_batch_checkbox" type="checkbox" onclick="checkAll();" /></th>
<?php endif; ?>
          [?php include_partial('<?php echo $this->getModuleName() ?>/list_header', array('helper' => $helper)) ?]
<?php if ($this->get('list_object_actions')): ?>
          <th id="sf_admin_list_th_actions"><?php echo $this->renderText('Actions') ?></th>
<?php endif; ?>
        </tr>
      </thead>
      <tfoot>
        <tr>
          <th colspan="<?php echo count($this->get('list_display')) + ($this->get('list_object_actions') ? 1 : 0) + ($this->get('list_batch_actions') ? 1 : 0) ?>">
            [?php include_partial('<?php echo $this->getModuleName() ?>/pagination', array('pager' => $pager, 'helper' => $helper)) ?]
          </th>
        </tr>
      </tfoot>
      <tbody>
        [?php foreach ($pager->getResults() as $i => $<?php echo $this->getSingularName() ?>): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?]
          [?php include_partial('<?php echo $this->getModuleName() ?>/list_row', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'helper' => $helper, 'checkbox' => <?php echo $this->asPhp($this->get('list_batch_actions') != false) ?>)) ?]
        [?php endforeach; ?]
      </tbody>
    </table>
  [?php endif; ?]
</div>
<script type="text/javascript">
/* <![CDATA[ */
function checkAll()
{
  var boxes = document.getElementsByTagName('input'); for(var index = 0; index < boxes.length; index++) { box = boxes[index]; if (box.type == 'checkbox' && box.className == 'sf_admin_batch_checkbox') box.checked = document.getElementById('sf_admin_list_batch_checkbox').checked } return true;
}
/* ]]> */
</script>
