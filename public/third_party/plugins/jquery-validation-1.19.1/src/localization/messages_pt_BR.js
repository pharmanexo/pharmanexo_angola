/*
 * Translated default messages for the jQuery validation plugin.
 * Locale: PT (Portuguese; português)
 * Region: BR (Brazil)
 */
$.extend( $.validator.messages, {

	// Core
<<<<<<< HEAD
	required: "Este campo &eacute; requerido.",
	remote: "Por favor, corrija este campo.",
	email: "Por favor, forne&ccedil;a um endere&ccedil;o de email v&aacute;lido.",
	url: "Por favor, forne&ccedil;a uma URL v&aacute;lida.",
	date: "Por favor, forne&ccedil;a uma data v&aacute;lida.",
	dateISO: "Por favor, forne&ccedil;a uma data v&aacute;lida (ISO).",
	number: "Por favor, forne&ccedil;a um n&uacute;mero v&aacute;lido.",
	digits: "Por favor, forne&ccedil;a somente d&iacute;gitos.",
	creditcard: "Por favor, forne&ccedil;a um cart&atilde;o de cr&eacute;dito v&aacute;lido.",
	equalTo: "Por favor, forne&ccedil;a o mesmo valor novamente.",
	maxlength: $.validator.format( "Por favor, forne&ccedil;a n&atilde;o mais que {0} caracteres." ),
	minlength: $.validator.format( "Por favor, forne&ccedil;a ao menos {0} caracteres." ),
	rangelength: $.validator.format( "Por favor, forne&ccedil;a um valor entre {0} e {1} caracteres de comprimento." ),
	range: $.validator.format( "Por favor, forne&ccedil;a um valor entre {0} e {1}." ),
	max: $.validator.format( "Por favor, forne&ccedil;a um valor menor ou igual a {0}." ),
	min: $.validator.format( "Por favor, forne&ccedil;a um valor maior ou igual a {0}." ),
	step: $.validator.format( "Por favor, forne&ccedil;a um valor m&uacute;ltiplo de {0}." ),

	// Metodos Adicionais
	maxWords: $.validator.format( "Por favor, forne&ccedil;a com {0} palavras ou menos." ),
	minWords: $.validator.format( "Por favor, forne&ccedil;a pelo menos {0} palavras." ),
	rangeWords: $.validator.format( "Por favor, forne&ccedil;a entre {0} e {1} palavras." ),
	accept: "Por favor, forne&ccedil;a um tipo v&aacute;lido.",
	alphanumeric: "Por favor, forne&ccedil;a somente com letras, n&uacute;meros e sublinhados.",
	bankaccountNL: "Por favor, forne&ccedil;a com um n&uacute;mero de conta banc&aacute;ria v&aacute;lida.",
	bankorgiroaccountNL: "Por favor, forne&ccedil;a um banco v&aacute;lido ou n&uacute;mero de conta.",
	bic: "Por favor, forne&ccedil;a um c&oacute;digo BIC v&aacute;lido.",
	cifES: "Por favor, forne&ccedil;a um c&oacute;digo CIF v&aacute;lido.",
	creditcardtypes: "Por favor, forne&ccedil;a um n&uacute;mero de cart&atilde;o de cr&eacute;dito v&aacute;lido.",
	currency: "Por favor, forne&ccedil;a uma moeda v&aacute;lida.",
	dateFA: "Por favor, forne&ccedil;a uma data correta.",
	dateITA: "Por favor, forne&ccedil;a uma data correta.",
	dateNL: "Por favor, forne&ccedil;a uma data correta.",
	extension: "Por favor, forne&ccedil;a um valor com uma extens&atilde;o v&aacute;lida.",
	giroaccountNL: "Por favor, forne&ccedil;a um n&uacute;mero de conta corrente v&aacute;lido.",
	iban: "Por favor, forne&ccedil;a um c&oacute;digo IBAN v&aacute;lido.",
	integer: "Por favor, forne&ccedil;a um n&uacute;mero n&atilde;o decimal.",
	ipv4: "Por favor, forne&ccedil;a um IPv4 v&aacute;lido.",
	ipv6: "Por favor, forne&ccedil;a um IPv6 v&aacute;lido.",
	lettersonly: "Por favor, forne&ccedil;a apenas com letras.",
	letterswithbasicpunc: "Por favor, forne&ccedil;a apenas letras ou pontua&ccedil;ões.",
	mobileNL: "Por favor, fornece&ccedil;a um n&uacute;mero v&aacute;lido de telefone.",
	mobileUK: "Por favor, fornece&ccedil;a um n&uacute;mero v&aacute;lido de telefone.",
	nieES: "Por favor, forne&ccedil;a um NIE v&aacute;lido.",
	nifES: "Por favor, forne&ccedil;a um NIF v&aacute;lido.",
	nowhitespace: "Por favor, n&atilde;o utilize espa&ccedil;os em branco.",
	pattern: "O formato fornecido &eacute; inv&aacute;lido.",
	phoneNL: "Por favor, forne&ccedil;a um n&uacute;mero de telefone v&aacute;lido.",
	phoneUK: "Por favor, forne&ccedil;a um n&uacute;mero de telefone v&aacute;lido.",
	phoneUS: "Por favor, forne&ccedil;a um n&uacute;mero de telefone v&aacute;lido.",
	phonesUK: "Por favor, forne&ccedil;a um n&uacute;mero de telefone v&aacute;lido.",
	postalCodeCA: "Por favor, forne&ccedil;a um n&uacute;mero de c&oacute;digo postal v&aacute;lido.",
	postalcodeIT: "Por favor, forne&ccedil;a um n&uacute;mero de c&oacute;digo postal v&aacute;lido.",
	postalcodeNL: "Por favor, forne&ccedil;a um n&uacute;mero de c&oacute;digo postal v&aacute;lido.",
	postcodeUK: "Por favor, forne&ccedil;a um n&uacute;mero de c&oacute;digo postal v&aacute;lido.",
	postalcodeBR: "Por favor, forne&ccedil;a um CEP v&aacute;lido.",
	require_from_group: $.validator.format( "Por favor, forne&ccedil;a pelo menos {0} destes campos." ),
	skip_or_fill_minimum: $.validator.format( "Por favor, optar entre ignorar esses campos ou preencher pelo menos {0} deles." ),
	stateUS: "Por favor, forne&ccedil;a um estado v&aacute;lido.",
	strippedminlength: $.validator.format( "Por favor, forne&ccedil;a pelo menos {0} caracteres." ),
	time: "Por favor, forne&ccedil;a um hor&aacute;rio v&aacute;lido, no intervado de 00:00 a 23:59.",
	time12h: "Por favor, forne&ccedil;a um hor&aacute;rio v&aacute;lido, no intervado de 01:00 a 12:59 am/pm.",
	url2: "Por favor, forne&ccedil;a uma URL v&aacute;lida.",
	vinUS: "O n&uacute;mero de identifica&ccedil;&atilde;o de ve&iacute;culo informado (VIN) &eacute; inv&aacute;lido.",
	zipcodeUS: "Por favor, forne&ccedil;a um c&oacute;digo postal americano v&aacute;lido.",
	ziprange: "O c&oacute;digo postal deve estar entre 902xx-xxxx e 905xx-xxxx",
	cpfBR: "Por favor, forne&ccedil;a um CPF v&aacute;lido.",
	nisBR: "Por favor, forne&ccedil;a um NIS/PIS v&aacute;lido",
	cnhBR: "Por favor, forne&ccedil;a um CNH v&aacute;lido.",
	cnpjBR: "Por favor, forne&ccedil;a um CNPJ v&aacute;lido."
=======
	required: "Este campo é requerido.",
	remote: "Por favor, corrija este campo.",
	email: "Por favor, forneça um endereço de email valido.",
	url: "Por favor, forneça uma URL valida.",
	date: "Por favor, forneça uma data valida.",
	dateISO: "Por favor, forneça uma data valida (ISO).",
	number: "Por favor, forneça um numero valido.",
	digits: "Por favor, forneça somente digitos.",
	creditcard: "Por favor, forneça um cartão de credito valido.",
	equalTo: "Por favor, forneça o mesmo valor novamente.",
	maxlength: $.validator.format( "Por favor, forneça um valor mais que {0} caracteres." ),
	minlength: $.validator.format( "Por favor, forneça ao menos {0} caracteres." ),
	rangelength: $.validator.format( "Por favor, forneça um valor entre {0} e {1} caracteres de comprimento." ),
	range: $.validator.format( "Por favor, forneça um valor entre {0} e {1}." ),
	max: $.validator.format( "Por favor, forneça um valor menor ou igual a {0}." ),
	min: $.validator.format( "Por favor, forneça um valor maior ou igual a {0}." ),
	step: $.validator.format( "Por favor, forneça um valor multiplo de {0}." ),

	// Metodos Adicionais
	maxWords: $.validator.format( "Por favor, forneça com {0} palavras ou menos." ),
	minWords: $.validator.format( "Por favor, forneça pelo menos {0} palavras." ),
	rangeWords: $.validator.format( "Por favor, forneça entre {0} e {1} palavras." ),
	accept: "Por favor, forneça um tipo valido.",
	alphanumeric: "Por favor, forneça somente com letras, numeros e sublinhados.",
	bankaccountNL: "Por favor, forneça com um numero de conta banc&aacute;ria valida.",
	bankorgiroaccountNL: "Por favor, forneça um banco valido ou numero de conta.",
	bic: "Por favor, forneça um codigo BIC valido.",
	cifES: "Por favor, forneça um ccodigo CIF valido.",
	creditcardtypes: "Por favor, forneça um numero de cartão de credito valido.",
	currency: "Por favor, forneça uma moeda valida.",
	dateFA: "Por favor, forneça uma data correta.",
	dateITA: "Por favor, forneça uma data correta.",
	dateNL: "Por favor, forneça uma data correta.",
	extension: "Por favor, forneça um valor com uma extensão valida.",
	giroaccountNL: "Por favor, forneça um numero de conta corrente valido.",
	iban: "Por favor, forneça um codigo IBAN valido.",
	integer: "Por favor, forneça um numero decimal.",
	ipv4: "Por favor, forneça um IPv4 valido.",
	ipv6: "Por favor, forneça um IPv6 valido.",
	lettersonly: "Por favor, forneça apenas com letras.",
	letterswithbasicpunc: "Por favor, forneça apenas letras ou pontua&ccedil;ões.",
	mobileNL: "Por favor, forneça um numero valido de telefone.",
	mobileUK: "Por favor, forneça um numero valido de telefone.",
	nieES: "Por favor, forneça um NIE valido.",
	nifES: "Por favor, forneça um NIF valido.",
	nowhitespace: "Por favor, não utilize espaços em branco.",
	pattern: "O formato fornecido está invalido.",
	phoneNL: "Por favor, forneça um numero de telefone valido.",
	phoneUK: "Por favor, forneça um numero de telefone valido.",
	phoneUS: "Por favor, forneça um numero de telefone valido.",
	phonesUK: "Por favor, forneça um numero de telefone valido.",
	postalCodeCA: "Por favor, forneça um numero de codigo postal valido.",
	postalcodeIT: "Por favor, forneça um numero de codigo postal valido.",
	postalcodeNL: "Por favor, forneça um numero de codigo postal valido.",
	postcodeUK: "Por favor, forneça um numero de codigo postal valido.",
	postalcodeBR: "Por favor, forneça um CEP valido.",
	require_from_group: $.validator.format( "Por favor, forneça pelo menos {0} destes campos." ),
	skip_or_fill_minimum: $.validator.format( "Por favor, optar entre ignorar esses campos ou preencher pelo menos {0} deles." ),
	stateUS: "Por favor, forneça um estado valido.",
	strippedminlength: $.validator.format( "Por favor, forneça pelo menos {0} caracteres." ),
	time: "Por favor, forneça um horario valido, no intervado de 00:00 a 23:59.",
	time12h: "Por favor, forneça um horário valido, no intervado de 01:00 a 12:59 am/pm.",
	url2: "Por favor, forneça uma URL valida.",
	vinUS: "O numero de identificação de veiculo informado (VIN) está invalido.",
	zipcodeUS: "Por favor, forneça um codigo postal americano valido.",
	ziprange: "O codigo postal deve estar entre 902xx-xxxx e 905xx-xxxx",
	cpfBR: "Por favor, forneça um CPF valido.",
	nisBR: "Por favor, forneça um NIS/PIS valido",
	cnhBR: "Por favor, forneça um CNH valido.",
	cnpjBR: "Por favor, forneça um CNPJ valido."
>>>>>>> master
} );
