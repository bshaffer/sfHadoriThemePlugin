<?php

require_once dirname(__FILE__).'/../bootstrap/bootstrap.php';

Doctrine_Query::create()->from('Company')->delete()->execute();
$company1 = new Company();
$company1->name = 'AAA Company';
$company1->save();

$company2 = new Company();
$company2->name = 'ZZZ Company';
$company2->save();

$browser = new sfTestFunctionalTheme(new sfBrowser());
$browser->setTester('doctrine', 'sfTesterDoctrine');

$browser->info('1. - Test generated module edit action')
  ->runTask('sfThemeGenerateTask', array('theme' => 'hadori'), array('application' => 'frontend', 'model' => 'Company', 'module' => 'company'))
  
  ->get('/company')
    ->isModuleAction('company', 'index')
  
  ->with('response')->begin()
    ->matches(sprintf('/%s/', $company1['name']))
    ->matches(sprintf('/%s/', $company2['name']))
  ->end()
  
  ->select('ids[]')
  ->setField('batch_action', 'batchDelete')

  ->click('go')
    ->isModuleAction('company', 'batch', 302)

  ->followRedirect()
    ->isModuleAction('company', 'index')
    
  ->with('doctrine')->begin()
    ->check('Company', array('id' => $company1['id']), false)
    ->check('Company', array('id' => $company2['id']), true)
  ->end()
;