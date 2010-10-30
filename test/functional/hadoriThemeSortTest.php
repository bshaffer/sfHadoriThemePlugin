<?php

require_once dirname(__FILE__).'/../bootstrap/bootstrap.php';

Doctrine_Query::create()->from('Company')->delete()->execute();
$company1 = new Company();
$company1->name = 'AAA Company';
$company1->created_at = date('Y-m-d', strtotime('-2 months'));
$company1->save();

$company2 = new Company();
$company2->name = 'ZZZ Company';
$company2->save();

$browser = new sfTestFunctionalHadori(new sfBrowser());

$browser->info('1. - Test generated module filter actions')
  ->runTask('sfThemeGenerateTask', array('theme' => 'hadori'), array('application' => 'frontend', 'model' => 'Company', 'module' => 'company'))

  ->setGeneratorConfigValue('company', 
      array('list' => 
        array('display' => array('name', 'city', 'state', 'zip', 'created_at'))))
    
  ->get('/company')
    ->isModuleAction('company', 'index')
  
  ->with('response')->begin()
    ->matches(sprintf('/%s.*%s/s', $company1['name'], $company2['name']))
  ->end()
  
  ->info('Filter by name - ASC')
    ->click('Name')
    
    ->with('response')->begin()
      ->matches(sprintf('/%s.*%s/s', $company1['name'], $company2['name']))
    ->end()
  
  ->info('Filter by name - DESC')
    ->click('Name')
    
    ->with('response')->begin()
      ->matches(sprintf('/%s.*%s/s', $company2['name'], $company1['name']))
    ->end()

  ->info('Filter by Created At - ASC')
    // ->click('Created at')

    ->with('response')->begin()
      ->matches(sprintf('/%s.*%s/s', $company2['name'], $company1['name']))
    ->end()
    
  ->info('Filter by Created At - DESC')
    ->click('Created at')

    ->with('response')->begin()
      ->matches(sprintf('/%s.*%s/s', $company1['name'], $company2['name']))
    ->end()
;