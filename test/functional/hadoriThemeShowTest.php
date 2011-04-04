<?php

require_once dirname(__FILE__).'/../bootstrap/bootstrap.php';

Doctrine_Query::create()->from('Company')->delete()->execute();
$company = new Company();
$company->name = 'Company Name 1';
$company->created_at = date('Y-m-d', strtotime('-2 months'));
$company->save();

$browser = new sfTestFunctionalTheme(new sfBrowser());

$browser->info('1. - Test generated module show action')
  ->runTask('sfThemeGenerateTask', array('theme' => 'hadori'), array('application' => 'frontend', 'model' => 'Company', 'module' => 'company'))

  ->get('/company')
    ->isModuleAction('company', 'index')

  ->click(sprintf('#company_%s .actions .show', $company['id']))
    ->isModuleAction('company', 'show')

  ->with('response')->begin()
    ->matches(sprintf('/%s/', $company['name']))
    ->matches(sprintf('/%s/', $company['created_at']))
  ->end()
;