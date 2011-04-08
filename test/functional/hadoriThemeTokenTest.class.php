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
  ->runTask('sfThemeGenerateTask', array('theme' => 'hadori'), array('application' => 'frontend', 'model' => 'Company', 'module' => 'company'))
  
  ->info('configure generator.yml to use wildcards')
    ->setGeneratorConfigValue('company', array(
      'list' => array(
        'title' => 'A List of %%class_label%% Items'),
      'edit' => array(
        'title' => 'Edit the \'%%name%%\' Item (%%created_at%%)',
      )))
  
  ->get('/company')
    ->isModuleAction('company', 'index')
  
  ->with('response')->begin()
    ->matches('/A List of Company Items/')
  ->end()
  
  ->click($company['id'])
    ->isModuleAction('company', 'edit')
  
  ->with('response')->begin()
    ->matches(sprintf('/Edit the \'%s\' Item \(%s\)/', $company['name'], $company['created_at']))
  ->end()
;