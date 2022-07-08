	var radios = document.querySelectorAll('input[type=radio][name="payment-option"]')
	radios.forEach(radio => radio.addEventListener('change', () => hiddenterms(radio.getAttribute('data-module-name'))));

	function hiddenterms(payment) {

		if (payment === 'ovribankingEmbedded') {
			document.getElementById('conditions-to-approve').style.display = 'none'

		} else {
			document.getElementById('conditions-to-approve').style.display = 'block'
		}

	}

	if (ips_failed == 1) {
		$('#checkout-payment-step').prepend('<div id="IPSERROR" class="alert alert-danger">' + ips_message + '</div>');
	}