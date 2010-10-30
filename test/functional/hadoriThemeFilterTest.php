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

$browser = new sfTestFunctionalTheme(new sfBrowser());
$browser->setTester('doctrine', 'sfTesterDoctrine');

$browser->info('1. - Test generated module actions')
  ->info('  1.1 - Verify module does not exist')
    // ->cleanup()
  
  ->get('/company')

  ->with('response')->begin()
    ->isStatusCode(404)
  ->end()
  
  ->info('  1.2 - Run generate:theme task')
  
  ->runTask('sfThemeGenerateTask', array('theme' => 'hadori'), array('application' => 'frontend', 'model' => 'Company', 'module' => 'company'))

  ->info('  1.3 - We\'ve got ourselves an admin module!')
  ->get('/company')
    ->isModuleAction('company', 'index')
    
  ->info('set filter field and check "include" checkbox.  This should filter')
    ->setField('include[name]', true)
    ->setField('company_filters[name][text]', $company1['name'])
    ->click('Filter')

    ->with('form')->begin()
      ->hasErrors(false)
    ->end()

    ->followRedirect()

    ->with('response')->begin()
      ->matches(sprintf('/%s/', $company1['name']))
      ->matches(sprintf('!/%s/', $company2['name']))
    ->end()

  ->info('reset the filters')
    ->click('Reset')

    ->followRedirect()

    ->with('response')->begin()
      ->matches(sprintf('/%s/', $company1['name']))
      ->matches(sprintf('/%s/', $company2['name']))
    ->end()
    
  ->info('Filter by Created At')
    ->setField('include[created_at]', true)
    ->setField('company_filters[created_at][from]', date('Y-m-d', strtotime('-1 months')))
    ->click('Filter')

    ->with('form')->begin()
      ->hasErrors(false)
    ->end()
    
    ->followRedirect()

    ->with('response')->begin()
      ->matches(sprintf('!/%s/', $company1['name']))
      ->matches(sprintf('/%s/', $company2['name']))
    ->end()
;