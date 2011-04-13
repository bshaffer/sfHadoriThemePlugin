<?php

/**
 * tester class for navigating through csvs and asserting columns and values
 *
 * @package    sfHadoriThemePlugin
 * @subpackage test
 * @author     Brent Shaffer <bshafs@gmail.com>
 */
class sfTesterCsv extends sfTesterResponse
{
  protected
    $response = null,
    $headers  = null,
    $csv      = null,
    $currentRow   = null;

  public function initialize()
  {
    $this->response = $this->browser->getResponse();
    $this->csv  = $this->loadCsv();
    $this->headers = null;

    if (strpos($this->response->getHttpHeader('Content-Type'), 'text/csv') === false) 
    {
      throw new LogicException('no CSV has been generated.');
    }
    
    parent::initialize();
  }
  
  public function contains($value, $column = null, $row = null, $spreadsheet = null)
  {
    $row = $this->getCurrentRow($row);
    
    $values = $this->getPossibleValues($column, $row, $spreadsheet);
    
    $this->tester->ok(in_array($value, $values), $this->buildMessage($value, $this->getHeaderForColumnNumber($column), $row, $spreadsheet));
    
    if (!in_array($value, $values)) 
    {
      $this->tester->comment(sprintf("          Value '%s' does not exist in values '%s'", $value, implode(',', $values)));
    }
    
    return $this->getObjectToReturn();
  }
  
  public function withRow($row = null)
  {
    if (is_array($row)) 
    {
      $this->currentRow = $this->getRowForValues($row);

      return $this->getObjectToReturn();
    }
    
    $this->currentRow = $row;

    return $this->getObjectToReturn();
  }
  
  public function doesNotContain($value, $column = null, $row = null, $spreadsheet = null)
  {
    $row = $this->getCurrentRow($row);
    
    $values = $this->getPossibleValues($column, $row, $spreadsheet);
    
    $this->tester->ok(!in_array($value, $values), $this->buildMessage($value, $this->getHeaderForColumnNumber($column), $row, $spreadsheet, false));
    
    return $this->getObjectToReturn();
  }
  
  protected function getCurrentRow($row = null)
  {
    if ($row === null) 
    {
      return $this->currentRow;
    }
    
    return $row;
  }
  
  protected function getPossibleValues($column = null, $row = null, $spreadsheet = null)
  {
    if ($row === null) 
    {
      array_shift($this->csv); // Pop off header
    }
    
    return $this->csv;
  }
  
  public function containsColumn($column)
  {
    $this->tester->ok(in_array($column, $this->getHeaders()), sprintf('Column "%s" exists', $column));
    
    return $this->getObjectToReturn();
  }
  
  protected function buildMessage($value, $column = null, $row = null, $spreadsheet = null, $bool = true)
  {
    $message = '';
    
    if ($column === null && $row === null) 
    {
      return sprintf("Value $value %s", $bool ? 'exists' : 'does not exist');
    }
    
    if ($column !== null) 
    {
      $message .= sprintf('Column "%s"', $column);
    }
    
    if ($row !== null) 
    {
      $message .= $message ? sprintf(' and row "%s"', $row) : sprintf('Row "%s"', $row);
    }
    
    if ($spreadsheet !== null) 
    {
      $message .= $message ? sprintf(' and spreadsheet "%s"', $spreadsheet) : sprintf('Spreadsheet "%s"', $spreadsheet);
    }
    
    $message .= sprintf(' %s value "%s"', $bool ? 'contains' : 'does not contain', $value);
    
    return $message;
  }
  
  protected function getHeaders()
  {
    return array_pop($this->csv);
  }
  
  protected function getRowForValues($values)
  {
    $possibleValues = null;
    foreach ($values as $col => $value) 
    {
      $possibleValues = $this->getPossibleValues($col);
      
      foreach ($possibleValues as $row => $val) 
      {
        if($val === $value) return ($row + 1);
      }
    }
    
    throw new LogicException("Unable to find a row matching any of the following parameters: ".csToolkit::assoc_implode($values, '%key%=%value%')); 
  }
  
  protected function getColumnNumbersForHeader($header)
  {
    $colNumbers = array();
    
    foreach ($this->getHeaders() as $i => $headers) 
    {
      if (($key = array_search($header, $headers)) !== false) 
      {
        $colNumbers[$i] = $key;
      }
    }
    
    return $colNumbers;
  }
  
  protected function getHeaderForColumnNumber($column)
  {
    if (is_array($column)) 
    {
      $headers = $this->headers;
      
      foreach ($column as $i => $col) 
      {
        if (isset($headers[$i][$col])) 
        {
          return $headers[$i][$col];
        }
      }
      
      return $col;
    }

    return isset($headers[$column]) ? $headers[$column] : $column;
  }
  
  protected function loadCsv()
  {
    $csvString = $this->response->getContent();
    $csvRows = array();
    if (function_exists('str_getcsv')) 
    {
      // PHP 5.3+
      return str_getcsv($csvString);
    }

    $tmpFile = sys_get_temp_dir() . '/symfony.csv.'.$this->getFilename();

    file_put_contents($tmpFile, $csvString);
    
    $handle = fopen($tmpFile, 'r');
    
    while($data = fgetcsv($handle))
    {
      $csvRows[] = $data;
    }
    
    return $csvRows;
  }
  
  public function getFilename()
  {
    $disposition = $this->response->getHttpHeader('Content-Disposition');
    
    $filename = substr($disposition, strpos($disposition, 'filename=') + strlen('filename='));
    
    return str_replace('"', '', $filename);
  }
}