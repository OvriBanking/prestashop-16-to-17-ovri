<?php
if ( !defined( '_PS_VERSION_' ) )
  exit;

function upgrade_module_1_0_0( $module ) {
  // Search for merchant values before reset (if old module installed)
  $integrated = Configuration::get( 'MONEYTIGO_INTEGRATED' );
  $trigerpnf3 = Configuration::get( 'MONEYTIGO_TRIGGER_P3F' );
  $pnf3active = Configuration::get( 'MONEYTIGO_GATEWAY_P3F' );
  $apikey = Configuration::get( 'MONEYTIGO_GATEWAY_API_KEY' );
  $secretkey = Configuration::get( 'MONEYTIGO_GATEWAY_CRYPT_KEY' );
  if(!$secretkey && !$apikey)
  {
    Logger::addLog( "[Update] - Stop, Moneytigo is not yet installed", 1 );
    return;
  }
  Logger::addLog( "[START] - Update moneytigo module to Ovri version 1.0.0", 1 );
  include_once( _PS_MODULE_DIR_ . '/ovribanking/ovribanking_install.php' );
  $ovribanking_install = new OvribankingInstall();
  $ovribanking_install->deleteConfiguration( 'moneytigo' );
  Logger::addLog( "[START] - Delete MoneyTigo Configuration", 1 );
  $ovribanking_install->createOrderState( 'moneytigo' );
  Logger::addLog( "[START] - Create new order state for OVRI Module", 1 );


  // Re-insertion of old merchant settings
  Configuration::updateValue( 'OVRI_INTEGRATED', $integrated );
  Configuration::updateValue( 'OVRI_TRIGGER_P3F', $trigerpnf3 );
  Configuration::updateValue( 'OVRI_GATEWAY_P3F', $pnf3active );
  Configuration::updateValue( 'OVRI_GATEWAY_API_KEY', $apikey );
  Configuration::updateValue( 'OVRI_GATEWAY_CRYPT_KEY', $secretkey );
  Logger::addLog( "[START] - Replace old parameter MoneyTigo in OVRI", 1 );
  Logger::addLog( "Moneytigo Updating module" . uniqid(), 3 );
  Logger::addLog( "[SUCCESS] - Update moneytigo module to version 1.1.1", 1 );
  Logger::addLog( "[SUCCESS] - Clear Smarty CACHE", 1 );
  Tools::clearSmartyCache();
  Logger::addLog( "[SUCCESS] - Clear Xml CACHE", 1 );
  Tools::clearXMLCache();
  Logger::addLog( "[SUCCESS] - Regenerate INDEX", 1 );
  Tools::generateIndex();

  return true;
}
