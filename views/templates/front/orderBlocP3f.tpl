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
<div class="panel card mt-2 d-print-none">
  <div class="card-header">
    <div class="row">
      <div class="col-md-6">
        <h3 class="card-header-title">{l s='Ovri Transaction Schedule Details (3x Payment)' mod='ovribanking'} </h3>
      </div>
    </div>
  </div>
  <div class="card-body">
    <table class="table" width="100%">
      <thead>
        <tr>
          <th> {l s='Transaction ID' mod='ovribanking'} </th>
          <th> {l s='Date' mod='ovribanking'} </th>
          <th> {l s='Amount' mod='ovribanking'} </th>
          <th> {l s='State' mod='ovribanking'} </th>
        </tr>
      </thead>
      <tbody>

        {section name=data loop=$transac}
          <tr>
            <td>
              {$transac[data].transacId|escape:'htmlall':'UTF-8'}
            </td>
            <td>
              {$transac[data].transacDate|escape:'htmlall':'UTF-8'}
            </td>
            <td>
              {$transac[data].transacMontant|escape:'htmlall':'UTF-8'}
            </td>
            <td>
              {$transac[data].transacOK|escape:'htmlall':'UTF-8'}
            </td>
          </tr>
        {/section}



      </tbody>
    </table>
  </div>
</div>