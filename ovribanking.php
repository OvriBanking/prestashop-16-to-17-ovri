<?php

/**
 * NOTICE OF LICENSE
 *
 * This file is create by OVRI
 * For the installation of the software in your application
 * You accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 *  @author    OVRI
 *  @copyright 2018-2022 OVRI SAS
 *  @license   ovri.com
 */


//this not work on ps 1.6 only on 1.7 need create condition for include this class PaymentOption !
//use PrestaShop\PrestaShop\Core\Payment\PaymentOption;

if (!defined('_PS_VERSION_')) {
  exit;
}

class Ovribanking extends PaymentModule
{
  protected $_html = '';
  protected $_postErrors = array();

  public $details;
  public $owner;
  public $address;
  public $extra_mail_vars;

  public function __construct()
  {
    $this->name = 'ovribanking';
    $this->tab = 'payments_gateways';
    $this->version = '1.2.5';
    $this->author = 'OVRI SAS';
    $this->controllers = array('validation');
    $this->is_eu_compatible = 1;
    $this->ps_versions_compliancy = array('min' => '1.5', 'max' => _PS_VERSION_);
    $this->currencies = true;
    $this->currencies_mode = 'checkbox';
    $this->bootstrap = true;
    $this->module_key = '7daffdb3008980c4393c94e13e02fd981a23s4';
    parent::__construct();
    $this->displayName = $this->l('Ovri Banking');
    $this->description = $this->l('Accept credit card payments in minutes');
    $this->confirmUninstall = $this->l('Do you really want to uninstall Ovri Banking ?');
    if (!count(Currency::checkPaymentCurrencies($this->id))) {
      $this->warning = $this->l('No currency has been set for this module.');
    }
    $this->BaseUriApi = 'https://api.ovri.app/payment';
    $this->BaseWebPaymentStandard = 'https://checkout.ovri.app/pay/standard/token/';
    $this->BaseWebPaymentInstallments = 'https://checkout.ovri.app/pay/installment/token/';
    $this->BaseWebPaymentSubscription = 'https://checkout.ovri.app/pay/subscription/token/';
    $this->ApiInitPayment = $this->BaseUriApi . '/init_transactions/';
    $this->ApiGetPayment = $this->BaseUriApi . '/transactions/';
    $this->ApiGetById = $this->BaseUriApi . '/transactions_by_merchantid/';
    $this->EmbeddedLibrary = 'https://checkout.ovri.app/js/ovriapp.js';
    $this->EmbeddedCss = 'https://checkout.ovri.app/css/app.css';
    $this->ModuleChecker = 'https://api.ovri.app/payment/checkingmodules/prestashop/';
    include_once(_PS_MODULE_DIR_ . '/' . $this->name . '/inc/function_core.php');
  }

  /**
   * Module installation
   */
  public function install()
  {
    if (Shop::isFeatureActive()) {
      Shop::setContext(Shop::CONTEXT_ALL);
    }
    include_once(_PS_MODULE_DIR_ . '/' . $this->name . '/ovribanking_install.php');
    $ovribanking_install = new OvribankingInstall(); //sent version
    $ovribanking_install->createOrderState($this->name);
    $ovribanking_install->createDatabaseTables();
    if (_PS_VERSION_ < '1.7') {
      return parent::install() &&
        $this->registerHook('payment') &&
        $this->registerHook('paymentReturn') &&
        $this->registerHook('displayHeader') &&
        $this->registerHook('displayAdminOrder') &&
        $this->registerHook('displayTop') &&
        $this->registerHook('displayBackOfficeHeader') &&
        $this->registerHook('displayAdminAfterHeader');
    } else {
      return parent::install() &&
        $this->registerHook('paymentOptions') &&
        $this->registerHook('paymentReturn') &&
        $this->registerHook('displayPayment') &&
        $this->registerHook('displayHeader') &&
        $this->registerHook('displayAdminOrder') &&
        $this->registerHook('displayTop') &&
        $this->registerHook('displayBackOfficeHeader') &&
        $this->registerHook('displayAdminAfterHeader');
    }
  }

  /**
   * Uninstalling the module
   */

  public function uninstall()
  {
    include_once(_PS_MODULE_DIR_ . '/' . $this->name . '/ovribanking_install.php');
    $ovribanking_install = new OvribankingInstall();
    $ovribanking_install->deleteConfiguration($this->name);
    //  $ovribanking_install->uninstallOrderStates( $this->name );
    if (_PS_VERSION_ < '1.7') {
      if (
        !$this->unregisterHook('payment') ||
        !$this->unregisterHook('paymentReturn') ||
        !$this->unregisterHook('displayHeader') ||
        !$this->unregisterHook('displayBackOfficeHeader') ||
        !$this->unregisterHook('displayAdminAfterHeader')
      ) {
        Logger::addLog('Ovri module: unregisterHook failed', 4);
        return false;
      }
    } else {
      if (
        !$this->unregisterHook('paymentOptions') ||
        !$this->unregisterHook('paymentReturn') ||
        !$this->unregisterHook('displayPayment') ||
        !$this->unregisterHook('displayHeader') ||
        !$this->unregisterHook('displayAdminOrder') ||
        !$this->unregisterHook('displayBackOfficeHeader') ||
        !$this->unregisterHook('displayAdminAfterHeader')
      ) {
        Logger::addLog('Ovri module: unregisterHook failed', 4);
        return false;
      }
    }
    if (!parent::uninstall()) {
      Logger::addLog('Ovri module: uninstall failed', 4);
      return false;
    }
    return true;
  }

  ###########################################################################################
  ###########################################################################################
  ##Function for prestashop version 1.6
  ###########################################################################################

  /**
   * Display of the payment method Ovri (Prestashop 1.6)
   */
  public function hookDisplayPayment($params, $ListPnfMethod = array(), $SmartyParams = array())
  {
    if (!$this->isAvailable()) {
      return;
    }


    $ovribankingCore = new ovribankingCore();
    $cart = $params["cart"];
    $PnfActive = $ovribankingCore::getleaseactive($cart->getordertotal(true));

    if (isset($PnfActive)) {
      foreach ($PnfActive as $key => $value) {
        $RequestPnf = $ovribankingCore::ovribanking_constructPayment($params, $value);
        if (isset($RequestPnf['success']) && $RequestPnf['success'] == true) {
          $ListPnfMethod['pnf' . $value] = array('links' => $RequestPnf['UriToRedirect'], 'fees' => $RequestPnf['fees'], 'status' => $RequestPnf['success']);
          if ($value == "2") {
            $Canp2f = true;
          }
          if ($value == "3") {
            $Canp3f = true;
          }
          if ($value == "4") {
            $Canp4f = true;
          }
        } else {
          if ($value == "2") {
            $Canp2f = false;
          }
          if ($value == "3") {
            $Canp3f = false;
          }
          if ($value == "4") {
            $Canp4f = false;
          }
        }
      }
    } else {
      $Canp2f = false;
      $Canp3f = false;
      $Canp4f = false;
    }
    $TokenIs = $ovribankingCore::ovribanking_constructPayment($params);


    // generated SmartyAssignator
    $SmartyParams = array();
    $SmartyParams['Library'] = $this->EmbeddedLibrary;
    $SmartyParams['LibraryCss'] = $this->EmbeddedCss;
    if (isset($TokenIs['success']) && $TokenIs['success'] == true) {
      $SmartyParams['Token'] = $TokenIs['Token'];
      $SmartyParams['LinkStandard'] = $TokenIs['UriToRedirect'];
    }

    if ($Canp2f == true) {
      $SmartyParams['Link2F'] = $ListPnfMethod['pnf2']['links'];
      $SmartyParams['2Fav'] = $Canp2f;
      $SmartyParams['2fees'] = $ListPnfMethod['pnf2']['fees'];
    } else {
      $SmartyParams['2Fav'] = $Canp2f;
    }
    if ($Canp3f == true) {
      $SmartyParams['Link3F'] = $ListPnfMethod['pnf3']['links'];
      $SmartyParams['3Fav'] = $Canp3f;
      $SmartyParams['3fees'] = $ListPnfMethod['pnf3']['fees'];
    } else {
      $SmartyParams['3Fav'] = $Canp3f;
    }
    if ($Canp4f == true) {
      $SmartyParams['Link4F'] = $ListPnfMethod['pnf4']['links'];
      $SmartyParams['4Fav'] = $Canp4f;
      $SmartyParams['4fees'] = $ListPnfMethod['pnf4']['fees'];
    } else {
      $SmartyParams['4Fav'] = $Canp4f;
    }
    $SmartyParams['path_img'] = $this->_path;
    $SmartyParams['MessageAnswer'] = Tools::getValue('ips_failed');


    $this->context->smarty->assign($SmartyParams);

    if (Configuration::get('OVRI_INTEGRATED') == on) {
      return $this->display(__FILE__, '/views/templates/front/16/embedded.tpl');
    } else {
      return $this->display(__FILE__, '/views/templates/front/16/standard.tpl');
    }
  }


  ###########################################################################################
  ###########################################################################################
  ##Function for prestashop version 1.7
  ###########################################################################################	


  /**
   * Display of the payment method Ovri (Prestashop 1.7)
   */

  public function hookPaymentOptions($params)
  {

    $ovribankingCore = new ovribankingCore();
    $cart = $params["cart"];
    $payment_options = array();
    if (Configuration::get('OVRI_INTEGRATED') == 'on') {
      $payment_options = [
        $this->getEmbeddedPaymentOption($params)
      ];
    } else {
      $optionPaymentD = $this->optionPaymentD($params);
      if ($optionPaymentD['result']) {
        $payment_options = array($optionPaymentD['params']);
      } else {
        echo '<div class="alert alert-danger"><b>Ovri Standard: </b>' . $optionPaymentD['message'] . '</div>';
      }
    }
    /** Pnf payment   **/
    $PnfActive = $ovribankingCore::getleaseactive($cart->getordertotal(true));

    if (isset($PnfActive)) {
      foreach ($PnfActive as $key => $value) {
        $PnfMethod = $this->optionPaymentPNF($params, $value);
        if ($PnfMethod['result']) {
          array_push($payment_options, $PnfMethod['params']);
        }
      }
    }
    return $payment_options;
  }

  public function getEmbeddedPaymentOption($params, $error = NULL)
  {
    $ovribankingCore = new ovribankingCore();
    $Tokenise = $ovribankingCore::ovribanking_constructPayment($params);
    if ($Tokenise['success'] == false) {
      $error = $Tokenise['error_message'];
    }
    $embeddedOption = new PrestaShop\PrestaShop\Core\Payment\PaymentOption;
    //Sent value for generate OVRI Request SDK
    $this->context->smarty->assign(
      array(
        'LibraryJs' => $this->EmbeddedLibrary,
        'LibraryCss' => $this->EmbeddedCss,
        'Token' => $Tokenise['Token'],
        'MessageAnswer' => Tools::getValue('ips_failed')
      )
    );
    $embeddedOption->setModuleName('ovribankingEmbedded')->setBinary(true)->setAdditionalInformation($this->context->smarty->fetch('module:ovribanking/views/templates/front/17/embedded.tpl'))->setLogo(Media::getMediaPath(_PS_MODULE_DIR_ . $this->name . '/views/img/carte.png'));
    return $embeddedOption;
  }

  public function optionPaymentD($params, $error = NULL)
  {
    $ovribankingCore = new ovribankingCore();
    $Tokenise = $ovribankingCore::ovribanking_constructPayment($params);
    if ($Tokenise['success'] == false) {
      $error = $Tokenise['error_message'];
    }
    $this->context->smarty->assign(
      array(
        'mtglogocard' => Media::getMediaPath(_PS_MODULE_DIR_ . $this->name . '/views/img/carte.png'),
        'MessageAnswer' => Tools::getValue('ips_failed')
      )
    );
    $optionD = new PrestaShop\PrestaShop\Core\Payment\PaymentOption;
    $optionD->setCallToActionText($this->l('Pay by card'))->setAction($Tokenise['UriToRedirect'])->setAdditionalInformation(
      $this->context->smarty->fetch('module:ovribanking/views/templates/front/17/standard_redirect.tpl')
    );
    return array("result" => $Tokenise['success'], "message" => $error, "params" => $optionD);
  }

  public function optionPaymentPNF($params, $lease, $error = NULL, $optionD = NULL)
  {
    $ovribankingCore = new ovribankingCore();
    $Tokenise = $ovribankingCore::ovribanking_constructPayment($params, $lease);
    if ($Tokenise['success'] == false) {
      $error = $Tokenise['error_message'];
    }
    if (isset($Tokenise['fees']) && $Tokenise['fees'] > 0) {
      $fees = ' - (' . $this->l('Fees:') . $Tokenise['fees'] . ')';
    } else {
      $fees = "";
    }
    if ($lease == 2) {
      $tras = $this->l('Pay by card in two times') . $fees;
    } else if ($lease == 3) {
      $tras = $this->l('Pay by card in three times') . $fees;
    } else if ($lease == 4) {
      $tras = $this->l('Pay by card in four times') . $fees;
    }
    $this->context->smarty->assign(
      array(
        'mtglogocard' => Media::getMediaPath(_PS_MODULE_DIR_ . $this->name . '/views/img/carte.png')
      )
    );
    if (isset($Tokenise['UriToRedirect'])) {

      $optionD = new PrestaShop\PrestaShop\Core\Payment\PaymentOption;
      $optionD->setCallToActionText($tras)->setAction($Tokenise['UriToRedirect'])->setAdditionalInformation(
        $this->context->smarty->fetch('module:ovribanking/views/templates/front/17/installment_redirect.tpl')
      );
    }
    return array("result" => $Tokenise['success'], "message" => $error, "params" => $optionD);
  }


  ###########################################################################################
  ###########################################################################################
  ##Universal function for prestashop 1.6 & 1.7
  ###########################################################################################	

  /**
   * Processing the return page after payment
   */
  public function hookPaymentReturn($params)
  {
    if (!$this->isAvailable()) {
      return;
    }


    // Get informations
    $orderId = Tools::getValue('id_order');
    $order = new Order($orderId);

    if ($order->current_state == Configuration::get('OVRI_OS_ACCEPTED')) {
      $paymenttitle = $this->l('Credit card');
    } elseif ($order->current_state == Configuration::get('OVRI_OS_ACCEPTED_P2F')) {
      $paymenttitle = $this->l('Credit card in 2 times');
    } elseif ($order->current_state == Configuration::get('OVRI_OS_ACCEPTED_P3F')) {
      $paymenttitle = $this->l('Credit card in 3 times');
    } elseif ($order->current_state == Configuration::get('OVRI_OS_ACCEPTED_P4F')) {
      $paymenttitle = $this->l('Credit card in 4 times');
    }


    $this->context->smarty->assign(
      array(
        'reference_order' => $order->{'reference'},
        'method' => $paymenttitle,
        'amount' => Tools::displayPrice($order->getOrdersTotalPaid())
      )
    );
    if (_PS_VERSION_ < '1.7') {

      return $this->display(__FILE__, 'views/templates/front/result/authorised.tpl');
    } else {
      return;
    }
  }

  /**
   * Adding custom CSS
   */
  public function hookDisplayHeader()
  {


    $this->context->controller->addCSS($this->_path . 'views/css/ovribanking.css', 'all');

    if ($this->context->controller->php_self == 'order') {
      $this->context->controller->addJS($this->_path . 'views/js/ovribanking_embedded.js');
    }
  }

  /**
   * Display the methods only if the module is correctly configured!
   */
  public function isAvailable() //work
  {


    if (!$this->active) {
      return false;
    }
    if ((Configuration::get('OVRI_GATEWAY_API_KEY') != "") && (Configuration::get('OVRI_GATEWAY_CRYPT_KEY') != "") && (Configuration::get('OVRI_GATEWAY_API_KEY') != "PRESTASHOP") && (Configuration::get('OVRI_GATEWAY_CRYPT_KEY') != "00000000000000000000000000000")) {
      return true;
    }
    Logger::addLog("Ovri : (" . date('Y-m-d H:i:s') . ") Mode not displayed because not active or ApiKey and SecretKey not defined !", 1);

    return false;
  }


  ###########################################################################################
  ###########################################################################################
  ##Universal function for prestashop 1.6 & 1.7 (ADMIN PRESTASHOP)
  ###########################################################################################	

  /**
   * Adding a custom CSS in the admin for the Ovri configuration page
   */
  public function hookDisplayBackOfficeHeader()
  {
    if (Tools::getValue('controller') == 'AdminModules') {
      $this->context->controller->addJquery();
      $this->context->controller->addCSS($this->_path . 'views/css/ovribanking_back.css', 'all');
      $this->context->controller->addJS($this->_path . 'views/js/validateConfiguration.js');
    }
  }

  public function hookDisplayAdminAfterHeader($updated = false)
  {
    /**
     * Check update module availability
     */
    $sql = 'SELECT version FROM ' . _DB_PREFIX_ . 'module WHERE name="ovribanking"';
    $version = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($sql)['version'];
    $ovribankingCore = new ovribankingCore();
    $updater = $ovribankingCore::checkVersion($this->version, $this->ModuleChecker);
    if ($updater['state'] == 'needupdate') {
      echo "<div class='alert alert-danger'>";
      echo "<p>" . $this->l('IMPORTANT: A new version of the OVRI module is available, remember to update to avoid any interruption of service') . "</p>";
      /**
       * Download new module file !
       */
      $f = file_put_contents("" . __DIR__ . "/upgrade-download/" . $updater['file'] . "", fopen($updater['uri'] . $updater['file'], 'r'), LOCK_EX);
      if (!$f) {
        echo "<p><b>" . $this->l('ERROR') . " : </b>" . $this->l('Impossible to download the update for OVRI !') . "</p>";
      } else {
        $zip = new ZipArchive;
        $res = $zip->open("" . __DIR__ . "/upgrade-download/" . $updater['file'] . "");
        if ($res === TRUE) {
          $zip->extractTo("" . __DIR__ . "/../");
          $zip->close();
        } else {
        }
      }
      echo "</div>";
    } else if ($updater['state'] == 'ok' && $updater['version'] != $version) {
      echo "<div class='alert alert-danger'>";
      echo "<p>" . $this->l('IMPORTANT: Please finalize the update of the OVRI module by clicking on "Update" in the Module Manager!') . "</p>";
      echo "</div>";
    }
  }


  /**
   * Display of the payment schedule on the order admin panel
   * Available at next update - not completed
    
  public function hookdisplayAdminOrder( $hook ) {
    if ( array_key_exists( 'id_order', $hook ) ) {
      $order_id = $hook[ 'id_order' ];
    }
    $db = Db::getInstance();
    try {
      $requestSqlTypeTr = 'SELECT `type_tr` FROM `'
        . _DB_PREFIX_
        . 'ovribanking_transactiondata` WHERE `order_id`='
      . ( int )$order_id;
      $resultTypeTr = $db->getRow( $requestSqlTypeTr );
		echo $resultTypeTr;
		
    } catch ( Exception $exception ) {
      Logger::addLog( "Ovri : Error while retrieving the transaction from the database ! For order ID : " . ( int )$order_id . $exception, 3 );
    }

    if ( $resultTypeTr[ 'type_tr' ] == "pnf2" || $resultTypeTr[ 'type_tr' ] == "pnf3" || $resultTypeTr[ 'type_tr' ] == "pnf4" ) {
      $this->hookdisplayAdminOrderP3f( $db, $order_id );
      $file = "orderBlocP3f.tpl";
    } elseif ( $resultTypeTr[ 'type_tr' ] == "standard" ) {
      $this->hookdisplayAdminOrderD( $db, $order_id );
      $file = "orderBlocD.tpl";
    } else {
      return;
    }
    return $this->display( __FILE__, 'views/templates/front/' . $file );
  }
   */
  /**
   * Table generator for installment payment
   * Available at next update - not completed
   
  public function hookdisplayAdminOrderP3f( $db, $order_id ) {
    try {
      $requestSql = 'SELECT `transaction_id`,`IPS_Return_Responses` FROM `'
        . _DB_PREFIX_
        . 'ovribanking_transactiondata` WHERE `order_id`='
      . ( int )$order_id;
      $resultIdTransac = $db->executeS( $requestSql );
    } catch ( Exception $exception ) {
      Logger::addLog( "Ovri : Error while retrieving the transaction from the database ! For order ID : " . ( int )$order_id, 3 );
    }


    $result = $resultIdTransac;
    if ( $result ) {
      $transac = array();
      foreach ( $result as $key => $value ) {
        if ( $key == 0 ) {
          $transactiondetails = json_decode( $value[ 'IPS_Return_Responses' ] );
          $transac[ $key ] = array(
            "transacId" => $transactiondetails->{'Bank'}->{'Internal_IPS_Id'},
            "transacDate" => $transactiondetails->{'Created'},
            "transacMontant" => $transactiondetails->{'Financial'}->{'Total_Paid'} . " EUR (€)",
            "transacOK" => $transactiondetails->{'Transaction_Status'}->{'Description'}
          );
        }
      }
      $datea = new DateTime( $transactiondetails->{'Created'} );
      $intervalera = new DateInterval( "P1M" );
      $datea->add( $intervalera );
      $datea = $datea->format( 'Y-m-d H:i:s' );
      $dateb = new DateTime( $transactiondetails->{'Created'} );
      $intervalerb = new DateInterval( "P2M" );
      $dateb->add( $intervalerb );
      $dateb = $dateb->format( 'Y-m-d H:i:s' );
      $transac[ "1" ] = array(
        "transacId" => $transactiondetails->{'Bank'}->{'Internal_IPS_Id'},
        "transacDate" => $datea,
        "transacMontant" => $transactiondetails->{'Financial'}->{'Total_Paid'} . " EUR (€)",
        "transacOK" => "Upcoming transaction"
      );
      $transac[ "2" ] = array(
        "transacId" => $transactiondetails->{'Bank'}->{'Internal_IPS_Id'},
        "transacDate" => $dateb,
        "transacMontant" => $transactiondetails->{'Financial'}->{'Total_Paid'} . " EUR (€)",
        "transacOK" => "Upcoming transaction"
      );
      $this->context->smarty->assign( 'transac', $transac );
    }
  }
   */
  /**
   * Table generator for simple payment
   * Available at next update - not completed
  public function hookdisplayAdminOrderD( $db, $order_id ) {
    try {
      $requestSql = 'SELECT `transaction_id`,`IPS_Return_Responses` FROM `'
        . _DB_PREFIX_
        . 'ovribanking_transactiondata` WHERE `order_id`='
      . ( int )$order_id;
      $resultIdTransac = $db->getRow( $requestSql );
    } catch ( Exception $exception ) {
      Logger::addLog( "Ovri : Error while retrieving the transaction from the database ! For order ID : " . ( int )$order_id, 3 );
    }
    $result = $resultIdTransac;
    if ( $result ) {
      $transactiondetails = json_decode( $result[ 'IPS_Return_Responses' ] );
      $transac = array(
        "transacID" => $transactiondetails->{'Bank'}->{'Internal_IPS_Id'},
        "transacDate" => $transactiondetails->{'Created'},
        "transacMontant" => $transactiondetails->{'Financial'}->{'Total_Paid'} . " EUR (€)",
        "transacOK" => $transactiondetails->{'Transaction_Status'}->{'Description'},
      );
      $this->context->smarty->assign( 'transac', $transac );
    }
  }
   */
  /**
   * Configuration admin page save update or change
   */

  public function getContent()
  {
    if (!isset($this->_html) || empty($this->_html)) {
      $this->_html = '';
    }
    $msg_confirmation = '';
    $msg_confirmation_class = '';
    $display_msg_confirmation = '0';
    $msg_information = '';
    $msg_information_class = '';
    $display_msg_information = '0';


    if (!empty(Tools::getValue('OVRI_ADMIN_ACTION'))) {
      Configuration::updateValue('OVRI_GATEWAY_API_KEY', Tools::getValue('OVRI_GATEWAY_API_KEY')); //Update ApiKey
      Configuration::updateValue('OVRI_GATEWAY_CRYPT_KEY', Tools::getValue('OVRI_GATEWAY_CRYPT_KEY')); //Update CrypKey or Secret Key
      Configuration::updateValue('OVRI_GATEWAY_P2F', Tools::getValue('OVRI_GATEWAY_P2F')); //Enable or not split payment 3steps
      Configuration::updateValue('OVRI_GATEWAY_P3F', Tools::getValue('OVRI_GATEWAY_P3F')); //Enable or not split payment 3steps
      Configuration::updateValue('OVRI_GATEWAY_P4F', Tools::getValue('OVRI_GATEWAY_P4F')); //Enable or not split payment 3steps
      Configuration::updateValue('OVRI_INTEGRATED', Tools::getValue('OVRI_INTEGRATED')); //Enable or not split payment 3steps
      /* Check Minimal amount for split payment 2xx */

      $error = '<h3>' . $this->l('Change not registered') . '</h3>';
      $nberror = 0;

      if (Tools::getValue('OVRI_TRIGGER_P2F') && ((int)Tools::getValue('OVRI_TRIGGER_P2F') < 50 && Tools::getValue('OVRI_GATEWAY_P2F') == "on")) {
        $nberror = $nberror + 1;
        $error .= "<b>P2F</b> - " . $this->l('The activation of the payment in two times is not possible because you defined a threshold lower than the minimum authorized 50€') . "<br>";
      } else {
        Configuration::updateValue('OVRI_TRIGGER_P2F', Tools::getValue('OVRI_TRIGGER_P2F'));
      }
      if (Tools::getValue('OVRI_TRIGGER_P3F') && ((int)Tools::getValue('OVRI_TRIGGER_P3F') < 50 && Tools::getValue('OVRI_GATEWAY_P3F') == "on")) {
        $nberror = $nberror + 1;
        $error .= "<b>P3F</b> - " . $this->l('The activation of the payment in three times is not possible because you defined a threshold lower than the minimum authorized 50€') . "<br>";
      } else {
        Configuration::updateValue('OVRI_TRIGGER_P3F', Tools::getValue('OVRI_TRIGGER_P3F'));
      }
      if (Tools::getValue('OVRI_TRIGGER_P4F') && ((int)Tools::getValue('OVRI_TRIGGER_P4F') < 50 && Tools::getValue('OVRI_GATEWAY_P4F') == "on")) {
        $nberror = $nberror + 1;
        $error .= "<b>P4F</b> - " . $this->l('The activation of the payment in four times is not possible because you defined a threshold lower than the minimum authorized 50€') . "<br>";
      } else {
        Configuration::updateValue('OVRI_TRIGGER_P4F', Tools::getValue('OVRI_TRIGGER_P4F'));
      }


      if (!is_numeric(Tools::getValue('OVRI_FEE_P2F')) && Tools::getValue('OVRI_FEE_P2F')) {
        $nberror = $nberror + 1;
        $error .= "<b>P2F</b> - $this->l('Non-numeric value for the two times payment processing fee. (e.g. 1.00) for 1%.')<br>";
      } else {
        Configuration::updateValue('OVRI_FEE_P2F', Tools::getValue('OVRI_FEE_P2F'));
      }


      if (!is_numeric(Tools::getValue('OVRI_FEE_P3F')) && Tools::getValue('OVRI_FEE_P3F')) {
        $nberror = $nberror + 1;
        $error .= "<b>P3F</b> - $this->l('Non-numeric value for the three times payment processing fee. (e.g. 1.00) for 1%.')<br>";
      } else {
        Configuration::updateValue('OVRI_FEE_P3F', Tools::getValue('OVRI_FEE_P3F'));
      }
      if (!is_numeric(Tools::getValue('OVRI_FEE_P4F')) && Tools::getValue('OVRI_FEE_P4F')) {
        $nberror = $nberror + 1;
        $error .= "<b>P4F</b> - $this->l('Non-numeric value for the four times payment processing fee. (e.g. 1.00) for 1%.')<br>";
      } else {
        Configuration::updateValue('OVRI_FEE_P4F', Tools::getValue('OVRI_FEE_P4F'));
      }

      if ($nberror === 0) {

        $msg_confirmation_class = 'alert alert-success';
        $msg_confirmation = $this->l('Saved change'); //Update is saved and split payment activated
        $display_msg_confirmation = '1';
      } else {
        $msg_confirmation = $error;
        $msg_confirmation_class = ' alert alert-danger';
        $display_msg_confirmation = '1';
      }
    }

    if (!empty(Tools::getValue('OVRI_ADMIN_ACTION'))) {
      $activeTab_1 = ' active';
      $activeTab_2 = '';
      $activeTabList_1 = 'active';
      $activeTabList_2 = '';
      $apiKeyNumber = Tools::safeOutput(
        Tools::getValue('OVRI_GATEWAY_API_KEY', Configuration::get('OVRI_GATEWAY_API_KEY'))
      );
      $cryptKeyNumber = Tools::safeOutput(
        Tools::getValue('OVRI_GATEWAY_CRYPT_KEY', Configuration::get('OVRI_GATEWAY_CRYPT_KEY'))
      );
    } else {
      $apiKeyNumber = Tools::getValue(
        'OVRI_GATEWAY_API_KEY',
        Configuration::get('OVRI_GATEWAY_API_KEY')
      );
      $cryptKeyNumber = Tools::getValue(
        'OVRI_GATEWAY_CRYPT_KEY',
        Configuration::get('OVRI_GATEWAY_CRYPT_KEY')
      );
    }

    $seuil_p2f = Tools::safeOutput(
      Tools::getValue('OVRI_TRIGGER_P2F', Configuration::get('OVRI_TRIGGER_P2F'))
    );
    $seuil_p3f = Tools::safeOutput(
      Tools::getValue('OVRI_TRIGGER_P3F', Configuration::get('OVRI_TRIGGER_P3F'))
    );
    $seuil_p4f = Tools::safeOutput(
      Tools::getValue('OVRI_TRIGGER_P4F', Configuration::get('OVRI_TRIGGER_P4F'))
    );
    $fee_p2f = Tools::safeOutput(
      Tools::getValue('OVRI_FEE_P2F', Configuration::get('OVRI_FEE_P2F'))
    );
    $fee_p3f = Tools::safeOutput(
      Tools::getValue('OVRI_FEE_P3F', Configuration::get('OVRI_FEE_P3F'))
    );
    $fee_p4f = Tools::safeOutput(
      Tools::getValue('OVRI_FEE_P4F', Configuration::get('OVRI_FEE_P4F'))
    );

    if (($apiKeyNumber == false) || ($apiKeyNumber == "")) {
      $apiKeyNumber = 'PRESTASHOP';
    }
    if (($cryptKeyNumber == false) || ($cryptKeyNumber == "")) {
      $cryptKeyNumber = '00000000000000000000000000000';
    }


    if (Tools::getValue('OVRI_INTEGRATED', Configuration::get('OVRI_INTEGRATED')) == "on") {
      $integrated_on = " checked=\"checked\"";
      $integrated_off = "";
    } else {
      $integrated_on = "";
      $integrated_off = " checked=\"checked\"";
    }
    if (Tools::getValue('OVRI_GATEWAY_P2F', Configuration::get('OVRI_GATEWAY_P2F')) == "on") {
      $p2f_on = " checked=\"checked\"";
      $p2f_off = "";
    } else {
      $p2f_on = "";
      $p2f_off = " checked=\"checked\"";
    }

    if (Tools::getValue('OVRI_GATEWAY_P3F', Configuration::get('OVRI_GATEWAY_P3F')) == "on") {
      $p3f_on = " checked=\"checked\"";
      $p3f_off = "";
    } else {
      $p3f_on = "";
      $p3f_off = " checked=\"checked\"";
    }


    if (Tools::getValue('OVRI_GATEWAY_P4F', Configuration::get('OVRI_GATEWAY_P4F')) == "on") {
      $p4f_on = " checked=\"checked\"";
      $p4f_off = "";
    } else {
      $p4f_on = "";
      $p4f_off = " checked=\"checked\"";
    }


    if (!empty(Tools::getValue('OVRI_ADMIN_ACTION'))) {
      $activeTab_1 = 'active';
      $activeTab_2 = '';
      $activeTabList_1 = 'active';
      $activeTabList_2 = '';
    } else {
      $activeTab_1 = 'active';
      $activeTab_2 = '';
      $activeTabList_1 = 'active';
      $activeTabList_2 = '';
    }

    if ($this->context->language->iso_code == 'fr') {
      $imageName = "ovribanking_header_admin.jpg";
      $imageNameBottom = "ovribanking_header_admin_bottom.jpg";
    } else {
      $imageName = "ovribanking_header_admin_en.jpg";
      $imageNameBottom = "ovribanking_header_admin_bottom_en.jpg";
    }

    $this->context->smarty->assign(
      array(
        'image_header' => "../modules/" . $this->name . "/views/img/" . $imageName,
        'image_header_bottom' => "../modules/" . $this->name . "/views/img/" . $imageNameBottom,
        'activeTabList_1' => $activeTabList_1,
        'activeTabList_2' => $activeTabList_2,
        'activeTab_1' => $activeTab_1,
        'actionForm' => '',
        'label_api_key' => $this->l('Your API Key (MerchantKey)'),
        'value_api_key' => $apiKeyNumber,
        'label_crypt_key' => $this->l('Your encryption key (SecretKey)'),
        'value_crypt_key' => $cryptKeyNumber,
        'p2f_on' => $p2f_on,
        'p2f_off' => $p2f_off,
        'p3f_on' => $p3f_on,
        'p3f_off' => $p3f_off,
        'p4f_on' => $p4f_on,
        'p4f_off' => $p4f_off,
        'integrated_on' => $integrated_on,
        'integrated_off' => $integrated_off,
        'seuil_p2f' => $seuil_p2f,
        'seuil_p3f' => $seuil_p3f,
        'seuil_p4f' => $seuil_p4f,
        'fee_p2f' => $fee_p2f,
        'fee_p3f' => $fee_p3f,
        'fee_p4f' => $fee_p4f,
        'activeTab_2' => $activeTab_2,
        'msg_information' => $msg_information,
        'msg_information_class' => $msg_information_class,
        'display_msg_information' => $display_msg_information,
        'msg_confirmation' => $msg_confirmation,
        'msg_confirmation_class' => $msg_confirmation_class,
        'display_msg_confirmation' => $display_msg_confirmation
      )
    );
    return $this->display(__FILE__, '/views/templates/front/admin.tpl');
  }
}
