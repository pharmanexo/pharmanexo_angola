<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>
<div class="content">
    <?php echo $heading; ?>
    <div class="content__inner">
        <div class="alert alert-primary" role="alert">
            <i class="fas fa-exclamation-circle"></i></i> Não deixe de conferir nossas promoções. 
            <a href="<?php echo base_url('/fornecedor/b2b/promocoes'); ?>" class="alert-link"><b>Clique aqui!</b></a>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row mx-auto mt-3">
                    <div class="col-12 col-sm">
                        <table id="table-oferta" class="table table-condensend table-hover w-100" data-url="<?php echo $datatables; ?>" data-interesse="<?php echo $tenho_interesse; ?>">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Descrição</th>
                                <th>Preço Unitário</th>
                                <th>Estoque</th>
                                <th>Qtde</th>
                                <th>Valor Máximo</th>
                                <th>Pz. Entrega</th>
                                <th>Forma Pagamento</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

<?php echo $scripts; ?>

<script>
    var url = $('#table-oferta').data('url');
    var url_interesse = $('#table-oferta').data('interesse');

    $(function () {
        var table = $('#table-oferta').DataTable({
            processing: true,
            serverSide: false,
            lengthChange: false,
            pageLength: 50,
            ajax: {
                url: $('#table-oferta').data('url'),
                type: 'POST',
                dataType: 'json'
            },
            columns: [
                {defaultContent: '', orderable: false, searchable: false},
                {data: 'descricao', name: 'descricao', className: 'text-wrap'},
                {defaultContent: '', orderable: false, searchable: false, width: '100px'},
                {data: 'quantidade', name: 'quantidade'},
                {defaultContent: '', orderable: false, searchable: false, width: '100px'},
                {defaultContent: '', orderable: false, searchable: false, width: '100px'},
                {data: 'prazo_entrega', name: 'prazo_entrega'},
                {data: 'forma_pagamento', name: 'forma_pagamento'},
            ],
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0}
            ],
            select: {style: "multi", selector: "td:first-child"},
            order: [[1, 'asc']],
            rowCallback: function (row, data) {
                $(row).data('id', data.id).css('cursor', 'pointer');

                var preco = $(`<input type="text" name="preco_unitario" value="${data.preco}" class="form-control preco" style="width: 100px" readonly>`);
                var input = $(`<input type="text" name="vl_max" class="form-control vl_max" style="width: 100px" >`);
                var input_qtd = $(`<input type="text" name="qtd" class="form-control qtd" style="width: 100px" >`);

                $('td:eq(2)', row).html(preco);
                $('td:eq(5)', row).html(input);
                $('td:eq(4)', row).html(input_qtd);
            },
            drawCallback: function () {

                $('[data-toggle="tooltip"]').tooltip();

                $('.vl_max').maskMoney({
                    thousands: ".",
                    decimal: ",",
                    precision: 4
                }).maskMoney('mask');

            }
        });

        $('#btnAdicionar').click(function (e) {
            e.preventDefault();
            var dados = [];

            $('.selected').each(function (i, a) {
                var data = $(this).data();
                var inpt = [];
                $(this).find('input').each(function (i, a) {
                    var name = $(this).attr('name');
                    var val = $(this).val();
                   inpt[name] = val;
                });

                var r = {
                    "id": data.id,
                    "id_fornecedor": data.id_fornecedor,
                    "preco_unitario": inpt['preco_unitario'],
                    "qtd": inpt['qtd'],
                    "vlmax": inpt['vl_max']
                };

               dados.push(r);
            });

            console.log(dados);

            if (dados.length > 0) {
                $.post(url_interesse, {dados: dados}, function (xhr) {
                    table.ajax.reload();
                    formWarning(xhr);
                }, 'JSON')
                .fail(function (xhr) {
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
    });
</script>
</html>