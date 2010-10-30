<?php
$_SERVER['SYMFONY'] = '/usr/local/lib/symfony/RELEASE_1_4_8/lib';
$_SERVER['SYMFONY_PLUGINS_DIR'] = '/Users/bshafs/Sites/localhost/sandbox/plugins';

if (!isset($_SERVER['SYMFONY']))
{
  throw new RuntimeException('Could not find symfony core libraries.');
}

require_once $_SERVER['SYMFONY'].'/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->setPlugins(array(
      'sfThemeGeneratorPlugin',
      'sfHadoriThemePlugin',
      'sfDoctrinePlugin',
    ));

    $this->setPluginPath('sfThemeGeneratorPlugin', $_SERVER['SYMFONY_PLUGINS_DIR'].'/sfThemeGeneratorPlugin');
    $this->setPluginPath('sfHadoriThemePlugin', dirname(__FILE__).'/../../../..');
  }
}
