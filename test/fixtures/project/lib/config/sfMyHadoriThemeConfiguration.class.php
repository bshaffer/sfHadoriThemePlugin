<?php 

class sfMyHadoriThemeConfiguration extends sfHadoriThemeConfiguration
{
  public function filesToCopy()
  {
    $files = array_merge(
      array('MODULE_DIR/templates/_list.php' => 'PROJECT_DIR/data/templates/_list.php'),
      parent::filesToCopy()
    );

    return $files;
  } 
}