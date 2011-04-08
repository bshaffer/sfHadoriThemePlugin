<?php

require_once dirname(__FILE__).'/../bootstrap/bootstrap.php';

Doctrine_Query::create()->from('Company')->delete()->execute();
$company = new Company();
$company->name = 'Test Company 1';
$company->created_at = date('Y-m-d', strtotime('-2 months'));
$company->save();

$browser = new sfTestFunctionalHadori(new sfBrowser());
$browser->setTester('export', 'sfTesterCsv');

$browser->info('1. - Test generated module export actions')
  ->runTask('sfThemeGenerateTask', array('theme' => 'my-hadori'), array('application' => 'frontend', 'model' => 'Company', 'module' => 'company'))
    
  ->get('/company')
    ->isModuleAction('company', 'index')
  
  ->with('response')->begin()
    ->matches('/A Custom List Partial For My Theme/')
  ->end()
;