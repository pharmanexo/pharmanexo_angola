<!DOCTYPE html>
<html lang="pt-BR">
<?php echo $header; ?>
<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>
<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <div class="row">
                        <div class="col-6">
                            <div class="pull-left">Filtros</div>
                        </div>
                        <div class="col-6 text-right">
                            <div class="pull-right">
                                <button type="button" id="clearFilters" class="btn btn-link"><i class="fas fa-trash"></i> Limpar Filtros</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-lg-3">
                        <div class="form-group">
                            <label for="">Código Cotação</label>
                            <input type="text" id="inputSearchCodigo" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-lg-3">
                        <div class="form-group">
                            <label for="">Comprador</label>
                            <input type="text" id="inputSearchComprador" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-lg-3">
                        <div class="form-group">
                            <label for="">UF</label>
                            <select id="selectEstados" class="form-control">
                                <option value="">Selecione...</option>
                                <option data-url="<?php echo base_url("fornecedor/cotacoes_oncoprod?uf=ac") ?>" <?php if (isset($_GET['uf']) && $_GET['uf'] == 'ac') echo 'selected' ?> value="AC">Acre</option>
                                <option data-url="<?php echo base_url("fornecedor/cotacoes_oncoprod?uf=al") ?>" <?php if (isset($_GET['uf']) && $_GET['uf'] == 'al') echo 'selected' ?> value="AL">Alagoas</option>
                                <option data-url="<?php echo base_url("fornecedor/cotacoes_oncoprod?uf=ap") ?>" <?php if (isset($_GET['uf']) && $_GET['uf'] == 'ap') echo 'selected' ?> value="AP">Amapá</option>
                                <option data-url="<?php echo base_url("fornecedor/cotacoes_oncoprod?uf=am") ?>" <?php if (isset($_GET['uf']) && $_GET['uf'] == 'am') echo 'selected' ?> value="AM">Amazonas</option>
                                <option data-url="<?php echo base_url("fornecedor/cotacoes_oncoprod?uf=ba") ?>" <?php if (isset($_GET['uf']) && $_GET['uf'] == 'ba') echo 'selected' ?> value="BA">Bahia</option>
                                <option data-url="<?php echo base_url("fornecedor/cotacoes_oncoprod?uf=ce") ?>" <?php if (isset($_GET['uf']) && $_GET['uf'] == 'ce') echo 'selected' ?> value="CE">Ceará</option>
                                <option data-url="<?php echo base_url("fornecedor/cotacoes_oncoprod?uf=df") ?>" <?php if (isset($_GET['uf']) && $_GET['uf'] == 'df') echo 'selected' ?> value="DF">Distrito Federal</option>
                                <option data-url="<?php echo base_url("fornecedor/cotacoes_oncoprod?uf=es") ?>" <?php if (isset($_GET['uf']) && $_GET['uf'] == 'es') echo 'selected' ?> value="ES">Espírito Santo</option>
                                <option data-url="<?php echo base_url("fornecedor/cotacoes_oncoprod?uf=go") ?>" <?php if (isset($_GET['uf']) && $_GET['uf'] == 'go') echo 'selected' ?> value="GO">Goiás</option>
                                <option data-url="<?php echo base_url("fornecedor/cotacoes_oncoprod?uf=ma") ?>" <?php if (isset($_GET['uf']) && $_GET['uf'] == 'ma') echo 'selected' ?> value="MA">Maranhão</option>
                                <option data-url="<?php echo base_url("fornecedor/cotacoes_oncoprod?uf=mt") ?>" <?php if (isset($_GET['uf']) && $_GET['uf'] == 'mt') echo 'selected' ?> value="MT">Mato Grosso</option>
                                <option data-url="<?php echo base_url("fornecedor/cotacoes_oncoprod?uf=ms") ?>" <?php if (isset($_GET['uf']) && $_GET['uf'] == 'ms') echo 'selected' ?> value="MS">Mato Grosso do Sul</option>
                                <option data-url="<?php echo base_url("fornecedor/cotacoes_oncoprod?uf=mg") ?>" <?php if (isset($_GET['uf']) && $_GET['uf'] == 'mg') echo 'selected' ?> value="MG">Minas Gerais</option>
                                <option data-url="<?php echo base_url("fornecedor/cotacoes_oncoprod?uf=pa") ?>" <?php if (isset($_GET['uf']) && $_GET['uf'] == 'pa') echo 'selected' ?> value="PA">Pará</option>
                                <option data-url="<?php echo base_url("fornecedor/cotacoes_oncoprod?uf=pb") ?>" <?php if (isset($_GET['uf']) && $_GET['uf'] == 'pb') echo 'selected' ?> value="PB">Paraíba</option>
                                <option data-url="<?php echo base_url("fornecedor/cotacoes_oncoprod?uf=pr") ?>" <?php if (isset($_GET['uf']) && $_GET['uf'] == 'pr') echo 'selected' ?> value="PR">Paraná</option>
                                <option data-url="<?php echo base_url("fornecedor/cotacoes_oncoprod?uf=pe") ?>" <?php if (isset($_GET['uf']) && $_GET['uf'] == 'pe') echo 'selected' ?> value="PE">Pernambuco</option>
                                <option data-url="<?php echo base_url("fornecedor/cotacoes_oncoprod?uf=pi") ?>" <?php if (isset($_GET['uf']) && $_GET['uf'] == 'pi') echo 'selected' ?> value="PI">Piauí</option>
                                <option data-url="<?php echo base_url("fornecedor/cotacoes_oncoprod?uf=rj") ?>" <?php if (isset($_GET['uf']) && $_GET['uf'] == 'rj') echo 'selected' ?> value="RJ">Rio de Janeiro</option>
                                <option data-url="<?php echo base_url("fornecedor/cotacoes_oncoprod?uf=rn") ?>" <?php if (isset($_GET['uf']) && $_GET['uf'] == 'rn') echo 'selected' ?> value="RN">Rio Grande do Norte</option>
                                <option data-url="<?php echo base_url("fornecedor/cotacoes_oncoprod?uf=rs") ?>" <?php if (isset($_GET['uf']) && $_GET['uf'] == 'rs') echo 'selected' ?> value="RS">Rio Grande do Sul</option>
                                <option data-url="<?php echo base_url("fornecedor/cotacoes_oncoprod?uf=ro") ?>" <?php if (isset($_GET['uf']) && $_GET['uf'] == 'ro') echo 'selected' ?> value="RO">Rondônia</option>
                                <option data-url="<?php echo base_url("fornecedor/cotacoes_oncoprod?uf=rr") ?>" <?php if (isset($_GET['uf']) && $_GET['uf'] == 'rr') echo 'selected' ?> value="RR">Roraima</option>
                                <option data-url="<?php echo base_url("fornecedor/cotacoes_oncoprod?uf=sc") ?>" <?php if (isset($_GET['uf']) && $_GET['uf'] == 'sc') echo 'selected' ?> value="SC">Santa Catarina</option>
                                <option data-url="<?php echo base_url("fornecedor/cotacoes_oncoprod?uf=sp") ?>" <?php if (isset($_GET['uf']) && $_GET['uf'] == 'sp') echo 'selected' ?> value="SP">São Paulo</option>
                                <option data-url="<?php echo base_url("fornecedor/cotacoes_oncoprod?uf=se") ?>" <?php if (isset($_GET['uf']) && $_GET['uf'] == 'se') echo 'selected' ?> value="SE">Sergipe</option>
                                <option data-url="<?php echo base_url("fornecedor/cotacoes_oncoprod?uf=to") ?>" <?php if (isset($_GET['uf']) && $_GET['uf'] == 'to') echo 'selected' ?> value="TO">Tocantins</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-12 col-lg-3">
                        <div class="form-group">
                            <label for="">Mostrar somente encontrados</label>
                            <br>
                            <div class="toggle-switch toggle-switch--blue">
                                <input type="checkbox" id="greenRadius" class="toggle-switch__checkbox">
                                <i class="toggle-switch__helper"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-12">
            <div class="enviado" style="width: 15px; height: 15px; border: 1px solid; border-radius: 20%; display: inline-block"></div>
            &nbsp;Respondida&nbsp;&nbsp;&nbsp;
            <div class="nenviado" style="width: 15px; height: 15px; border-radius: 20%; border: 1px solid; display: inline-block"></div>
            Sem responder&nbsp;
        </div>
        <br>

        <div id="content_cotacoes" data-urlcontent="<?php if (isset($url_cotacoes)) echo $url_cotacoes; ?>">
            <h3 hidden>Este módulo está em manutenção, redefinindo sincronisco com a Plataforma Síntese. <br>
                <small>Previsão de retorno: 04 de Novembro de 2019 as 12:00:00</small>
            </h3>
        </div>
    </div>
</div>
<?php echo $scripts; ?>

<script>
    var content = $('#content_cotacoes');
    var url_cotacoes = content.data('urlcontent');

    var url_info = "<?php echo $url_info ?>";
    var url_revisar = "<?php echo $url_revisar ?>";

    $(function () {
        get_cotacoes();

        $('.content__inner').find('[data-toggle="dropdown"]').dropdown();

        $('#selectEstados').on('change', function (e) {
            e.preventDefault();

            var url = $(this).children("option:selected").data('url');

            window.location.replace(url);
        });

        $('#greenRadius').on('click', function (e) {
            if ($('#greenRadius').is(':checked')) {
                $('#content_cotacoes').find('.card').css('display', 'none');
                $('#content_cotacoes').find('.greenBotton').parent().parent().parent().parent().css('display', 'block');
            } else {
                $('#content_cotacoes').find('.card').css('display', 'block');
            }
        });

        $('#clearFilters').click(function (e) {
            e.preventDefault();

            $('#inputSearchCodigo, #inputSearchComprador, #inputSearchDescricao').val('');
            $('#greenRadius').prop('checked', false);
            $('.card').each(function () {
                $(this).show();
            });
        });

        $('#inputSearchCodigo').on('keydown', function (e) {
            var txt = $(this).val();

            $('.searchCodigo').each(function () {
                if ($(this).text().toUpperCase().indexOf(txt.toUpperCase()) == -1) {
                    $(this).parent().parent().parent().parent().parent().parent().parent().hide();
                }
            });
        });

        $('#inputSearchComprador').on('keydown', function (e) {
            var txt = $(this).val();

            $('.searchEmpresa').each(function () {
                if ($(this).text().toUpperCase().indexOf(txt.toUpperCase()) == -1) {
                    $(this).parent().hide();
                }
            });
        });

        $('#inputSearchDescricao').on('change', function (e) {
            var txt = $(this).val().toUpperCase();

            $('.estado').each(function () {
                var value = $(this).text().toUpperCase();
                if (txt != value) {
                    $(this).parent().parent().hide();
                }
            });
        });
    });

    function get_cotacoes() {

        var spin = "<h4 id='loading' class='text-center'><i class='fas fa-spinner fa-spin '></i> Processando ... </h4>";
        content.html(spin);

        var message = ["Buscando estoques integranexo nível I",
            "Buscando estoques integranexo nível II",
            "Efetuando match e utilizando IA integranexo nível III",
            "IA utilizando regras e criando bioestatística",
            "IA acessando banco de preços regionais",
            "Integranexo nível I, II e III executados com sucesso",
            "Construindo vizualizações em tela"
        ];

        var i = 0;

        var spin = setInterval(function () {
            if (i > 7) clearInterval(spin);
            var spin = "<h4 id='loading' class='text-center'><i class='fas fa-spinner fa-spin '></i> " + message[i] + " </h4>";
            content.html(spin);
            i++;
        }, 10000);


        Pace.start();

        $.get(url_cotacoes, function (xhr) {
            clearInterval(spin);
            $('#loading').remove();


            $.each(xhr, function (i, v) {

                var markup = (v.bolinha == 1) ? '<div class=" float-right rounded-circle bg-success greenBotton mt-3" style="width: 25px; height: 25px; margin-top: -30px"></div>' : '';

                var id_aleatorio = new Date().getTime();
                var btn_group = `
                    <div class="col-6 justify-content-end">
                      ${markup}  
                        <a href="#" data-toggle="dropdown" title="" id="dropdownMenuLink_${id_aleatorio}" class="dropdown-toggle position-absolute" style="top:16px; right: 60px;">
                            <i class="fas fa-list-ul" role="button"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink_${id_aleatorio}">
                           <a class="dropdown-item" data-action="list" data-href="${url_info}${v.cd_cotacao}/1">Listar Produtos</a>
                           <a class="dropdown-item" data-action="details" id="teste" data-href="${url_info}${v.cd_cotacao}">Dados Cotação</a>
                        </div> 
                      
                    </div>
                    `;

                var resp = (v.respondido == 1) ? 'style="background-color: #aecbff"' : ''; 

                var card = $(`<div class="card"></div>`); 

                var cliente_nome = ( v.cliente.nome_fantasia ) ? v.cliente.nome_fantasia : v.cliente.razao_social;

                var cidade = (v.cliente.cidade == '' || v.cliente.cidade == null) ? '' : v.cliente.cidade + ' - ';

                var revisao = (v.revisao == 1) ? 'checked' : '';
                var revisaoTitle = (v.revisao == 1) ? 'Cotação revisada' : 'Marcar como revisada';

                var cardTitle = $(`
                    <div class='card-header searchEmpresa' ${resp}>
                        <div class="row d-flex flex-row">
                            <div class="col-6 justify-content-start">
                                <div class="pull-left">
                                    <div class="row col">
                                        <div class="checkbox">
                                            <input type="checkbox" id="${v.cd_cotacao}" data-cotacao="${v.cd_cotacao}" ${revisao}>
                                            <label class="checkbox__label" id="${v.cd_cotacao}" data-toggle="toggle" title="${revisaoTitle}" for="${v.cd_cotacao}"></label>
                                        </div>
                                        <a href="${v.link}" class="lnk_cot">
                                            <div class="card-title mr-5 searchCodigo">#${v.cd_cotacao}</div>
                                        </a> 
                                    </div>
                                    <br>  
                                    ${v.cliente.cnpj} - ${cliente_nome} | ${cidade}  <span class="estado">${v.cliente.estado}</span> 
                                </div>
                            </div> 
                           ${btn_group}
                        </div>
                    </div>`);

                var cardBody = $('<div class="card-body"></div>');

                var row = $('<div class="row"></div>');
                var col1 = $(`<div class="col-12 col-lg-3 searchDesc"><p class="text-primary ">Descrição</p>${v.Ds_Cotacao}</div>`);
                var col2 = $(`<div class="col-12 col-lg-3"><p class="text-primary">Data Início</p>${v.data_inicio}</div>`);
                var col3 = $(`<div class="col-12 col-lg-3"><p class="text-primary">Data Fim</p>${v.data_fim}</div>`);
                var col4 = $(`<div class="col-12 col-lg-3 text-center"><p class="text-primary">Qtde. Itens</p>${v.itens}</div>`);

                row.append(col1, col2, col3, col4);
                cardBody.append(row);

                card.append(cardTitle, cardBody);

                content.append(card);
                Pace.stop();
            });

            $('[data-action]').click(function (e) {
                e.preventDefault();

                var url = $(this).data('href');

                newwindow=window.open(url,"dados cotação",'height=400,width=800');
                if (window.focus) {newwindow.focus()}

                return false;
            });

            $('[data-cotacao]').on('change', function (e) {
                e.preventDefault();

                var s = ( $(this).prop("checked") == true  ) ? "1" : "0";


               if ( s == 1) {
                    $(`label#${$(this).data('cotacao')}`).attr('title', 'Cotação revisada');
               } else {
                    $(`label#${$(this).data('cotacao')}`).attr('title', 'Marcar como revisada');
               }

                $.ajax({
                    url: url_revisar + $(this).data('cotacao'),
                    type: 'post',
                    data: { status: s },
                    beforeSend: function(jqXHR, settings) { 
                    },
                    success: function(xhr) {
                        formWarning(xhr);
                    },
                    error: function(xhr) {
                        console.log(xhr);
                    }
                })

            });

            $('.lnk_cot').click(function () {
                $(this).html("<h3><i class='fas fa-spin fa-spin'></i> Aguarde carregando Cotação... </h3>");
                Pace.restart();
            });
        }, 'JSON');
    }
</script>
</body>

</html>