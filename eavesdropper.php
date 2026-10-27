<?php

require_once 'eavesdropper.civix.php';
use CRM_Eavesdropper_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function eavesdropper_civicrm_config(&$config) {
  _eavesdropper_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function eavesdropper_civicrm_install() {
  _eavesdropper_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function eavesdropper_civicrm_enable() {
  _eavesdropper_civix_civicrm_enable();
}
