  public function getActionsDefault()
  {
    return <?php echo $this->asPhp(isset($this->config['actions']) ? $this->config['actions'] : array()) ?>;
<?php unset($this->config['actions']) ?>
  }

  public function getFormActions()
  {
    return <?php echo $this->asPhp(isset($this->config['form']['actions']) ? $this->config['form']['actions'] : array()) ?>;
  }

  public function getNewActions()
  {
    return <?php echo $this->asPhp(isset($this->config['new']['actions']) ? $this->config['new']['actions'] : array()) ?>;
  }

  public function getEditActions()
  {
    return <?php echo $this->asPhp(isset($this->config['edit']['actions']) ? $this->config['edit']['actions'] : array()) ?>;
  }

  public function getExportActions()
  {
    return <?php echo $this->asPhp(isset($this->config['export']['actions']) ? $this->config['export']['actions'] : array()) ?>;
  }
  
  public function getShowActions()
  {
    return <?php echo $this->asPhp(isset($this->config['show']['actions']) ? $this->config['show']['actions'] : array()) ?>;
  }

  public function getListObjectActions()
  {
    return <?php echo $this->asPhp(isset($this->config['list']['object_actions']) ? $this->config['list']['object_actions'] : array()) ?>;
  }

  public function getListActions()
  {
    return <?php echo $this->asPhp(isset($this->config['list']['actions']) ? $this->config['list']['actions'] : 
                                        (isset($this->config['export']) && $this->config['export'] ? 
                                        array('_new' => null, 'export' => array('action' => 'export')) : array('_new' => null))) ?>;
  }

  public function getListBatchActions()
  {
    return <?php echo $this->asPhp(isset($this->config['list']['batch_actions']) ? $this->config['list']['batch_actions'] : array('_delete' => null)) ?>;
  }

  public function hasExporting()
  {
    return <?php echo $this->asPhp(array_key_exists('export', $this->config) && $this->config['export'] !== false) ?>;
<?php if(isset($this->config['export']) && !is_array($this->config['export'])) unset($this->config['export']) ?>
  }
