<?php

/**
 * Model generator field.
 *
 * @package    sfHadoriThemePlugin
 * @subpackage generator
 * @author     Brent Shaffer <bshafs@gmail.com>
 */
class sfHadoriField
{
  protected
    $name    = null,
    $type    = null,
    $options = null;

  /**
   * Constructor.
   *
   * @param string $name   The field name
   * @param array  $options The configuration for this field
   */
  public function __construct($name, $options)
  {
    $this->options = $options;
    
    switch ($name[0]) {
      case '_':
        $this->setPartial(true);
        $name = substr($name, 1);
        break;

      case '~':
        $this->setComponent(true);
        $name = substr($name, 1);
        break;

      case '=':
        $this->setLink(true);
        $name = substr($name, 1);
        break;
    }
    
    $this->name = $name;
  }

  /**
   * Returns the name of the field.
   *
   * @return string The field name
   */
  public function getName()
  {
    return $this->name;
  }

  /**
   * Returns the option value for a given key.
   *
   * @param string  $key     A key string
   * @param mixed   $default The default value if the key does not exist
   * @param Boolean $escaped Whether to escape single quote (false by default)
   *
   * @return mixed The option value associated with the key
   */
  public function getOption($key, $default = null, $escaped = false)
  {
    $value = isset($this->options[$key]) ? $this->options[$key] : $default;

    return $escaped ? str_replace("'", "\\'", $value) : $value;
  }
  
  public function getOptions()
  {
    return $this->options;
  }

  /**
   * Returns the type of the field.
   *
   * @return string The field type
   */
  public function getType()
  {
    return $this->getOption('type', 'Text');
  }

  /**
   * Returns true if the column maps a database column.
   *
   * @return boolean true if the column maps a database column, false otherwise
   */
  public function isReal()
  {
    return $this->getOption('is_real', false);
  }

  /**
   * Returns true if the column is a partial.
   *
   * @return boolean true if the column is a partial, false otherwise
   */
  public function isPartial()
  {
    return $this->getOption('is_partial', false);
  }

  /**
   * Sets or unsets the partial flag.
   *
   * @param Boolean $boolean true if the field is a partial, false otherwise
   */
  public function setPartial($boolean)
  {
    $this->options['is_partial'] = $boolean;
  }

  /**
   * Returns true if the column is a component.
   *
   * @return boolean true if the column is a component, false otherwise
   */
  public function isComponent()
  {
    return $this->getOption('is_component', false);
  }

  /**
   * Sets or unsets the component flag.
   *
   * @param Boolean $boolean true if the field is a component, false otherwise
   */
  public function setComponent($boolean)
  {
    $this->options['is_component'] = $boolean;
  }

  /**
   * Returns true if the column has a link.
   *
   * @return boolean true if the column has a link, false otherwise
   */
  public function isLink()
  {
    return $this->getOption('is_link', false);
  }

  /**
   * Sets or unsets the link flag.
   *
   * @param Boolean $boolean true if the field is a link, false otherwise
   */
  public function setLink($boolean)
  {
    $this->options['is_link'] = $boolean;
  }
}
