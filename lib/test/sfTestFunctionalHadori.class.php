<?php

/**
 * provides convenience methods for writing tests within the hadori testing environment
 *
 * @package    sfHadoriThemePlugin
 * @subpackage test
 * @author     Brent Shaffer <bshafs@gmail.com>
 */
class sfTestFunctionalHadori extends sfTestFunctionalTheme
{
  public function setGeneratorConfigValue($module, array $values)
  { 
    $values = array('generator' => array('param' => array('config' => $values)));
    
    return $this->setGeneratorValue($module, $values);
  }
  
  public function setGeneratorParamValue($module, array $values)
  {
    $values = array('generator' => array('param' => $values));

    return $this->setGeneratorValue($module, $values);
  }
  
  public function setGeneratorValue($module, array $values)
  {
    $this->info('setting generator.yml config values');
      
    $path = sfConfig::get('sf_app_module_dir') . '/' . $module . '/config/generator.yml';
        
    $config = Doctrine_Lib::arrayDeepMerge(sfYaml::load($path), $values);

    file_put_contents($path, sfYaml::dump($config));
    
    sfToolkit::clearDirectory(sfConfig::get('sf_cache_dir'));
    
    return $this;
  }
  
  public function setSecurityValue($module, array $values)
  {
    $this->info('setting security.yml config values');
      
    $path = sfConfig::get('sf_app_module_dir') . '/' . $module . '/config/security.yml';
        
    $config = Doctrine_Lib::arrayDeepMerge(sfYaml::load($path), $values);

    file_put_contents($path, sfYaml::dump($config));
    
    sfToolkit::clearDirectory(sfConfig::get('sf_cache_dir'));
    
    return $this;
  }
  
  public function clearSecurityValues($module)
  {
    $this->info('clearing security.yml config values');
      
    $path = sfConfig::get('sf_app_module_dir') . '/' . $module . '/config/security.yml';
        
    file_put_contents($path, '');
    
    sfToolkit::clearDirectory(sfConfig::get('sf_cache_dir'));
    
    return $this;
  }
  
  public function clearModuleDirectory($module)
  {
    $this->info(sprintf('clearing module directory "%s" in application "%s"', $module, sfConfig::get('sf_application')));
    
    $path = sfConfig::get('app_module_dir') . '/' . $module;
    
    sfToolkit::clearDirectory($path);
    
    return $this;
  }
  
  public function setRoutingValue(array $values)
  {
    $this->info('setting routing.yml config values');
      
    $path = sfConfig::get('sf_app_dir') . '/config/routing.yml';
        
    $config = Doctrine_Lib::arrayDeepMerge(sfYaml::load($path), $values);

    file_put_contents($path, sfYaml::dump($config));
    
    sfToolkit::clearDirectory(sfConfig::get('sf_cache_dir'));
    
    return $this;
  }
}
