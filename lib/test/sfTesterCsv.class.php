<?php

class sfTesterCsv extends sfTesterResponse
{
  protected
    $response = null,
    $headers  = null,
    $spreadsheets = null,
    $currentRow   = null;

  public function initialize()
  {
    $this->response = $this->browser->getResponse();
    $this->headers = null;
    $this->spreadsheets = null;
    
    if (!$this->response->getHeader('Content-Type') != 'application/vnd.ms-excel') 
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
    if (is_string($column)) 
    {
      $columnNumbers = $this->getColumnNumbersForHeader($column);
      
      if (!$columnNumbers) 
      {
        throw new LogicException(sprintf('Column %s does not exist.  (headers: %s)', $column, implode(',', array_unique($this->getHeaders()))));
      }
      
      $column = $columnNumbers;
    }
    
    $selector = $this->buildRowColumnSelector($row, $column);
    
    $values = $this->domCssSelector->matchAll($selector)->getValues();

    if ($row === null) 
    {
      array_shift($values); // Pop off header
    }
    
    return $values;
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
  
  protected function buildRowColumnSelector($row, $column)
  {
    $selector = '';
    
    if ($row !== null && $row !== '')
    {
      $selector .= sprintf('tr.row%s ', (int)$row);
    }
    
    if ($column !== null && $column !== '') 
    {
      $columnSelectors = array();

      foreach ((array) $column as $spreadsheet => $col) 
      {
        $columnSelectors[] = sprintf('table#sheet%s %std.column%s', $spreadsheet, $selector, (int)$col);
      }
      
      $selector = implode(', ', $columnSelectors);
    }
    
    return $selector;
  }
  
  protected function getSpreadsheets()
  {
    if ($this->spreadsheets === null) 
    {
      $this->spreadsheets = $this->domCssSelector->matchAll('table');
    }
    
    return $this->spreadsheets;
  }

  protected function getHeadersBySpreadsheet()
  {
    if ($this->headers === null) 
    {
      $this->headers = array();
      
      foreach ($this->getSpreadsheets() as $spreadsheet) 
      {
        $this->headers[] = $this->domCssSelector->matchAll(sprintf("table#%s .row0 td", $spreadsheet->getAttribute('id')))->getValues();
      }
    }
    
    return $this->headers;
  }
  
  protected function getHeaders()
  {
    $allHeaders = array();
    
    $headersbySpreadsheet = $this->getHeadersBySpreadsheet();
    
    foreach ($headersbySpreadsheet as $headers) 
    {
      $allHeaders = array_merge($allHeaders, $headers);
    }
    
    return $allHeaders;
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
    
    foreach ($this->getHeadersBySpreadsheet() as $i => $headers) 
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
      $headers = $this->getHeadersBySpreadsheet();
      
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
}