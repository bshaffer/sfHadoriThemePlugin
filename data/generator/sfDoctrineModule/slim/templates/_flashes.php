<?php if ($sf_user->hasFlash('error')): ?>
  <div id="error"><?php echo $sf_user->getFlash('error') ?></div>
<?php endif; ?>

<?php if ($sf_user->hasFlash('notice')): ?>
  <div id="notice"><?php echo $sf_user->getFlash('notice') ?></div>
<?php endif; ?>

<?php if ($sf_user->hasFlash('success')): ?>
  <div id="success"><?php echo $sf_user->getFlash('success') ?></div>
<?php endif; ?>