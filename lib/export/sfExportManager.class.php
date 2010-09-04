<?php

/**
* 
*/
class sfExportManager
{
  protected $options            = array(),
            $_xls               = null,
            $_sheets            = 0,
            $_current_increment = 0,
            $_total_increments  = 1;
  
  public function __construct($class = null, $title = null, $multisheet = false)
  {
    if ($class && !class_exists($class)) 
    {
      throw new sfException("Invalid class: $class.  Class does not exist");
    }
    
    if (is_null($title))
    {
      $title = $this->getExportTitle($class);
    }
    
    $this->_xls = new sfPhpExcel();
    $this->_xls->getProperties()->setTitle($title);
    $this->_xls->getProperties()->setSubject($title);
    $this->_xls->getProperties()->setDescription($title);
  }
  
  public static function create($class, $title = null, $multisheet = false)
  {
    $managerClass = sprintf('sfExportManager%s', sfInflector::camelize($class));
    if (class_exists($managerClass)) 
    {
      return new $managerClass($class, $title, $multisheet);
    }
    
    return new self($class, $title, $multisheet);
  }

  public function initialize($params = array())
  {
  }


  /**
   * Returns the current manager's options.
   *
   * @return array The current manager's options
   */
  public function getOptions()
  {
    return $this->options;
  }

  /**
   * Sets an option value.
   *
   * @param string $name  The option name
   * @param mixed  $value The default value
   *
   * @return sfExportManager The current manager instance
   */
  public function setOption($name, $value)
  {
    $this->options[$name] = $value;

    return $this;
  }

  /**
   * Gets an option value.
   *
   * @param string $name    The option name
   * @param mixed  $default The default value (null by default)
   *
   * @param mixed  The default value
   */
  public function getOption($name, $default = null)
  {
    return isset($this->options[$name]) ? $this->options[$name] : $default;
  }

  public function getExportTitle($class = null)
  {
    return substr(sprintf('%sData Export', $class ? $class . ' ' : ''), 0, 31);
  }
   
  public function filterColumns($fields, $ids = array())
  {
    return $fields;
  }
  
  public function exportField($object, $field)
  {
    return $this->exportObjectRowFieldDefault($object, $field);
  }
  
  public function exportObjectRowFieldDefault($object, $field)
  {
    return $object[$field];
  }
  
  public function getTotalIncrements()
  {
    return $this->_total_increments;
  }
  
  public function setTotalIncrements($total)
  {
    $this->_total_increments = $total;
  }

  public function getCurrentIncrement()
  {
    return $this->_current_increment;
  }
  
  public function setCurrentIncrement($current)
  {
    $this->_current_increment = $current;
  }

  public function increment()
  {
    $this->setCurrentIncrement($this->getCurrentIncrement() + 1);
  }

  /**
   * exportCollectionSheet
   *
   * default sheet export for collection
   *
   * @param string $object 
   * @param string $fields 
   * @return void
   * @author Brent Shaffer
   */
  public function exportCollectionSheet($collection, $fields, $title = null)
  {
    if($this->_sheets > 0)
    {
      $workSheet = $this->_xls->createSheet();
      $this->_sheets++;
    }
    else
    {
      $workSheet = $this->_xls->getActiveSheet();
      $this->_sheets++;
    }

    $workSheet->setTitle($this->getExportTitle($title));

    // Initialize coordinate counters
    $row = 1;
    $col = 0;

    foreach ($fields as $field => $label)
    {
      $workSheet->setCellValueByColumnAndRow($col, $row, $this->exportLabel($field, $label));
      $workSheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($col))->setAutoSize(true);
      $col++;
    }
    $row++;

    foreach ($collection as $record) 
    {
      $col = 0;
      foreach ($fields as $field => $label)
      {
        $workSheet->setCellValueByColumnAndRow($col, $row, $this->exportField($record, $field));
        $col++;
      }
      $row++;

      $this->increment();
    }
  }
  
  public function exportLabel($field, $label)
  {
    return $label ? $label : sfInflector::humanize($field);
  }
  
  public function getDefaultFormat()
  {
    if (sfConfig::get('sf_environment') == 'test') 
    {
      return 'HTML';
    }
    
    return 'Excel5';
  }
  
  public function output($filename = 'export', $format = null)
  {
    if(is_null($format))
    {
      $format = $this->getDefaultFormat();
    }

    switch(strtolower($format))
    {
      case 'excel2007':
        $content_type = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        $ext = 'xlsx';
        break;
      case 'csv':
        $content_type = 'application/vnd.ms-excel';
        $ext = 'csv';
        break;
      case 'html':
        $content_type = 'text/html';
        $ext = 'html';
        break;
      case 'pdf':
        $content_type = 'application/pdf';
        $ext = 'pdf';
        break;
      default:
        $content_type = 'application/vnd.ms-excel';
        $ext = 'xls';
    }

    // redirect output to client browser
    if($format != 'HTML')
    {
      header('Content-Type: ' . $content_type);
      header('Content-Disposition: attachment;filename="' . $filename . "." . $ext . '"');
      header('Cache-Control: max-age=0');
    }
    
    $xlsWriter = PHPExcel_IOFactory::createWriter($this->_xls, $format);
    $xlsWriter->save('php://output'); 
  }
}
