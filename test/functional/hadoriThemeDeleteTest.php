<?php

require_once dirname(__FILE__).'/../bootstrap/bootstrap.php';

Doctrine_Query::create()->from('Company')->delete()->execute();
$company1 = new Company();
$company1->name = 'Company 1';
$company1->save();

$company2 = new Company();
$company2->name = 'Company 2';
$company2->save();

$browser = new sfTestFunctionalTheme(new sfBrowser());
$browser->setTester('doctrine', 'sfTesterDoctrine');

$browser->info('1. - Test generated module edit action')
  ->runTask('sfThemeGenerateTask', array('theme' => 'hadori'), array('application' => 'frontend', 'model' => 'Company', 'module' => 'company'))

  ->get('/company')
    ->isModuleAction('company', 'index')
  
  ->info('Test LIST delete')    
    ->with('response')->begin()
      ->checkElement(sprintf('#company_%s .actions .delete', $company1['id']))
    ->end()
    
    ->call(sprintf('/company/%s', $company1['id']), 'delete')
      ->isModuleAction('company', 'delete', 302)

    ->followRedirect()
      ->isModuleAction('company', 'index')
    
    ->with('doctrine')->begin()
      ->check('Company', array('id' => $company1['id']), false)
    ->end()
    
  ->info('Test EDIT delete')    
    ->click($company2['id'])
      ->isModuleAction('company', 'edit')
      
    ->with('response')->begin()
      ->checkElement(sprintf('a.delete', $company2['id']))
    ->end()
  
    ->call(sprintf('/company/%s', $company2['id']), 'delete')
      ->isModuleAction('company', 'delete', 302)

    ->followRedirect()
      ->isModuleAction('company', 'index')

    ->with('doctrine')->begin()
      ->check('Company', array('id' => $company2['id']), false)
    ->end()
;