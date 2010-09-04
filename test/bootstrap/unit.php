<?php

if (!isset($_SERVER['SYMFONY']) && file_exists('/tmp/symfony_dir')) 
{
  $_SERVER['SYMFONY'] = file_get_contents('/tmp/symfony_dir');
}

if (!isset($_SERVER['SYMFONY']) || !file_exists($_SERVER['SYMFONY'])) 
{
  throw new Exception(sprintf("Symfony directory%s not found.  Please set \$_SERVER['SYMFONY'] or provide a --symfony_dir argument", isset($_SERVER['SYMFONY']) ? " '$_SERVER[SYMFONY]'" : ''));
}

require_once $_SERVER['SYMFONY'].'/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

$configuration = new sfProjectConfiguration(dirname(__FILE__).'/../fixtures/project');
require_once $configuration->getSymfonyLibDir().'/vendor/lime/lime.php';

function sfSlimAdminGeneratorPlugin_autoload_again($class)
{
  $autoload = sfSimpleAutoload::getInstance();
  $autoload->reload();
  return $autoload->autoload($class);
}
spl_autoload_register('sfSlimAdminGeneratorPlugin_autoload_again');

function sfSlimAdminGeneratorPlugin_cleanup()
{
  sfToolkit::clearDirectory(dirname(__FILE__).'/../fixtures/project/cache');
  sfToolkit::clearDirectory(dirname(__FILE__).'/../fixtures/project/log');
}
sfSlimAdminGeneratorPlugin_cleanup();
copy(dirname(__FILE__).'/../fixtures/project/data/fresh_test_db.sqlite', dirname(__FILE__).'/../fixtures/project/data/test.sqlite');
register_shutdown_function('sfSlimAdminGeneratorPlugin_cleanup');

require_once dirname(__FILE__).'/../fixtures/project/config/ProjectConfiguration.class.php';
$configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'test', isset($debug) ? $debug : true);
$context = sfContext::createInstance($configuration);
new sfDatabaseManager($configuration);

// so that all notices will appear
error_reporting(E_ALL);
