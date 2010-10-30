<?php

require_once dirname(__FILE__).'/../bootstrap/bootstrap.php';

$t = new lime_test(null, new lime_output_color());

$company1 = new Company();
$company1['name'] = 'Company Name 1';
$company1['city'] = 'City 1';
$company1['state'] = 'State 1';
$company1->save();

$company2 = new Company();
$company2['name'] = 'Company Name 2';
$company2['city'] = 'City 2';
$company2['state'] = 'State 2';
$company2->save();

$companies = Doctrine::getTable('Company')
  ->createQuery()
  ->whereIn('id', array($company1['id'], $company2['id']))
  ->execute();

$manager = new sfExportManager();
$csvArray = $manager->doExport($companies, array('name'));

$t->is(count($csvArray), 3, 'Three rows (including "headers")');
$t->is(count($csvArray[0]), 1, 'One Column: "name"');
$t->is($csvArray[1], $company1['name'], 'First row is Company 1');
$t->is($csvArray[2], $company2['name'], 'Second row is Company 2');

$manager = new sfExportManager();
$csvArray = $manager->doExport($companies, array('name', 'city' => 'Company City', 'state'));

$t->is(count($csvArray), 3, 'Three rows (including "headers")');
$headers = explode(',', $csvArray[0]);

$t->is(count($headers), 3, 'Three Column: "name", "city", "state"');
$t->is($headers[1], 'Company City', 'Label for "city" is "Company City"');

$row1 = explode(',', $csvArray[1]);
$row2 = explode(',', $csvArray[2]);
$t->is($row1[1], $company1['city'], 'First row second column is is Company 1 city');
$t->is($row2[1], $company2['city'], 'Second row second column is is Company 2 city');