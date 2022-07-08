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
<h1 class="ovribanking_saveorder">{l s='Your order has been placed!' mod='ovribanking'}</h1>
<p class="ovribanking_confirmp">
  {l s='We are pleased to confirm that we have taken into account your order n°' mod='ovribanking'}
  <b>{$reference_order}.</b></p>
<center>
  <table style="border: 1px solid #CDCCCC; width: 60%">
    <tr>
      <td class="ovribanking_td">{l s='Order No.' mod='ovribanking'}</td>
      <td class="ovribanking_tdlight">{$reference_order}</td>
    </tr>
    <tr>
      <td class="ovribanking_td">{l s='Amount' mod='ovribanking'}</td>
      <td class="ovribanking_tdlight">{$amount}</td>
    </tr>
    <tr>
      <td class="ovribanking_td">{l s='Method of payment' mod='ovribanking'}</td>
      <td class="ovribanking_tdlight">{$method}</td>
    </tr>
    <tr>
      <td colspan="2">
        <hr>
        <center>
          <button class="btn" onclick="window.print()">{l s='Print' mod='ovribanking'}</button>
        </center>
      </td>
    </tr>
  </table>
</center>