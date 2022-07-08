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

class OvribankingInstall
{

  /**
   * Delete Ovri configuration
   */
  public function deleteConfiguration($modulename)
  {
    $query = new DbQuery();
    $query->select('name');
    $query->from('configuration');
    $query->where('name LIKE \'' . pSQL(Tools::strtoupper($modulename)) . '_%\'');
    $results = Db::getInstance()->executeS($query);
    if (empty($results)) {
      return true;
    }
    $configurationKeys = array_column($results, 'name');
    $result = true;
    foreach ($configurationKeys as $configurationKey) {
      $result &= Configuration::deleteByName($configurationKey);
    }
    return $result;
  }

  /** delete all order state **/
  public function checkOrderStates($modulename, $terms = NULL, $iso = NULL)
  {
    /* @var $orderState OrderState */
    $collection = new PrestaShopCollection('OrderState');
    $collection->where('module_name', '=', $modulename);
    $orderStates = $collection->getResults();
    if ($orderStates == false) {
      return false;
    }
    foreach ($orderStates as $orderState) {
      if ($orderState->name[$iso] == $terms) {
        $idStates = $orderState->id;
        return $idStates;
      }
    }
  }

  /**
   * Create data table for store transaction logs
   */
  public function createDatabaseTables()
  {
    try {
      $db = Db::getInstance();
      $db->execute(
        'CREATE TABLE IF NOT EXISTS `'
          . _DB_PREFIX_
          . 'ovribanking_transactiondata`
                (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `order_id` varchar(255),
                `transaction_id` varchar(255),
                `datetime` datetime,
                `type_tr` varchar(255),
				`IPS_Return_Responses` text DEFAULT NULL,
                PRIMARY KEY (`id`)
                );'
      );
      return true;
    } catch (Exception $exception) {
      return false;
    }
  }

  /**
   * Create a new order state
   */

  public function createOrderState($modulename)
  {
    $this->acceptedD($modulename);
    $this->acceptedP2f($modulename);
    $this->acceptedP3f($modulename);
    $this->acceptedP4f($modulename);
  }

  public function acceptedD($modulename)
  {
    // Create status only if it does not exist
    // If it already exists then it is assigned to the Ovri configuration
    foreach (Language::getLanguages() as $language) {
      if (Tools::strtolower($language['iso_code']) == 'fr') {
        $nameToCheck = 'Paiement CB Accepté';
        $iso = $language['id_lang'];
      } else {
        $nameToCheck = 'Card payment approved';
        $iso = $language['id_lang'];
      }
    }
    $checkingStatus = $this->checkOrderStates($modulename, $nameToCheck, $iso);

    if (!Configuration::get('OVRI_OS_ACCEPTED')) {
      if ($checkingStatus == false) {
        $orderState = new OrderState();
        $orderState->name = array();
        foreach (Language::getLanguages() as $language) {
          if (Tools::strtolower($language['iso_code']) == 'fr') {
            $orderState->name[$language['id_lang']] = 'Paiement CB Accepté';
          } else {
            $orderState->name[$language['id_lang']] = 'Card payment approved';
          }
        }
        $orderState->send_email = true;
        $orderState->color = '#96CA2D';
        $orderState->unremovable = true;
        $orderState->hidden = false;
        $orderState->delivery = false;
        $orderState->logable = true;
        $orderState->invoice = true;
        $orderState->paid = true;
        $orderState->module_name = $modulename;
        $orderState->template[$language['id_lang']] = 'payment';
        if ($orderState->add()) {
          $source = dirname(__FILE__) . '/views/img/stateapproved.gif';
          $destination = dirname(__FILE__) . '/../../img/os/' . (int)$orderState->id . '.gif';
          copy($source, $destination);
        }
        Configuration::updateValue('OVRI_OS_ACCEPTED', (int)$orderState->id);
      } else {
        Configuration::updateValue('OVRI_OS_ACCEPTED', (int)$checkingStatus);
      }
    }
  }
  public function acceptedP2f($modulename)
  {
    // Create status only if it does not exist
    // If it already exists then it is assigned to the Ovri configuration
    foreach (Language::getLanguages() as $language) {
      if (Tools::strtolower($language['iso_code']) == 'fr') {
        $nameToCheck = 'Paiement CB 2X accepté';
        $iso = $language['id_lang'];
      } else {
        $nameToCheck = 'Card payment(2X) approved';
        $iso = $language['id_lang'];
      }
    }
    $checkingStatus = $this->checkOrderStates($modulename, $nameToCheck, $iso);

    if (!Configuration::get('OVRI_OS_ACCEPTED_P2F')) {
      if ($checkingStatus == false) {
        $orderState = new OrderState();
        $orderState->name = array();
        foreach (Language::getLanguages() as $language) {
          if (Tools::strtolower($language['iso_code']) == 'fr') {
            $orderState->name[$language['id_lang']] = 'Paiement CB 2X accepté';
          } else {
            $orderState->name[$language['id_lang']] = 'Card payment(2X) approved';
          }
        }
        $orderState->send_email = true;
        $orderState->color = '#96CA2D';
        $orderState->unremovable = true;
        $orderState->hidden = false;
        $orderState->delivery = false;
        $orderState->logable = true;
        $orderState->invoice = true;
        $orderState->paid = true;
        $orderState->module_name = $modulename;
        $orderState->template[$language['id_lang']] = 'payment';
        if ($orderState->add()) {
          $source = dirname(__FILE__) . '/views/img/stateapproved.gif';
          $destination = dirname(__FILE__) . '/../../img/os/' . (int)$orderState->id . '.gif';
          copy($source, $destination);
        }
        Configuration::updateValue('OVRI_OS_ACCEPTED_P2F', (int)$orderState->id);
      } else {
        Configuration::updateValue('OVRI_OS_ACCEPTED_P2F', (int)$checkingStatus);
      }
    }
  }
  public function acceptedP3f($modulename)
  {
    // Create status only if it does not exist
    // If it already exists then it is assigned to the Ovri configuration
    foreach (Language::getLanguages() as $language) {
      if (Tools::strtolower($language['iso_code']) == 'fr') {
        $nameToCheck = 'Paiement CB 3X accepté';
        $iso = $language['id_lang'];
      } else {
        $nameToCheck = 'Card payment(3X) approved';
        $iso = $language['id_lang'];
      }
    }
    $checkingStatus = $this->checkOrderStates($modulename, $nameToCheck, $iso);

    if (!Configuration::get('OVRI_OS_ACCEPTED_P3F')) {
      if ($checkingStatus == false) {
        $orderState = new OrderState();
        $orderState->name = array();
        foreach (Language::getLanguages() as $language) {
          if (Tools::strtolower($language['iso_code']) == 'fr') {
            $orderState->name[$language['id_lang']] = 'Paiement CB 3X accepté';
          } else {
            $orderState->name[$language['id_lang']] = 'Card payment(3X) approved';
          }
        }
        $orderState->send_email = true;
        $orderState->color = '#96CA2D';
        $orderState->unremovable = true;
        $orderState->hidden = false;
        $orderState->delivery = false;
        $orderState->logable = true;
        $orderState->invoice = true;
        $orderState->paid = true;
        $orderState->module_name = $modulename;
        $orderState->template[$language['id_lang']] = 'payment';
        if ($orderState->add()) {
          $source = dirname(__FILE__) . '/views/img/stateapproved.gif';
          $destination = dirname(__FILE__) . '/../../img/os/' . (int)$orderState->id . '.gif';
          copy($source, $destination);
        }
        Configuration::updateValue('OVRI_OS_ACCEPTED_P3F', (int)$orderState->id);
      } else {
        Configuration::updateValue('OVRI_OS_ACCEPTED_P3F', (int)$checkingStatus);
      }
    }
  }
  public function acceptedP4f($modulename)
  {
    // Create status only if it does not exist
    // If it already exists then it is assigned to the Ovri configuration
    foreach (Language::getLanguages() as $language) {
      if (Tools::strtolower($language['iso_code']) == 'fr') {
        $nameToCheck = 'Paiement CB 4X accepté';
        $iso = $language['id_lang'];
      } else {
        $nameToCheck = 'Card payment(4X) approved';
        $iso = $language['id_lang'];
      }
    }
    $checkingStatus = $this->checkOrderStates($modulename, $nameToCheck, $iso);

    if (!Configuration::get('OVRI_OS_ACCEPTED_P4F')) {
      if ($checkingStatus == false) {
        $orderState = new OrderState();
        $orderState->name = array();
        foreach (Language::getLanguages() as $language) {
          if (Tools::strtolower($language['iso_code']) == 'fr') {
            $orderState->name[$language['id_lang']] = 'Paiement CB 4X accepté';
          } else {
            $orderState->name[$language['id_lang']] = 'Card payment(4X) approved';
          }
        }
        $orderState->send_email = true;
        $orderState->color = '#96CA2D';
        $orderState->unremovable = true;
        $orderState->hidden = false;
        $orderState->delivery = false;
        $orderState->logable = true;
        $orderState->invoice = true;
        $orderState->paid = true;
        $orderState->module_name = $modulename;
        $orderState->template[$language['id_lang']] = 'payment';
        if ($orderState->add()) {
          $source = dirname(__FILE__) . '/views/img/stateapproved.gif';
          $destination = dirname(__FILE__) . '/../../img/os/' . (int)$orderState->id . '.gif';
          copy($source, $destination);
        }
        Configuration::updateValue('OVRI_OS_ACCEPTED_P4F', (int)$orderState->id);
      } else {
        Configuration::updateValue('OVRI_OS_ACCEPTED_P4F', (int)$checkingStatus);
      }
    }
  }
}
