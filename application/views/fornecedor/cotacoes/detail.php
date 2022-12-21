<!DOCTYPE html>
<html lang="pt-BR">
<?php echo $header; ?>

<body class="bg-light">
    <?php echo $navbar; ?>
    <?php echo $sidebar; ?>
    <div class="content">
        <?php echo $heading; ?>
        <div class="content__inner">

            <input type="hidden" name="integrador" id="integrador" value="<?php echo $integrador; ?>"> <input type="hidden" name="cd_cotacao" id="cd_cotacao" value="<?php if (isset($cotacao['cd_cotacao'])) echo $cotacao['cd_cotacao']; ?>">
            <input type="hidden" name="id_cotacao" id="id_cotacao" value="<?php if (isset($cotacao['id_cotacao'])) echo $cotacao['id_cotacao']; ?>"> <input type="hidden" name="dt_inicio_cotacao" id="dt_inicio_cotacao" value="<?php if (isset($cotacao['data_inicio'])) echo $cotacao['data_inicio']; ?>">
            <input type="hidden" name="dt_fim_cotacao" id="dt_fim_cotacao" value="<?php if (isset($cotacao['data_fim'])) echo $cotacao['data_fim']; ?>"> <input type="hidden" name="cnpj_comprador" id="cnpj_comprador" value="<?php if (isset($cotacao['cnpj'])) echo $cotacao['cnpj']; ?>">
            <input type="hidden" name="razao_social" id="razao_social" value="<?php if (isset($cotacao['cliente']['razao_social'])) echo $cotacao['cliente']['razao_social']; ?>">
            <input type="hidden" name="id_cliente" id="id_cliente" value="<?php if (isset($cotacao['cliente']['id'])) echo $cotacao['cliente']['id']; ?>"> <input type="hidden" name="id_estado" id="id_estado" value="<?php if (isset($cotacao['estado']['id'])) echo $cotacao['estado']['id']; ?>"> <input type="hidden" name="uf_cotacao" id="uf_cotacao" value="<?php if (isset($cotacao['uf_cotacao'])) echo $cotacao['uf_cotacao']; ?>">

            <?php if ($cotacao['data_fim'] < date('Y-m-d H:i:s', strtotime("-1 hour"))) : ?>
                <div class="alert alert-danger" role="alert"><i class="fas fa-exclamation-triangle"></i> Esta cotação se
                    encontra encerrada.
                </div>
            <?php endif; ?>

            <!-- Cabeçalho -->
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col text-left">
                            <div class="checkbox">
                                <input type="checkbox" value="1" id="revisarCotacao" data-integrador="<?php echo $integrador; ?>" data-cotacao="<?php echo $cotacao['cd_cotacao']; ?>" <?php if ($cotacao['revisao'] == 1) echo 'checked' ?>>
                                <label class="checkbox__label mt-2" id="<?php echo $cotacao['cd_cotacao']; ?>" for="revisarCotacao"> <?php echo ($cotacao['revisao'] == 1) ? 'Cotação revisada' : 'Marcar como revisada'; ?> </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="row">
                        <div class="col-9">

                            <div class="form-group">
                                <label>Comprador</label>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon2">
                                            <i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                    <?php $nome_comprador = (!empty($cotacao['cliente']['nome_fantasia'])) ? $cotacao['cliente']['nome_fantasia'] : $cotacao['cliente']['razao_social']; ?>
                                    <input type="text" class="form-control" value="<?php echo $cotacao['cliente']['cnpj'] . ' - ' . $nome_comprador; ?>" disabled>
                                </div>

                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label>Cidade/UF</label>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon2"><i class="fas fa-city"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="<?php echo (!empty($cotacao['cliente']['cidade'])) ? "{$cotacao['cliente']['cidade']} - {$cotacao['cliente']['estado']}" : $cotacao['cliente']['estado']; ?>" disabled>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>Data Inicio</label>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon2"><i class="far fa-calendar-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="<?php if (isset($cotacao['data_inicio'])) echo date('d/m/Y H:i:s', strtotime($cotacao['data_inicio'])); ?>" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label>Data Fim</label>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon2"><i class="far fa-calendar-alt"></i></span>
                                    </div>

                                    <input type="text" class="form-control" value="<?php if (isset($cotacao['data_fim'])) echo date('d/m/Y H:i:s', strtotime($cotacao['data_fim'])); ?>" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label>Condição de Pagamento</label>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon2">
                                            <i class="far fa-handshake"></i>
                                        </span>
                                    </div>

                                    <input type="text" class="form-control" value="<?php if (isset($cotacao['condicao_pagamento'])) echo $cotacao['condicao_pagamento']; ?>" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label>Total de Itens</label>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon2">
                                            <i class="fas fa-clipboard-list"></i>
                                        </span>
                                    </div>

                                    <input type="text" class="form-control" value="<?php if (isset($cotacao['itens'])) echo $cotacao['itens']; ?>" disabled>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <label>Forma de Pagamento</label> <select class="select2" id="id_forma_pagamento" name="id_forma_pagamento">
                                <?php foreach ($select_formas_pagamento as $f) : ?>
                                    <option value="<?php echo $f['id']; ?>" <?php if (isset($forma_pagamento) && $forma_pagamento == $f['id']) echo 'selected'; ?>><?php echo $f['descricao']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label>Prazo de Entrega</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="prazo_entrega" name="prazo_entrega" value="<?php if (!empty($prazo_entrega)) echo $prazo_entrega ?>">
                                <div class="input-group-append">
                                    <div class="input-group-text bg-light">Dias</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="obs">Observações da cotação</label> <input type="text" name="obs" id="obs" class="form-control" maxlength="<?php echo (strtoupper($integrador) == 'SINTESE') ? '500' : '300'; ?>" value="<?php if (isset($observacao)) echo $observacao ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            <!-- Legenda -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="enviado" style="width: 15px; height: 15px; border-radius: 20%; border: 1px solid; display: inline-block"></div>
                    &nbsp;Respondida&nbsp;&nbsp;
                    <div class="nenviado" style="width: 15px; height: 15px; border-radius: 20%; border: 1px solid; display: inline-block"></div>
                    Sem responder&nbsp;&nbsp;
                    <div class="nencontrado" style="width: 15px; height: 15px; border-radius: 20%; border: 1px solid; display: inline-block"></div>
                    Produto não cadastrado
                </div>
            </div>

            <!-- Lista de Produtos -->
            <?php $this->load->view("fornecedor/cotacoes/list"); ?>

        </div>
    </div>

    <div class="modal fade" id="obsModal" tabindex="-1" role="dialog" aria-labelledby="obsModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="obsModalLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <input type="hidden" id="target" class="modal-obs"> <textarea class="form-control" name="obsProduto" id="obsProduto"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" id="btn_obs" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="historicoModal" tabindex="-1" role="dialog" aria-labelledby="historicoModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historicoModalLabel"></h5>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <thead>
                            <th>Cotação</th>
                            <th>Oferta (R$)</th>
                            <th>Registrado em</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="upgradeModal" tabindex="-1" role="dialog" aria-labelledby="historicoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historicoModalLabel"></h5>
                </div>
                <div class="modal-body modalUpgradeDePara">
                    <div class="upgradeModal"></div>
                    <div class="col-12 mt-2 text-right">
                        <button type="button" href id="btnCombinarUpgrade" style="position: relative;z-index:1;width:100px;height: 40px;right: 40px;top: 11px;" title="Combinar Produtos" class="btn btn-primary btnCombinarUpgrade" data-original-title="Combinar Produtos">
                            <i style="font-size:20px;padding-top: 3px;" class="fas fa-random"></i>
                        </button>
                    </div>

                    <form>
                        <div class="card">
                            <div class="card-header">
                            </div>
                            <div class="card-body">
                                <div class="table" style="margin-top: -110px;">
                                    <table id="data-tableUpgradeDePara" class="table w-100 table-hover data-tableUpgradeDePara" data-url="<?php echo $datatables; ?>" data-url2="<?php echo $url_combinar; ?>">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Código</th>
                                                <th>Produto</th>
                                                <th>Marca</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>''
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <?php echo $scripts; ?>

    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

    <script>
        var url_historico = "<?php if (isset($url_historico)) echo $url_historico; ?>";
        var url_ocultar = "<?php if (isset($url_ocultar)) echo $url_ocultar; ?>";
        var url_revisar = "<?php if (isset($url_revisar)) echo $url_revisar; ?>";
        var url_price = "<?php if (isset($url_price)) echo $url_price; ?>";
        var url_restricao = "<?php if (isset($url_restricao)) echo $url_restricao; ?>";
        var form_action = "<?php if (isset($form_action)) echo $form_action; ?>";
        var url_lista = "<?php if (isset($url_lista)) echo $url_lista; ?>";

        $(function() {
            <?php //foreach ($cotacao['produtos'] as $k => $produto) : 
            ?>

            <?php // endforeach; 
            ?>

            $('.btn_depara').click(function() {
                //console.log($(this).parent().parent());
                var idElem = $(this).data('idelem');
                var produto = $(this).data('produto');
                var cod_prod = $(this).data('codproduto');

                if (!$.fn.DataTable.isDataTable('#data-tableDePara' + idElem)) {
                    loadDatatables(idElem, produto, cod_prod);
                }
            });

            $('.btn_upgradeDePara').tooltip();
            $('.btn_upgradeDePara').click(function() {
                //console.log($(this).parent().parent());
                var idElemU = $(this).data('idelemu');
                var produtoU = $(this).data('produtou');
                var cod_prodU = $(this).data('codprodutou');
                var idsintese = $(this).data('sintese');

                $('.btnCombinarUpgrade').attr('id', 'btnCombinarUpgrade' + idElemU);
                $('.data-tableUpgradeDePara').attr('id', 'data-tableUpgradeDePara' + idElemU);
                $('.upgradeModal').text(produtoU);
                if (!$.fn.DataTable.isDataTable('#data-tableUpgradeDePara' + idElemU)) {
                    loadDatatableUpgrade(idElemU, produtoU, cod_prodU, idsintese);
                }
                if ($('.modalUpgradeDePara table').DataTable()) {
                    $('.modalUpgradeDePara table').DataTable().destroy();
                    setTimeout(function() {
                        loadDatatableUpgrade(idElemU, produtoU, cod_prodU, idsintese);
                    }, 0);
                }

                console.log(idElemU, produtoU, cod_prodU, idsintese);
            });

            $("#btnCount").html($('[data-check]:checked').length);

            $('#btn_obs').on('click', function() {
                // Pega o Valor do campo textarea
                var obs = $('#obsProduto').val();

                // Pega o valor do campo hidden no modala
                var target = $('#target').val();

                // Atualiza o input hidden de observacao do formulario
                $(`#${target}`).val(obs);

                //verificar se o checkbox está marcado
                var key = $(this).data('key')
                if ($(`input[data-key="${key}"]`).is(':checked') === false) {

                    formWarning({
                        type: 'warning',
                        message: 'Marque o produto para salvar a observação',
                    });


                    return false;
                }

                //Faz a requisição para o controller
                save_item($(this).data('key'));

                $('#obsModal').modal('hide');


            });

            $('#obsModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var modal = $(this);


                // Passa o valor $key do loop em list.php para o botão
                $("#btn_obs").attr('data-key', button.attr('data-key'));

                // Define o value do campo textarea pelo valor do campo oculto do form
                $('#obsProduto').val($('#' + button.data('produto')).val());

                modal.find('.modal-title').text('Nova Observação');
                // Passa o ID do campo observacao do form para o value do campo oculto do modal
                modal.find('.modal-obs').val(button.data('produto'));
            });

            $('#upgradeModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var upgradeModal = $(this);


                var row = button.parent().parent().parent().parent();

                var dropdown = row.find('select');
            }).on('hidden.bs.modal', function(event) {
                var upgradeModal = $(this);

                upgradeModal.find('tbody').html('');
            });

            $('#historicoModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var modal = $(this);


                var row = button.parent().parent().parent().parent();

                var dropdown = row.find('select');

                var data = {
                    id_fornecedor: dropdown.val(),
                    codigo: button.data('cod'),
                    id_cliente: button.data('cliente'),
                };

                $.post(url_historico, data, function(xhr) {

                        if (xhr.data.length > 0) {
                            modal.find('.modal-title').text("Histórico de ofertas - Preço Médio: R$" + xhr.media);
                            $.each(xhr.data, function(index, value) {
                                modal.find('tbody').append(`<tr><td>${value.cd_cotacao}</td><td>${value.preco_marca}</td><td>${value.data}</td></tr>`);
                            })
                        } else {
                            modal.find('.modal-title').text("Histórico de ofertas");
                            modal.find('tbody').append(`<tr><td colspan="3">Não encontramos ofertas anteriores para este produto.</td></tr>`);
                        }
                    }, 'JSON')
                    .fail(function(xhr) {
                        console.log(xhr);
                    });
            }).on('hidden.bs.modal', function(event) {
                var modal = $(this);

                modal.find('tbody').html('');
            });

            $('#respostaCotacao').submit(function() {
                var check = [];

                $("input:checked").not("#revisarCotacao, .olck, .restritock, .semestoqueck").each(function() {
                    check.push($(this).id);
                });

                var action = $('#respostaCotacao').attr('action');

                if (check.length < 1 && action.indexOf('/1') == -1) {
                    formWarning({
                        type: 'warning',
                        message: "Nenhum produto selecionado!"
                    });
                    event.preventDefault();
                }
            });

            $('#btn_ocultar').on('click', function(e) {
                e.preventDefault();

                var text = "";

                <?php if ($cotacao['oculto'] == 0) : ?>
                    text = "Tem certeza que deseja ocultar a cotação?";
                <?php else : ?>
                    text = "Tem certeza que deseja desocultar a cotação?";
                <?php endif; ?>

                Swal.fire({
                    title: text,
                    text: "",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sim',
                    cancelButtonText: 'Não, cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {

                        $.post(url_ocultar, {}, function(xhr) {
                                formWarning(xhr);
                                setTimeout(function() {
                                    window.location.href = url_lista
                                }, 1500);
                            }, 'JSON')
                            .fail(function(xhr) {});
                    }
                })
            })

            $('[data-desconto]').each(function(i, v) {
                var me = $(v);

                me.on('blur', function() {

                    var tr = $(this).parent().parent();

                    var campo_desconto = tr.find('input#desconto');
                    var campo_preco_unitario = tr.find('input#preco_unitario');
                    var campo_precocaixa = tr.find('input#preco_caixa');

                    var quantidade_unidade = campo_desconto.data('qnt');
                    var preco_unidade = $(this).data('precounidade');

                    // Converte os campos pra float (troca a virgula por ponto)
                    preco_unidade = preco_unidade.replace(".", "").replace(",", ".");
                    var preco = campo_preco_unitario.val().replace(".", "").replace(",", ".");
                    var desconto = campo_desconto.val().replace(".", "").replace(",", ".");
                    var preco_caixa = campo_precocaixa.data('precocaixa').replace(".", "").replace(",", ".");

                    if (typeof preco_unidade == 'string') {
                        preco_unidade = parseFloat(preco_unidade);
                    }
                    if (typeof preco == 'string') {
                        preco = parseFloat(preco);
                    }
                    if (typeof desconto == 'string') {
                        desconto = parseFloat(desconto);
                    }
                    if (typeof preco_caixa == 'string') {
                        preco_caixa = parseFloat(preco_caixa);
                    }


                    if (desconto == '' || desconto == 0 || desconto == '0,00') {
                        campo_desconto.val(0);
                        campo_desconto.maskMoney({
                            thousands: ".",
                            decimal: ",",
                            precision: 2
                        }).maskMoney('mask');

                        campo_preco_unitario.val(mascaraValor(preco_unidade.toFixed(4)));
                        campo_preco_unitario.maskMoney({
                            thousands: ".",
                            decimal: ",",
                            precision: 4
                        }).maskMoney('mask');

                        campo_precocaixa.val(mascaraValor(preco_caixa.toFixed(4)));
                        campo_precocaixa.maskMoney({
                            thousands: ".",
                            decimal: ",",
                            precision: 4
                        }).maskMoney('mask');
                    } else {

                        var result = eval(`${preco_unidade} - (${preco_unidade} * (${desconto} / 100) )`);

                        campo_preco_unitario.val(mascaraValor(result.toFixed(4)));
                        campo_preco_unitario.maskMoney({
                            thousands: ".",
                            decimal: ",",
                            precision: 4
                        }).maskMoney('mask');


                        var resultado = eval(`${result} * ${quantidade_unidade}`);

                        campo_precocaixa.val(mascaraValor(resultado.toFixed(4)));
                        campo_precocaixa.maskMoney({
                            thousands: ".",
                            decimal: ",",
                            precision: 4
                        }).maskMoney('mask');
                    }
                });
            });

            $('[data-precocaixa]').each(function(i, v) {
                var me = $(v);

                me.on('blur', function() {

                    var tr = $(this).parent().parent();

                    var campo_desconto = tr.find('input#desconto');
                    var campo_preco_unitario = tr.find('input#preco_unitario');
                    var campo_preco_caixa = tr.find('input#preco_caixa');

                    var quantidade_unidade = campo_desconto.data('qnt');

                    // Valor vindo do backend
                    var preco_caixa = $(this).data('precocaixa').replace(".", "").replace(",", ".");

                    // Valor inserido no input
                    var preco = campo_preco_caixa.val().replace(".", "").replace(",", ".");

                    if (preco != preco_caixa) {

                        preco_caixa = parseFloat(preco_caixa);
                        preco = parseFloat(preco);

                        var subtracao = preco_caixa - preco;
                        var divisao = subtracao / preco_caixa;
                        var result = divisao * 100;


                        if (result < 1) {
                            result = 0;
                        }

                        campo_desconto.val(Math.round(result));
                        campo_desconto.maskMoney({
                            thousands: ".",
                            decimal: ",",
                            precision: 2
                        }).maskMoney('mask');

                        // campo preco unitario
                        var resultado = preco / quantidade_unidade;

                        campo_preco_unitario.val(mascaraValor(resultado.toFixed(4)));
                        campo_preco_unitario.maskMoney({
                            thousands: ".",
                            decimal: ",",
                            precision: 4
                        }).maskMoney('mask');
                    } else {

                        // campo_desconto.val(0);
                        // campo_desconto.maskMoney({
                        //     thousands: ".",
                        //     decimal: ",",
                        //     precision: 2
                        // }).maskMoney( 'mask' );

                        // campo_precocaixa.val(mascaraValor(campo_precocaixa.data('prcaixa').toFixed(4)));
                        // campo_precocaixa.maskMoney({
                        //     thousands: ".",
                        //     decimal: ",",
                        //     precision: 4
                        // }).maskMoney( 'mask' );
                    }
                });
            });

            $('[data-preco]').each(function(i, v) {
                var me = $(v);

                me.on('blur', function() {

                    var tr = $(this).parent().parent();

                    var campo_desconto = tr.find('input#desconto');
                    var campo_preco_unitario = tr.find('input#preco_unitario');
                    var campo_precocaixa = tr.find('input#preco_caixa');

                    var quantidade_unidade = campo_desconto.data('qnt');

                    var preco_unitario = campo_preco_unitario.val().replace('.', '').replace(",", ".");
                    var preco_caixa = campo_precocaixa.data('precocaixa').replace('.', '').replace(",", ".");

                    if (preco_unitario != '' && preco_unitario != 0 && preco_unitario != "0.0000") {

                        var resultado = preco_unitario * quantidade_unidade;

                        campo_precocaixa.val(mascaraValor(resultado.toFixed(4)));
                        campo_precocaixa.maskMoney({
                            thousands: ".",
                            decimal: ",",
                            precision: 4
                        }).maskMoney('mask');

                        // desconto
                        var subtracao = preco_caixa - resultado;
                        var divisao = subtracao / preco_caixa;
                        var result = divisao * 100;

                        campo_desconto.val(Math.round(result));
                        campo_desconto.maskMoney({
                            thousands: ".",
                            decimal: ",",
                            precision: 2
                        }).maskMoney('mask');
                    } else {
                        campo_precocaixa.val(0);
                        campo_precocaixa.maskMoney({
                            thousands: ".",
                            decimal: ",",
                            precision: 4
                        }).maskMoney('mask');

                        campo_desconto.val(100);
                        campo_desconto.maskMoney({
                            thousands: ".",
                            decimal: ",",
                            precision: 2
                        }).maskMoney('mask');
                    }

                    if (tr.find(`[data-key="${$(this).data('key')}"]`).prop('checked') == true) {

                        save_item($(this).data('key'));
                    }
                });
            });

            $('#revisarCotacao').on('change', function(e) {
                e.preventDefault();

                var s = ($(this).prop("checked") == true) ? "1" : "0";

                if (s == 1) {
                    $(`label#${$(this).data('cotacao')}`).text('Cotação revisada');
                } else {
                    $(`label#${$(this).data('cotacao')}`).text('Marcar como revisada');
                }

                var integrador = $(this).data('integrador');

                $.ajax({
                    url: url_revisar + $(this).data('cotacao'),
                    type: 'post',
                    data: {
                        status: s,
                        integrador: integrador
                    },
                    beforeSend: function(jqXHR, settings) {},
                    success: function(xhr) {
                        formWarning(xhr);
                    },
                    error: function(xhr) {
                        console.log(xhr);
                    }
                })
            });

            $("[data-restricao]").change(function(index, element) {

                var card = $(this).parent().parent().parent().parent().parent();

                var inputs = card.find('input').not('.restritock');
                var dropdown = card.find('select');
                var links = card.find('a');

                if ($(this).prop("checked") == true) {

                    $(`label[for=${$(this).prop('id')}]`).attr('data-original-title', 'Produto com restrição');

                    $.each(inputs, function(index, input) {

                        if ($(input).prop('checked') == true) {
                            $(input).prop('checked', false);
                        }

                        if ($(this).attr('type') == 'checkbox') {

                            $(this).attr("disabled", true).addClass("desativado");
                        } else {

                            $(this).attr("readonly", true);
                        }
                    });

                    dropdown.attr("readonly", "readonly");


                    $.each(links, function(index, value) {
                        $(this).attr("style", 'pointer-events: none');
                    });

                    // Salva a restricao e remove os itens
                    save_item($(this).data('key'));
                } else {

                    $(`label[for=${$(this).prop('id')}]`).attr('data-original-title', 'Marcar restrição para este produto');


                    inputs = card.find('input').not('.restritock');
                    links = card.find('a').not('.restritock');

                    $.each(inputs, function(index, value) {

                        if ($(this).attr('type') == 'checkbox') {

                            $(this).attr("disabled", false).removeClass("desativado");;
                        } else {

                            $(this).attr("readonly", false);
                        }
                    });

                    dropdown.attr("readonly", false);

                    $.each(links, function(index, value) {
                        $(this).attr("style", '');
                    });

                    delete_restriction($(this).data('key'));
                }
            });

            $("[data-ol]").change(function(index, element) {

                var card = $(this).parent().parent().parent().parent().parent();

                var inputs = card.find('input').not('.restritock');
                var dropdown = card.find('select');
                var links = card.find('a');

                if ($(this).prop("checked") == true) {

                    $(`label[for=${$(this).prop('id')}]`).attr('data-original-title', 'Operação Logística');
                } else {

                    $(`label[for=${$(this).prop('id')}]`).attr('data-original-title', 'Marcar operação logística');
                }
            });

            $("[data-semestq]").change(function(index, element) {

                if ($(this).prop("checked") == true) {

                    $(`label[for=${$(this).prop('id')}]`).attr('data-original-title', 'Sem estoque');

                } else {

                    $(`label[for=${$(this).prop('id')}]`).attr('data-original-title', 'Marcar como sem estoque');
                }
            });

            $("[data-selectfornecedor]").change(function(index, element) {

                console.log('entrou');

                var row = $(this).parent().parent();
                var inputs = row.find("input")
                var links = row.find('a');

                var estoque = $(this).data($(this).val());
                var qtd_solicitada = row.data('qtdsolicitada');
                var codigo = $(this).data('codigo');

                $.ajax({
                    url: url_price,
                    type: 'post',
                    data: {
                        codigo: codigo,
                        id_fornecedor: $(this).val(),
                        id_estado: $("#id_estado").val(),
                        id_cliente: $("#id_cliente").val()
                    },
                    success: function(xhr) {

                        if (xhr.type == 'success') {

                            // Retira o alerta e os disabled dos campos
                            row.prev().find("#label_alert").html("");

                            // Resposta
                            var precocaixa = xhr.data['preco_caixa'];
                            var preco_unidade = xhr.data['preco_unidade'];
                            var qnt = xhr.data['qtd'];
                            var marca = xhr.data['marca'];

                            // Campos Input
                            var input_desconto = row.find('[data-desconto]');
                            var input_precocx = row.find('[data-precocaixa]');
                            var input_precounit = row.find('[data-preco]');


                            // data attribute CAMPO DESCONTO
                            input_desconto.data('precounidade', preco_unidade);
                            input_desconto.data('qnt', qnt);
                            input_desconto.val(0);
                            input_desconto.maskMoney({
                                thousands: ".",
                                decimal: ",",
                                precision: 2
                            }).maskMoney('mask');

                            // data attribute CAMPO PRECO CAIXA
                            input_precocx.data('precocaixa', precocaixa);
                            input_precocx.val(mascaraValor(precocaixa));
                            input_precocx.maskMoney({
                                thousands: ".",
                                decimal: ",",
                                precision: 4
                            }).maskMoney('mask');

                            // data attribute CAMPO PRECO UNIDADE
                            input_precounit.val(mascaraValor(preco_unidade));
                            input_precounit.maskMoney({
                                thousands: ".",
                                decimal: ",",
                                precision: 4
                            }).maskMoney('mask');


                            var ultima_oferta = (xhr.data['ultima_oferta'] == '0,0000') ? 'Sem oferta' : 'Ultima oferta: R$' + xhr.data['ultima_oferta'];
                            input_precounit.attr('data-original-title', ultima_oferta).tooltip('update');

                            // Altera label qtd e preco unitario
                            row.find("#label_preco").html(preco_unidade);
                            row.find("#label_qtd").html(qnt);
                            row.find("#label_marca").html(marca);
                        } else {

                            if (xhr.type == 'warning') {

                                var alert = `<i style="color : #ff0000; font-size: 16px; margin-left: 10px"
                                data-toggle="tooltip"
                                title="Existe uma restrição no painel de controle"
                                class="fas fa-exclamation-circle"></i>&nbsp;&nbsp;&nbsp;`;

                                row.prev().find("#label_alert").html(alert);
                            }
                        }
                    },
                    error: function(xhr) {}
                })


                if (estoque > 0 && estoque >= qtd_solicitada) {

                    row.removeClass(row[0].className).addClass('table-success');
                    row.prev().removeClass(row.prev()[0].className).addClass('table-success');
                } else if (estoque > 0 && estoque < qtd_solicitada) {

                    row.removeClass(row[0].className).addClass('table-warning');
                    row.prev().removeClass(row.prev()[0].className).addClass('table-warning');
                } else {

                    row.removeClass(row[0].className).addClass('table-danger');
                    row.prev().removeClass(row.prev()[0].className).addClass('table-danger');
                }

                row.find("[data-check]").data('estoque', estoque);

                if (row.find(`[data-key="${$(this).data('key')}"]`).prop('checked') == true) {

                    save_item($(this).data('key'));
                }
            });

            $("[data-depara]").on('click', function(e) {

                e.preventDefault();

                var url = $(this).attr('href');

                $.ajax({
                    url: url,
                    type: 'post',
                    contentType: false,
                    processData: false,
                    data: {},
                    success: function(xhr) {
                        if (xhr.type == 'success') {
                            window.open(xhr.link, '_blank');
                        } else {

                            formWarning(xhr);
                        }

                    },
                    error: function(xhr) {}
                });
            });

            $("[data-check]").change(function(index, element) {

                if ($(this).data("integrador") == 'BIONEXO') {

                    if ($(this).prop('checked') == false) {

                        $(this).removeAttr("checked");
                    } else {

                        campoNome = $(`[data-cd_comprador="${$(this).data('cd_comprador')}"]`);
                        campoNome.prop("checked", false);
                        $(this).prop('checked', true);
                    }
                }

                $("#btnCount").html($('[data-check]:checked').length);

                save_item($(this).data('key'));
            });

            $("#btnEnviar").on('click', function(e) {
                e.preventDefault();

                $(this).addClass("disabled");

                var ofertas = 0;
                var data = [];
                var produtos = [];

                $("[data-formproduto]").each(function(i, v) {

                    ofertas += $(v).find("input[data-check='produto']:checked").length;
                });

                if (ofertas > 0) {

                    data.push({
                        name: "obs",
                        value: $("#obs").val()
                    });
                    data.push({
                        name: "prazo_entrega",
                        value: $("#prazo_entrega").val()
                    });
                    data.push({
                        name: "id_forma_pagamento",
                        value: $("#id_forma_pagamento").val()
                    });
                    data.push({
                        name: "cnpj_comprador",
                        value: $("#cnpj_comprador").val()
                    });
                    data.push({
                        name: "razao_social",
                        value: $("#razao_social").val()
                    });
                    data.push({
                        name: "dt_inicio_cotacao",
                        value: $("#dt_inicio_cotacao").val()
                    });
                    data.push({
                        name: "dt_fim_cotacao",
                        value: $("#dt_fim_cotacao").val()
                    });
                    data.push({
                        name: "uf_cotacao",
                        value: $("#uf_cotacao").val()
                    });
                    data.push({
                        name: "id_cotacao",
                        value: $("#id_cotacao").val()
                    });
                    data.push({
                        name: "id_estado",
                        value: $("#id_estado").val()
                    });
                    data.push({
                        name: "id_cliente",
                        value: $("#id_cliente").val()
                    });
                    data.push({
                        name: "cd_cotacao",
                        value: $("#cd_cotacao").val()
                    });
                    data.push({
                        name: "integrador",
                        value: $("#integrador").val()
                    });

                    $.ajax({
                        url: form_action,
                        type: 'POST',
                        data: data,
                        success: function(xhr) {

                            if (xhr.type !== undefined) {

                                if (xhr.type == 'warning') {

                                    formWarning(xhr);
                                } else {

                                    formWarning({
                                        type: 'success',
                                        message: 'Espelho da cotação criado com sucesso'
                                    });

                                    if (xhr.route !== undefined) {

                                        setTimeout(function() {
                                            window.location.href = xhr.route
                                        }, 1500);
                                    }
                                }
                            }
                        },
                        error: function(xhr) {}
                    });
                } else {

                    formWarning({
                        type: 'warning',
                        message: "Nenhum produto selecionado"
                    });
                }

                $(this).removeClass("disabled");
            })
        });


        function loadDatatables(id, produto, codprod) {
            var url_combinar = $('#data-table' + id).data('url2');
            var table = $('#data-tableDePara' + id).DataTable({
                serverSide: false,
                pageLength: 10,
                lengthChange: false,
                "oSearch": {
                    "sSearch": produto
                },
                ajax: {
                    url: $('#data-tableDePara' + id).data('url'),
                    type: 'post',
                    dataType: 'json',
                },
                columns: [{
                        defaultContent: '',
                        orderable: false,
                        searchable: false
                    },
                    {
                        name: 'codigo',
                        data: 'codigo'
                    },
                    {
                        name: 'apresentacao',
                        data: 'apresentacao'
                    },
                    {
                        name: 'marca',
                        data: 'marca'
                    },
                ],
                columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                }, ],
                select: {
                    style: 'multiple',
                    selector: 'td'
                },
                order: [
                    [1, 'asc']
                ],
                rowCallback: function(row, data) {
                    $(row).data('id', data.id_produto).css('cursor', 'pointer');
                },
                drawCallback: function() {

                }
            });


            $('#btnCombinar' + id).on('click', function(e) {
                e.preventDefault();
                var urlPost = $('#data-tableDePara' + id).data('url2');
                var dados = [];
                var table = $('#data-tableDePara' + id).DataTable();

                $.map(table.rows('.selected').data(), function(item) {
                    dados.push({
                        id_fornecedor: item.id_fornecedor,
                        cd_produto: item.codigo,
                        id_sintese: $('#data-tableDePara' + id).data('sintese'),
                        id_cliente: $('#id_cliente').val(),
                        id_produto_comprado: codprod //produto cotado
                    });
                });

                if (dados.length > 0) {

                    $.post(urlPost, {
                            dados
                        }, function(xhr) {
                            formWarning(xhr);

                            if (xhr.type == 'success') {
                                Swal.fire({
                                    title: 'Produto(s) Combinado(s)',
                                    text: "Deseja atualizar a página ou continuar fazendo outros de/para?",
                                    icon: 'success',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Atualizar Página',
                                    cancelButtonText: 'Continuar'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.reload();
                                    } else {
                                        table.ajax.reload();
                                    }
                                })
                            }
                        }, 'JSON')
                        .fail(function(xhr) {
                            formWarning(xhr);
                            table.ajax.reload();
                        });
                } else {

                    formWarning({
                        type: 'warning',
                        message: "Nenhum registro selecionado!"
                    });
                }
            });


        }

        function loadDatatableUpgrade(id, produto, codprod, idsintese) {
            var url_combinar = $('#data-tableUpgradeDePara' + id).data('url2');
            var table = $('#data-tableUpgradeDePara' + id).DataTable({
                serverSide: false,
                pageLength: 10,
                lengthChange: false,
                "oSearch": {
                    "sSearch": produto
                },
                ajax: {
                    url: $('#data-tableUpgradeDePara' + id).data('url'),
                    type: 'post',
                    dataType: 'json',
                },
                columns: [{
                        defaultContent: '',
                        orderable: false,
                        searchable: false
                    },
                    {
                        name: 'codigo',
                        data: 'codigo'
                    },
                    {
                        name: 'apresentacao',
                        data: 'apresentacao'
                    },
                    {
                        name: 'marca',
                        data: 'marca'
                    },
                ],
                columnDefs: [{
                    orderable: false,
                    className: 'select-checkbox',
                    targets: 0
                }, ],
                select: {
                    style: 'multiple',
                    selector: 'td'
                },
                order: [
                    [1, 'asc']
                ],
                rowCallback: function(row, data) {
                    $(row).data('id', data.id_produto).css('cursor', 'pointer');
                },
                drawCallback: function() {

                }
            });


            $('#btnCombinarUpgrade' + id).on('click', function(e) {
                e.preventDefault();
                var urlPost = $('#data-tableUpgradeDePara' + id).data('url2');
                var dados = [];
                var table = $('#data-tableUpgradeDePara' + id).DataTable();

                $.map(table.rows('.selected').data(), function(item) {
                    dados.push({
                        id_fornecedor: item.id_fornecedor,
                        cd_produto: item.codigo,
                        id_sintese: idsintese,
                        id_cliente: $('#id_cliente').val(),
                        id_produto_comprado: codprod //produto cotado
                    });
                });

                if (dados.length > 0) {

                    $.post(urlPost, {
                            dados
                        }, function(xhr) {
                            formWarning(xhr);

                            if (xhr.type == 'success') {
                                Swal.fire({
                                    title: 'Produto(s) Combinado(s)',
                                    text: "Deseja atualizar a página ou continuar fazendo upgrade de/para?",
                                    icon: 'success',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Atualizar Página',
                                    cancelButtonText: 'Continuar'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location.reload();
                                    } else {
                                        table.ajax.reload();
                                    }
                                })
                            }
                        }, 'JSON')
                        .fail(function(xhr) {
                            formWarning(xhr);
                            table.ajax.reload();
                        });
                } else {

                    formWarning({
                        type: 'warning',
                        message: "Nenhum registro selecionado!"
                    });
                }
            });


        }

        function save_item(key) {
            var form = $(`#form-${key}`);

            var card = $(`#card-${key}`);

            var data = form.serializeArray();

            data.unshift({
                name: "obs",
                value: $("#obs").val()
            });
            data.unshift({
                name: "prazo_entrega",
                value: $("#prazo_entrega").val()
            });
            data.unshift({
                name: "id_forma_pagamento",
                value: $("#id_forma_pagamento").val()
            });
            data.unshift({
                name: "cnpj_comprador",
                value: $("#cnpj_comprador").val()
            });
            data.unshift({
                name: "dt_inicio_cotacao",
                value: $("#dt_inicio_cotacao").val()
            });
            data.unshift({
                name: "uf_cotacao",
                value: $("#uf_cotacao").val()
            });
            data.unshift({
                name: "id_estado",
                value: $("#id_estado").val()
            });
            data.unshift({
                name: "id_cliente",
                value: $("#id_cliente").val()
            });
            data.unshift({
                name: "cd_cotacao",
                value: $("#cd_cotacao").val()
            });
            data.unshift({
                name: "integrador",
                value: $("#integrador").val()
            });

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: data,
                beforeSend: function(jqXHR, settings) {
                    $('input:checkbox').attr("disabled", true);
                    card.LoadingOverlay("show");
                },
                success: function(xhr) {

                    if (xhr.type == 'warning') {

                        formWarning(xhr);

                        card.addClass('border-danger');

                        form.find('[data-key').prop("checked", false);

                        setTimeout(function() {
                            card.removeClass('border-danger');
                        }, 3000);
                    } else {

                        card.addClass('border-success');
                        setTimeout(function() {
                            card.removeClass('border-success');
                        }, 3000);
                    }

                    $('input:checkbox').not('.desativado').attr("disabled", false); // remove o disabled dos cks
                    card.LoadingOverlay("hide", true); // Remove o loading do card
                },
                error: function(xhr) {

                    card.LoadingOverlay("hide", true);
                }
            })
        }

        function delete_restriction(key) {

            var form = $(`#form-${key}`);

            var data = form.serializeArray();

            data.unshift({
                name: "cd_cotacao",
                value: $("#cd_cotacao").val()
            });
            data.unshift({
                name: "integrador",
                value: $("#integrador").val()
            });

            $.ajax({
                url: url_restricao,
                type: 'POST',
                data: data,
                success: function(xhr) {}
            })
        }

        function mascaraValor(valor) {
            valor = valor.toString().replace(/\D/g, "");
            valor = valor.toString().replace(/(\d)(\d{8})$/, "$1.$2");
            valor = valor.toString().replace(/(\d)(\d{5})$/, "$1.$2");
            valor = valor.toString().replace(/(\d)(\d{2})$/, "$1,$2");
            return valor
        }
    </script>
</body>

</html>