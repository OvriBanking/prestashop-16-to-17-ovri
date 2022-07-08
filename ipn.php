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

require_once(dirname(__FILE__) . '/../../config/config.inc.php');
include_once(dirname(__FILE__) . '/../../init.php');
require_once(dirname(__FILE__) . '/ovribanking.php');
require_once(dirname(__FILE__) . '/inc/function_core.php');

$ovri = new ovribankingCore();
$trxgtwId =  Tools::getValue("TransId");
if ($trxgtwId) {
    $ovri->checkingTransaction($trxgtwId);
} else {
    header('HTTP/1.0 403 Forbidden');
    exit();
}
