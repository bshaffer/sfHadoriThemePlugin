[?php

/**
 * <?php echo $this->getModuleName() ?> module configuration.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage <?php echo $this->getModuleName()."\n" ?>
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: configuration.php 24171 2010-10-18 16:37:50Z Brent Shaffer $
 */
class <?php echo $this->getModuleName() ?>GeneratorConfiguration extends sfHadoriGeneratorConfiguration
{
<?php include dirname(__FILE__).'/actionsConfiguration.php' ?>

<?php include dirname(__FILE__).'/fieldsConfiguration.php' ?>

  public function hasFilterForm()
  {
    return <?php echo !isset($this->config['filter']['class']) || false !== $this->config['filter']['class'] ? 'true' : 'false' ?>;
  }

  /**
   * Gets the filter form class name
   *
   * @return string The filter form class name associated with this generator
   */
  public function getFilterFormClass()
  {
    return '<?php echo isset($this->config['filter']['class']) && !in_array($this->config['filter']['class'], array(null, true, false), true) ? $this->config['filter']['class'] : $this->getModelClass().'FormFilter' ?>';
<?php unset($this->config['filter']['class']) ?>
  }

  public function hasExportFilterForm()
  {
    return <?php echo !isset($this->config['export']['filter']['class']) || false !== $this->config['export']['filter']['class'] ? 'true' : 'false' ?>;
<?php unset($this->config['export']['filter']) ?>
  }

  /**
   * Gets the filter form class name
   *
   * @return string The filter form class name associated with this generator
   */
  public function getExportFilterFormClass()
  {
    return '<?php echo isset($this->config['export']['filter']['class']) && !in_array($this->config['export']['filter']['class'], array(null, true, false), true) ? $this->config['export']['filter']['class'] : $this->getModelClass().'FormFilter' ?>';
<?php unset($this->config['export']['filter']['class']) ?>
  }

  public function getTableMethod()
  {
    return '<?php echo isset($this->config['list']['table_method']) ? $this->config['list']['table_method'] : null ?>';
<?php unset($this->config['list']['table_method']) ?>
  }

  public function getTableCountMethod()
  {
    return '<?php echo isset($this->config['list']['table_count_method']) ? $this->config['list']['table_count_method'] : null ?>';
<?php unset($this->config['list']['table_count_method']) ?>
  }
  
  protected function getConfig()
  {
    return array_merge(parent::getConfig(), array('export' => $this->getFieldsExport()));
  }
  
  public function getExportFilename()
  {
    return "<?php echo isset($this->config['export']['filename']) ? $this->config['export']['filename'] : ucfirst($this->getModuleName()).'_Export' ?>_".date("Y-m-d_Hi");
<?php unset($this->config['export']['filename']) ?>
  }
  
  public function getCredentialPrefix()
  {
    return <?php echo $this->asPhp(isset($this->params['credential_prefix'] ) ? $this->params['credential_prefix'] : null) ?>;
  }
}
