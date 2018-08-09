<?php

use CRM_Eavesdropper_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */
class CRM_Eavesdropper_Form_Settings extends CRM_Core_Form {

  public function buildQuickForm() {
    $defaults = CRM_Core_BAO_Setting::getItem('eavesdropper', 'eavesdropper-settings');
    if ($defaults != NULL) {
      $values = json_decode(utf8_decode($defaults), TRUE);
    }
    else {
      $values['eavesdropper_redis_host'] = "redis";
      $values['eavesdropper_redis_port'] = '6379';
    }

    // add form elements
    $this->add(
      'text',
      'eavesdropper_redis_host',
      'Redis host',
      ['value' => $values['eavesdropper_redis_host']],
      TRUE
    );
    $this->add(
      'text',
      'eavesdropper_redis_port',
      'Redis port',
      ['value' => $values['eavesdropper_redis_port']],
      TRUE
    );

    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => E::ts('Submit'),
        'isDefault' => TRUE,
      ),
    ));

    $this->assign('elementNames', $this->getRenderableElementNames());
    parent::buildQuickForm();
  }

  /**
   * Save the settings form.
   */
  public function postProcess() {
    // Get the submitted values as an array
    $values = $this->controller->exportValues($this->_name);
    $credentials['eavesdropper_redis_host'] = $values['eavesdropper_redis_host'];
    $credentials['eavesdropper_redis_port'] = $values['eavesdropper_redis_port'];
    $encode = json_encode($credentials);
    CRM_Core_BAO_Setting::setItem($encode, 'eavesdropper', 'eavesdropper-settings');
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  public function getRenderableElementNames() {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = array();
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }

}
