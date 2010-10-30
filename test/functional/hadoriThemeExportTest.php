<?php

require_once dirname(__FILE__).'/../bootstrap/bootstrap.php';

Doctrine_Query::create()->from('Company')->delete()->execute();
$company1 = new Company();
$company1->name = 'Company Name 1';
$company1->created_at = date('Y-m-d', strtotime('-2 months'));
$company1->save();

$company2 = new Company();
$company2->name = 'Company Name 2';
$company2->save();

$browser = new sfTestFunctionalHadori(new sfBrowser());
$browser->setTester('export', 'sfTesterCsv');

$browser->info('1. - Test generated module export actions')
  ->runTask('sfThemeGenerateTask', array('theme' => 'hadori'), array('application' => 'frontend', 'model' => 'Company', 'module' => 'company'))
  
  ->info('configure export route and generator.yml param')
    ->setRoutingValue(array('company' => array('options' => array('with_export' => true))))
    ->setGeneratorConfigValue('company', array('export' => array(true)))
  
  ->get('/company')
    ->isModuleAction('company', 'index')
    
  ->click('Export')
    ->isModuleAction('company', 'export')
    
  ->click('Export')
    ->isModuleAction('company', 'export')
    
  ->with('export')->begin()
    ->contains($company1['name'], 'Name')
    ->contains($company2['name'], 'Name')
  ->end()
;