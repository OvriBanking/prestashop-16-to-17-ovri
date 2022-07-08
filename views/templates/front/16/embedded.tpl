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
<!-- It is important to indicate rel="stylesheet" -->
<link href="{$LibraryCss|escape:'htmlall':'UTF-8'}" rel="stylesheet">
<p class="payment_module">
<div class="row">
  <div class="col-xs-12">
    <div class="payment_module" style="border: 1px solid #A09F9F;">
      <h3 style="padding: 5px; color: black; margin:0px;"> {l s='Credit card payment' mod='ovribanking'} <img id="CB"
          class="ovribanking-logo" src="{$path_img|escape:'htmlall':'UTF-8'}/views/img/carte.png"
          alt="{l s='Pay by credit card with ovri' mod='ovribanking'}" width="140px" /> </h3>
      <div class="alert" style="background-color: #9FCDFF; color: black;">
        {l s='Enter your card information to complete your purchase' mod='ovribanking'}</div>
      <script type="application/javascript">
        var ips_failed = "{$MessageAnswer}";
        var ips_message = "{l s='Your bank refused the payment!' mod='ovribanking'}";
        if (ips_failed == 1) {
          $(".center_column").prepend("<div class='alert alert-danger'>" + ips_message + "</div>");
        }
      </script>
      <div style="padding: 10px;">
        <ovri-app embedded="true" actuatorid="submit-credit-card-button" token="{$Token}"></ovri-app>
      </div>
      <div id="ipsBtnSubmit">
        <div class="text-right" style="padding: 5px;">
          <button id="submit-credit-card-button"
            class="btn btn-success center-block">{l s='Pay and validate my order' mod='ovribanking'}</button>
        </div>
        <div class="ps-hidden-by-js" style="display: none;"> </div>
      </div>
      <script type="text/javascript" src="{$Library|escape:'htmlall':'UTF-8'}"></script>
    </div>
  </div>
</div>
</p>
{if $2Fav}
  <p class="payment_module"> <a id="P2F" class="ovribanking-logo-link bankwire" href="{$Link2F|escape:'htmlall':'UTF-8'}"
      title="{l s='Pay in two time with ovri' mod='ovribanking'}"> <img id="CB" class="ovribanking-logo"
        src="{$path_img|escape:'htmlall':'UTF-8'}/views/img/carte.png"
        alt="{l s='Pay in two time with ovri' mod='ovribanking'}" width="140px" />
      {l s='Pay in two time with ovri' mod='ovribanking'}
      {if $2fees }
        ({l s='Fees' mod='ovribanking'} {$2fees} )
      {/if} </a> </p>
{/if}
{if $3Fav}
  <p class="payment_module"> <a id="P3F" class="ovribanking-logo-link bankwire" href="{$Link3F|escape:'htmlall':'UTF-8'}"
      title="{l s='Pay in three time with ovri' mod='ovribanking'}"> <img id="CB" class="ovribanking-logo"
        src="{$path_img|escape:'htmlall':'UTF-8'}/views/img/carte.png"
        alt="{l s='Pay in three time with ovri' mod='ovribanking'}" width="140px" />
      {l s='Pay in three time with ovri' mod='ovribanking'}
      {if $3fees }
        ({l s='Fees' mod='ovribanking'} {$3fees} )
      {/if} </a> </p>
{/if}
{if $4Fav}
  <p class="payment_module"> <a id="P4F" class="ovribanking-logo-link bankwire" href="{$Link4F|escape:'htmlall':'UTF-8'}"
      title="{l s='Pay in four time with ovri' mod='ovribanking'}"> <img id="CB" class="ovribanking-logo"
        src="{$path_img|escape:'htmlall':'UTF-8'}/views/img/carte.png"
        alt="{l s='Pay in four time with ovri' mod='ovribanking'}" width="140px" />
      {l s='Pay in four time with ovri' mod='ovribanking'} {if $4fees }
        ({l s='Fees' mod='ovribanking'} {$4fees} )
      {/if} </a> </p>
{/if}