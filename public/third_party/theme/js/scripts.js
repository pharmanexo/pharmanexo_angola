//janela popup
var janela = null;


$(document).ready(function () {
    $("input[type='search']").attr("placeholder", "Pesquisar registros");

    $('#filtro-cep').click(function () {
        var cep = $('#cep').val().replace(/\D/g, '');

        if (cep !== '') {
            var validacep = /^[0-9]{8}$/;

            if (validacep.test(cep)) {
                $("#rua").val("...");
                $("#bairro").val("...");
                $("#municipio").val("...");
                $("#estado").val("...");

                $.getJSON('https://viacep.com.br/ws/' + cep + '/json/?callback=?', function (data) {
                    if (!('erro' in data)) {
                        $("#rua").val(data.logradouro);
                        $("#bairro").val(data.bairro);
                        $("#municipio").val(data.localidade);
                        $("#estado").val(data.uf);
                        $('#numero').focus();
                    } else {
                        limparCamposEndereco();
                        alert('CEP não encontrado!');
                    }
                });
            } else {
                limparCamposEndereco();
                alert('Formato de CEP inválido!');
            }
        } else {
            limparCamposEndereco();
        }
    });

    $('[data-ma-action="notifications-clear"]').click(function () {

        var url = $(this).data('url');

        $.post(url, function (response) {

            if (response.type == 'success') {
                $('#containerNotifications').html('');
                $("#btnNotfifications").removeClass("top-nav__notify");
            }

            formWarning(response);
        });
    });

    $("#cnpj").on('blur', function () {
        dadosCnpj($(this).val());
    });


    $(".inpt-search-cnt").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $(".listview__item").css("display", function() {
            return this.innerText.toLowerCase().indexOf(value) > -1 ? "":"none"
        });
    });
});


function getMessagesUnread(url) 
{
    $.get(url, function (xhr) {
        var container = $('#messagesNavbar');
        var count = 0;
        container.html('');
        $.each(xhr, function(index, item) {

            var elem = $(`<a href="${item.url}" class="listview__item">`);
            var img = $(`<img src="${item.src_logo}" style="width: 40px; height: 40px" class="listview__img" alt="">`);
            var listContent = $(`<div class="listview__content">`)
            var listHeading = $(`<div class="listview__heading">`)
            var text = $(`<p>Mensagens não lidas: ${item.qtd_msg}</p>`)
            listHeading.html(item.nome)

            listContent.append(listHeading)
            listContent.append(text);
            elem.append(img);
            elem.append(listContent);

            container.append(elem);
            count++;
        });


        if (count > 0){

            $('#btnMessages').addClass('top-nav__notify');

        }
    })

}

let reloadPlugin = function (params) {
    // jQuery inputmask set default mask
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


        $('[data-toggle="tooltip"]').tooltip();
    }

    $.fn.extend({
        treed: function (o) {

            var openedClass = 'glyphicon-minus-sign';
            var closedClass = 'glyphicon-plus-sign';

            if (typeof o != 'undefined') {
                if (typeof o.openedClass != 'undefined') {
                    openedClass = o.openedClass;
                }
                if (typeof o.closedClass != 'undefined') {
                    closedClass = o.closedClass;
                }
            }
            ;

            //initialize each of the top levels
            var tree = $(this);
            tree.addClass("tree");
            tree.find('li').has("ul").each(function () {
                var branch = $(this); //li with children ul
                branch.prepend("<i class='indicator glyphicon " + closedClass + "'></i>");
                branch.addClass('branch');
                branch.on('click', function (e) {
                    if (this == e.target) {
                        var icon = $(this).children('i:first');
                        icon.toggleClass(openedClass + " " + closedClass);
                        $(this).children().children().toggle();
                    }
                })
                branch.children().children().toggle();
            });
            //fire event from the dynamically added icon
            tree.find('.branch .indicator').each(function () {
                $(this).on('click', function () {
                    $(this).closest('li').click();
                });
            });
            //fire event to open branch if the li contains an anchor instead of text
            tree.find('.branch>a').each(function () {
                $(this).on('click', function (e) {
                    $(this).closest('li').click();
                    e.preventDefault();
                });
            });
            //fire event to open branch if the li contains a button instead of text
            tree.find('.branch>button').each(function () {
                $(this).on('click', function (e) {
                    $(this).closest('li').click();
                    e.preventDefault();
                });
            });
        }
    });

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

    var dataTableButtons = '<div class="dataTables_buttons hidden-sm-down actions">' +
        '<span class="actions__item zmdi zmdi-print" data-table-action="print" />' +
        '<span class="actions__item zmdi zmdi-fullscreen" data-table-action="fullscreen" />' +
        '<div class="dropdown actions__item">' +
        '<i data-toggle="dropdown" class="zmdi zmdi-download" />' +
        '<ul class="dropdown-menu dropdown-menu-right">' +
        '<a href="" class="dropdown-item" data-table-action="excel">Excel (.xlsx)</a>' +
        '<a href="" class="dropdown-item" data-table-action="csv">CSV (.csv)</a>' +
        '</ul>' +
        '</div>' +
        '</div>';


    if ($.fn.dataTable) {
        $.extend(true, $.fn.dataTable.defaults, {
            dom: 'Bfrtip',
            pageLength: 25,
            processing: true,
            responsive: true,
            serverSide: false,
            language: {
                searchPlaceholder: "Pesquisar registros..."
            },
            buttons: [ // Data table buttons for export and print
                {
                    extend: 'excelHtml5',
                    title: 'Export Data',
                    exportOptions: {
                        modifier: {
                            page: 'all',
                            search: 'none'
                        }
                    }
                },
                {
                    extend: 'csvHtml5',
                    title: 'Export Data',
                    exportOptions: {
                        modifier: {
                            page: 'all',
                            search: 'none'
                        }
                    }
                },
                {
                    extend: 'print',
                    title: $(document).find("title").text(),
                    exportOptions: {
                        modifier: {
                            page: 'all',
                            search: 'none'
                        }
                    }
                }
            ],
            oLanguage: {
                'sEmptyTable': 'Nenhum registro encontrado',
                'sInfo': 'Mostrando de _START_ até _END_ de _TOTAL_ registros',
                'sInfoEmpty': 'Mostrando 0 até 0 de 0 registros',
                'sInfoFiltered': '',
                'sInfoPostFix': '',
                'sInfoThousands': '.',
                'sLengthMenu': '_MENU_ resultados por página',
                'sLoadingRecords': 'Carregando...',
                'sProcessing': 'Carregando registros...',
                'sZeroRecords': 'Nenhum registro encontrado',
                'searchPlaceholder': 'Pesquisar registros',
                'sSearch': 'Pesquisar',
                'oPaginate': {
                    'sNext': 'Próximo',
                    'sPrevious': 'Anterior',
                    'sFirst': 'Primeiro',
                    'sLast': 'Último'
                },
                'oAria': {
                    'sSortAscending': ': Ordenar colunas de forma ascendente',
                    'sSortDescending': ': Ordenar colunas de forma descendente'
                }
            },
            "initComplete": function (settings, json) {
                $(this).closest('.dataTables_wrapper').prepend(dataTableButtons); // Add custom button (fullscreen, print and export)
            }
        });

        // Data table button actions
        $('body').on('click', '[data-table-action]', function (e) {
            e.preventDefault();

            var exportFormat = $(this).data('table-action');

            if (exportFormat === 'excel') {
                $(this).closest('.dataTables_wrapper').find('.buttons-excel').trigger('click');
            }
            if (exportFormat === 'csv') {
                $(this).closest('.dataTables_wrapper').find('.buttons-csv').trigger('click');
            }
            if (exportFormat === 'print') {
                $(this).closest('.dataTables_wrapper').find('.buttons-print').trigger('click');
            }
            if (exportFormat === 'fullscreen') {
                var parentCard = $(this).closest('.card');

                if (parentCard.hasClass('card--fullscreen')) {
                    parentCard.removeClass('card--fullscreen');
                    $('body').removeClass('data-table-toggled');
                } else {
                    parentCard.addClass('card--fullscreen')
                    $('body').addClass('data-table-toggled');
                }
            }
        });

    }

    if ($.fn.validate) {
        $.extend($.validator.messages, {

            // Core
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
            maxlength: $.validator.format("Por favor, forneça um valor mais que {0} caracteres."),
            minlength: $.validator.format("Por favor, forneça ao menos {0} caracteres."),
            rangelength: $.validator.format("Por favor, forneça um valor entre {0} e {1} caracteres de comprimento."),
            range: $.validator.format("Por favor, forneça um valor entre {0} e {1}."),
            max: $.validator.format("Por favor, forneça um valor menor ou igual a {0}."),
            min: $.validator.format("Por favor, forneça um valor maior ou igual a {0}."),
            step: $.validator.format("Por favor, forneça um valor multiplo de {0}."),

            // Metodos Adicionais
            maxWords: $.validator.format("Por favor, forneça com {0} palavras ou menos."),
            minWords: $.validator.format("Por favor, forneça pelo menos {0} palavras."),
            rangeWords: $.validator.format("Por favor, forneça entre {0} e {1} palavras."),
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
            require_from_group: $.validator.format("Por favor, forneça pelo menos {0} destes campos."),
            skip_or_fill_minimum: $.validator.format("Por favor, optar entre ignorar esses campos ou preencher pelo menos {0} deles."),
            stateUS: "Por favor, forneça um estado valido.",
            strippedminlength: $.validator.format("Por favor, forneça pelo menos {0} caracteres."),
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
        });
    }

    // Plugin jQuery SweetAlert custom show modal confirm
    $.fn.showConfirm = function (options) {
        if (typeof (swal) == 'undefined') {
            console.warn('Plugin SweetAlert requerido. http://t4t5.github.io/sweetalert/');
            return false;
        }

        var defaults = $.extend({
            title: 'Confirmar?',
            text: 'Deseja realmente executar essa ação?',
            trigger: 'click',
        }, options);

        var isAjax = (typeof (defaults.ajax) === 'object');

        var swalOptions = {
            type: defaults.type || "warning",
            allowEscapeKey: false,
            showCancelButton: true,
            closeOnConfirm: defaults.closeOnConfirm || true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Sim",
            cancelButtonText: "Não",
            text: 'Deseja realmente executar essa ação?',
        };

        swalOptions.text = defaults.text;
        swal.mixin(swalOptions);

        $.each(this, function () {
            var me = $(this);
            me.on(defaults.trigger, function (e) {
                e.preventDefault();
                swal({
                        title: defaults.title,
                        showLoaderOnConfirm: true
                    },
                    function (isConfirm) {
                        if (isConfirm) {
                            if (isAjax) {
                                defaults.ajax.url = me.attr('href');
                                $.ajax(defaults.ajax).done(function (xhr) {
                                    if (defaults.onConfirm) defaults.onConfirm(me, xhr);
                                    if (defaults.closeOnConfirm) swal.close();
                                });
                            } else {
                                if (defaults.onConfirm) defaults.onConfirm(me);
                                if (defaults.closeOnConfirm) swal.close();
                            }
                        } else {
                            if (defaults.onCancel) defaults.onCancel();
                            swal.close();
                        }
                    }
                );
            });
        });
    };


    $.fn.select2.defaults.set('language', {
        errorLoading: function () {
            return "Erro ao carregar os registros";
        },
        inputTooLong: function (args) {
            return `Máximo de ${args.maximum} caracteres`;
        },
        inputTooShort: function (args) {
            console.log(args);

            return `Digite ${args.minimum} caracteres ou mais`;
        },
        loadingMore: function () {
            return "Carregando Registros";
        },
        maximumSelected: function (args) {
            return "MAX_SELECTED";
        },
        noResults: function () {
            return "Nenhum registro encontrado";
        },
        searching: function () {
            return "Procurando registros...";
        }
    });
};

$(function () {
    reloadPlugin();
});

function limparCamposEndereco() {
    $('#rua').val('');
    $('#numero').val('');
    $('#bairro').val('');
    $('#municipio').val('');
    $('#estado').val('');
}

function formWarning(warning) {
    if (warning) {
        toastr[warning.type](warning.message);
    }
}

/////////// FUNCTIONs PASSWORD STRENGTH //////////////

/* 
 *
 * Determina a porcentagem da barra de progresso do nivel da senha
 * @param - valor do campo senha
 * @param - valor do campo de confirmação de senha
 */
function checkPassword(password, c_password) {
    var strengthBar = document.getElementById("progressPassword");
    var strength = 0;

    if (password.length == 0) {
        strengthBar.style.width = '0%';
        checks(1, null, null);
        return;
    }

    if (password.match(/[a-z]+/) && password.match(/[0-9]+/)) {
        strength += 1;
        document.getElementById('valid-alfa').className = "fa fa-check";
    } else {
        document.getElementById('valid-alfa').className = "fa fa-angle-right";
    }

    if (password.match(/[A-Z]+/)) {
        strength += 1;
        document.getElementById('valid-maiuscula').className = "fa fa-check";
    } else {
        document.getElementById('valid-maiuscula').className = "fa fa-angle-right";
    }

    if (password.match(/[$@$!%*#?&_]+/)) {
        strength += 1;
        document.getElementById('valid-especial').className = "fa fa-check";
    } else {
        document.getElementById('valid-especial').className = "fa fa-angle-right";
    }

    if (password.length >= 8) {
        strength += 1;
        document.getElementById('valid-min').className = "fa fa-check";
    } else {

        document.getElementById('valid-min').className = "fa fa-angle-right";
    }

    if (password === c_password) {
        strength += 1;
        document.getElementById('valid-confirma').className = "fa fa-check";
    } else {

        document.getElementById('valid-confirma').className = "fa fa-angle-right";
    }

    switch (strength) {
        case 0:
            strengthBar.style.width = '0%';
            strengthBar.className = "progress-bar bg-danger";
            break;
        case 1:
            strengthBar.style.width = '20%';
            strengthBar.className = "progress-bar bg-danger";
            break;
        case 2:
            strengthBar.style.width = '40%';
            strengthBar.className = "progress-bar bg-warning";
            break;
        case 3:
            strengthBar.style.width = '60%';
            strengthBar.className = "progress-bar bg-warning";
            break;
        case 4:
            strengthBar.style.width = '80%';
            strengthBar.className = "progress-bar bg-info";
            break;
        case 5:
            strengthBar.style.width = '100%';
            strengthBar.className = "progress-bar bg-success";
            break;
    }
}

/* 
 *
 * Modifica os checks de validação no popover
 * @param - int habilita limpeza dos checks do popover
 * @param - valor do campo senha
 * @param - valor do campo de confirmação de senha
 */
function checks(clear = null, password = null, c_password = null) {
    if (password == null && c_password == null && clear != null) {
        document.getElementById('valid-alfa').className = "fa fa-angle-right";
        document.getElementById('valid-maiuscula').className = "fa fa-angle-right";
        document.getElementById('valid-especial').className = "fa fa-angle-right";
        document.getElementById('valid-min').className = "fa fa-angle-right";
        document.getElementById('valid-confirma').className = "fa fa-angle-right";
    } else {

        checkPassword(password, c_password);
    }
}

/* 
 *
 * Faz a validação da senha
 * @param valor do campo senha
 * @param valor do campo confirmar senha
 * @return 0/1
 */
function validaPassword(password, c_password) {

    if (!password.match(/[a-z]+/)) {
        return 0;
    }
    if (!password.match(/[0-9]+/)) {
        return 0;
    }
    if (!password.match(/[A-Z]+/)) {
        return 0;
    }
    if (!password.match(/[$@$!%*#?&_]+/)) {
        return 0;
    }
    if (password.length < 8) {
        return 0;
    }
    if (password != c_password) {
        return 0;
    }
    return 1;
}

/* 
 *
 * Abre os popover de validação de senha
 * @param - string do id do campo senha
 * @param - string do id do campo confirmar senha
 */
function password_popover(password, c_password) {
    $(password).popover({
        container: 'body',
        title: '<small><b>A senha deve conter:<b></small>',
        html: true,
        placement: 'top',
        trigger: 'focus',
        content:
            `<p>
        <small><i class="fa fa-angle-right" id="valid-min"></i> Minimo 8 digitos</small><br>
        <small><i class="fa fa-angle-right" id="valid-maiuscula"></i> Pelo menos 1 letra Maiuscula</small><br>
        <small><i class="fa fa-angle-right" id="valid-especial"></i> Pelo menos 1 caracter especial</small><br>
        <small><i class="fa fa-angle-right" id="valid-alfa"></i> Caracteres alfanuméricos</small><br>
        <small><i class="fa fa-angle-right" id="valid-confirma"></i> Confirmar senha</small><br>
        </p>
         <div class="progress">
        <div class="progress-bar" role="progressbar" id="progressPassword" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>`
    });

    $(c_password).popover({
        container: 'body',
        title: '<small>As senhas devem ser iguais</small>',
        html: true,
        placement: 'top',
        trigger: 'focus'
    });
}


//////////////////////////////////////////////////////

function jsMaskMoney(number) {
    let tmp = parseInt(number.replace(/[\D]+/g, '')) + '';
    let neg = (tmp < 0);

    if (tmp.length == 1) tmp = "0" + tmp;

    tmp = tmp.replace(/([0-9]{2})$/g, ",$1");
    if (tmp.length > 6)
        tmp = tmp.replace(/([0-9]{3}),([0-9]{2}$)/g, ".$1,$2");

    if (tmp.length > 9)
        tmp = tmp.replace(/([0-9]{3}).([0-9]{3}),([0-9]{2}$)/g, ".$1.$2,$3");

    if (tmp.length == 12)
        tmp = tmp.replace(/([0-9]{3}).([0-9]{3}).([0-9]{3}),([0-9]{2}$)/g, ".$1.$2.$3,$4");

    if (tmp.length > 12)
        tmp = tmp.replace(/([0-9]{3}).([0-9]{3}).([0-9]{3}).([0-9]{3}),([0-9]{2}$)/g, ".$1.$2.$3.$4,$5");

    if (tmp.indexOf(".") == 0) tmp = tmp.replace(".", "");
    if (tmp.indexOf(",") == 0) tmp = tmp.replace(",", "0,");

    return (neg ? '-' + tmp : tmp);
};

function dadosCnpj(cnpj) {

    if (cnpj != '') {
        cnpj = cnpj.replace(".", "").replace(".", "").replace("/", "").replace("-", "");

        var url = 'https://pharmanexo.com.br/API/ConsultaCNPJ/get/' + cnpj;

        $.get(url, function (data) {

            if (data.status != "ERROR") {

                if (data.situacao == 'ATIVA') {
                    $('#razao_social').val(data.nome);
                    $('#cnpj').val(data.cnpj);
                    $('#nome_fantasia').val(data.fantasia);
                    $("#complemento").val(data.complemento);
                    $("#estado").val(data.uf);
                    $("#telefone").val(data.telefone);
                    $("#email").val(data.email);
                    $("#bairro").val(data.bairro);
                    $("#rua").val(data.logradouro);
                    $("#endereco").val(data.logradouro);
                    $("#numero").val(data.numero);
                    $("#cep").val(data.cep);
                    $("#cidade").val(data.municipio);
                    $("#municipio").val(data.municipio);
                } else {
                    formWarning({
                        'type': 'error',
                        'message': "Está empresa está com a situação " + data.situacao + " na junta comercial."
                    })
                }
            }
        });
    }
}

function abrir(dochtml, largura, altura, scroll) {
    // verifica se a janela está aberta
    if (janela != null && !janela.closed) {
        // caso esteja aberta, foco na janelaF
        janela.focus;
    } else if (janela != null && janela.closed) {
        // se a janela foi fechada, limpo a variavel janela para permitir que ela seja re-aberta
        janela = null;
    }

    // só abre a janela se a variavel 'janela' é nula
    if (janela == null) {
        if (navigator.appName == "Netscape") {
            sec = window.open(dochtml, '_blank', 'scrollbars=' + scroll + ',toolbar=no,location=no,directories=no,status=no,menubar=no,resizable=no,scrollbars=yes,copyhistory=no,height=' + altura + ',width=' + largura + ',top=174,left=295');
            window.sec.focus();
        } else {
            janela = window.open(dochtml, '_blank', 'scrollbars=' + scroll + ',toolbar=no,location=no,directories=no,status=no,scrollbars=yes,menubar=no,resizable=no,copyhistory=no,height=' + altura + ',width=' + largura + ',top=174,left=295');
        }

    }
}

//
function carregar_formulario(formulario, arquivo) {
    var n_form = '#' + formulario;
    $(n_form).prop('action', arquivo);
    $(n_form).prop('method', 'post');
    $(n_form).submit();
}

//
function mask(val, mask) {
    var valorCampo = new Array;
    valorCampo = val.value.split('');

    var valorMask = new Array;
    valorMask = mask.split('');

    var maskared = '';
    var k = 0;
    for (var i = 0; i <= (valorMask.length) - 1; i++) {
        if (valorMask[i] == '#') {
            if (!(window.valorCampo[k] === undefined))
                maskared += valorCampo[k++];
        } else {
            if (!(window.valorMask[i] === undefined))
                maskared += valorMask[i];
        }
    }
    return maskared;
}

function mascaras(campo, tipo) {
    var componente = "#" + campo;
    switch (tipo) {
        case 'cpf':
            $(componente).mask("999.999.999-99", {completed: validaCPF($(this), $(this).val())});
            break;

        case 'cnpj':
            $(componente).mask("999.999.999/9999-99");
            break;

        case 'telefone':
            $(componente).mask("(99) 99999-9999");
            break;

        case 'data':
            $(componente).mask("99/99/9999", {completed: validaData($(this), $(this).val())});
            break;

        case 'data_nascimento':
            $(componente).mask("99/99/9999");
            $(componente).change(function (e) {
                validaDataNascimento($(this), $(this).val())
            });
            break;

        case 'cep':
            $(componente).mask("99999-999");
            break;

        default:

            break;
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

/* Função para verificar se o valor digitado é número 
Utilizar no onKeyPress
 */
function digitos(event) {
    if (window.event) {
        // IE 
        key = event.keyCode;
    } else if (event.which) {
        // netscape 
        key = event.which;
    }
    if (key != 8 || key != 13 || key < 48 || key > 57)
        return (((key > 47) && (key < 58)) || (key == 8) || (key == 13));

    return true;
}

function mascara(tipo, campo, teclaPress) {
    if (window.event) {
        var tecla = teclaPress.keyCode;
    } else {
        tecla = teclaPress.which;
    }

    var s = new String(campo.value);
    // Remove todos os caracteres à seguir: ( ) / - . e espaço, para tratar a string denovo. 
    s = s.replace(/(\.|\(|\)|\/|\-| )+/g, '');

    tam = s.length + 1;

    if (tecla != 9 && tecla != 8) {
        switch (tipo) {
            case 'cpf':
                if (tam > 3 && tam < 8)
                    campo.value = s.substr(0, 3) + '.' + s.substr(3, tam);
                if (tam >= 8 && tam < 11)
                    campo.value = s.substr(0, 3) + '.' + s.substr(3, 3) + '.' + s.substr(6, tam - 6);
                if (tam >= 10 && tam < 12)
                    campo.value = s.substr(0, 3) + '.' + s.substr(3, 3) + '.' + s.substr(6, 3) + '-' + s.substr(9, tam - 9);
                break;

            case 'nis':
                if (tam > 3 && tam < 8)
                    campo.value = s.substr(0, 3) + '.' + s.substr(3, tam);
                if (tam >= 9 && tam < 11)
                    campo.value = s.substr(0, 3) + '.' + s.substr(3, 5) + '.' + s.substr(8, tam - 8);
                if (tam >= 11 && tam < 12)
                    campo.value = s.substr(0, 3) + '.' + s.substr(3, 5) + '.' + s.substr(8, 8) + '-' + s.substr(11, tam - 11);

                break;

            case 'cnpj':
                if (tam > 2 && tam < 6)
                    campo.value = s.substr(0, 2) + '.' + s.substr(2, tam);
                if (tam >= 6 && tam < 9)
                    campo.value = s.substr(0, 2) + '.' + s.substr(2, 3) + '.' + s.substr(5, tam - 5);
                if (tam >= 9 && tam < 13)
                    campo.value = s.substr(0, 2) + '.' + s.substr(2, 3) + '.' + s.substr(5, 3) + '/' + s.substr(8, tam - 8);
                if (tam >= 13 && tam < 15)
                    campo.value = s.substr(0, 2) + '.' + s.substr(2, 3) + '.' + s.substr(5, 3) + '/' + s.substr(8, 4) + '-' + s.substr(12, tam - 12);
                break;

            case 'telefone':
                if (tam > 2 && tam < 4)
                    campo.value = '(' + s.substr(0, 2) + ') ' + s.substr(2, tam);
                if (tam >= 7 && tam < 11)
                    campo.value = '(' + s.substr(0, 2) + ') ' + s.substr(2, 4) + '-' + s.substr(6, tam - 6);
                if (tam >= 12)
                    campo.value = '(' + s.substr(0, 2) + ') ' + s.substr(2, 5) + '-' + s.substr(7, tam - 7);
                break;

            case 'hora':
                if (tam > 2 && tam < 4)
                    campo.value = s.substr(0, 2) + ':' + s.substr(2, tam);
                break;

            case 'data':
                if (tam > 2 && tam < 4)
                    campo.value = s.substr(0, 2) + '/' + s.substr(2, tam);
                if (tam > 4 && tam < 11)
                    campo.value = s.substr(0, 2) + '/' + s.substr(2, 2) + '/' + s.substr(4, tam - 4);
                break;

            case 'data_mes_ano':
                if (tam > 2)
                    campo.value = s.substr(0, 2) + '/' + s.substr(2, tam);
                break;

            case 'cep':
                if (tam > 5 && tam < 9)
                    campo.value = s.substr(0, 5) + '-' + s.substr(5, tam);
                break;
        }
    }
}

function validaCPF(campo, cpf) {
    erro = new String;
    if (cpf.length < 14)
        erro += "São necessários 14 digitos para verificação do CPF! \n\n";

    cpf = cpf.replace('.', '');
    cpf = cpf.replace('-', '');
    /*
	var nonNumbers = /\D/; 
    if (nonNumbers.test(cpf)) 
        erro += "A verificação de CPF suporta apenas Números! \n\n"; 
    
    if (cpf == "00000000000" || 
            cpf == "11111111111" || 
            cpf == "22222222222" || 
            cpf == "33333333333" || 
            cpf == "44444444444" || 
            cpf == "55555555555" || 
            cpf == "66666666666" || 
            cpf == "77777777777" || 
            cpf == "88888888888" || 
            cpf == "99999999999")
    { 
        erro += "Número de CPF inválido!";
        campo.focus();
        campo.value = "";
        return false;
    }
 
    var a = [];
    var b = new Number; 
    var c = 11; 
    for (i = 0; i < 11; i++){ 
        a[i] = cpf.charAt(i); 
        if (i < 9) 
            b += (a[i] * --c); 
    }
    if ((x = b % 11) < 2) 
        a[9] = 0; 
    else  
        a[9] = 11 - x; 
     
    b = 0; 
    c = 11; 
    for (y = 0; y < 10; y++) 
        b += (a[y] * c--); 
    if ((x = b % 11) < 2)  
        a[10] = 0; 
    else 
        a[10] = 11 - x; 
  
    status = a[9] + "" + a[10]; 
    if ((cpf.charAt(9) != a[9]) || (cpf.charAt(10) != a[10]))
        erro += "Digíto verificador com problema!"; 
  */
    if (erro.length > 0) {
        alert(erro);
        campo.focus();
        campo.value = "";
        return false;
    }

    return true;
}

function validaData(campo, valor) {
    if (valor) {
        var date = valor;
        var ardt = new Array;
        var ExpReg = new RegExp("(0[1-9]|[12][0-9]|3[01])/(0[1-9]|1[012])/[12][0-9]{3}");
        ardt = date.split("/");
        erro = false;
        if (date.search(ExpReg) == -1) {
            erro = true;
        } else if (((ardt[1] == 4) || (ardt[1] == 6) || (ardt[1] == 9) || (ardt[1] == 11)) && (ardt[0] > 30))
            erro = true;
        else if (ardt[1] == 2) {
            if ((ardt[0] > 28) && ((ardt[2] % 4) != 0))
                erro = true;
            if ((ardt[0] > 29) && ((ardt[2] % 4) == 0))
                erro = true;
        }
        if (erro) {
            alert("\"" + valor + "\" não é uma data válida!!!");
            campo.focus();
            campo.value = "";
            return false;
        }
        return true;
    }
}

function validaHora(campo, valor) {
    if (valor) {
        var hora = valor;
        var variavel = new Array;
        variavel = hora.split(":");
        erro = true;
        if (variavel[0] < 24)
            if (variavel[1] < 60) {
                erro = false;
            }
        if (variavel[1] == 0) {
            minutosString = "0" + variavel[1].toString();

            if (minutosString == "00")
                erro = true;
        }
        if (erro) {
            alert("\"" + valor + "\" não é uma hora válida!!!");
            campo.value = "";
            campo.focus();
            return false;
        }
        return true;
    }
}

function validaCEP(cep) {
    if (cep.length != 9) return false;
    var parte_1 = cep.substr(0, 5);
    var parte_2 = cep.substr(6, 8);
    if (parte_1.length != 5) return false;
    if (parte_2.length != 3) return false;
    if (cep.substr(5, 1) != "-") return false;
    return true;
}

function inicializa_checkbox(componente, valores) {
    if (valores) {
        var valor = valores.split(',');
        var maximo = valor.length;
        var i = 0;
        for (i = 0; i < maximo; i++)
            $('input[name="' + componente + '"]').each(function (index, element) {
                if (valor[i] == this.value) {
                    this.checked = 'checked';
                    return false;
                }
            });
    }
}

function limpa_checkbox(formulario) {
    for (i = 0; i < document.forms.form_tv.elements.length; i++)
        if (document.forms.form_tv.elements[i].type == "checkbox")
            document.forms.form_tv.elements[i].checked = false;
}

//menu cssmenu
(function ($) {

    $(document).ready(function () {
        $('#cssmenu').prepend('<div id="indicatorContainer"><div id="pIndicator"><div id="cIndicator"></div></div></div>');
        var activeElement = $('#cssmenu>ul>li:first');

        $('#cssmenu>ul>li').each(function () {
            if ($(this).hasClass('active')) {
                activeElement = $(this);
            }
        });


        var posLeft = activeElement.position();
        var elementWidth = activeElement.width();
        posLeft = posLeft + elementWidth / 2 - 6;
        if (activeElement.hasClass('has-sub')) {
            posLeft -= 6;
        }

        $('#cssmenu #pIndicator').css('left', posLeft);
        var element, leftPos, indicator = $('#cssmenu pIndicator');

        $("#cssmenu>ul>li").hover(function () {
            element = $(this);
            var w = element.width();
            if ($(this).hasClass('has-sub')) {
                leftPos = element.position().left + w / 2 - 12;
            } else {
                leftPos = element.position().left + w / 2 - 6;
            }

            $('#cssmenu #pIndicator').css('left', leftPos);
        }, function () {
            $('#cssmenu #pIndicator').css('left', posLeft);
        });

        $('#cssmenu>ul').prepend('<li id="menu-button"><a>Menu</a></li>');
        $("#menu-button").click(function () {
            if ($(this).parent().hasClass('open')) {
                $(this).parent().removeClass('open');
            } else {
                $(this).parent().addClass('open');
            }
        });

        // DataTable plugin default setting
        if ($.fn.dataTable) {
            console.log('dtable');
            $.extend(true, $.fn.dataTable.defaults, {
                pageLength: 25,
                processing: true,
                responsive: true,
                serverSide: true,
                oLanguage: {
                    'sEmptyTable': 'Nenhum registro encontrado',
                    'sInfo': 'Mostrando de _START_ até _END_ de _TOTAL_ registros',
                    'sInfoEmpty': 'Mostrando 0 até 0 de 0 registros',
                    'sInfoFiltered': '',
                    'sInfoPostFix': '',
                    'sInfoThousands': '.',
                    'sLengthMenu': '_MENU_ resultados por página',
                    'sLoadingRecords': 'Carregando...',
                    'sProcessing': 'Carregando registros...',
                    'sZeroRecords': 'Nenhum registro encontrado',
                    'sSearch': 'Pesquisar',
                    'oPaginate': {
                        'sNext': 'Próximo',
                        'sPrevious': 'Anterior',
                        'sFirst': 'Primeiro',
                        'sLast': 'Último'
                    },
                    'oAria': {
                        'sSortAscending': ': Ordenar colunas de forma ascendente',
                        'sSortDescending': ': Ordenar colunas de forma descendente'
                    }
                }
            });
        }
        ;

    });
})(jQuery);

//Função que coloca a data no menu --
function labelData() {
    var mydate = new Date()
    var year = mydate.getYear()
    if (year < 1000)
        year += 1900
    var day = mydate.getDay()
    var month = mydate.getMonth()
    var daym = mydate.getDate()
    if (daym < 10)
        daym = "0" + daym
    var dayarray = new Array("Domingo", "Segunda Feira", "Ter&ccedil;a Feira", "Quarta Feira", "Quinta Feira", "Sexta Feira", "S&aacute;bado")
    var montharray = new Array("Janeiro", "Fevereiro", "Mar&ccedil;o", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro")
    document.write("<small><font face='Arial' size='2'><b>" + dayarray[day] + ", " + daym + " de " + montharray[month] + " de " + year + "</b></font></small>")
}

//
function ResetFormValues(formulario) {
    $('#' + formulario).each(function () {
        this.reset();
    });
    //document.getElementById(form).reset();
}

//Função que coloca Hora e Minuto no menu --
function mostra_hora() {
    var time = new Date();
    var hour = time.getHours();
    var minute = time.getMinutes();
    var second = time.getSeconds();
    if (hour < 10)
        hour = "0" + hour;
    if (minute < 10)
        minute = "0" + minute;
    if (second < 10)
        second = "0" + second;
    var st = hour + ":" + minute + ":" + second;
    document.getElementById("hora").innerHTML = st;
}

//
/*
	Transferir para as respctivas rotinas
*/
function busca_cidades(id_estado) {
    $.post(base_url + "Endereco/busca_cidades", {
        id_estado: id_estado,
    }, function (data) {
        $('#id_cidade').html(data);
    });
};

//
function busca_bairros(id_cidade) {
    $.post(base_url + "Endereco/busca_bairros", {
        id_cidade: id_cidade,
    }, function (data) {
        $('#id_bairro').html(data);
    });
};

//
function busca_comunidades(id_bairro) {
    $.post(base_url + "Endereco/busca_comunidades", {
        id_bairro: id_bairro,
    }, function (data) {
        $('#id_comunidade').html(data);
    });
};

//
function busca_orgaos_publicos(orgao_publico) {
    $.post(base_url + "Orgao_publico/busca_orgaos_publicos", {
        orgao_publico: orgao_publico,
    }, function (data) {
        $('#detalhe').html(data);
    });
};

//
function busca_territorios(id_estado, id_cidade) {
    $.post(base_url + "Territorio/busca_territorios/" + id_estado + "/" + id_cidade, {
        id_estado: id_estado,
        id_cidade: id_cidade,
    }, function (data) {
        $('#detalhe').html(data);
    });
};

//
function busca_unidades(tipo_unidade, unidade) {
    $.post(base_url + "Unidade_publica/busca_unidades/" + tipo_unidade, {
        tipo_unidade: tipo_unidade,
        unidade: unidade,
    }, function (data) {
        $('#detalhe').html(data);
    });
};

//
function busca_unidade_patrimonios(id_unidade) {
    $.post(base_url + "Unidade_publica/busca_unidade_patrimonios", {
        id_unidade: id_unidade,
    }, function (data) {
        $('#unidade_patrimonios').html(data);
    });
};

// inicio modal unidade_patrimonio
function editar_unidade_patrimonio(id_patrimonio) {
    var link = "<a href='javascript:gravar_up(" + id_patrimonio + ")'><li class='btn btn-primary btn-xs' id='grava_p'><span role='button'>Gravar</span></li></a>";
    $.post(base_url + "Unidade_publica/editar_unidade_patrimonio/" + id_patrimonio, {
        id_patrimonio: id_patrimonio
    }, function (data) {
        $('#id_tipo_patrimonio').val(data.id_tipo_patrimonio);
        $('#patrimonio').val(data.patrimonio);
        if (data.data_entrada) $('#data_entrada').val(data.data_entrada.split("-").reverse().join("/"));
        $('#estado_conservacao').val(data.estado_conservacao);
        $('#origem').val(data.origem);
        if (data.data_saida) $('#data_saida').val(data.data_saida.split("-").reverse().join("/"));
        $('#descarte').val(data.descarte);
        $('#grava_p').html(link);
        $('#modal_patrimonio').modal('show');
    }, 'json');
};

//
function gravar_up(id_patrimonio) {
    $('#form_p').prop('action', base_url + "Unidade_publica/gravar_patrimonio/" + id_patrimonio);
    $('#form_p').prop('method', 'post');
    $('#form_p').submit();
}

//
function unid_patrimonio(id_unidade) {
    var link = "<a href='javascript:novo_unid_patrimonio(" + id_unidade + ")'><li class='btn btn-primary btn-xs' id='grava_p'><span role='button'>Gravar</span></li></a>";
    ResetFormValues('form_p');
    $.post(base_url + "Unidade_publica/novo_patrimonio/" + id_unidade);
    $('#grava_p').html(link);
    $('#modal_patrimonio').modal('show');
};

//
function novo_unid_patrimonio(id_unidade) {
    $('#form_p').prop('action', base_url + "Unidade_publica/novo_patrimonio/" + id_unidade);
    $('#form_p').prop('method', 'post');
    $('#form_p').submit();
};
// fim modal unidade_patrimonio
//
function busca_unidade_abrangencia(id_unidade) {
    $.post(base_url + "Unidade_publica/busca_unidade_abrangencia/" + id_unidade, {
        id_unidade: id_unidade,
    }, function (data) {
        $('#unidade_abrangencia').html(data);
    });
};

//inicio do modal unidade_territorio
function editar_unidade_abrangencia(id_unidade) {
    var link = "<a href='javascript:grava_ab(" + id_unidade + ")'><li class='btn btn-primary btn-xs' ><span role='button'>Gravar</span></li></a>";
    $('#grava_ab').html(link);
    $('#modal_area_abrangencia').modal('show');
};

//

function grava_ab(id_unidade) {
    $('#form_ab').prop('action', base_url + "Unidade_publica/grava_ab/" + id_unidade);
    $('#form_ab').prop('method', 'post');
    $('#form_ab').submit();
};
//fim do modal	unidade_territorio
//
function busca_unidade_servicos(id_unidade) {
    $.post(base_url + "Unidade_publica/busca_unidade_servicos/" + id_unidade, {
        id_unidade: id_unidade,
    }, function (data) {
        $('#unidade_servicos').html(data);
    });
};

function busca_unidade_modalidades(id_unidade) {
    $.post(base_url + "Unidade_publica/busca_unidade_modalidades/" + id_unidade, {
        id_unidade: id_unidade,
    }, function (data) {
        $('#unidade_modalidades').html(data);
    });
};

// inicio modal unidade_servicos_modalidades
function editar_unidade_servicos_modalidades(id_unidade) {
    var link = "<a href='javascript:grava_usm(" + id_unidade + ")'><li class='btn btn-primary btn-xs' id='grava_sm'><span role='button'>Gravar</span></li></a>";
    $('#grava_sm').html(link);
    $('#modal_servico_modalidade').modal('show');
};

//
function grava_usm(id_unidade) {
    $('#form_sm').prop('action', base_url + "Unidade_publica/grava_usm/" + id_unidade);
    $('#form_sm').prop('method', 'post');
    $('#form_sm').submit();
};
// fim modal unidade_servicos_modalidades
//
function busca_unidade_procedimentos(id_unidade) {
    $.post(base_url + "Unidade_publica/busca_unidade_procedimentos/" + id_unidade, {
        id_unidade: id_unidade,
    }, function (data) {
        $('#unidade_procedimentos').html(data);
    });
};

//inicio do modal	unidade_procedimentos
function editar_unidade_procedimentos(id_unidade) {
    var link = "<a href='javascript:grava_u_proc(" + id_unidade + ")'><li class='btn btn-primary btn-xs' id='grava_procedimentos'><span role='button'>Gravar</span></li></a>";
    $('#grava_procedimentos').html(link);
    $('#modal_procedimentos').modal('show');
};

//
function grava_u_proc(id_unidade) {
    $('#form_procedimento').prop('action', base_url + "Unidade_publica/grava_procedimentos/" + id_unidade);
    $('#form_procedimento').prop('method', 'post');
    $('#form_procedimento').submit();
};
//fim do modal	entidade_acoes
//
function busca_entidade_patrimonios(id_entidade) {
    $.post(base_url + "Entidade/busca_entidade_patrimonios", {
        id_entidade: id_entidade,
    }, function (data) {
        $('#entidade_patrimonios').html(data);
    });
};

//
function busca_entidade_territorios(id_entidade) {
    $.post(base_url + "Entidade/busca_entidade_territorios/" + id_entidade, {
        id_entidade: id_entidade,
    }, function (data) {
        $('#entidade_territorios').html(data);
    });
};

//
function busca_entidade_representantes(id_entidade) {
    $.post(base_url + "Entidade/busca_entidade_representantes", {
        id_entidade: id_entidade,
    }, function (data) {
        $('#entidade_representantes').html(data);
    });
};
//
// inicio modal entidade_representante
function editar_entidade_representante(id_erl, id_entidade, id_representante_legal) {

    var link = "<a href='javascript:gravar_erl(" + id_erl + "," + id_entidade + "," + id_representante_legal + ")'><li class='btn btn-primary btn-xs' id='grava_erl'><span role='button'>Gravar</span></li></a>";
    $.post(base_url + "Entidade/editar_entidade_representante/" + id_erl + "/" + id_entidade + "/" + id_representante_legal, {
        id_erl: id_erl,
        id_entidade: id_entidade,
        id_representante_legal: id_representante_legal,
    }, function (data) {

        $('#cargo').val(data.cargo);
        if (data.data_inicio_mandato) $('#data_inicio_mandato').val(data.data_inicio_mandato.split("-").reverse().join("/"));
        if (data.data_termino_mandato) $('#data_termino_mandato').val(data.data_termino_mandato.split("-").reverse().join("/"));
        $('#ativo').val(data.ativo);
        $('#representante_legal').val(data.representante_legal);
        if (data.data_nascimento) $('#data_nascimento').val(data.data_nascimento.split("-").reverse().join("/"));
        $('#e_mail_rl').val(data.e_mail);
        $('#telefone_rl').val(data.telefone);
        $('#cpf').val(data.cpf);
        $('#carteira_identidade').val(data.carteira_identidade);
        if (data.data_emissao_identidade) $('#data_emissao_identidade').val(data.data_emissao_identidade.split("-").reverse().join("/"));
        $('#id_estado_emissor_identidade').val(data.id_estado_emissor_identidade);
        $('#orgao_emissor_identidade').val(data.orgao_emissor_identidade);
        $('#grava_erl').html(link);
        $('#modal_representante').modal('show');
    }, 'json');
};

//
function gravar_erl(id_erl, id_entidade, id_representante_legal) {
    $('#form_erl').prop('action', base_url + "Entidade/gravar_erl/" + id_erl + "/" + id_entidade + "/" + id_representante_legal);
    $('#form_erl').prop('method', 'post');
    $('#form_erl').submit();
}

//
function representante(id_entidade) {
    var link = "<a href='javascript:novo_representante(" + id_entidade + ")'><li class='btn btn-primary btn-xs' id='grava_erl'><span role='button'>Gravar</span></li></a>";
    ResetFormValues('form_erl');
    $.post(base_url + "Entidade/novo_representante/" + id_entidade);
    $('#grava_erl').html(link);
    $('#modal_representante').modal('show');
};

//
function novo_representante(id_entidade) {
    $('#form_erl').prop('action', base_url + "Entidade/novo_representante/" + id_entidade);
    $('#form_erl').prop('method', 'post');
    $('#form_erl').submit();
};
// fim modal entidade_representante
//
function busca_entidade_procedimentos(id_entidade) {
    $.post(base_url + "Entidade/busca_entidade_procedimentos/" + id_entidade, {
        id_entidade: id_entidade,
    }, function (data) {
        $('#entidade_procedimentos').html(data);
    });
};

//
function busca_entidade_acoes(id_entidade) {
    $.post(base_url + "Entidade/busca_entidade_acoes/" + id_entidade, {
        id_entidade: id_entidade,
    }, function (data) {
        $('#entidade_acoes').html(data);
    });
};

//
function busca_entidade_cneas(id_entidade) {
    $.post(base_url + "Entidade/busca_entidade_ceneas", {
        id_entidade: id_entidade,
    }, function (data) {
        $('#entidade_cneas').html(data);
    });
};

// inicio modal entidade_patrimonio
function editar_entidade_patrimonio(id_patrimonio) {
    var link = "<a href='javascript:gravar_patrimonio(" + id_patrimonio + ")'><li class='btn btn-primary btn-xs' id='grava_p'><span role='button'>Gravar</span></li></a>";
    $.post(base_url + "Entidade/editar_entidade_patrimonio/" + id_patrimonio, {
        id_patrimonio: id_patrimonio
    }, function (data) {
        $('#id_tipo_patrimonio').val(data.id_tipo_patrimonio);
        $('#patrimonio').val(data.patrimonio);
        if (data.data_entrada) $('#data_entrada').val(data.data_entrada.split("-").reverse().join("/"));
        $('#estado_conservacao').val(data.estado_conservacao);
        $('#origem').val(data.origem);
        if (data.data_saida) $('#data_saida').val(data.data_saida.split("-").reverse().join("/"));
        $('#descarte').val(data.descarte);
        $('#grava_p').html(link);
        $('#modal_patrimonio').modal('show');
    }, 'json');
};

//
function gravar_patrimonio(id_patrimonio) {
    $('#form_p').prop('action', base_url + "Entidade/gravar_patrimonio/" + id_patrimonio);
    $('#form_p').prop('method', 'post');
    $('#form_p').submit();
}

//
function patrimonio(id_entidade) {
    var link = "<a href='javascript:novo_patrimonio(" + id_entidade + ")'><li class='btn btn-primary btn-xs' id='grava_p'><span role='button'>Gravar</span></li></a>";
    ResetFormValues('form_p');
    $.post(base_url + "Entidade/novo_patrimonio/" + id_entidade);
    $('#grava_p').html(link);
    $('#modal_patrimonio').modal('show');
};

//
function novo_patrimonio(id_entidade) {
    $('#form_p').prop('action', base_url + "Entidade/novo_patrimonio/" + id_entidade);
    $('#form_p').prop('method', 'post');
    $('#form_p').submit();
};
// fim modal entidade_patrimonio
//inicio do modal	entidade_territorio
function editar_entidade_territorios(id_entidade) {
    var link = "<a href='javascript:grava_et(" + id_entidade + ")'><li class='btn btn-primary btn-xs' id='grava_ter'><span role='button'>Gravar</span></li></a>";
    $('#grava_ab').html(link);
    $('#modal_area_abrangencia').modal('show');
};

//
function grava_et(id_entidade) {
    $('#form_ab').prop('action', base_url + "Entidade/grava_et/" + id_entidade);
    $('#form_ab').prop('method', 'post');
    $('#form_ab').submit();
};
//fim do modal	entidade_territorio
//inicio do modal	entidade_procedimentos
function editar_entidade_procedimentos(id_entidade) {
    var link = "<a href='javascript:grava_procedimentos(" + id_entidade + ")'><li class='btn btn-primary btn-xs' id='grava_procedimentos'><span role='button'>Gravar</span></li></a>";
    $('#grava_procedimentos').html(link);
    $('#modal_procedimentos').modal('show');
};

//
function grava_procedimentos(id_entidade) {
    $('#form_procedimento').prop('action', base_url + "Entidade/grava_procedimentos/" + id_entidade);
    $('#form_procedimento').prop('method', 'post');
    $('#form_procedimento').submit();
};
//fim do modal	entidade_acoes
//inicio do modal	entidade_acoes
function editar_entidade_acoes(id_entidade) {
    var link = "<a href='javascript:grava_ea(" + id_entidade + ")'><li class='btn btn-primary btn-xs' id='grava_ea'><span role='button'>Gravar</span></li></a>";
    $('#grava_ea').html(link);
    $('#modal_acoes').modal('show');
};

//
function grava_ea(id_entidade) {
    $('#form_ea').prop('action', base_url + "Entidade/grava_ea/" + id_entidade);
    $('#form_ea').prop('method', 'post');
    $('#form_ea').submit();
};
//fim do modal	entidade_acoes		
//
function busca_bairros_territorios(id_cidade) {
    $.post(base_url + "Territorio/busca_bairros_territorios/" + id_cidade, {
        id_cidade: id_cidade,
    }, function (data) {
        $('#bairros').html(data);
    });
};

//
function busca_estados_naturalidade(id_nacionalidade) {
    $.post(base_url + "Pessoa/busca_estados_naturalidade", {
        id_nacionalidade: id_nacionalidade,
    }, function (data) {
        $('#id_estado_naturalidade').html(data);
    });
};

//
function busca_cidades_naturalidade(id_estado_naturalidade) {
    $.post(base_url + "Pessoa/busca_cidades_naturalidade", {
        id_estado_naturalidade: id_estado_naturalidade,
    }, function (data) {
        $('#id_cidade_naturalidade').html(data);
    });
};

//
function busca_cidades_emissor_rc(id_estado) {
    $.post(base_url + "Endereco/busca_cidades", {
        id_estado: id_estado,
    }, function (data) {
        $('#id_cidade_emissor_rc').html(data);
    });
};

function busca_profissoes(pchave) {
    $.post(base_url + "Pessoa/busca_profissoes", {
        pchave: pchave,
    }, function (data) {
        $('#id_profissao').prop('disabled', false);
        $('#id_profissao').html(data);
    });
};

//
function busca_relatos(id_pessoa) {
    $.post(base_url + "Pessoa/busca_relatos/" + id_pessoa, {
        id_pessoa: id_pessoa,
    }, function (data) {
        $('#relatos').html(data);
    });
};

//
function busca_pessoas(nome_pessoa, mae, cpf, nascimento, id_bairro, situacao_municipal) {
    opc1 = $("input[name='opc1']:checked").val();
    opc2 = $("input[name='opc2']:checked").val();
    $.post(base_url + "Pessoa/busca_pessoas", {
        nome_pessoa: nome_pessoa,
        mae: mae,
        cpf: cpf,
        nascimento: nascimento,
        id_bairro: id_bairro,
        situacao_municipal: situacao_municipal,
        opc1: opc1,
        opc2: opc2,
    }, function (data) {
        $('#detalhe').html(data);
    });
};

//
function number_format(number, decimals, dec_point, thousands_sep) {
    var n = number,
        c = isNaN(decimals = Math.abs(decimals)) ? 2 : decimals;
    var d = dec_point == undefined ? "," : dec_point;
    var t = thousands_sep == undefined ? "." : thousands_sep,
        s = n < 0 ? "-" : "";
    var i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
    return 'R$' + s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
}

function numeros(event) {

    var tecla = event.key;

    if (!tecla.match(/[0-9-/*-+,=Cc]+/) && tecla != "Enter") {
        return false;
    } else {
        switch (tecla) {
            case '/':
                $("#divide").click();
                return false;
            case '*':
                $("#multiply").click();
                return false;
            case 'c':
                $("#clear").click();
                return false;
            case 'C':
                $("#clear").click();
                return false;
            case '=':
                $("#equals").click();
                return false;
            case 'Enter':
                $("#equals").click();
                return false;
        }
    }
}