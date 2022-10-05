<!DOCTYPE html>
<html lang="pt-BR">
<?php echo $header; ?>
<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>
<div class="content">
    <?php echo $heading; ?>
    <div class="card">
        <div class="card-body">
            <h4>Cliente</h4>
            <hr>
            <div class="row">
                <div class="col-5">
                    <p><?php echo "<strong>Razao Social - </strong>" . $entity['razao_social']; ?></p>
                    <p><strong>Endereço - </strong><?php echo (isset($entity['endereco']) && $entity['endereco'] !== '') ? "{$entity['endereco']}, {$entity['numero']}, {$entity['bairro']}, {$entity['cep']}" : ''; ?></p>
                </div>
                <div class="col-4">
                    <p><?php echo "<strong>CNPJ - </strong>" . $entity['cnpj']; ?></p>
                    <p><?php echo "<strong>Localidade -</strong> {$entity['cidade']} - {$entity['estado']}"; ?></p>
                </div>
                <div class="col-3">
                    <p><strong>STATUS DO PEDIDO - <?php echo ($entity['status'] == 1) ? "<span class='text-success'>Finalizado</span>" : (($entity['status'] == 2) ? "<span class='text-danger'>Recusado</span>" : "Aberto"); ?></strong></p>
                </div>
            </div>
            <p></p>
            <!-- <div class="row">
                <div class="col-10"></div>
                <div class="col-2">
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <a title="Ações" data-toggle="tooltip" class="text-primary"><i class="fas fa-cog"></i></a>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                            <button id="btn_recusar" class="dropdown-item" type="button">Recusar</button>
                            <button id="btn_retomar" class="dropdown-item" type="button">Retomar </button>
                        </div>
                    </div>
                </div>
            </div> -->
            <h4>Itens do Pedido</h4>
            <hr>
            <div class="row">
                <div class="col-12">
                    <div class="table-reponsive">
                        <table id="data-table" class="table table-condensed table-hover" data-url="<?php echo $datasource; ?>" data-status="<?php echo $url_status; ?>"data-update="<?php echo $url_update; ?>">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>ID</th>
                                    <th>Codigo</th>
                                    <th>Produto Descricao</th>
                                    <th>Marca</th>
                                    <th>Quantidade</th>
                                    <th>Preco Unidade</th>
                                    <th>Total</th>
                                    <th>Situação</th>
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
</body>
<?php echo $scripts; ?>
<script>
    var url_status = $('#data-table').data('status');

    $(document).ready(function () {
        let dt = $('#data-table');
        var groupColumn = 9;
        dt = dt.DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: dt.data('url'),
                type: 'get',
                dataType: 'json'
            },
            columns: [
                { defaultContent: '', orderable: false, searchable: false },
                { name: 'id', data: 'id'},
                { name: 'codigo', data: 'codigo'},
                { name: 'produto_descricao', data: 'produto_descricao' },
                { name: 'marca', data: 'marca' },
                { name: 'quantidade', data: 'quantidade' },
                { name: 'preco_unidade', data: 'preco_unidade' },
                { name: 'total', data: 'total' },
                { name: 'status', data: 'status', visible: true },
                { data: 'fornecedor', name: 'fornecedor' },
            ],
            columnDefs: [
                { orderable: false, className: 'select-checkbox', targets: 0 },
                { "visible": false, "targets": groupColumn }
            ],
            select: {
                style: "multi",
                selector: "td:first-child"
            },
            order: [0, 'asc'],
            rowCallback: function (row, data) {
                switch (data.status) {
                    // EM ANALISE
                    case '0':
                        $('td:eq(8)', row).html(`<a title="Em análise" data-toggle="tooltip" class="text-warning"><i class="fas fa-clock"></i></a>`);
                        break;
                    // ACEITO
                    case '1':
                        $('td:eq(8)', row).html(`<a title="Aceito" data-toggle="tooltip" class="text-success"><i class="fas fa-check"></i></a>`);
                        break;
                    // RECUSADO
                    case '2':
                        $('td:eq(8)', row).html(`<a title="Recusado" data-toggle="tooltip" class="text-danger"><i class="fas fa-ban"></i></a>`);
                        break;
                }
                $(row).data('id', data.id).css('cursor', 'pointer');
            },
            drawCallback: function (settings) {
                var api = this.api();
                var rows = api.rows( {page:'current'} ).nodes();
                var last=null;

                api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
                    if ( last !== group ) {
                        $(rows).eq( i ).before(
                            '<tr class="bg-light group"><td colspan="9">'+ group +'</td></tr>'
                        );
                        last = group;
                    }
                });

                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        $('#btn_recusar').click(function (e) {
            e.preventDefault();
            var ids = [];
            $.map(dt.rows('.selected').data(), function (item) { ids.push(item.id); });

            if (ids.length > 0) {
                $.ajax({
                    url: url_status,
                    type: 'get',
                    data: {
                        'ids': ids
                    },
                    dataType: 'html',
                    success: function (data) {
                        $('body').append(data);
                        $('.modal').modal('show').on('hide.bs.modal', function (e) {
                            $('.modal').remove();
                            dt.ajax.reload();
                        });
                    },
                    error: function(data) {
                        formWarning(data);
                        dt.ajax.reload();
                    },
                })
            } else {
                formWarning({
                    type: 'warning',
                    message: "Nenhum registro selecionado!"
                });
            }
        });

        $('#btn_retomar').on('click', function (e) {
            e.preventDefault();
            var ids = [];
            $.map(dt.rows('.selected').data(), function (item) { ids.push(item.id); });

            if (ids.length > 0) {
                $.ajax({
                    url: url_status,
                    type: 'post',
                    dataType: 'json',
                    data: {status: 0, justificativa: '', ids: ids, data: 'retornar'},
                    success: function (xhr) {
                        toastr[xhr.type](xhr.message);
                        dt.ajax.reload();
                    }
                })
            } else {
                formWarning({
                    type: 'warning',
                    message: "Nenhum registro selecionado!"
                });
            }
        });

        // var node = document.createElement("span");
        // var teste = document.getElementById("data-table_wrapper")
        //     // .getElementsByClassName("dataTables_buttons hidden-sm-down actions")
        //     .appendChild(node);

        $( "div#data-table_wrapper > div.actions" ).prepend('<span class="actions__item zmdi zmdi-print" data-table-action="print"></span>');

        // console.log(teste);

        // $("#data-table_wrapper")
        //     .find('.dataTables_buttons')
        //     .prepend('<span class="actions__item zmdi zmdi-print" data-table-action="print"></span>');


    });
</script>
</html>
