<?php

require_once dirname(__FILE__).'/../bootstrap/bootstrap.php';

Doctrine_Query::create()->from('Company')->delete()->execute();
$company = new Company();
$company->name = 'Company Name';
$company->save();

$browser = new sfTestFunctionalTheme(new sfBrowser());
$browser->setTester('doctrine', 'sfTesterDoctrine');

$browser->info('1. - Test generated module edit action')
  ->runTask('sfThemeGenerateTask', array('theme' => 'hadori'), array('application' => 'frontend', 'model' => 'Company', 'module' => 'company'))
  
  ->get('/company')
    ->isModuleAction('company', 'index')
    
  ->click($company['id'])
    ->isModuleAction('company', 'edit')
    
  ->setField('company[name]', 'New Company Name')
  ->click('Save')
  
  ->with('form')->begin()
    ->hasErrors(false)
  ->end()
  
  ->with('doctrine')->begin()
    ->check('Company', array('id' => $company['id'], 'name' => 'New Company Name'))
  ->end()
;