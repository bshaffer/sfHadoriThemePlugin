<?php

require_once dirname(__FILE__).'/../bootstrap/bootstrap.php';

$browser = new sfTestFunctionalHadori(new sfBrowser());
$pass = 'PASS';
$fail = 'FAIL';

$browser->info('1. - Test generated module filter actions')
  ->runTask('sfThemeGenerateTask', array('theme' => 'hadori'), array('application' => 'frontend', 'model' => 'Company', 'module' => 'company'))

  ->setSecurityValue('company', array(
        'show' => 
          array('credentials' => array($fail)),
        'edit' => 
          array('credentials' => array($fail)),
        ))

  ->get('/company')
    ->isModuleAction('company', 'index')
  
  ->with('response')->begin()
    ->matches('!/Edit/')
    ->matches('!/Show/')
    ->matches('/Delete/')
  ->end()
;