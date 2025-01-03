<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>
<div class="content">
    <?php echo $heading; ?>
    <div class="content__inner">
        <div class="card mb-3">
            <div class="card-header">
                <div class="card-title">
                    Dados do Distribuidor Interessado
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">CNPJ</label>
                            <input type="text" class="form-control" value="<?php echo $dados['cnpj']; ?>" readonly>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">Razão Social</label>
                            <input type="text" class="form-control" value="<?php echo $dados['razao_social']; ?>" readonly>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="">Estado</label>
                            <input type="text" class="form-control" value="<?php echo $dados['estado']; ?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <p>Dados para contato</p>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="">Telefone</label>
                            <input type="text" class="form-control" value="<?php echo $dados['telefone']; ?>" readonly>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="">Celular</label>
                            <input type="text" class="form-control" value="<?php echo $dados['celular']; ?>" readonly>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="">E-mail</label>
                            <input type="text" class="form-control" value="<?php echo $dados['email']; ?>" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="data-table" class="table table-condensend table-hover w-100" data-url="<?php echo $datatables; ?>" data-urlrejeitar="<?php if (isset($url_rejeitar)) echo $url_rejeitar; ?>">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Produto</th>
                                    <th>Forma Pagamento</th>
                                    <th>Prazo Entrega</th>
                                    <th>Quantidade</th>
                                    <th>Valor Máximo</th>
                                    <th></th>
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
</body>

<?php echo $scripts; ?>

<script>
    var url_rejeitar = $('#data-table').data('urlrejeitar');
    $(function () {

        $('.btn_rejeitar').click(function (e) {

            e.preventDefault();

            var url = $(this).attr('href');
            Swal.fire({
                title: 'Informe o motivo para rejeitar a proposta',
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


        }).tooltip({
            title: 'Rejeitar toda a proposta'
        });

        $('.btn_aceitar').click(function (e) {
            e.preventDefault();

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

                        setTimeout(function () {
                            location.reload();
                        }, 3000);
                    })

                }
            })


        }).tooltip({
            title: 'Apenas itens não rejeitados serão aprovados.'
        });

        var table = $('#data-table').DataTable({
            processing: true,
            serverSide: false,
            lengthChange: false,
            responsive: true,
            pageLength: 10,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'POST',
                dataType: 'json'
            },
            columns: [
                {data: 'id_solicitacao', name: 'id_solicitacao', visible: false},
                {data: 'descricao', name: 'descricao', clasName: 'text-nowrap'},
                {data: 'id_forma_pagamento', name: 'id_forma_pagamento'},
                {data: 'id_prazo_entrega', name: 'id_prazo_entrega'},
                {data: 'quantidade', name: 'quantidade'},
                {data: 'valor_maximo', name: 'valor_maximo'},
                {defaultContent: '', orderable: false, searchable: false, width: '100px'},
            ],
            order: [[1, 'asc']],
            rowCallback: function (row, data) {
                $(row).data('id_solicitacao', data.id_solicitacao).css('cursor', 'pointer');


                var btn = $(`<button data-href="${url_rejeitar}${data.codigo}" data-toggle="tooltip" title="Rejeitar oferta do item" class="btn btn-link text-danger"><i class="fas fa-ban"></i></button>`);

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
                                $.post(url, {motivo: result.value}, function (xhr) {
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
                        $('td:eq(5)', row).html('Aprovado');
                        break;
                    case '9':
                        $(row).addClass('table-danger');
                        $('td:eq(5)', row).html('Rejeitado');
                        break;
                    default:
                        $('td:eq(5)', row).html(btn);
                        break;
                }

            },
            drawCallback: function () {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    });
</script>
</html>