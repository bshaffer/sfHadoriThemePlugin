[?php

/**
 * <?php echo $this->getModuleName() ?> module configuration.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage <?php echo $this->getModuleName()."\n" ?>
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: helper.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class Base<?php echo ucfirst($this->getModuleName()) ?>GeneratorHelper extends sfModelGeneratorHelper
{
  protected $_filters = array();
  
  public function getUrlForAction($action)
  {
    return in_array($action, array('list', 'index')) ? '<?php echo $this->params['route_prefix'] ?>' : '<?php echo $this->params['route_prefix'] ?>_'.$action;
  }
  
  public function getRouteForAction($action)
  {
    return '@'.$this->getUrlForAction($action);
  }
  
  public function setFilters($filters)
  {
    $this->_filters = $filters;
  }
  
  public function getFilters()
  {
    return $this->_filters;
  }
  
  public function getFilter($filter)
  {
    return isset($this->_filters[$filter]) ? $this->_filters[$filter] : null;
  }
  
  public function activeFilters()
  {
    return (bool) $this->_filters;
  }
  
  public function activeFilter($filter)
  {
    if ($filter == 'occurs_in_range')
    {
      if (!empty($this->_filters['occurs_in_range']['from']) || !empty($this->_filters['occurs_in_range']['to']))
      {
        return true;
      }
      else
      {
        return false;
      }
    }
    return isset($this->_filters[$filter]);
  }
  
  public function postSaveAction()
  {
    return 'edit';
  }
  
  public function getExportManager()
  {
    $manager = sfExportManager::create('<?php echo $this->getModelClass() ?>');
    $manager->setFilters($this->getFilters());
    return $manager;
  }

  public function linkToNew($params)
  {
    return '<li class="sf_admin_action_new">'.link_to(__($params['label'], array(), 'sf_admin'), '@'.$this->getUrlForAction('new'), array('title' => __('Add A New ' . $params['label'] == 'New' ? <?php echo sfInflector::humanize($this->params['model_class']) ?> : $params['label'], array(), 'sf_admin'), 'class' => 'qtip')).'</li>';
  }

  public function linkToShow($object, $params)
  {
    return '<li class="sf_admin_action_show">'.link_to(__($params['label'], array(), 'sf_admin'), $this->getUrlForAction('show'), $object, array('title' => __('View ', array(), 'sf_admin') . $object, 'class' => 'qtip')).'</li>';
  }

  public function linkToEdit($object, $params)
  {
    return '<li class="sf_admin_action_edit">'.link_to(__($params['label'], array(), 'sf_admin'), $this->getUrlForAction('edit'), $object, array('title' => __('Edit ', array(), 'sf_admin') . $object, 'class' => 'qtip')).'</li>';
  }

  public function linkToExport($params)
  {
    return '<li class="sf_admin_action_export">'.link_to(__($params['label'], array(), 'sf_admin'), '@'.$this->getUrlForAction('export'), array('title' => __('Export Spreadsheet of <?php echo $this->configuration->getValue('list.title') ?>', array(), 'sf_admin'), 'class' => 'qtip')).'</li>';
  }

  public function linkToDelete($object, $params)
  {
    if ($object->isNew())
    {
      return '';
    }

    return '<li class="sf_admin_action_delete">'.link_to(__($params['label'], array(), 'sf_admin'), $this->getUrlForAction('delete'), $object, array('method' => 'delete', 'confirm' => !empty($params['confirm']) ? __($params['confirm'], array(), 'sf_admin') : $params['confirm'], 'title' => __('Delete ', array(), 'sf_admin') . $object, 'class' => 'qtip')).'</li>';
  }

  public function linkToDeactivate($object, $params)
  {
    if ($object->isNew())
    {
      return '';
    }

    return '<li class="sf_admin_action_deactivate' . (method_exists($object, 'isActive') && $object->isActive() ? '' : '_disabled') . '">'.link_to(__($params['label'], array(), 'sf_admin'), $this->getUrlForAction('delete'), $object, array('method' => 'delete', 'confirm' => !empty($params['confirm']) ? __($params['confirm'], array(), 'sf_admin') : $params['confirm'], 'title' => __('Deactivate ', array(), 'sf_admin') . $object, 'class' => 'qtip')).'</li>';
  }

  public function linkToList($params)
  {
    return '<li class="sf_admin_action_list">'.link_to(__($params['label'], array(), 'sf_admin'), '@'.$this->getUrlForAction('list'), array('title' => __('View All <?php echo $this->configuration->getValue('list.title') ?>', array(), 'sf_admin'), 'class' => 'qtip')).'</li>';
  }
}
