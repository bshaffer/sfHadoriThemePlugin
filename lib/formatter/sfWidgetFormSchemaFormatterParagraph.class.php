<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * 
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWidgetFormSchemaFormatterTable.class.php 5995 2007-11-13 15:50:03Z fabien $
 */
class sfWidgetFormSchemaFormatterParagraph extends sfWidgetFormSchemaFormatter
{
  protected
    $rowFormat       = "<p>\n  %label%\n  <br>\n  %error%%field%%help%%hidden_fields%</p>",
    $errorRowFormat  = "<div class=\"error\">\n  %errors%</div>\n",
    $helpFormat      = '<br />%help%',
    $decoratorFormat = "<div class=\"form-group\">\n  %content%</div>";
}
