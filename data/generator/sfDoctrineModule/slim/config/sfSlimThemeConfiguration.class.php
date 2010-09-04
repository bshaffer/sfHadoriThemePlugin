<?php 

class sfSlimThemeConfiguration extends sfModuleThemeConfiguration
{
  public function filesToCopy()
  {
    return array_merge(array(
      'templates/_flashes.php' => '%app_dir%/templates/_flashes.php',
    ), parent::filesToCopy());
  } 
}