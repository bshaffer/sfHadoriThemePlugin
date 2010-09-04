<?php

/**
 * ContactAddress form base class.
 *
 * @method ContactAddress getObject() Returns the current form's model object
 *
 * @package    skeleton
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseContactAddressForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'name'       => new sfWidgetFormInputText(),
      'is_primary' => new sfWidgetFormInputCheckbox(),
      'city'       => new sfWidgetFormInputText(),
      'state'      => new sfWidgetFormInputText(),
      'zip'        => new sfWidgetFormInputText(),
      'contact_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Contact'), 'add_empty' => false)),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'name'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'is_primary' => new sfValidatorBoolean(array('required' => false)),
      'city'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'state'      => new sfValidatorString(array('max_length' => 25, 'required' => false)),
      'zip'        => new sfValidatorString(array('max_length' => 25, 'required' => false)),
      'contact_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Contact'))),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('contact_address[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ContactAddress';
  }

}
