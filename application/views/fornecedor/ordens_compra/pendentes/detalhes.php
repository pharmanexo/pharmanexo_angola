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
            <div class="card-body">

                <h4 class="card-title">Selecione uma forma de pagamento para efeturar resgate</h4>

                <div class="row">
                    <?php if (isset($formas_pagamento) && !empty($formas_pagamento)): ?>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Selecionar Forma de Pagamento</label> <select class="select2" id="forma_pagto"
                                                                                     data-placeholder="Selecione">
                                    <option></option>
                                    <?php foreach ($formas_pagamento as $f): ?>
                                        <option value="<?php echo $f['id'] ?>"><?php echo $f['value'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Forma de Pagamento</label> <input type="text" class="form-control"
                                                                         id="forma_pagto"
                                                                         value="Nenhuma forma de pagamento econtrada"
                                                                         disabled>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="">Usuário Resgate</label>
                            <select class="select2" name="usuario_resgate" required data-placeholder="Selecione... " id="usuario_resgate">
                                <option value=""></option>
                                <?php foreach ($usuarios as $usuario){ ?>
                                    <option value="<?php echo $usuario['usuario']; ?>"><?php echo $usuario['nome']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>


                </div>


                <p class="text-muted border-bottom"><strong>Dados da Ordem de Compra</strong></p>
                <div class="row">
                    <div class="col-12 col-lg-2">
                        <strong>Número</strong> <br>
                        <?php echo $oc['Cd_Ordem_Compra']; ?>
                    </div>
                    <div class="col-12 col-lg-4">
                        <strong>Situação</strong> <br>
                        <?php echo $oc['situacao']; ?>
                    </div>
                    <div class="col-12 col-lg-2">
                        <strong>Cotação</strong> <br>
                        <?php echo $oc['Cd_Cotacao'] ?>
                    </div>
                    <div class="col-12 col-lg-4">
                        <strong>Nome Aprovador</strong> <br>
                        <?php echo $oc['Nm_Aprovador']; ?>
                    </div>
                </div>

                <p class="text-muted border-bottom mt-4"><strong>Dados de Faturamento</strong></p>
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <strong>Empresa</strong> <br>
                        <?php if (isset($oc['comprador'])) echo $oc['comprador']['razao_social']; ?>
                    </div>
                    <div class="col-12 col-lg-3">
                        <strong>CNPJ</strong> <br>
                        <?php if (isset($oc['comprador'])) echo $oc['comprador']['cnpj']; ?>
                    </div>
                    <div class="col-12 col-lg-3">
                        <strong>E-mail</strong> <br>
                        <?php if (isset($oc['comprador'])) echo $oc['comprador']['email']; ?>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 col-lg-3">
                        <strong> Condição de Pagamento Cotação</strong> <br>
                        <?php echo isset($oc['fp']) ? $oc['fp'] : 'Não informado' ?>
                    </div>

                    <div class="col-12 col-lg-3">
                        <strong> Condição de Pagamento Pedido</strong> <br>
                        <?php echo isset($oc['fp_oc']) ? $oc['fp_oc'] : 'Não informado' ?>
                    </div>
                    <div class="col-12 col-lg-3">
                        <strong>Data de Entrega</strong> <br>
                        <?php echo isset($oc['Dt_Previsao_Entrega']) ? date('d/m/Y', strtotime($oc['Dt_Previsao_Entrega'])) : 'Não informado' ?>
                    </div>

                    <div class="col-12 col-lg-3">
                        <strong>Tipo de Frete</strong> <br>
                        <?php echo isset($oc['Tp_Frete']) ? $oc['Tp_Frete'] : 'Não informado' ?>
                    </div>
                </div>
                <p class="text-muted border-bottom mt-4"><strong>Observações</strong></p>
                <div class="row">
                    <div class="col-12">
                        <?php if (isset($oc['Ds_Observacao'])) echo $oc['Ds_Observacao']; ?>
                    </div>
                </div>

                <div class="table-responsive col-sm">
                    <table id="data-table" class="table table-condensend table-hover"
                           data-cnpj="<?php echo $oc['comprador']['cnpj']; ?>"
                           data-resgate="<?php echo $url_resgate; ?>" data-codigo="<?php echo $url_codigo; ?>"
                           data-has_no_code="<?php echo $oc['hasNoCode']; ?>">
                        <thead>
                        <tr>
                            <th>Código</th>
                            <th>Produto</th>
                            <th>Produto Catálogo</th>
                            <th>Marca</th>
                            <th>Unidade</th>
                            <th>Qtd Embalagem</th>
                            <th>Qtd Produto</th>
                            <th>Preço (R$)</th>
                            <th hidden></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (isset($oc['produtos'])): ?>

                            <?php foreach ($oc['produtos'] as $kk => $produto): ?>
                                <tr>
                                    <td>
                                        <?php if (isset($produto['codigo']) && !empty($produto['codigo'])): ?>

                                            <?php echo $produto['codigo']; ?><?php else: ?>
                                            <form name="form_<?php echo $kk; ?>" id="form_<?php echo $kk; ?>"
                                                  onSubmit="changeCode(this)" enctype="multipart/form-data">
                                                <input type="hidden" name="Cd_Produto_Comprador"
                                                       value="<?php echo $produto['Cd_Produto_Comprador'] ?>"/> <input
                                                        type="hidden" name="Id_Produto_Sintese"
                                                        value="<?php echo $produto['Id_Produto_Sintese'] ?>"/>
                                                <div class="input-group">
                                                    <input type="number" name="codigo" class="form-control"/>
                                                    <div class="input-group-append">
                                                        <button type="submit" form="form_<?php echo $kk; ?>"
                                                                class="btn btn-primary">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $produto['Ds_Produto_Comprador']; ?></td>
                                    <td><?php echo (isset($produto['produto_catalogo'])) ? $produto['produto_catalogo'] : ''; ?></td>
                                    <td><?php echo $produto['Ds_Marca']; ?></td>
                                    <td><?php echo $produto['Ds_Unidade_Compra']; ?></td>
                                    <td><?php echo $produto['Qt_Embalagem']; ?></td>
                                    <td><?php echo $produto['Qt_Produto']; ?></td>
                                    <td><?php echo number_format($produto['Vl_Preco_Produto'], 4, ',', '.') ?></td>
                                    <td hidden><?php echo $produto['Cd_Produto_Compra']; ?></td>
                                </tr>
                            <?php endforeach; ?><?php else: ?>

                            <tr>
                                <td colspan="8">Nenhum registro encontrado</td>
                            </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<?php echo $scripts; ?>
<script>

    var url_resgate = $("#data-table").data('resgate');
    var url_codigo_update = $("#data-table").data('codigo');
    var url_list = "<?php if (isset($url_list)) echo $url_list; ?>";
    var url_changestatus = "<?php if (isset($urlChangeStatusPending)) echo $urlChangeStatusPending; ?>";

    $(function () {

        <?php if (!isset($formas_pagamento) || empty($formas_pagamento)): ?>

        $("#btnResgate").remove();
        <?php endif; ?>

        var table = $('#data-table').DataTable({
            processing: true,
            serverSide: false,
            lengthChange: false,
            "order": [[1, "asc"]],
            rowCallback: function (row, data) {
            },
            drawCallback: function () {
            }
        });

        $("#btnChangeStatus").on('click', function (e) {

            e.preventDefault();

            $.post(url_changestatus, {}, function (xhr) {
                formWarning(xhr);

                if (xhr['type'] === 'success') {
                    setTimeout(function () {
                        window.location.href = url_list
                    }, 1500);
                }
            }, 'JSON')
                .fail(function (xhr) {
                });
        });

        $("#btnResgate").on('click', function (e) {

            e.preventDefault("aki");

            var cnpj = $("#data-table").data('cnpj');
            var forma_pagto = $("#forma_pagto").val();
            var usuario = $("#usuario_resgate").val();


            if (forma_pagto !== '' && forma_pagto != 'Nenhuma forma de pagamento econtrada') {
                if (usuario !== ''){
                    if ($('#data-table').data('has_no_code') == 0) {

                        $.ajax({
                            url: url_resgate,
                            type: 'post',
                            data: {
                                cnpj: cnpj,
                                forma_pagto: forma_pagto,
                                usuario_resgate: usuario,
                            },
                            beforeSend: function (jqXHR, settings) {
                                $("#btnResgate").html('<i class="fas fa-spinner"></i>');
                            },
                            success: function (xhr) {
                                if (xhr['type'] == 'success') {
                                    window.location.href = xhr['route'];
                                }else{
                                    formWarning(xhr);
                                }

                                $("#btnResgate").html('<i class="fas fa-check"></i>');
                            },
                            error: function (xhr) {
                                $("#btnResgate").html('<i class="fas fa-check"></i>');
                            }
                        })
                    } else {

                        formWarning({type: 'warning', message: 'Existem produtos sem codigo cadastrado'});
                    }
                }else{
                    formWarning({type: 'warning', message: 'O campo forma de usuário é obrigatório'});
                }

            } else {

                formWarning({type: 'warning', message: 'O campo forma de pagamento é obrigatório'});
            }
        })
    });

    function changeCode(element) {
        event.preventDefault();

        var data = $(element).serializeArray();

        if ( data[2]['value'] != '' ) {
            $.ajax({
                url: url_codigo_update,
                type: 'post',
                data: $(element).serialize(),
                beforeSend: function () {
                    // $(element).html('<i class="fas fa-spin fa-spinner"></i> Aguarde');
                    $.each($('button, input', element), function (i, v) {
                        $(v).attr('disabled', true);
                    })
                    Pace.start();
                },
                success: function (xhr) {

                    if ( xhr['type'] == 'success' ) {
                         window.location.reload();
                    }
                },
                error: function (xhr) {
                }
            });
        } else {
            formWarning({type: 'warning', 'message': 'O campo código é obrigatório'});
        }
    }
</script>

</html>
