<?php

/**
 * tester class for sorting, filtering, pagination, etc for hadori modules
 *
 * @package    sfHadoriThemePlugin
 * @subpackage test
 * @author     Brent Shaffer <bshafs@gmail.com>
 */
class sfTesterAdmin extends sfTester
{
  protected 
    $user, 
    $request;
  
  public function prepare()
  {
  }
  
  /**
   * Initializes the tester.
   */
  public function initialize()
  {
    $this->user = $this->browser->getUser();
    $this->request = $this->browser->getRequest();
  }
  
  /**
   * Tests a user attribute value.
   *
   * @param string $key
   * @param string $value
   * @param string $ns
   *
   * @return sfTestFunctionalBase|sfTester
   */
  public function hasFilter($exists = true)
  {
    $this->tester->is($this->user->hasAttribute($this->getFilterKey(), 'admin_module'), $exists, $exists ? 'filtering is active' : 'filters is not active');

    return $this->getObjectToReturn();
  }

  public function isFilter($field, $value)
  {
    $filter = $this->user->getAttribute($this->getFilterKey(), null, 'admin_module');

    $this->tester->is(isset($filter[$field]) ? $filter[$field] : null, $value, sprintf('filtering for "%s", "%s"', $field, $value));

    return $this->getObjectToReturn();    
  }

  public function hasSort($exists = true)
  {
    $this->tester->is($this->user->hasAttribute($this->getSortKey(), 'admin_module'), $exists, $exists ? 'sorting is active' : 'sorting is not active');

    return $this->getObjectToReturn();
  }
  
  public function isSort($field, $direction = null)
  {
    $sort = $this->user->getAttribute($this->getSortKey(), null, 'admin_module');
    
    $this->tester->is(count($sort) ? $sort[0] : null, $field, sprintf('sorting by "%s"', $field));
    
    if ($direction) 
    {
      $this->tester->is(count($sort) > 1 ? strtolower($sort[1]) : null, strtolower($direction), sprintf('sorting direction is "%s"', $direction));
    }

    return $this->getObjectToReturn();    
  }
  
  public function isPage($page)
  {
    $currentPage = $this->user->getAttribute($this->getPageKey(), null, 'admin_module');
    
    $this->tester->is($currentPage, $page, sprintf('page is "%s"', $page));

    return $this->getObjectToReturn();    
  }
  
  public function debug()
  {
    $sort = $this->user->getAttribute($this->getSortKey(), null, 'admin_module');
    $filter = $this->user->getAttribute($this->getFilterKey(), null, 'admin_module');
    $page = $this->user->getAttribute($this->getPageKey(), null, 'admin_module');
    
    printf("Sort: %s", print_r($sort, true));
    printf("Filter: %s", print_r($filter, true));
    echo "Page: " . $page;
    
    return $this->getObjectToReturn();
  }

  protected function getSortKey()
  {
    return sprintf('%s.sort', $this->request->getParameter('module'));
  }

  protected function getPageKey()
  {
    return sprintf('%s.page', $this->request->getParameter('module'));
  }
  
  protected function getFilterKey()
  {
    return sprintf('%s.filters', $this->request->getParameter('module'));
  }
}
