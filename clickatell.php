<?php

require_once 'clickatell.civix.php';

/**
 * Implementation of hook_civicrm_config
 */
function clickatell_civicrm_config(&$config) {
  _clickatell_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_install
 */
function clickatell_civicrm_install() {
  $groupID = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionGroup','sms_provider_name','id','name');
  $params  =
    array('option_group_id' => $groupID,
          'label' => 'Clickatell',
          'value' => 'org.civicrm.sms.clickatell',
          'name'  => 'clickatell',
          'is_default' => 1,
          'is_active'  => 1,
          'version'    => 3,);
  require_once 'api/api.php';
  civicrm_api( 'option_value','create', $params );

  return _clickatell_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 */
function clickatell_civicrm_uninstall() {
  $optionID = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionValue','clickatell','id','name');
  if ($optionID)
    CRM_Core_BAO_OptionValue::del($optionID);

  $filter    =  array('name'  => 'org.civicrm.sms.clickatell');
  $Providers =  CRM_SMS_BAO_Provider::getProviders(False, $filter, False);
  if ($Providers){
    foreach($Providers as $key => $value){
      CRM_SMS_BAO_Provider::del($value['id']);
    }
  }
  return TRUE;
}

/**
 * Implementation of hook_civicrm_enable
 */
function clickatell_civicrm_enable() {
  $optionID = CRM_Core_DAO::getFieldValue('CRM_Core_DAO_OptionValue','clickatell' ,'id','name');
  if ($optionID)
    CRM_Core_BAO_OptionValue::setIsActive($optionID, TRUE);

  $filter    =  array('name' => 'org.civicrm.sms.clickatell');
  $Providers =  CRM_SMS_BAO_Provider::getProviders(False, $filter, False);
  if ($Providers){
    foreach($Providers as $key => $value){
      CRM_SMS_BAO_Provider::setIsActive($value['id'], TRUE);
    }
  }
  return _clickatell_civix_civicrm_enable();
}
