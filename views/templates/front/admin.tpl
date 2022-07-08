{*
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
*}
<div class="alert alert-info" style="margin-top: 10px; height: 60px;"> <img src="../modules/ovribanking/logofull.png"
    style="float:left; margin-right:15px; width: 100px; height: 32px;">
  <p><strong>{l s='This module allows you to accept credit card payments with Ovri' mod='ovribanking'}</strong></p>
</div>
<div id="config_ovribankingdirect" style="max-width:900px; margin-left:auto; margin-right:auto;">
  {if $display_msg_information == '1'}
    <div class="panel">
      <div class="alert_ovribanking_admin{$msg_information_class|escape:'htmlall':'UTF-8'}">
        {$msg_information|escape:'htmlall':'UTF-8'} </div>
    </div>
  {/if}
  {if $display_msg_confirmation == '1'}
    <div class="panel">
      <div class="{$msg_confirmation_class|escape:'htmlall':'UTF-8'}"> {$msg_confirmation} </div>
    </div>
  {/if}
  <div role="tabpanel" style="margin-top: 20px;">
    <ul role="tablist" class="nav nav-tabs">
      <li class="{$activeTabList_1|escape:'htmlall':'UTF-8'}" role="presentation"> <a data-toggle="tab" role="tab"
          id="D" aria-controls="config_ovribanking_div" href="#config_ovribanking_div"
          style="color: darkblue; font-weight:bold;"> {l s='Settings' mod='ovribanking'} </a> </li>
      <li class="{$activeTabList_2|escape:'htmlall':'UTF-8'}" role="presentation"> <a data-toggle="tab" role="tab"
          id="faq_ovribanking" aria-controls="faq_ovribanking_div" href="#faq_ovribanking_div"
          style="color: darkblue; font-weight:bold;"> {l s='Help (Faq)' mod='ovribanking'} </a> </li>
    </ul>
    <div class="tab-content">
      <div id="config_ovribanking_div" class="tab-pane {$activeTab_1|escape:'htmlall':'UTF-8'}" role="tabpanel">
        <div class="panel" style="min-height:470px;">
          <form method="post" action="{$actionForm|escape:'htmlall':'UTF-8'}" class="account-creation"
            id="formOvribanking">
            <h2>{l s='Technical settings' mod='ovribanking'}</h2>
            <div class="alert alert-warning">
              {l s='First of all, in order for the Ovri payment module to appear on your shopping cart, you need to enter your API Key (MerchantKey) and your secret encryption key (SecretKey)' mod='ovribanking'}
            </div>
            <input type="hidden" id="ips_action" name="OVRI_ADMIN_ACTION" value="UPDATE">
            <label for="api_key" style="margin-top:5px;"> {$label_api_key|escape:'htmlall':'UTF-8'} </label>
            <input type="text" id="api_key" name="OVRI_GATEWAY_API_KEY"
              value="{$value_api_key|escape:'htmlall':'UTF-8'}">
            <label for="crypt_key" style="margin-top:5px;"> {$label_crypt_key|escape:'htmlall':'UTF-8'} </label>
            <input type="text" id="crypt_key" name="OVRI_GATEWAY_CRYPT_KEY"
              value="{$value_crypt_key|escape:'htmlall':'UTF-8'}">
            <hr>
            <p> <a href="https://my.ovri.app" title="{l s='Open Ovri Account for free' mod='ovribanking'}"
                target="_blank">{l s='Open an account immediately' mod='ovribanking'}</a></p>
            <hr>
            <h2>{l s='Activate the embedded mode' mod='ovribanking'}</h2>
            <span class="badge badge-pill badge-info" style="font-size: 10px;">
              {l s='Only for standard payment' mod='ovribanking'}</span>
            <p>
              {l s='The embedded mode allows you not to redirect the customer to the page of Ovri but to display directly on your website the payment form' mod='ovribanking'}
            </p>
            <span class="switch prestashop-switch">
              <input type="radio" name="OVRI_INTEGRATED" id="activer_integrated" value="on"
                {$integrated_on|escape:'htmlall':'UTF-8'} />
              <label for="activer_integrated">{l s='Enable' mod='ovribanking'}</label>
              <input type="radio" name="OVRI_INTEGRATED" id="desactiver_integrated" value="off"
                {$integrated_off|escape:'htmlall':'UTF-8'} />
              <label for="desactiver_integrated">{l s='Disable' mod='ovribanking'}</label>
              <a class="slide-button btn"></a> </span>
            <hr>
            <h2>{l s='Setting of the payment in 2 times' mod='ovribanking'}</h2>
            <p>
              {l s='The payment in 2 times allows you to propose to your customer a facility of payment in order to settle his order in two monthly payments' mod='ovribanking'}
              **</p>
            <label for="p2f">{l s='Activate the payment in 2 times' mod='ovribanking'}</label>
            <span class="switch prestashop-switch">
              <input type="radio" name="OVRI_GATEWAY_P2F" id="activer_p2f" value="on"
                {$p2f_on|escape:'htmlall':'UTF-8'} />
              <label for="activer_p2f">{l s='Enable' mod='ovribanking'}</label>
              <input type="radio" name="OVRI_GATEWAY_P2F" id="desactiver_p2f" value="off"
                {$p2f_off|escape:'htmlall':'UTF-8'} />
              <label for="desactiver_p2f">{l s='Disable' mod='ovribanking'}</label>
              <a class="slide-button btn"></a> </span>
            <div id="settings_p2f" {if $p2f_off}style="display: none" {/if}>
              <label for="seuil_p2f"
                style="margin-top:5px;">{l s='Minimum triggering threshold (Min 50 €)' mod='ovribanking'} </label>
              <input type="text" id="seuil_p2f" name="OVRI_TRIGGER_P2F" value="{$seuil_p2f|escape:'htmlall':'UTF-8'}"
                placeholder="50" ;>
              <div>
                {l s='If you set a threshold in this case this payment method will be displayed only when the customer cart total is at least equal to this threshold' mod='ovribanking'}<span
                  class="badge badge-pill badge-warning" style="font-size: 10px;">
                  {l s='If not defined, 50€ will be the default threshold' mod='ovribanking'}</span></div>
              <label for="fee_p2f"
                style="margin-top:5px;">{l s='Fees to be applied to this payment method' mod='ovribanking'} </label>
              <input type="text" id="fee_p2f" name="OVRI_FEE_P2F" value="{$fee_p2f|escape:'htmlall':'UTF-8'}"
                placeholder="0" ;>
              <div class="alert alert-danger" style='margin-top: 5px;'>
                {l s='0 indicates no fees, however you can indicate fees that correspond to a percentage of the total amount of the cart, if you indicate 1 it will indicate 1%, be careful not to use a comma but only a point as a decimal separator.' mod='ovribanking'}
              </div>
              <hr>
            </div>
            <h2>{l s='Setting of the payment in 3 times' mod='ovribanking'}</h2>
            <p>
              {l s='The payment in 3 times allows you to propose to your customer a facility of payment in order to settle his order in three monthly payments' mod='ovribanking'}
              **</p>
            <label for="p3f">{l s='Activate the payment in 3 times' mod='ovribanking'}</label>
            <span class="switch prestashop-switch">
              <input type="radio" name="OVRI_GATEWAY_P3F" id="activer_p3f" value="on"
                {$p3f_on|escape:'htmlall':'UTF-8'} />
              <label for="activer_p3f">{l s='Enable' mod='ovribanking'}</label>
              <input type="radio" name="OVRI_GATEWAY_P3F" id="desactiver_p3f" value="off"
                {$p3f_off|escape:'htmlall':'UTF-8'} />
              <label for="desactiver_p3f">{l s='Disable' mod='ovribanking'}</label>
              <a class="slide-button btn"></a> </span>
            <div id="settings_p3f" {if $p3f_off}style="display: none" {/if}>
              <label for="seuil_p3f"
                style="margin-top:5px;">{l s='Minimum triggering threshold (Min 50 €)' mod='ovribanking'} </label>
              <input type="text" id="seuil_p3f" name="OVRI_TRIGGER_P3F" value="{$seuil_p3f|escape:'htmlall':'UTF-8'}"
                placeholder="50" ;>
              <div>
                {l s='If you set a threshold in this case this payment method will be displayed only when the customer cart total is at least equal to this threshold' mod='ovribanking'}<span
                  class="badge badge-pill badge-warning" style="font-size: 10px;">
                  {l s='If not defined, 50€ will be the default threshold' mod='ovribanking'}</span></div>
              <label for="fee_p3f"
                style="margin-top:5px;">{l s='Fees to be applied to this payment method' mod='ovribanking'} </label>
              <input type="text" id="fee_p3f" name="OVRI_FEE_P3F" value="{$fee_p3f|escape:'htmlall':'UTF-8'}"
                placeholder="0" ;>
              <div class="alert alert-danger" style='margin-top: 5px;'>
                {l s='0 indicates no fees, however you can indicate fees that correspond to a percentage of the total amount of the cart, if you indicate 1 it will indicate 1%, be careful not to use a comma but only a point as a decimal separator.' mod='ovribanking'}
              </div>
              <hr>
            </div>
            <h2>{l s='Setting of the payment in 4 times' mod='ovribanking'}</h2>
            <p>
              {l s='The payment in 4 times allows you to propose to your customer a facility of payment in order to settle his order in four monthly payments' mod='ovribanking'}
              **</p>
            <label for="p4f">{l s='Activate the payment in 4 times' mod='ovribanking'}</label>
            <span class="switch prestashop-switch">
              <input type="radio" name="OVRI_GATEWAY_P4F" id="activer_p4f" value="on"
                {$p4f_on|escape:'htmlall':'UTF-8'} />
              <label for="activer_p4f">{l s='Enable' mod='ovribanking'}</label>
              <input type="radio" name="OVRI_GATEWAY_P4F" id="desactiver_p4f" value="off"
                {$p4f_off|escape:'htmlall':'UTF-8'} />
              <label for="desactiver_p4f">{l s='Disable' mod='ovribanking'}</label>
              <a class="slide-button btn"></a> </span>
            <div id="settings_p4f" {if $p4f_off}style="display: none" {/if}>
              <label for="seuil_p4f" c>{l s='Minimum triggering threshold (Min 50 €)' mod='ovribanking'} </label>
              <input type="text" id="seuil_p4f" name="OVRI_TRIGGER_P4F" value="{$seuil_p4f|escape:'htmlall':'UTF-8'}"
                placeholder="50" ;>
              <div>
                {l s='If you set a threshold in this case this payment method will be displayed only when the customer cart total is at least equal to this threshold' mod='ovribanking'}
                <span class="badge badge-pill badge-warning" style="font-size: 10px;">
                  {l s='If not defined, 50€ will be the default threshold' mod='ovribanking'}</span></div>
              <label for="fee_p4f"
                style="margin-top:5px;">{l s='Fees to be applied to this payment method' mod='ovribanking'} </label>
              <input type="text" id="fee_p4f" name="OVRI_FEE_P4F" value="{$fee_p4f|escape:'htmlall':'UTF-8'}"
                placeholder="0" ;>
              <div class="alert alert-danger" style='margin-top: 5px;'>
                {l s='0 indicates no fees, however you can indicate fees that correspond to a percentage of the total amount of the cart, if you indicate 1 it will indicate 1%, be careful not to use a comma but only a point as a decimal separator.' mod='ovribanking'}
              </div>
            </div>
            <hr>
            <input type="button" name="submitOvribanking" class="btn btn-primary"
              value="{l s='Update configuration' mod='ovribanking'}" id="submitOvribanking"
              onclick="OvribankingFX.validateFormOvribanking();" style="margin-top:-5px;">
          </form>
        </div>
      </div>
      <div id="faq_ovribanking_div" class="tab-pane {$activeTab_2|escape:'htmlall':'UTF-8'}" role="tabpanel">
        <div class="panel" style="min-height:650px;">
          <h2 class="colorBlueOvribanking">{l s='I Need one Merchant Account for use Ovri ?' mod='ovribanking'}</h2>
          <p>
            {l s='No,  the Merchant account is included at the time of subscription. You have no further steps to take with your bank or another organization.' mod='ovribanking'}
          </p>
          <h2 class="colorBlueOvribanking">{l s='How much does it cost ?' mod='ovribanking'}</h2>
          <p>
            {l s='You will not have any monthly subscriptions or other fees until you use the solution and have a successful transaction. Yes, you only pay when you use the solution and also only when payments are accepted' mod='ovribanking'}
          </p>
          <p>{l s='Pay-as-you-go billing' mod='ovribanking'}</p>
          <ul>
            <li>{l s='A single rate regardless of the country of the card from 0.9% to 2.3%' mod='ovribanking'}</li>
            <li>{l s='Only on accepted transactions' mod='ovribanking'}</li>
            <li>{l s='No fees on declined or refunded transactions' mod='ovribanking'}</li>
          </ul>
          <p>
            {l s='If you need a custom offer this is possible just contact us by email at hello@ovri.com depending on your profile an offer will be sent to you.' mod='ovribanking'}
          </p>
          <h2 class="colorBlueOvribanking">{l s='How the money is transferred to my bank account ?' mod='ovribanking'}
          </h2>
          <ul>
            <li>{l s='You can make manual withdrawal requests in addition' mod='ovribanking'}</li>
            <li>{l s='We transfer your sales according to your preferences :' mod='ovribanking'}
              <ul>
                <li>{l s='Everyday' mod='ovribanking'}</li>
                <li>{l s='Every 2 days' mod='ovribanking'}</li>
                <li>{l s='Or more' mod='ovribanking'}</li>
                <li>{l s='You decide that !' mod='ovribanking'}</li>
              </ul>
            </li>
          </ul>
          <h2 class="colorBlueOvribanking">
            {l s='How long does it take to open and verify an account ?' mod='ovribanking'}</h2>
          <p>
            {l s='The opening is fast in less than 5 minutes, For the verification you must send us your documents, we validate the accounts in the morning and in the afternoon generally the verification is carried out in 1 working day!' mod='ovribanking'}.
          </p>
          <p>{l s='But don\'t forget you will be able to collect your customers from the opening!' mod='ovribanking'}
          </p>
          <h2 class="colorBlueOvribanking">{l s='How to configure Ovri?' mod='ovribanking'}</h2>
          <p>
            {l s='Add your website, in your Ovri dashboard, retrieve the API key as well as your secret key and indicate it in the  settings section of this module. Then you need to add to your dashboard in the contract section the IP address of the server that hosts your website.' mod='ovribanking'}
          </p>
          <h2 class="colorBlueOvribanking">{l s='Ovri is not displayed on the order page ? ' mod='ovribanking'}</h2>
          <p>
            {l s='If you have well configured the module (adding the API key and the secret key) it means that you have prestashop restrictions active, in the tab "payment>preferences" check that in "country restrictions" that ovri is active for payments where you want to display it' mod='ovribanking'}
          </p>
          <h2 class="colorBlueOvribanking">{l s='How to install an update of Ovri module?' mod='ovribanking'}</h2>
          <p>
            {l s='You just have to download the new archive and install it as if it was a new module, prestashop will automatically update your current version to the new version' mod='ovribanking'}
          </p>
          <h2 class="colorBlueOvribanking">{l s='What are the payment methods accepted by Ovri?' mod='ovribanking'}</h2>
          <p>
            {l s='Visa, Carte Bleue, Mastercard, Amex, Electron, Maestro ... We are increasing day by day the available modes' mod='ovribanking'}
          </p>
          <h2 class="colorBlueOvribanking">{l s='Who are we ?' mod='ovribanking'}</h2>
          <p>
            {l s='Ovri, is a payment solution published by the IPS INTERNATIONNAL SAS, based in France in PARIS and BORDEAUX. We are a financial institution' mod='ovribanking'}
          </p>
          <p>&nbsp;</p>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  $("#csrf").val(makeid(20));

  function makeid(number) {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for (var i = 0; i < number; i++)
      text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
  }
</script>