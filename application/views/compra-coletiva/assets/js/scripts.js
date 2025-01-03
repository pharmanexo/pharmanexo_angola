$(document).ready(function () {
	reloadPlugins();
});

function reloadPlugins() {


	$('[data-toggle="tooltip"]').tooltip();




	/** busca cep **/
	$('#cep').blur(function (e) {
		var val = $(this).val();
		$.get(`https://viacep.com.br/ws/${val}/json/`, function (xhr) {
			$('#logradouro').val(xhr.logradouro)
			$('#complemento').val(xhr.complemento)
			$('#bairro').val(xhr.bairro)
			$('#localidade').val(xhr.localidade)
			$('#estado').val(xhr.uf)

			$('#numero').focus();
		})
	})

	$('#com_cep').blur(function (e) {
		var val = $(this).val();
		$.get(`https://viacep.com.br/ws/${val}/json/`, function (xhr) {
			$('#com_logradouro').val(xhr.logradouro)
			$('#com_complemento').val(xhr.complemento)
			$('#com_bairro').val(xhr.bairro)
			$('#com_localidade').val(xhr.localidade)
			$('#com_estado').val(xhr.uf)
			$('#com_numero').focus();
		})
	})

	if ($.fn.inputmask) {
		$('[data-inputmask="cpf"]').inputmask('999.999.999-99');
		$('[data-inputmask="cnpj"]').inputmask('99.999.999/9999-99');
		$('[data-inputmask="cep"]').inputmask('99999-999', {'clearIncomplete': true});
		// $('[data-inputmask="date"]').inputmask('99/99/9999', { 'clearIncomplete': true });
		$('[data-inputmask="time"]').inputmask('99:99');
		$('[data-inputmask="tel"]').inputmask('(99)9999-9999');
		$('[data-inputmask="cel"]').inputmask('(99)99999-999[9]');
		$('[data-inputmask="ans"]').inputmask('999.999/99-9');
		$('[data-inputmask="matricula"]').inputmask('999999-9');
		$('[data-inputmask="cpf_cnpj"]').inputmask({
			mask: ['999.999.999-99', '99.999.999/9999-99'],
			keepStatic: true
		});
		$('[data-inputmask="phone"]').inputmask({
			mask: ['(99)9999-9999', '(99)99999-9999'],
			keepStatic: true
		});
		$('[data-inputmask="serialkey"]').inputmask('999-999-99-9999');
		// valid cnpj
		$('[data-inputmask="cnpj"]').each(function () {
			$(this).on('blur', function () {
				if ($(this).val() !== '' && !validarCNPJ($(this).val())) {
					$(this).focus();
					toastr.warning('CNPJ Inválido!');
				}
			});
		});

		$('[data-inputmask="datetime"]').inputmask("datetime", {
			mask: "1/2/y h:s",
			placeholder: "dd/mm/yyyy hh:mm",
			leapday: "/02/29",
			separator: "/",
			alias: "dd/mm/yyyy",
			clearIncomplete: true
		});

		$('[data-inputmask="datetimeSeconds"]').inputmask("datetime", {
			mask: "1/2/y h:s:s",
			placeholder: "dd/mm/yyyy hh:mm:ss",
			leapday: "/02/29",
			separator: "/",
			alias: "dd/mm/yyyy",
			clearIncomplete: true
		});


		$('[data-inputmask="date"]').inputmask("date", {
			mask: "1/2/y",
			placeholder: "dd/mm/yyyy",
			leapday: "/02/29",
			separator: "/",
			alias: "dd/mm/yyyy",
			clearIncomplete: true
		});
	}

	// MasMoney plugin setting
	if ($.fn.maskMoney) {
		$('[data-inputmask="money"]').maskMoney({
			thousands: '.',
			decimal: ',',
			allowZero: true,
			allowNegative: true,
			allowEmpty: true
		});
		$('[data-inputmask="money4"]').maskMoney({
			thousands: '.',
			decimal: ',',
			allowZero: true,
			allowNegative: true,
			allowEmpty: true,
			precision: 4
		});
	}

	if ($.fn.dataTable) {
		$.extend(true, $.fn.dataTable.defaults, {
			oLanguage: {
				'sEmptyTable': 'Nenhum registro encontrado',
				'sInfo': 'Mostrando de _START_ até _END_ de _TOTAL_ registros',
				'sInfoEmpty': 'Mostrando 0 até 0 de 0 registros',
				'sInfoFiltered': '(Filtrados de _MAX_ registros)',
				'sInfoPostFix': '',
				'sInfoThousands': '.',
				'sLengthMenu': '_MENU_ resultados por página',
				'sLoadingRecords': 'Carregando...',
				'sProcessing': 'Carregando registros...',
				'sZeroRecords': 'Nenhum registro encontrado',
				'searchPlaceholder': 'Pesquisar registros',
				'sSearch': 'Pesquisar',
				'oPaginate': {
					'sNext': '>',
					'sPrevious': '<',
					'sFirst': '<<',
					'sLast': '>>'
				},
				'oAria': {
					'sSortAscending': ': Ordenar colunas de forma ascendente',
					'sSortDescending': ': Ordenar colunas de forma descendente'
				}
			},
		});
	}


}

function formWarning(warning) {
	if (warning) {
		toastr.options = {
			"closeButton": true,
			"debug": false,
			"newestOnTop": false,
			"progressBar": true,
			"positionClass": "toast-top-full-width",
			"preventDuplicates": false,
			"onclick": null,
			"showDuration": "300",
			"hideDuration": "1000",
			"timeOut": "50000",
			"extendedTimeOut": "10000",
			"showEasing": "swing",
			"hideEasing": "linear",
			"showMethod": "fadeIn",
			"hideMethod": "fadeOut"
		};
		toastr[warning.type](warning.message);
	}
}

function validarCNPJ(cnpj) {

	cnpj = cnpj.replace(/[^\d]+/g, '');

	if (cnpj == '') return false;

	if (cnpj.length != 14)
		return false;

	// Elimina CNPJs invalidos conhecidos
	if (cnpj == "00000000000000" ||
		cnpj == "11111111111111" ||
		cnpj == "22222222222222" ||
		cnpj == "33333333333333" ||
		cnpj == "44444444444444" ||
		cnpj == "55555555555555" ||
		cnpj == "66666666666666" ||
		cnpj == "77777777777777" ||
		cnpj == "88888888888888" ||
		cnpj == "99999999999999")
		return false;

	// Valida DVs
	tamanho = cnpj.length - 2
	numeros = cnpj.substring(0, tamanho);
	digitos = cnpj.substring(tamanho);
	soma = 0;
	pos = tamanho - 7;
	for (i = tamanho; i >= 1; i--) {
		soma += numeros.charAt(tamanho - i) * pos--;
		if (pos < 2)
			pos = 9;
	}
	resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
	if (resultado != digitos.charAt(0))
		return false;

	tamanho = tamanho + 1;
	numeros = cnpj.substring(0, tamanho);
	soma = 0;
	pos = tamanho - 7;
	for (i = tamanho; i >= 1; i--) {
		soma += numeros.charAt(tamanho - i) * pos--;
		if (pos < 2)
			pos = 9;
	}
	resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
	if (resultado != digitos.charAt(1))
		return false;

	return true;

}
