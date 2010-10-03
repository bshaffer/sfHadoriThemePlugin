<?php

/**
* 
*/
abstract class sfSlimThemeGeneratorHelper extends sfModelGeneratorHelper
{
  protected 
    $_filters         = array(),
    $_sort            = array(),
    $choiceFormatter  = null;
    
  public function getRouteForAction($action)
  {
    return '@'.$this->getUrlForAction($action);
  }
  
  public function getChoiceFormatter()
  {
    if (!$this->choiceFormatter) 
    {
      $this->choiceFormatter = $this->format = new sfChoiceFormat();
    }
    
    return $this->choiceFormatter;
  }
    
  public function setSort($sort)
  {
    $this->_sort = $sort;
  }
  
  public function getSort()
  {
    return $this->_sort;
  }
  
  public function isActiveSort($name = null)
  {
    if ($name) 
    {
      return $this->getSortField() == $name;
    }
    
    return (bool) $this->_sort;
  }
  
  public function getSortField()
  {
    return isset($this->_sort[0]) ? $this->_sort[0] : null;
  }
  
  public function getSortDirection()
  {
    return isset($this->_sort[1]) ? $this->_sort[1] : null;
  }
  
  public function toggleSortDirection($sortDirection = null)
  {
    $sortDirection = $sortDirection ? $sortDirection : $this->getSortDirection();
    
    return $sortDirection == 'asc' ? 'desc' : 'asc';
  }

  public function setFilters($filters)
  {
    $this->_filters = array_filter($filters);
  }
  
  public function getFilters()
  {
    return $this->_filters;
  }
  
  public function getFilter($filter)
  {
    return isset($this->_filters[$filter]) ? $this->_filters[$filter] : null;
  }
  
  public function isActiveFilter($name = null)
  {
    if ($name) 
    {
      if(!isset($this->_filters[$name]))
      {
        return false;
      }
      
      if (is_array($this->_filters[$name])) 
      {
        return $this->arrayHasValue($this->_filters[$name]);
      }

      return false;
    }
    
    return (bool) $this->_filters;
  }
  
  protected function arrayHasValue($array)
  {
    foreach ($array as $key => $value) 
    {
      if (is_array($value) && $this->arrayHasValue($value)) 
      {
        return true;
      }
      elseif ($value != null) 
      {
        return true;
      }
    }
  }
}
