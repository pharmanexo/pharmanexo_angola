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
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="filtro-situacao">Filtrar por situação</label>
                            <select class="select2" id="filtro-situacao" data-index="5">
                                <option value="">Selecione</option>
                                <?php foreach($situacao as $key => $value) { ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 col-xs-12 form-group">
                        <label for="filtro-data-emissao">Data do Pedido</label>
                        <div class="input-group date">
                            <input type="text" class="form-control" id="filter-start-date" data-index="2">
                            <div class="input-group-append">
                                <span class="input-group-text bg-light">a</span>
                            </div>
                            <input type="text" class="form-control" id="filter-end-date" data-index="2">
                        </div>
                    </div>
                </div>
                <br>
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="bg-light mr-2" style="width: 15px; height: 15px; border-radius: 20%; display: inline-block"></div>
                        Em aberto
                        <div class="table-secondary mr-2 ml-2" style="width: 15px; height: 15px; border-radius: 20%; display: inline-block"></div>
                        Enviado para análise
                        <div class="table-secondary mr-2 ml-2" style="width: 15px; height: 15px; border-radius: 20%; display: inline-block"></div>
                        Aguardando Comprador
                        <div class="table-warning ml-2" style="width: 15px; height: 15px; border-radius: 20%; display: inline-block"></div>
                        Aprovado parcialmente
                        <div class="table-info mr-2 ml-2" style="width: 15px; height: 15px; border-radius: 20%; display: inline-block"></div>
                        Aprovado
                        <div class="table-success mr-2 ml-2" style="width: 15px; height: 15px; border-radius: 20%; display: inline-block"></div>
                        Faturado
                        <div class="table-danger ml-2" style="width: 15px; height: 15px; border-radius: 20%; display: inline-block"></div>
                        Cancelado
                    </div>
                </div>
                <div class="row mx-auto mt-3">
                    <div id="" class="col-12">
                        <div class="table-responsive col-sm">
                            <table id="data-table" class="table table-condensend table-hover" data-url="<?php echo $to_datatable; ?>" data-cancel="<?php if (isset($url_cancel)) echo $url_cancel ?>" data-update="<?php if (isset($url_update)) echo $url_update ?>">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Data Abetura</th>
                                    <th>Fornecedor</th>
                                    <th>Situação</th>
                                    <th></th>
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
    var url_cancel = $('#data-table').data('cancel');

    $(function () {
        var table = $('#data-table').DataTable({
            serverSide: true,
            lengthChange: false,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'post',
                dataType: 'json',
                data: function (data) {
                    let nw_data = data;

                    if ($('#filter-start-date').val() !== '') {
                        let dt1 = $('#filter-start-date').val().split('/');
                        let dt2 = ($('#filter-end-date').val() !== '') ? $('#filter-end-date').val().split('/') : dt1;

                        nw_data.columns[2].search.value = `${dt1[2]}-${dt1[1]}-${dt1[0]},${dt2[2]}-${dt2[1]}-${dt2[0]}`;
                        nw_data.columns[2].search.type = 'date';
                    }

                    console.log(nw_data);
                    return nw_data;
                }
            },
            columns: [
                {name: 'cp.id', data: 'id', visible: false},
                {name: 'cp.data_pedido', data: 'data_pedido'},
                {name: 'f.nome_fantasia', data: 'fornecedor'},
                {name: 'cp.situacao', data: 'situacao_lbl'},
                {name: 'cp.situacao', data: 'situacao', orderable: false, searchable: true, visible: false},
                {defaultContent: '', width: '100px', orderable: false, searchable: false},
            ],
            "order": [[1, "desc"]],
            rowCallback: function (row, data) {
                $(row).data('id', data.id).css('cursor', 'pointer');

                switch (data.situacao) {
                    case '1':
                        $(row).addClass('table-light');
                        break;
                    case '2':
                        $(row).addClass('table-secondary');
                        break;
                    case '3':
                        $(row).addClass('table-info');
                        break;
                    case '4':
                        $(row).addClass('table-success');
                        break;
                    case '5':
                        $(row).addClass('table-danger');
                        break;
                    case '6':
                        $(row).addClass('table-warning');
                        break;
                    case '7':
                        $(row).addClass('table-ocre');
                        break;
                }

                $('td:not(:last-child)', row).each(function () {
                    $(this).on('click', function () {
                        window.location.href = $('#data-table').data('update') + data.id
                    });
                });

            },
            drawCallback: function () {

                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        $('#filter-start-date, #filter-end-date').datepicker({
            format: "dd/mm/yyyy",
            language: "pt-BR",
            orientation: "bottom auto",
            autoclose: true
        });

        $('[data-index]').on('keyup change', function () {
            var col = $(this).data('index');
            var value = $(this).val();
            table.columns(col).search(value).draw();
        });

        $('[data-action="reset-filter"]').click(function (e) {
            e.preventDefault();
            $('[data-index]').val(null);
            table.columns([0, 1, 2, 4, 5, 6, 7]).search('').draw();
        });
    });


    function cancel_request(id_pedido) {
        $.ajax({
            url: url_cancel,
            type: 'post',
            data: {id_pedido: id_pedido},
            success: function (xhr) {
                $('#data-table').DataTable().ajax.reload();
            },
            error: function (xhr) {
                console.log(xhr);
                formWarning({type: 'warning', message: "Erro ao cancelar pedido"});
            }
        })
    }
</script>

</html>