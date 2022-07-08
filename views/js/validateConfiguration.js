/**
 * NOTICE OF LICENSE
 *
 * This file is create by Ovri
 * For the installation of the software in your application
 * You accept the licence agreement.
 *
 * You must not modify, adapt or create derivative works of this source code
 *
 *  @author    OVRI SAS
 *  @copyright 2018-2022 Ovri
 *  @license   ovri.com
 */

$(function () {

  var p2fradio = $(':radio[name="OVRI_GATEWAY_P2F"]')
  var p3fradio = $(':radio[name="OVRI_GATEWAY_P3F"]')
  var p4fradio = $(':radio[name="OVRI_GATEWAY_P4F"]')

  p2fradio.click(function () {
    if ($(this).val() === 'off') {

      $('#settings_p2f').hide();
    } else {
      $('#settings_p2f').show();
    }
    //alert ("stato ->" + $(this).val())
  })
  p3fradio.click(function () {
    if ($(this).val() === 'off') {

      $('#settings_p3f').hide();
    } else {
      $('#settings_p3f').show();
    }
    //alert ("stato ->" + $(this).val())
  })
  p4fradio.click(function () {
    if ($(this).val() === 'off') {

      $('#settings_p4f').hide();
    } else {
      $('#settings_p4f').show();
    }
    //alert ("stato ->" + $(this).val())
  })


  OvribankingFX = {
    validateFormOvribanking: function () {
      // Values configuration
      var seuil_p2f = $("#seuil_p2f").val();
      var seuil_p3f = $("#seuil_p3f").val();
      var seuil_p4f = $("#seuil_p4f").val();

      var fee_p2f = $("#fee_p2f").val();
      var fee_p3f = $("#fee_p3f").val();
      var fee_p4f = $("#fee_p4f").val();

      if (seuil_p2f) {
        var p2f = true;
      } else {
        var p2fok = true;
      }
      if (seuil_p3f) {
        var p3f = true;
      } else {
        var p3fok = true;
      }
      if (seuil_p4f) {
        var p4f = true;
      } else {
        var p4fok = true;
      }

      if (fee_p2f) {
        var p2ffee = true;
      } else {
        var p2ffeeok = true;
      }
      if (fee_p3f) {
        var p3ffee = true;
      } else {
        var p3ffeeok = true;
      }
      if (fee_p4f) {
        var p4ffee = true;
      } else {
        var p4ffeeok = true;
      }


      if (p2f) {
        if (seuil_p2f >= 50 && !isNaN(seuil_p2f)) {
          var p2fok = true;
        }
      }
      if (p3f) {
        if (seuil_p3f >= 50 && !isNaN(seuil_p3f)) {
          var p3fok = true;
        }
      }
      if (p4f) {
        if (seuil_p4f >= 50 && !isNaN(seuil_p4f)) {
          var p4fok = true;
        }
      }
      if (p2ffee) {
        if (!isNaN(fee_p2f)) {
          var p2ffeeok = true;
        }
      }
      if (p3ffee) {
        if (!isNaN(fee_p3f)) {
          var p3ffeeok = true;
        }
      }
      if (p4ffee) {
        if (!isNaN(fee_p4f)) {
          var p4ffeeok = true;
        }
      }

      if (p2fok && p3fok && p4fok && p2ffeeok && p3ffeeok && p4ffeeok) {
        $("#formOvribanking").submit();
      } else {
        var errorlist = "";
        if (!p2fok) {
          errorlist += "Payment in 2X: The minimum threshold is 50 € and must be in numeric format ex: 100.50 for 100,50€. \n";
        }
        if (!p3fok) {
          errorlist += "Payment in 3X: The minimum threshold is 50 € and must be in numeric format ex: 100.50 for 100,50€. \n";
        }
        if (!p4fok) {
          errorlist += "Payment in 4X: The minimum threshold is 50 € and must be in numeric format ex: 100.50 for 100,50€. \n";
        }
        if (!p2ffeeok) {
          errorlist += "Payment in 2x: Fees must be in numeric format ex: 1.00 for 1%. \n"
        }
        if (!p3ffeeok) {
          errorlist += "Payment in 3x: Fees must be in numeric format ex: 1.00 for 1%. \n"
        }
        if (!p4ffeeok) {
          errorlist += "Payment in 4x: Fees must be in numeric format ex: 1.00 for 1%. \n"
        }

        alert(errorlist);
        return false;
      }


    },
    showCompteIPS: function () {

    },
    hideCompteIPS: function () {

    }
  };
});