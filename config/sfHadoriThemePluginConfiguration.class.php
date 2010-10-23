<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfHadoriThemePlugin configuration.
 * 
 * @package    sfHadoriThemePlugin
 * @subpackage config
 * @author     Brent Shaffer
 * @version    SVN: $Id: sfHadoriThemePluginConfiguration.class.php
 */
class sfHadoriThemePluginConfiguration extends sfPluginConfiguration
{
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    $plugins = $this->configuration->getPlugins();
    
    if (!in_array('sfThemeGeneratorPlugin', $plugins)) 
    {
      throw new sfException("Plugin 'sfThemeGeneratorPlugin' must be enabled in order to run 'sfHadoriThemePlugin'.  Please visit http://github.com/bshaffer/sfThemeGeneratorPlugin to install this plugin");
    }
  }
}