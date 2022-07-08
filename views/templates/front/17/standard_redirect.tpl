{*
    * NOTICE OF LICENSE
    *
    * This file is create by Ovri
    * For the installation of the software in your application
    * You accept the licence agreement.
    *
    * You must not modify, adapt or create derivative works of this source code
    *
    *  @author    OVRI
    *  @copyright 2018-2022 OVRI SAS
    *  @license   ovri.com
*}

<script type="application/javascript">
  var ips_failed = "{$MessageAnswer}";
  var ips_message = "{l s='Your bank refused the payment!' mod='ovribanking'}";
</script>

<p class="payment_module"><img id="CB" class="ovribanking-logo" src="{$mtglogocard|escape:'htmlall':'UTF-8'}"
    alt="carte" width="140px" /><br>
{l s='You will be redirected to our secure payment server' mod='ovribanking'}</p>