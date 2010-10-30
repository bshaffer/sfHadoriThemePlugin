<?php

require_once dirname(__FILE__).'/../bootstrap/bootstrap.php';

$browser = new sfTestFunctionalTheme(new sfBrowser());

$browser->info('1. - Test generated module actions')
  ->info('  1.1 - Verify module does not exist')
    // ->cleanup()
  
  ->get('/contact')

  ->with('response')->begin()
    ->isStatusCode(404)
  ->end()
  
  ->info('  1.2 - Run generate:theme task')
  
  ->runTask('sfThemeGenerateTask', array('theme' => 'hadori'), array('application' => 'frontend', 'model' => 'Contact', 'module' => 'contact'))

  ->info('  1.3 - We\'ve got ourselves an admin module!')
  ->get('/contact')
    ->isModuleAction('contact', 'index')
;