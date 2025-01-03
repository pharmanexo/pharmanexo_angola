<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
    <?php echo $navbar; ?>
    <?php echo $sidebar; ?>

    <div class="content">
        <?php echo $heading; ?>

        <div class="content__inner">
            <input type="hidden" id="id" name="id" value="<?php echo $pedido['id']; ?>" class="form-control">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label for="">Comprador</label>
                                <input type="text" readonly value="<?php echo $pedido['razao_social']; ?>"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-lg-3">
                            <label for="">UF Comprador</label>
                            <input type="text" class="form-control text-center" name="uf_comprador"
                                   value="<?php if (isset($pedido['estado'])) echo $pedido['estado']; ?>"
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

                                <select name="id_forma_pagamento" id="id_forma_pagamento" class="select2" disabled>
                                    <?php foreach ($formas_pagamento as $item) { ?>
                                        <option value="<?php echo $item['id']; ?>" <?php if ($item['id'] == $pedido['id_forma_pagamento']) echo 'selected'; ?>><?php echo $item['descricao']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-lg-4">
                            <div class="form-group">
                                <label for="">Prazo de entrega</label>
                                <div class="input-group">
                                    <input type="text" id="prazo_entrega" name="prazo_entrega"
                                           value="<?php if (isset($pedido['prazo_entrega'])) echo $pedido['prazo_entrega']; ?>"
                                           class="form-control text-center" <?php echo (isset($dados)) ? 'disabled' : '' ?> readonly
                                           required>
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
                                           value="<?php if (isset($pedido['valor_minimo'])) echo $pedido['valor_minimo']; ?>"
                                           class="form-control text-right" readonly
                                           data-inputmask="money">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive"> 
                        <table id="data-table" class="table w-100 table-hover" data-url="<?php echo $datatables; ?>" data-reject="<?php echo $url_reject ?>">
                            <thead>
                            <tr>
                                <th>Código Produto</th>
                                <th>Produto</th>
                                <th>Quantidade Solicitada</th>
                                <th>Preço unidade</th>
                                <th>Desconto</th>
                                <th>Preço Desconto</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<?php echo $scripts; ?>

<script>
    var url_reject = $('#data-table').data('reject');

    $(function() {

        var table = $('#data-table').DataTable({
            serverSide: false,
            lengthChange: false,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'post',
                dataType: 'json',
            },
            columns: [
                { name: 'cd_produto_fornecedor', data: 'cd_produto_fornecedor'},
                { name: 'nome_comercial', data: 'nome_comercial'},
                { name: 'quantidade_solicitada', data: 'quantidade_solicitada'},
                { name: 'preco_unidade', data: 'preco_unidade'},
                { name: 'desconto', data: 'desconto'},
                { name: 'preco_desconto', data: 'preco_desconto'},
                { name: 'total', data: 'total'},
                { defaultContent: '', width: '', orderable: false, searchable: false }
            ],
            order: [[ 1, 'asc' ]],
            rowCallback: function(row, data) {

                var btn = $(`<button data-href="${url_reject}" data-toggle="tooltip" title="Rejeitar item" class="btn btn-link text-danger"><i class="fas fa-ban"></i></button>`);

                btn.click(function (e) {
                    e.preventDefault();
                    var url = $(this).data('href');
                    Swal.fire({
                        title: 'Informe o motivo para rejeitar este item',
                        input: 'text',
                        inputAttributes: {
                            autocapitalize: 'off'
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Confirmar',
                        cancelButtonText: 'Fechar',
                        showLoaderOnConfirm: true,
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.value) {
                            var text = result.value;

                            if (text.length > 5) {
                                $.post(url, {motivo: result.value, codigo: data.cd_produto_fornecedor}, function (xhr) {
                                    formWarning(xhr);
                                    table.ajax.reload();
                                })
                            } else {
                                formWarning({type: 'warning', message: 'Não informou o motivo'})
                            }
                        }
                    })
                });

                switch (data.status) {
                    case '1':
                        $(row).addClass('table-success');
                        $('td:eq(7)', row).html('Aprovado');
                        break;
                    case '9':
                        $(row).addClass('table-danger');
                        $('td:eq(7)', row).html('Rejeitado');
                        break;
                    default:
                        $('td:eq(7)', row).html(btn);
                        break;
                }
            },
            drawCallback: function() {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        $('#btnApprove').click(function (e) {
            e.preventDefault();

            var elementos = [];

            $.map(table.rows().data(), function (item) {   
                elementos.push(item.cd_produto_fornecedor);
            });

            if (elementos.length > 0) {
                var url = $(this).attr('href');

                Swal.fire({
                    title: 'Deseja realmente aprovar os itens (não rejeitados)?',
                    showCancelButton: true,
                    confirmButtonText: 'Sim',
                    cancelButtonText: 'Não',
                    showLoaderOnConfirm: true,
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.value) {

                        $.get(url, function (xhr) {
                            formWarning(xhr);
                            table.ajax.reload();
                        })
                    }
                })
            } else {
                formWarning({type: 'warning', message: "Pedido sem produto"});
            }
        }).tooltip({
            title: 'Apenas itens não rejeitados serão aprovados.'
        });

        $('#btnReject').click(function (e) {
            e.preventDefault();

            var elementos = [];

            $.map(table.rows().data(), function (item) {   
                elementos.push(item.cd_produto_fornecedor);
            });

            if (elementos.length > 0) {
                var url = $(this).attr('href');

                Swal.fire({
                    title: 'Informe o motivo para rejeitar o pedido',
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Confirmar',
                    cancelButtonText: 'Fechar',
                    showLoaderOnConfirm: true,
                    allowOutsideClick: false,
                }).then((result) => {
                    if (result.value) {
                        var text = result.value;

                        if (text.length > 5) {
                            $.post(url, {motivo: result.value}, function (xhr) {
                                formWarning(xhr);
                                table.ajax.reload();
                            })
                        } else {
                            formWarning({type: 'warning', message: 'Não informou o motivo'})
                        }

                    }
                })
            } else {
                formWarning({type: 'warning', message: "Pedido sem produto"});
            }
        }).tooltip({
            title: 'Rejeitar todo o pedido'
        });
    });
</script>

</html>