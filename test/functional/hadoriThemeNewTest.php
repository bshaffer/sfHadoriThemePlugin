<?php

require_once dirname(__FILE__).'/../bootstrap/bootstrap.php';

$browser = new sfTestFunctionalTheme(new sfBrowser());
$browser->setTester('doctrine', 'sfTesterDoctrine');
$companyName = 'New Company Name '.rand();

$browser->info('1. - Test generated module new action')
  ->runTask('sfThemeGenerateTask', array('theme' => 'hadori'), array('application' => 'frontend', 'model' => 'Company', 'module' => 'company'))
  
  ->get('/company')
    ->isModuleAction('company', 'index')
    
  ->click('New')
    ->isModuleAction('company', 'new')
    
  ->setField('company[name]', $companyName)
  ->click('Save')
  
  ->with('form')->begin()
    ->hasErrors(false)
  ->end()
  
  ->with('doctrine')->begin()
    ->check('Company', array('name' => $companyName))
  ->end()
;