[?php

/**
 * <?php echo $this->getModuleName() ?> module configuration.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage <?php echo $this->getModuleName()."\n" ?>
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: helper.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
class <?php echo $this->getModuleName() ?>GeneratorHelper extends sfSlimThemeGeneratorHelper
{
  public function getUrlForAction($action)
  {
    return in_array($action, array('list', 'index')) ? '<?php echo $this->params['route_prefix'] ?>' : '<?php echo $this->params['route_prefix'] ?>_'.$action;
  }
  
  public function linkToNew($params)
  {
    return link_to(__($params['label'], array(), 'sf_admin'), '@'.$this->getUrlForAction('new'), array('title' => __('Add A New ' . $params['label'] == 'New' ? <?php echo sfInflector::humanize($this->params['model_class']) ?> : $params['label'], array(), 'sf_admin')));
  }

  public function linkToShow($object, $params)
  {
    return link_to(__($params['label'], array(), 'sf_admin'), $this->getUrlForAction('show'), $object, array('title' => __('View ', array(), 'sf_admin') . $object, 'class' => 'qtip'));
  }

  public function linkToEdit($object, $params)
  {
    return link_to(__($params['label'], array(), 'sf_admin'), $this->getUrlForAction('edit'), $object, array('title' => __('Edit ', array(), 'sf_admin') . $object, 'class' => 'qtip'));
  }

  public function linkToSave($object, $params)
  {
    return '<input class="greyButton" type="submit" value="'.__($params['label'], array(), 'sf_admin').'" />';
  }

  public function linkToSaveAndAdd($object, $params)
  {
    if (!$object->isNew())
    {
      return '';
    }

    return '<input class="greyButton" type="submit" value="'.__($params['label'], array(), 'sf_admin').'" name="_save_and_add" />';
  }

  public function linkToExport($params)
  {
    return link_to(__($params['label'], array(), 'sf_admin'), '@'.$this->getUrlForAction('export'), array('title' => __('Export Spreadsheet of <?php echo $this->configuration->getValue('list.title') ?>', array(), 'sf_admin')));
  }

  public function linkToDelete($object, $params)
  {
    if ($object->isNew())
    {
      return '';
    }

    return link_to(__($params['label'], array(), 'sf_admin'), $this->getUrlForAction('delete'), $object, array('method' => 'delete', 'confirm' => !empty($params['confirm']) ? __($params['confirm'], array(), 'sf_admin') : $params['confirm'], 'title' => __('Delete ', array(), 'sf_admin') . $object, 'class' => 'qtip'));
  }

  public function linkToList($params)
  {
    return link_to(__($params['label'], array(), 'sf_admin'), '@'.$this->getUrlForAction('list'), array('title' => __('View All <?php echo $this->configuration->getValue('list.title') ?>', array(), 'sf_admin'), 'class' => 'qtip'));
  }
}
