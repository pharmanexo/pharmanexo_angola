<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>
    <div class="content__inner">
        <form
                action="<?php if (isset($form_action)) echo $form_action; ?>"
                id="frm_pedido"
                method="post"
                enctype="multipart/form-data">
            <input type="hidden" id="id_fornecedor" name="id_fornecedor"
                   value="<?php if (isset($id_fornecedor)) echo $id_fornecedor; ?>">
            <input type="hidden" name="comissao" value="<?php echo (isset($comissao)) ? $comissao : '0.00' ?>">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <a data-toggle="collapse" class="pull-right text-right d-inline-block" href="#test-block"
                               aria-expanded="true" aria-controls="test-block">
                                <h3 class="card-title pull-left d-inline-block">Orçamento</h3>
                            </a>

                        </div>
                        <div class="col-6 text-right">
                            <input type="checkbox" name="prioridade"
                                   value="1" <?php if (isset($dados['prioridade']) && $dados['prioridade'] == 1) echo 'checked' ?> >
                            Orçamento Urgente
                        </div>
                    </div>

                </div>
                <div id="test-block" class="card-body collapse show">
                    <input type="hidden" id="id_comprador" name="id_comprador"
                           value="<?php echo $cliente['cliente']['id']; ?>">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label for="">Comprador</label>
                                <input type="text" readonly value="<?php echo $cliente['cliente']['razao_social']; ?>"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-lg-3">
                            <label for="">UF Comprador</label>
                            <input type="text" class="form-control text-center" name="uf_comprador"
                                   value="<?php if (isset($cliente['cliente']['estado'])) echo $cliente['cliente']['estado']; ?>"
                                   id="uf_comprador" readonly placeholder="Selecione o comprador">
                        </div>
                        <div class="col-12 col-lg-3">
                            <label for="">Data</label>
                            <input type="text" class="form-control text-center" readonly
                                   value="<?php echo date("d/m/Y H:i", time()) ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <div class="form-group">
                                <label for="">Condição de Pagamento</label>
                                <input type="hidden" id="id_forma_pagamento" name="id_forma_pagamento"
                                       value="<?php if (isset($cliente['forma_pagamento']['id'])) echo $cliente['forma_pagamento']['id']; ?>">
                                <input type="text" name="condicao_pagamento" readonly
                                       value="<?php if (isset($cliente['forma_pagamento']['descricao'])) echo $cliente['forma_pagamento']['descricao']; ?>"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="form-group">
                                <label for="">Prazo de entrega</label>
                                <div class="input-group">
                                    <input type="text" id="prazo_entrega" name="prazo_entrega" readonly
                                           value="<?php if (isset($cliente['prazo_entrega'])) echo $cliente['prazo_entrega']; ?>"
                                           class="form-control text-center" <?php echo (isset($dados)) ? 'disabled' : '' ?>
                                           required>
                                    <input type="hidden" id="id_prazo_entrega" readonly
                                           value="<?php if (isset($cliente['id_prazo_entrega'])) echo $cliente['id_prazo_entrega']; ?>"
                                           name="id_prazo_entrega" class="form-control text-center">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            dias
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="form-group">
                                <label for="">Valor mínimo</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            R$
                                        </div>
                                    </div>
                                    <input type="text" id="valor_minimo" name="valor_minimo"
                                           value="<?php if (isset($cliente['valor_minimo'])) echo $cliente['valor_minimo']; ?>"
                                           class="form-control text-right"
                                           data-inputmask="money" <?php echo (isset($dados)) ? 'disabled' : 'readonly' ?>>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (!isset($dados)) { ?>
                        <div class="row">
                            <div class="col-12">
                                <button id="btnCadastroPedido" class="btn btn-light btn-block" style="height: 100%"><i
                                            class="fas fa-pills"></i> Inserir Produtos do Orçamento
                                </button>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </form>

        <?php if (!isset($dados) || isset($dados) && $dados['situacao'] == 1) { ?>
            <form
                    action="<?php if (isset($form_action_produtos)) echo $form_action_produtos; ?>"
                    id="form_produto"
                    method="post"
                    enctype="multipart/form-data"
                    data-historico="<?php if (isset($url_historico)) echo $url_historico ?>">
                <input type="hidden" id="id_pedido" name="id_pedido"
                       value="<?php echo isset($dados) ? $dados['id'] : ''; ?>">
                <input type="hidden" id="cd_produto_fornecedor" name="cd_produto_fornecedor">
                <div class="card <?php if (!isset($dados)) {
                    echo 'd-none';
                } ?>" id="formProdutos">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="">Produto</label>

                                    <select class="form-control w-100" id="slct_produtos"
                                            data-url="<?php echo $select_produtos; ?><?php if (isset($cliente['cliente']['estado'])) echo $cliente['cliente']['estado']; ?>"
                                            style="width: 100%"></select>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="">Estoque</label>
                                    <input type="text" id="estoque" readonly value="0" class="form-control">
                                    <input type="hidden" id="preco" name="preco_unidade" readonly placeholder="0,00"
                                           data-inputmask="money4" class="form-control">
                                    <input type="hidden" id="total" name="total" class="form-control" disabled>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="">Quantidade</label>
                                    <input type="number" id="quantidade" min="0" name="quantidade_solicitada"
                                           class="form-control" required disabled>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button id="" class="btn btn-success btn-block" style="height: 100%"><i
                                            class="fas fa-plus"></i> Adicionar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        <?php } ?>

        <div class="card <?php if (!isset($dados)) {
            echo 'd-none';
        } ?>" id="datatable_produtos">
            <div class="card-body">
                <div class="row mx-auto mt-3">
                    <div class="col-12 col-sm">
                        <div class="table-responsive">

                            <table id="table-produtos" class="table table-condensend table-hover w-100"
                                   data-url="<?php echo $url_datatable ?>"
                                   data-finalize="<?php echo $url_finalize ?>"
                                   data-cancel="<?php echo $url_cancel ?>"
                                   data-preco="<?php echo $url_preco ?>">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Código Produto</th>
                                    <th>Código EAN</th>
                                    <th>Produto</th>
                                    <th>Quantidade</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="historicoModal" tabindex="-1" role="dialog" aria-labelledby="historicoModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historicoModalLabel"></h5>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <thead>
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


<?php if (isset($scripts)) echo $scripts; ?>

<script type="text/javascript">

    var url_finalize = $('#table-produtos').data('finalize');
    var url_cancel = $('#table-produtos').data('cancel');
    var url_preco = $('#table-produtos').data('preco');

    var url_historico = $('#form_produto').data('historico');

    var dt;
    var slct_group, slct_prods, slct_cliente;

    $(document).ready(function () {
        habilitarButtons();
    });

    $(function () {

        $("#total").focus(function () {
            $("#total").off("click");
        });

        $('#btnCadastroPedido').click(function (e) {
            e.preventDefault();

            if ($('#id_cliente').val() == '') {

                formWarning({type: 'warning', message: 'Selecione um cliente'});
            } else {

                var form = $('#frm_pedido');
                var data = form.serialize();

                $.post(form.attr('action'), data, function (xhr) {

                    if (xhr.type === 'success') {

                        $('#id_pedido').val(xhr.id_pedido);
                        load_produtos(xhr.id_pedido);
                        habilitarButtons(1);

                        $('#frm_pedido').find('select').each(function (i, v) {
                            $(this).prop('disabled', true);
                        });

                        $('#frm_pedido').find('input:not([readonly])').each(function (i, v) {
                            $(this).prop('readonly', true);
                        });

                        window.location = "https://pharmanexo.com.br/pharmanexo_v2/pharma/pedidos/open/" + xhr.id_pedido;

                    } else {

                        if (typeof xhr.url !== 'undefined') {
                            Swal.fire({
                                title: xhr.message,
                                text: "Deseja ir para o pedido em aberto?",
                                type: 'warning',
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ir para o Pedido',
                            }).then((result) => {
                                if (result.value) {
                                    location.href = xhr.url
                                }
                            })
                        }
                    }
                });

                $('#formProdutos').removeClass('d-none').addClass('d-block');
                $(this).remove();
            }
        });

        $('#form_produto').submit(function (e) {
            e.preventDefault();

            if ($('#slct_produtos').val() == '') {
                formWarning({type: 'warning', message: 'Selecione um produto'});
            } else {
                var form = $('#form_produto');
                var data = form.serialize();

                $.post(form.attr('action'), data, function (xhr) {
                    e.preventDefault();
                    if (xhr.type === 'success') {

                        formWarning({type: xhr.type, message: xhr.message});

                        $('#formProdutos').removeClass('d-none').addClass('d-block');
                        $('#table-produtos').DataTable().ajax.reload();
                        $('#table-produtos').DataTable().draw();
                        $(':input', '#form_produto').not(':button, :submit, :reset, :hidden').val("").removeAttr('selected');
                        $('#datatable_produtos').removeClass('d-none').addClass('d-block');

                        $('#quantidade').prop("disabled", true);
                        $('#desconto').prop("disabled", true);
                        $('#valor_desconto').prop("disabled", true);
                        $('#btn_historico').prop("hidden", true);

                        $('#slct_produtos').val(null);
                        $('#slct_produtos').trigger('change.select2');
                    }
                });
            }
        });

        slct_group = $('#formasPagamento');
        slct_prods = $('#slct_produtos');
        slct_cliente = $('#select_clientes');

        slct_cliente.select2({
            placeholder: 'Selecione...',
            ajax: {
                url: slct_cliente.data('url'),
                type: 'get',
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    return {
                        columns: [{
                            name: 'razao_social',
                            search: params.term
                        }],
                        page: params.page || 1,
                    }
                }
            },
            sorter: function (data) {
                return data.sort(function (a, b) {
                    return a.text < b.text ? -1 : a.text > b.text ? 1 : 0;
                });
            },
            processResults: function (data) {
                return {
                    results: data
                }
            },

            templateResult: function (data, container) {
                if (!data.id) {
                    return data.text;
                }

                return data.cnpj + ' - ' + data.razao_social;
            },
            templateSelection: function (data, container) {
                if (!data.id) {
                    return data.text;
                }

                return (typeof data.cnpj !== 'undefined') ? data.cnpj + ' - ' + data.razao_social : data.text;
            }
        });

        slct_cliente.on('select2:select', function (e) {
            var data = e.params.data;
            var url = slct_cliente.data('urlinfo') + data.id;

            $.get(url, function (xhr) {
                $('#id_cliente').val(xhr.cliente['id']);
                $('#uf_comprador').val(xhr.cliente['estado']);
                $('#prazo_entrega').val(xhr.prazo_entrega['prazo']);
                $('#id_prazo_entrega').val(xhr.prazo_entrega['id']);
                $('#valor_minimo').val(xhr.valor_minimo);

                var $option = new Option(xhr.condicao_desc, xhr.condicao, false, false);

                $('#formasPagamento').append($option).val(xhr.condicao).trigger('change');

                var u = slct_prods.data('url');
                slct_prods.data('url', u + '/' + xhr.cliente['estado']);

                slct_prods.select2({
                    placeholder: 'Selecione...',
                    ajax: {
                        url: slct_prods.data('url'),
                        type: 'get',
                        dataType: 'json',
                        delay: 300,
                        data: function (params) {
                            return {
                                columns: [{
                                    name: 'nome_comercial',
                                    search: params.term
                                }],
                                page: params.page || 1
                            }
                        }
                    },

                    processResults: function (data) {
                        return {
                            results: data
                        }
                    },
                    templateResult: function (data, container) {
                        if (!data.id) {
                            return data.text;
                        }

                        var ret = `${data.codigo} -  ${data.nome_comercial}`;

                        return ret;
                    },
                    templateSelection: function (data, container) {
                        if (!data.id) {
                            return data.text;
                        }


                        $.post(url_preco, {
                            codigo: $('#cd_produto_fornecedor').val(),
                            uf: $('#uf_comprador').val()
                        }, function (xhr) {

                            $('#preco').val(xhr.preco_unitario);
                            $('#valor_desconto').val(xhr.preco_unitario);
                        });


                        $('#cd_produto_fornecedor').val(data.codigo);
                        $('#estoque').val(data.estoque);
                        $('#quantidade').prop("disabled", false);
                        $('#desconto').prop("disabled", false);
                        $('#valor_desconto').prop("disabled", false);
                        $('#btn_historico').prop("hidden", false);


                        return (typeof data.nome_comercial !== 'undefined') ? data.codigo + ' - ' + data.nome_comercial : data.text;
                    }
                });

                slct_prods.on('select2:select', function (e) {

                    $.post(url_preco, {
                        codigo: $('#cd_produto_fornecedor').val(),
                        uf: $('#uf_comprador').val()
                    }, function (xhr) {
                        console.log(xhr);
                        $('#preco').val(xhr.preco_unitario)
                            .maskMoney({
                                thousands: ".",
                                decimal: ",",
                                precision: 4
                            }).maskMoney('mask');

                        $('#valor_desconto').val(xhr.preco_unitario)
                            .maskMoney({
                                thousands: ".",
                                decimal: ",",
                                precision: 4
                            }).maskMoney('mask');

                    });
                });

            });
        });

        slct_group.select2({
            placeholder: 'SELECIONE ...',
            ajax: {
                url: slct_group.data('url'),
                type: 'get',
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    return {
                        columns: [{
                            name: 'descricao',
                            search: params.term
                        }],
                        page: params.page || 1
                    }
                }
            },

            processResults: function (data) {
                return {
                    results: data
                }
            },

            templateResult: function (data) {
                if (!data.id) {
                    return data.text;
                }
                return data.descricao;
            },

            templateSelection: function (data) {
                if (!data.id) {
                    return data.text;
                }
                var cond = (typeof data.descricao !== 'undefined') ? data.descricao : data.text;
                $('#condicao_pagamento').val(cond);
                return cond;
            }
        });

        // desconto
        $('#desconto').on('blur', function () {

            var preco = $('#preco').val().replace('.', '').replace(',', '.');

            var desconto = $(this).val();

            var target = $('#valor_desconto');

            if (desconto == '') {

                target.val(mascaraValor(preco));
                target.maskMoney({
                    thousands: ".",
                    decimal: ",",
                    precision: 4
                }).maskMoney('mask');

                return;
            }

            desconto = desconto.replace(",", ".");
            desconto = parseFloat(desconto);

            var result = preco - (preco * (desconto / 100));

            target.val(mascaraValor(result.toFixed(4)));
            target.maskMoney({
                thousands: ".",
                decimal: ",",
                precision: 4
            }).maskMoney('mask');

            somarTotal(1);
        });

        $('#valor_desconto').on('blur', function () {

            var preco_oferta = $('#preco').val().replace('.', '').replace(',', '.');
            ;

            var preco = $('#valor_desconto').val();

            var target = $('#desconto');
            somarTotal()
            if (preco == '') {
                return;
            }

            if (preco != preco_oferta) {

                preco = preco.replace(",", ".");
                preco = parseFloat(preco);

                var subtracao = preco_oferta - preco;
                var divisao = subtracao / preco_oferta;
                var result = divisao * 100;

                target.val(Math.round(result));

                target.maskMoney({
                    thousands: ".",
                    decimal: ",",
                    precision: 2
                }).maskMoney('mask');

                somarTotal(1);

            } else {
                target.val(0);
            }
        });

        // quantidade
        $('#quantidade').on('keyup', function (e) {
            somarTotal(1);
        });

        // Historico de ofertas do produto
        $('#historicoModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var modal = $(this);

            modal.find('.modal-title').text('Histórico de ofertas');

            var codigo = $('#cd_produto_fornecedor').val();
            var id_pedido = $("#id_pedido").val();

            if (codigo != "") {
                $.post(url_historico, {codigo: codigo, id_pedido: id_pedido}, function (xhr) {

                    if (xhr.length > 0) {
                        $.each(xhr, function (index, value) {
                            modal.find('tbody').append(`<tr><td>${value.preco_desconto}</td><td>${value.data}</td></tr>`);
                        })
                    } else {
                        modal.find('tbody').append(`<tr><td colspan="2">Não encontramos ofertas anteriores para este produto.</td></tr>`);
                    }
                }, 'JSON')
                    .fail(function (xhr) {
                        console.log(xhr);
                    });
            } else {
                modal.find('tbody').append(`<tr><td colspan="2">Não encontramos ofertas anteriores para este produto.</td></tr>`);
            }
        }).on('hidden.bs.modal', function (event) {
            var modal = $(this);

            modal.find('tbody').html('');
        });


        $('#btnInsert').click(function (e) {
            e.preventDefault();
            var id_pedido = $("#id_pedido").val();

            if (id_pedido != "") {
                $.post(url_finalize, {id_pedido: id_pedido}, function (xhr) {
                    e.preventDefault();

                    formWarning({type: xhr.type, message: xhr.message});

                    if (xhr.type == "success") {
                        setTimeout(function () {
                            location.href = xhr.url
                        }, 1000);
                    }
                }, 'JSON')
                    .fail(function (xhr) {
                        formWarning(xhr);
                    });
            } else {
                formWarning({
                    type: 'warning',
                    message: "Não é possivel finalizar o pedido!"
                });
            }
        });

        $('#btnCancel').click(function (e) {
            e.preventDefault();
            var id_pedido = $("#id_pedido").val();

            if (id_pedido != "") {
                $.post(url_cancel, {id_pedido: id_pedido}, function (xhr) {
                    e.preventDefault();

                    formWarning({type: xhr.type, message: xhr.message});

                    if (xhr.type == "success") {
                        setTimeout(function () {
                            location.href = xhr.url
                        }, 1000);
                    }
                }, 'JSON')
                    .fail(function (xhr) {
                        formWarning(xhr);
                    });
            } else {
                formWarning({
                    type: 'warning',
                    message: "Não é possivel cancelar o pedido!"
                });

            }
        });


        <?php if (isset($dados)) {?>

        habilitarButtons(1);

        slct_prods.select2({
            placeholder: 'Selecione...',
            ajax: {
                url: slct_prods.data('url'),
                type: 'get',
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    return {
                        columns: [
                            {name: 'pc.nome_comercial', search: params.term},
                            {name: 'pc.ean', search: params.term}
                        ],
                        page: params.page || 1
                    }
                }
            },
            processResults: function (data) {
                return {
                    results: data
                }
            },

            templateResult: function (data, container) {
                if (!data.id) {
                    return data.text;
                }

                var ret = `${data.ean} -  ${data.nome_comercial}`;

                return ret;
            },
            templateSelection: function (data, container) {
                if (!data.id) {
                    return data.text;
                }

                $('#cd_produto_fornecedor').val(data.codigo);
                $('#estoque').val(data.estoque);


                $('#quantidade').prop("disabled", false);
                $('#desconto').prop("disabled", false);
                $('#valor_desconto').prop("disabled", false);
                $('#btn_historico').prop("hidden", false);

                return (typeof data.nome_comercial !== 'undefined') ? data.codigo + ' - ' + data.nome_comercial : data.text;
            }
        });

        slct_prods.on('select2:select', function (e) {

            $.post(url_preco, {
                codigo: $('#cd_produto_fornecedor').val(),
                uf: $('#uf_comprador').val()
            }, function (xhr) {
                console.log(xhr);
                $('#preco').val(xhr.preco_unitario)
                    .maskMoney({
                        thousands: ".",
                        decimal: ",",
                        precision: 4
                    }).maskMoney('mask');

                $('#valor_desconto').val(xhr.preco_unitario)
                    .maskMoney({
                        thousands: ".",
                        decimal: ",",
                        precision: 4
                    }).maskMoney('mask');

            });
        });

        load_produtos(<?php echo $dados['id'] ?>);
        initSelect2(slct_group);
        initSelectCliente(slct_cliente);
        <?php  } ?>

    });

    function habilitarButtons(on = null) {
        if (on != null) {

            $("#btnCancel").show();
            $("#btnExport").show();
            $("#btnInsert").show();
        } else {

            $("#btnCancel").hide();
            $("#btnExport").hide();
            $("#btnInsert").hide();
        }
    }

    function somarTotal(format = null) {
        var qtd = $('#quantidade').val();
        var preco = $('#preco').val().replace('.', '').replace(',', '.');
        var target = $('#total');


        var result = qtd * preco;


        target.val(mascaraValor(result.toFixed(4)));
        target.maskMoney({
            thousands: ".",
            decimal: ",",
            precision: 4
        }).maskMoney('mask');
    }

    function load_produtos(id_pedido) {
        dt = $('#table-produtos').DataTable({
            processing: true,
            serverSide: false,
            lengthChange: false,
            responsive: true,
            pageLength: 10,
            searching: false,
            ajax: {
                url: $('#table-produtos').data('url') + id_pedido,
                type: 'POST',
                dataType: 'json'
            },
            columns: [
                {data: 'id_pedido', name: 'id_pedido', visible: false},
                {data: 'cd_produto_fornecedor', name: 'cd_produto_fornecedor'},
                {data: 'ean', name: 'ean'},
                {data: 'nome_comercial', name: 'nome_comercial'},
                {data: 'quantidade_solicitada', name: 'quantidade_solicitada'},
            ],
            rowCallback: function (row, data) {
                switch (data.status) {
                    case '1':
                        $(row).addClass('table-success');
                        // $('td:eq(7)', row).html('Aprovado');
                        break;
                    case '9':
                        $(row).addClass('table-danger');
                        // $('td:eq(7)', row).html('Rejeitado');
                        break;
                    default:
                        //  $('td:eq(7)', row).html(btn);
                        break;
                }


            },
            drawCallback: function () {
                $('#data-table_filter, .dataTables_buttons').remove();
            }
        });
    }

    function initSelect2(e) {
        $.ajax({
            url: e.data('url'),
            type: 'get',
            dataType: "json",
            data: {
                columns: [{
                    name: 'id',
                    search: e.data('value'),
                    equal: true
                }]
            }
        }).then(function (data) {
            var id;
            data.results.forEach(function (entry) {
                id = entry.id;
                var $option = new Option(entry.descricao, entry.id, false, false);
                $(e).append($option).val(id).trigger('change');
            });
        });
    }

    function initSelectProds(e) {
        $.ajax({
            url: e.data('url'),
            type: 'get',
            dataType: "json",
            data: {
                columns: [{
                    name: 'id',
                    search: e.data('value'),
                    equal: true
                }]
            }
        }).then(function (data) {
            var id;

            data.results.forEach(function (entry) {
                id = entry.id;

                var $option = new Option(entry.codigo + ' - ' + entry.nome_comercial, entry.codigo, false, false);
                $(e).append($option).val(entry.codigo).trigger('change');
            });
        });
    }

    function initSelectCliente(e) {
        $.ajax({
            url: e.data('url'),
            type: 'get',
            dataType: "json",
            data: {
                columns: [{
                    name: 'id',
                    search: e.data('value'),
                    equal: true
                }]
            }
        }).then(function (data) {
            var id;
            data.results.forEach(function (entry) {
                id = entry.id;
                var $option = new Option(entry.cnpj + ' - ' + entry.razao_social, entry.id, false, false);
                $(e).append($option).val(id).trigger('change');
            });
        });
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
