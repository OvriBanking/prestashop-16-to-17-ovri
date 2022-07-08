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
  if (ips_failed == 1) {
    $(".center_column").prepend("<div class='alert alert-danger'>" + ips_message + "</div>");
  }
</script>
<p class="payment_module"> <a class="ovribanking-logo-link bankwire" href="{$LinkStandard|escape:'htmlall':'UTF-8'}"
    title="{l s='Pay by credit card with ovri' mod='ovribanking'}"> <img id="CB" class="ovribanking-logo"
      src="{$path_img|escape:'htmlall':'UTF-8'}/views/img/carte.png"
      alt="{l s='Pay by credit card with ovri' mod='ovribanking'}" width="140px" />
    {l s='Pay with ovri' mod='ovribanking'} </a> </p>
{if $2Fav }
  <p class="payment_module"> <a id="P2F" class="ovribanking-logo-link bankwire" href="{$Link2F|escape:'htmlall':'UTF-8'}"
      title="{l s='Pay in two time with ovri' mod='ovribanking'}"> <img id="CB" class="ovribanking-logo"
        src="{$path_img|escape:'htmlall':'UTF-8'}/views/img/carte.png"
        alt="{l s='Pay in two time with ovri' mod='ovribanking'}" width="140px" />
      {l s='Pay in two time with ovri' mod='ovribanking'}
      {if $2fees}
        ({l s='Fees' mod='ovribanking'} {$2fees} )
      {/if} </a> </p>
{/if}
{if $3Fav }
  <p class="payment_module"> <a id="P3F" class="ovribanking-logo-link bankwire" href="{$Link3F|escape:'htmlall':'UTF-8'}"
      title="{l s='Pay in three time with ovri' mod='ovribanking'}"> <img id="CB" class="ovribanking-logo"
        src="{$path_img|escape:'htmlall':'UTF-8'}/views/img/carte.png"
        alt="{l s='Pay in three time with ovri' mod='ovribanking'}" width="140px" />
      {l s='Pay in three time with ovri' mod='ovribanking'}
      {if $3fees}
        ({l s='Fees' mod='ovribanking'} {$3fees} )
      {/if} </a> </p>
{/if}
{if $4Fav }
  <p class="payment_module"> <a id="P4F" class="ovribanking-logo-link bankwire" href="{$Link4F|escape:'htmlall':'UTF-8'}"
      title="{l s='Pay in four time with ovri' mod='ovribanking'}"> <img id="CB" class="ovribanking-logo"
        src="{$path_img|escape:'htmlall':'UTF-8'}/views/img/carte.png"
        alt="{l s='Pay in four time with ovri' mod='ovribanking'}" width="140px" />
      {l s='Pay in four time with ovri' mod='ovribanking'} {if $4fees}
        ({l s='Fees' mod='ovribanking'} {$4fees} )
      {/if} </a> </p>
{/if}