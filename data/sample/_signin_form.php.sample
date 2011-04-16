<div id="login-box">
  <form action="<?php echo url_for('@sf_guard_signin') ?>" method="post" id="login-form">
    <?php echo $form->renderHiddenFields() ?>
    <?php echo $form->renderGlobalErrors() ?>
    <table>
      <tbody>
        <?php echo $form['username']->renderRow(array('class' => 'formfield')) ?>
        <?php echo $form['password']->renderRow(array('class' => 'formfield')) ?>
        <tr class="checkbox-row">
          <th><?php echo $form['remember']->renderLabel() ?></th>
          <td><?php echo $form['remember']->render(array('class' => 'remember-me')) ?></td>
        </tr>

      </tbody>
      <tfoot>
        <tr>
          <td colspan="2" class="submit">
            <input type="submit" value="Signin" />
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>