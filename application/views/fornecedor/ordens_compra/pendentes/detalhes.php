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
                <?php if (isset($matriz) && $matriz == 1) { ?>
                    <h4 class="card-title">Selecione uma forma de pagamento para efeturar resgate</h4>

                    <div class="row">
                        <?php if (isset($formas_pagamento) && !empty($formas_pagamento)): ?>
                            <div class="col-4">
                                <div class="form-group">
                                    <label>Selecionar Forma de Pagamento</label> <select class="select2"
                                                                                         id="forma_pagto"
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
                                <select class="select2" name="usuario_resgate" required data-placeholder="Selecione... "
                                        id="usuario_resgate">
                                    <option value=""></option>
                                    <?php foreach ($usuarios as $usuario) { ?>
                                        <option value="<?php echo $usuario['usuario']; ?>"><?php echo $usuario['nome']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>


                    </div>
                <?php } ?>

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

            </div>
        </div>


        <div class="card" data-cnpj="<?php echo $oc['comprador']['cnpj']; ?>"
             data-resgate="<?php echo $url_resgate; ?>" data-codigo="<?php echo $url_codigo; ?>"
             data-has_no_code="<?php echo $oc['hasNoCode']; ?>" id="produtos">
            <div class="card-header">
                <p class="card-title">Produtos</p>
            </div>
        </div>
        <?php if (isset($oc['produtos'])) { ?>
            <?php foreach ($oc['produtos'] as $kk => $produto) { ?>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-2">

                                <p><strong>Código</strong> <br>
                                    <?php if (isset($produto['codigo']) && !empty($produto['codigo'])): ?>

                                        <?php echo $produto['codigo']; ?>

                                    <?php else: ?>
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

                                </p>

                            </div>
                            <div class="col-4">
                                <p><strong>Produto Comprador</strong>
                                    <br> <?php echo $produto['Ds_Produto_Comprador']; ?></p>
                            </div>
                            <div class="col-4">
                                <p><strong>Produto Catálogo</strong>
                                    <br> <?php if (isset($produto['produto_catalogo'])) echo $produto['produto_catalogo']; ?>
                                </p>
                            </div>
                            <div class="col-2">
                                <p><strong>Marca</strong> <br> <?php echo $produto['Ds_Marca']; ?></p>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-4">
                                <p><strong>Obs Produto Cotação</strong>
                                    <br> <?php if (isset($produto['obs_cot_produto'])) echo $produto['obs_cot_produto']; ?>
                                </p>
                            </div>
                            <div class="col-2">

                                <p><strong>Und Compra</strong> <br> <?php echo $produto['Ds_Unidade_Compra']; ?></p>

                            </div>
                            <div class="col-2">

                                <p><strong>Qtd. Embalagem: </strong><?php echo $produto['Qt_Embalagem']; ?> <br>
                                    <strong>Qtd Solicitada: </strong><?php echo $produto['Qt_Produto']; ?>
                                </p>
                            </div>
                            <div class="col-2">
                                <p><strong>Preço</strong>
                                    <br> <?php echo number_format($produto['Vl_Preco_Produto'], 4, ',', '.') ?></p>
                            </div>
                            <div class="col-2">

                                <p class="text-success"><strong>Total</strong>
                                    <br> <?php echo number_format(($produto['Vl_Preco_Produto'] * $produto['Qt_Produto']), 4, ',', '.') ?>
                                </p>

                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>

        <div class="card">
            <div class="card-header">
                <p class="card-title">
                <div class="row">
                    <div class="col-6">
                        <h2>
                            <strong>Total do Pedido</strong>
                        </h2>
                    </div>
                    <div class="col-6 text-right">
                        <h2>
                            <strong><?php if (isset($oc['total'])) echo number_format($oc['total'], "4", ',', '.'); ?></strong>
                        </h2>
                    </div>
                </div>
                </p>
            </div>
        </div>

    </div>
</div>
</body>
<?php echo $scripts; ?>
<script>

    var url_resgate = $("#produtos").data('resgate');
    var url_codigo_update = $("#produtos").data('codigo');
    var url_list = "<?php if (isset($url_list)) echo $url_list; ?>";
    var url_changestatus = "<?php if (isset($urlChangeStatusPending)) echo $urlChangeStatusPending; ?>";

    $(function () {


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

            var cnpj = $("#produtos").data('cnpj');
            var forma_pagto = $("#forma_pagto").val();
            var usuario = $("#usuario_resgate").val();

            if ($('#produtos').data('has_no_code') == 0) {

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
                        } else {
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

        })
    });

    function changeCode(element) {
        event.preventDefault();

        var data = $(element).serializeArray();

        if (data[2]['value'] != '') {
            $.ajax({
                url: url_codigo_update,
                type: 'post',
                data: $(element).serialize(),
                beforeSend: function () {
                    // $(element).html('<i class="fas fa-spin fa-spinner"></i> Aguarde');
                    $.each($('button, input', element), function (i, v) {
                        $(v).attr('disabled', true);
                    })
                },
                success: function (xhr) {

                    if (xhr['type'] == 'success') {
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
