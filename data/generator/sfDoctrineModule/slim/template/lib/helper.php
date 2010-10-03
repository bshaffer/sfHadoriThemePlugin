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
    return link_to($params['label'], '@'.$this->getUrlForAction('new'), array('title' => 'Add A New ' . $params['label'] == 'New' ? <?php echo sfInflector::humanize($this->params['model_class']) ?> : $params['label']));
  }

  public function linkToShow($object, $params)
  {
    return link_to($params['label'], $this->getUrlForAction('show'), $object, array('title' => 'View ' . $object));
  }

  public function linkToEdit($object, $params)
  {
    return link_to($params['label'], $this->getUrlForAction('edit'), $object, array('title' => 'Edit ' . $object));
  }

  public function linkToSave($object, $params)
  {
    return '<input class="greyButton" type="submit" value="'.$params['label'].'" />';
  }

  public function linkToSaveAndAdd($object, $params)
  {
    if (!$object->isNew())
    {
      return '';
    }

    return '<input class="greyButton" type="submit" value="'.$params['label'].'" name="_save_and_add" />';
  }

  public function linkToExport($params)
  {
    return link_to($params['label'], '@'.$this->getUrlForAction('export'), array('title' => 'Export Spreadsheet of <?php echo $this->configuration->getValue('list.title') ?>'));
  }

  public function linkToDelete($object, $params)
  {
    if ($object->isNew())
    {
      return '';
    }

    return link_to($params['label'], $this->getUrlForAction('delete'), $object, array('method' => 'delete', 'confirm' => !empty($params['confirm']) ? $params['confirm'] : $params['confirm'], 'title' => 'Delete ' . $object));
  }

  public function linkToList($params)
  {
    return link_to($params['label'], '@'.$this->getUrlForAction('list'), array('title' => 'View All <?php echo $this->configuration->getValue('list.title') ?>'));
  }
}
