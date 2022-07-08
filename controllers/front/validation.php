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


class OvribankingValidationModuleFrontController extends ModuleFrontController
{
  public function postProcess()
  {
    $cart = $this->context->cart;
    if (
      $cart->id_customer == 0 ||
      $cart->id_address_delivery == 0 ||
      $cart->id_address_invoice == 0 ||
      !$this->module->active
    ) {
      Tools::redirect('index.php?controller=order&step=1');
    }

    // Check that this payment option is still available in case the customer
    // changed his address just before the end of the checkout process
    $authorized = false;
    foreach (Module::getPaymentModules() as $module) {
      if ($module['name'] == 'paymentexample') {
        $authorized = true;
        break;
      }
    }

    if (!$authorized) {
      die($this->module->l('This payment method is not available.', 'validation'));
    }

    $this->context->smarty->assign(array(
      'params' => $_REQUEST,
    ));

    $this->setTemplate('payment_return.tpl');
  }
}
