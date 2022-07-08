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

if (!defined('_PS_VERSION_')) {
  exit;
}
class ovribankingCore extends Ovribanking
{
  public function __construct()
  {
    parent::__construct();
  }
  /* Management of the activation and deactivation of the payment in several times */
  public function getleaseactive($amount)
  {
    $seuil2 = Configuration::get('OVRI_TRIGGER_P2F');
    $seuil3 = Configuration::get('OVRI_TRIGGER_P3F');
    $seuil4 = Configuration::get('OVRI_TRIGGER_P4F');
    if (!$seuil2 || $seuil2 < 50) {
      $seuil2 = "50";
    }
    if (!$seuil3 || $seuil3 < 50) {
      $seuil3 = "50";
    }
    if (!$seuil4 || $seuil4 < 50) {
      $seuil4 = "50";
    }
    $pnf2 = Configuration::get('OVRI_GATEWAY_P2F');
    $pnf3 = Configuration::get('OVRI_GATEWAY_P3F');
    $pnf4 = Configuration::get('OVRI_GATEWAY_P4F');
    $pnflist = array();
    if ($pnf2 == "on" && $amount >= $seuil2) {
      $pnflist[] = '2';
    }
    if ($pnf3 == "on" && $amount >= $seuil3) {
      $pnflist[] = '3';
    }
    if ($pnf4 == "on" && $amount >= $seuil4) {
      $pnflist[] = '4';
    }
    return $pnflist;
  }

  /* Builder of payment initiation requests sent to Ovri */
  public function ovribanking_constructPayment($params, $Lease = NULL, $feefordisplay = NULL)
  {
    $Mtd = new Ovribanking();
    $cart = $params["cart"];
    $CustomerIs = new Customer($params["cart"]->id_customer);
    $urlIPN = (Configuration::get('PS_SSL_ENABLED') ? 'https' : 'http');
    $urlIPN .= '://' . $_SERVER['HTTP_HOST'] . __PS_BASE_URI__ . 'modules/' . $Mtd->name . '/ipn.php';
    //Need Get Token //for simple payment
    global $cookie;
    $currency = new CurrencyCore($cookie->id_currency);
    $params = array(
      "MerchantKey" => Configuration::get('OVRI_GATEWAY_API_KEY'),
      "RefOrder" => (int)$cart->id,
      'Customer_Name' => $CustomerIs->{'lastname'},
      'Customer_FirstName' => $CustomerIs->{'firstname'},
      'Customer_Email' => $CustomerIs->{'email'},
      'extension' => 'prestashop-1.1.1',
      'urlIPN' => $urlIPN,
      'urlOK' => $Mtd->context->link->getModuleLink('ovribanking', 'return', array('customer' => $CustomerIs->secure_key, 'id_cart' => (int)$cart->id)),
      'urlKO' => $Mtd->context->link->getModuleLink('ovribanking', 'return', array('customer' => $CustomerIs->secure_key, 'id_cart' => (int)$cart->id)),
    );
    /* Set up payments in installments if available and apply fee preferences if defined */
    if ($Lease == 2 || $Lease == 3 || $Lease == 4) {
      $params['Lease'] = $Lease;
      $fee2 = Configuration::get('OVRI_FEE_P2F');
      $fee3 = Configuration::get('OVRI_FEE_P3F');
      $fee4 = Configuration::get('OVRI_FEE_P4F');
      if ($Lease == 2) {
        if (isset($fee2) && $fee2 > 0) {

          $fee2_preset = $cart->getordertotal(true) * $fee2 / 100;

          $feefordisplay = ' ' . number_format($fee2_preset, 2, ',', '.') . ' €';

          $params['amount'] = number_format($cart->getordertotal(true) + $fee2_preset, 2, '.', ' ');
        } else {
          $params['amount'] = $cart->getordertotal(true); /* No fee, original amount applies */
        }
      }

      if ($Lease == 3) {
        if (isset($fee3) && $fee3 > 0) {
          $fee3_preset = $cart->getordertotal(true) * $fee3 / 100;
          $feefordisplay = ' ' . number_format($fee3_preset, 2, ',', '.') . ' €';
          $params['amount'] = number_format($cart->getordertotal(true) + $fee3_preset, 2, '.', ' ');
        } else {
          $params['amount'] = $cart->getordertotal(true); /* No fee, original amount applies */
        }
      }

      if ($Lease == 4) {

        if (isset($fee4) && $fee4 > 0) {
          $fee4_preset = $cart->getordertotal(true) * $fee4 / 100;
          $feefordisplay = ' ' . number_format($fee4_preset, 2, ',', '.') . ' €';
          $params['amount'] = number_format($cart->getordertotal(true) + $fee4_preset, 2, '.', ' ');
        } else {
          $params['amount'] = $cart->getordertotal(true); /* No fee, original amount applies */
        }
      }

      $PaymentLinks = $Mtd->BaseWebPaymentInstallments;
    } else {
      /* Standard payment the normal amount of the order is applied */
      $params['amount'] = $cart->getordertotal(true);
      $PaymentLinks = $Mtd->BaseWebPaymentStandard;
    }
    $params['currency'] = $currency->{'iso_code_num'};

    $TokenIs = ovribankingCore::ovribanking_getToken($params);
    if (isset($TokenIs['Code']) && $TokenIs['Code'] == 200) {
      $PaymentReturn = array();
      $PaymentReturn['success'] = true;
      $PaymentReturn['Links'] = $PaymentLinks;
      $PaymentReturn['Token'] = $TokenIs['SACS'];
      $PaymentReturn['UriToRedirect'] = $PaymentLinks . $TokenIs['SACS'];
      $PaymentReturn['fees'] = $feefordisplay;

      return $PaymentReturn;
    } else {
      $PaymentReturn = array();
      $PaymentReturn['success'] = false;
      $PaymentReturn['error_message'] = $TokenIs['Full_Description'];
      return $PaymentReturn;
    }
  }

  /* @Curl query executor */
  private function ovribanking_getToken($args)
  {

    $Mtd = new Ovribanking();
    $tokencurl = curl_init();
    curl_setopt($tokencurl, CURLOPT_URL, $Mtd->ApiInitPayment);
    curl_setopt($tokencurl, CURLOPT_POST, 1);
    curl_setopt($tokencurl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($tokencurl, CURLOPT_POSTFIELDS, http_build_query(ovribankingCore::ovribanking_signRequest($args)));
    $tokenheaders = array();
    $tokenheaders[] = 'Content-Type: application/x-www-form-urlencoded';
    $tokenheaders[] = 'Content-Type: application/json';
    curl_setopt($tokencurl, CURLOPT_HTTPHEADER, $tokenheaders);
    $resultoken = json_decode(curl_exec($tokencurl), true);
    return ($resultoken);
  }

  /* Ovri Payment Initiation Signature Calculation */
  private function ovribanking_signRequest($params, $beforesign = "")
  {
    $ShaKey = Configuration::get('OVRI_GATEWAY_CRYPT_KEY');
    foreach ($params as $key => $value) {
      $beforesign .= $value . "!";
    }
    $beforesign .= $ShaKey;
    $sign = hash("sha512", base64_encode($beforesign . "|" . $ShaKey));
    $params['SHA'] = $sign;
    return $params;
  }
  /* Constructeur de requête pour l'interrogation de l'api Ovri @transactions */
  public function checkingTransaction($transactionBankID)
  {
    $RequestContruct = array(
      "TransID" => $transactionBankID,
      "ApiKey" => Configuration::get('OVRI_GATEWAY_API_KEY'),
      "SHA" => $this->signRequest(Configuration::get('OVRI_GATEWAY_API_KEY'), Configuration::get('OVRI_GATEWAY_CRYPT_KEY'), $transactionBankID)
    );
    $TransactionSinfo = $this->getTransactionInfo($RequestContruct);

    if (isset($TransactionSinfo->{'ErrorCode'})) {
      Logger::addLog("IPN - Ovri : " . $TransactionSinfo->{'ErrorCode'} . " - " . $TransactionSinfo->{'ErrorDescription'} . " for", 4);
      $answerIs = json_encode(
        array(
          "success" => "false",
          "error" => $TransactionSinfo->{'ErrorCode'},
          "error_description" => $TransactionSinfo->{'ErrorDescription'}
        )
      );
      header("Status: 401 Authorization failed or transaction not found", false, 401);
      exit($answerIs);
    } else {

      $this->confirmOrder($TransactionSinfo);
    }
  }

  /* Executeur @curl Ovri pour @transactions */
  private function getTransactionInfo($request)
  {
    $UriRequest = "" . $this->ApiGetPayment . "?";
    foreach ($request as $key => $value) {
      $UriRequest .= $key . "=" . $value . "&";
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $UriRequest);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $headers = array();
    $headers[] = 'Accept: application/json';
    $headers[] = 'Content-Type: application/json';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);

    if (curl_errno($ch)) {
      Logger::addLog("IPN - Ovri : " . curl_error($ch) . " for", 4);
      $answerIs = json_encode(array(
        "success" => "false",
        "error" => "internal",
        "error_description" => curl_error($ch)
      ));
      header("Status: 500 Internal fatal error with curl request GetTransactionInfo", false, 500);
      exit($answerIs);
    } else {
      return json_decode($result);
    }
    curl_close($ch);
  }


  /* Executeur @curl Ovri pour @transactions */
  static function checkVersion($request, $uri)
  {
    $UriRequest =  $uri . $request;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $UriRequest);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $headers = array();
    $headers[] = 'Accept: application/json';
    $headers[] = 'Content-Type: application/json';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    $updating = json_decode($result, true);
    curl_close($ch);
    if ($updating['state'] != 'ok') {
      return array('state' => 'needupdate', 'file' => $updating['file'], 'uri' => $updating['uri']);
    } else {
      return array('state' => 'ok', 'version' => $updating['version']);
    }
    return false;
  }

  /* Enregistrement et confirmateur de commande */

  private function confirmOrder($transactiondetails)
  {

    // know type of payment
    if ($transactiondetails->{'Type'}->{'type'} === "installment") {
      //payment pnf
      if ($transactiondetails->{'Type'}->{'condition'} === "2") {
        //2n
        $ApprovedState = Configuration::get('OVRI_OS_ACCEPTED_P2F');
        $typetransaction = "pnf2";
      } elseif ($transactiondetails->{'Type'}->{'condition'} === "3") {
        //3n
        $ApprovedState = Configuration::get('OVRI_OS_ACCEPTED_P3F');
        $typetransaction = "pnf3";
      } elseif ($transactiondetails->{'Type'}->{'condition'} === "4") {
        //4n
        $ApprovedState = Configuration::get('OVRI_OS_ACCEPTED_P4F');
        $typetransaction = "pnf4";
      }
    } else {
      $ApprovedState = Configuration::get('OVRI_OS_ACCEPTED');
      $typetransaction = "standard";
    }

    $CartingID = $transactiondetails->{'Merchant_Order_Id'};
    $cart = new Cart($CartingID);
    $order = new Order();
    $orderId = $order->getOrderByCartId($cart->id);
    if ($orderId) {
      //This cart has already been transformed into an order, we check if the payment is processed, if necessary we record it.
      if ($transactiondetails->{'Transaction_Status'}->{'State'} == 2) { //Beginning of the procedure only if the transaction is accepted.
        $order = new Order($orderId);
        if ($order->getCurrentState() == $ApprovedState) {
          //Order already processed and confirmed
          Logger::addLog("IPN - Ovri : Order ID #" . $orderId . " - is already processed and confirmed in your prestashop", 1);
          $answerIs = json_encode(array("success" => "true", "OrderID" => $orderId)); //success cause already processed and confirmed
          exit($answerIs); //Immediate stop of the process, action completed and response received.
        } else {
          //Order confirmation not processed, we start processing.
          $orderHistory = new OrderHistory();
          $orderHistory->id_order = $orderId;
          $orderHistory->changeIdOrderState((int)$ApprovedState, $orderId);
          $orderHistory->addWithemail();
          $orderHistory->save();
          $this->insertDataBase($orderId, $transactiondetails->{'Merchant_Order_Id'}, $typetransaction, $transactiondetails);
          if (_PS_VERSION_ > '1.5' && _PS_VERSION_ < '1.5.2') {
            $order->current_state = $orderHistory->id_order_state;
            $order->update();
          }
          Logger::addLog("IPN - Ovri : Order # " . $orderId . " to was processed successfully!", 1);
          $answerIs = json_encode(array("success" => "true", "OrderID" => $orderId)); //success cause already processed and confirmed
          exit($answerIs);
        }
      } else {
        Logger::addLog("IPN - Ovri : Order # " . $order->id . " at this moment the payment seems not to be accepted yet !", 2);
        exit();
      }
    } else {
      if ($cart->id) {
        if ($transactiondetails->{'Transaction_Status'}->{'State'} == 2) {
          $customer = new Customer((int)$cart->id_customer);
          $message = "Process processing for the transaction " . $transactiondetails->{'Merchant_Order_Id'};
          $this->validateOrder(
            $cart->id,
            $ApprovedState,
            (float)$cart->getOrderTotal(true, Cart::BOTH),
            $this->displayName,
            $message,
            array(),
            (int)$cart->id_currency,
            false,
            $customer->secure_key
          );
          $order = new Order($this->currentOrder);
          $this->insertDataBase($order->id, $transactiondetails->{'Merchant_Order_Id'}, $typetransaction, $transactiondetails);
          $answerIs = json_encode(array("success" => "true", "OrderID" => $order->id));
          Logger::addLog("IPN - Ovri : Order # " . $order->id . " to was processed successfully!", 1);
          exit($answerIs);
        } else {
          Logger::addLog("IPN - Ovri : Cart # " . $cart->id . " at this moment the payment seems not to be accepted yet !", 2);
          exit();
        }
      } else {
        Logger::addLog("IPN - Ovri : Payment validation error , CART $CartingID not exist!", 4);
        header('HTTP/1.0 403 Forbidden');
        exit();
      }
    }
  }

  /* Signature de requête @get transactions */

  private function signRequest($key, $secret, $txid)
  {
    $BeforeSign = $key . "!" . $txid . "!" . $secret;
    return hash("sha512", base64_encode($BeforeSign . "|" . $secret));
  }

  /**
   * Changer Id Order State
   */
  public function changeIdOrderState($transactionId, $stateId)
  {
    if ($transactionId == "") {
      return false;
    }
    $orderHistory = new OrderHistory();
    $orderHistory->id_order = $transactionId;
    $orderHistory->changeIdOrderState($stateId, $transactionId);
    $orderHistory->addWithemail();
    $orderHistory->save();
    return true;
  }

  /**
   * Insert transaction in database
   */


  public function insertDataBase($orderId, $transactionId, $type_tr, $ipsanswer = null)
  {
    //Adding transaction information to a separate table
    if (!empty($orderId) && !empty($transactionId) && ($type_tr == "standard" || $type_tr == "pnf2" || $type_tr == "pnf3" || $type_tr == "pnf4")) {
      $now = date("Y-m-d H:i:s");
      $db = Db::getInstance();
      $requestSql = 'INSERT INTO `'
        . _DB_PREFIX_
        . 'ovribanking_transactiondata`
                (`order_id`, `transaction_id`, `datetime`, `type_tr`, `IPS_Return_Responses`) VALUES("' .
        (int)$orderId . '", "' .
        pSQL($transactionId) . '", "' .
        $now . '", "' .
        pSQL($type_tr) . '", "' .
        pSQL(json_encode($ipsanswer)) . '")';
      try {
        $db->execute($requestSql);
      } catch (Exception $exception) {
        Logger::addLog("IPN - Ovri : Failure while adding the transaction to the database !  OrderID : " . $orderId, 3);
      }
    } else {
      return false;
    }
  }
}
