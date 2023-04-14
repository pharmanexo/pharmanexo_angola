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

                <p class="text-muted border-bottom"><strong>Dados da Ordem de Compra</strong></p>
                <div class="row">
                    <div class="col-12 col-lg-2">
                        <strong>Número</strong> <br>
                        <?php echo $oc['id_solicitacao']; ?>
                    </div>
                    <div class="col-12 col-lg-4" <?php if (isset($oc['usuario_cancelamento'])) echo "data-toggle='tooltip' data-title='RESPONSAVEL CANCELAMENTO: {$oc['usuario_cancelamento']['id']} - {$oc['usuario_cancelamento']['email']}' " ?>>
                        <strong>Situação</strong> <br>
                        <?php echo $oc['status']; ?>
                    </div>
                </div>

                <p class="text-muted border-bottom mt-4"><strong>Dados de Faturamento</strong></p>
                <div class="row">
                    <div class="col-12 col-lg-3">
                        <strong>Empresa</strong> <br>
                        <?php if (isset($oc['nome_fantasia'])) echo $oc['nome_fantasia']; ?>
                    </div>
                    <div class="col-12 col-lg-3">
                        <strong>CNPJ</strong> <br>
                        <?php if (isset($oc['cnpj'])) echo $oc['cnpj']; ?>
                    </div>
                    <div class="col-12 col-lg-6">
                        <strong>Contatos</strong> <br>
                        <?php if (isset($oc['email'])) echo $oc['email']; ?> / <?php if (isset($oc['telefone'])) echo $oc['telefone']; ?>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12 col-lg-3">
                        <strong> Condição de Pagamento</strong> <br>
                        <?php echo isset($oc['fp']) ? $oc['fp'] : 'Não informado' ?>
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


        <div class="card" data-cnpj="<?php echo $oc['cnpj']; ?>"
             data-resgate="<?php echo $url_resgate; ?>" data-codigo="<?php echo $url_codigo; ?>"
             data-has_no_code="<?php if (isset($oc['hasNoCode'])) echo $oc['hasNoCode']; ?>" id="produtos">
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

                                <?php endif; ?>

                                </p>

                            </div>
                            <div class="col-4">
                                <p><strong>Produto Catálogo</strong>
                                    <br> <?php if (isset($produto['nome_comercial'])) echo $produto['nome_comercial']; ?> - <?php if (isset($produto['apresentacao'])) echo $produto['apresentacao']; ?>
                                </p>
                            </div>
                            <div class="col-2">
                                <p><strong>Marca</strong> <br> <?php echo $produto['marca']; ?></p>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-4">
                                <p><strong>Obs Produto Cotação</strong>
                                    <br> <?php if (isset($produto['obs_cot_produto'])) echo $produto['obs_cot_produto']; ?>
                                </p>
                            </div>
                            <div class="col-2">

                                <p><strong>Und Compra</strong> <br> <?php echo $produto['unidade']; ?></p>

                            </div>
                            <div class="col-2">

                                <p><strong>Qtd. Embalagem: </strong><?php echo $produto['quantidade_unidade']; ?> <br>
                                    <strong>Qtd Solicitada: </strong><?php echo $produto['quantidade']; ?>
                                </p>
                            </div>
                            <div class="col-2">
                                <p><strong>Preço</strong>
                                    <br> <?php echo number_format($produto['preco'], 4, ',', '.') ?></p>
                            </div>
                            <div class="col-2">

                                <p class="text-success"><strong>Total</strong>
                                    <br> <?php echo number_format(($produto['preco'] * $produto['quantidade']), 4, ',', '.') ?>
                                </p>

                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        <?php } ?>

        <div class="card" hidden>
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

        $('.btnCancelItem').click(function (e) {

            var url = $(this).data('href');

            Swal.fire({
                title: 'Deseja rejeitar este item?',
                text: 'Informe o motivo do cancelamento',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off',
                    required: true
                },
                inputValidator: (value) => {
                    if (value.length == 0) {
                        return 'Informe o motivo'
                    }
                },
                showCancelButton: true,
                confirmButtonText: 'Rejeitar Item',
                cancelButtonText: 'Fechar',
                showLoaderOnConfirm: true
            }).then((result) => {

                if (result.isConfirmed) {

                    $.post(url, {motivo: result.value}, function (xhr) {
                        window.location.reload();
                    })
                }
            })
        });

        $('#btnCancelOc').click(function (e) {
            e.preventDefault();

            var url = $(this).attr('href');

            Swal.fire({
                title: 'Deseja rejeitar esta ordem de compra?',
                text: 'Informe o motivo do cancelamento',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off',
                    required: true
                },
                inputValidator: (value) => {
                    if (value.length == 0) {
                        return 'Informe o motivo'
                    }
                },
                showCancelButton: true,
                confirmButtonText: 'Rejeitar Item',
                cancelButtonText: 'Fechar',
                showLoaderOnConfirm: true
            }).then((result) => {

                if (result.isConfirmed) {

                    $.post(url, {motivo: result.value}, function (xhr) {
                        Swal.fire({
                            position: 'center',
                            icon: xhr.type,
                            title: xhr.message,
                            showConfirmButton: false,
                            timer: 3000
                        }).then(function () {
                            window.location = xhr.redir;
                        });
                    })
                }
            })
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
