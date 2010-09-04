<?php

// handle --symfony_dir argument
if (!isset($_SERVER['SYMFONY']))
{
  foreach ($argv as $arg) 
  {
    $params = explode('=', $arg);
    if (isset($params[1]) && $params[0] == '--symfony_dir') 
    {
      $_SERVER['SYMFONY'] = $params[1];
      file_put_contents('/tmp/symfony_dir', $params[1]);
      break;
    }
  }
}

if (!isset($_SERVER['SYMFONY']) || !file_exists($_SERVER['SYMFONY'])) 
{
  throw new Exception(sprintf("Symfony directory%s not found.  Please set \$_SERVER['SYMFONY'] or provide a --symfony_dir argument", isset($_SERVER['SYMFONY']) ? " '$_SERVER[SYMFONY]'" : ''));
}

include dirname(__FILE__).'/../bootstrap/unit.php';

$h = new lime_harness(new lime_output_color());
$h->register(sfFinder::type('file')->name('*Test.php')->in(dirname(__FILE__).'/..'));

exit($h->run() ? 0 : 1);