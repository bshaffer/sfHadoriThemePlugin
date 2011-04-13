<?php

/**
 * class to handle sorting, filtering, pagination, and formatting
 *
 * @package    sfHadoriThemePlugin
 * @subpackage helper
 * @author     Brent Shaffer <bshafs@gmail.com>
 */
class sfHadoriThemeHelper
{
  protected
    $_filters         = array(),
    $_sort            = array(),
    $choiceFormatter  = null;

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

  public function getSortDirection($name = null)
  {
    if ($name && !$this->isActiveSort($name)) {
      // The field is not being sorted - no sort direction
      return null;
    }

    return isset($this->_sort[1]) ? $this->_sort[1] : null;
  }

  public function toggleSortDirection($name = null)
  {
    if ($name && !$this->isActiveSort($name)) {
      // The field is not being sorted - return default sort direction
      return 'asc';
    }

    return $this->getSortDirection() == 'asc' ? 'desc' : 'asc';
  }

  public function setFilters($filters)
  {
    $this->_filters = array();

    // only remember non-null filters
    foreach ($filters as $key => $value) {
      if ($value !== null) {
        $this->_filters[$key] = $value;
      }
    }
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

      return true;
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
      elseif ($value !== null)
      {
        return true;
      }
    }
  }
}
