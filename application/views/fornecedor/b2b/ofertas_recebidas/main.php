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
                <div class="row mx-auto mt-3">
                    <div class="col-12 col-sm">
                        <div class="table-responsive">
                            <table id="data-table" class="table table-condensend table-hover w-100" data-url="<?php echo $datatables; ?>" data-detalhes="<?php echo $url_detalhes; ?>">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Status</th>
                                    <th>Data oferta</th>
                                    <th>Fornecedor Interessado</th>
                                    <th>CNPJ</th>
                                    <th>Estado</th>
                                    <th>Total itens</th>
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

    var url_detalhes = $('#data-table').data('detalhes');

    $(function () {

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
                {data: 'status', name: 'status', searchable: false},
                {data: 'data', name: 'id_solicitacao', searchable: false},
                {data: 'razao_social', name: 'razao_social', className: 'text-nowrap'},
                {data: 'cnpj', name: 'cnpj'},
                {data: 'estado', name: 'estado'},
                {data: 'itens', name: 'itens'},
            ],
            order: [[1, 'asc']],
            rowCallback: function (row, data) {
                $(row).data('id_solicitacao', data.id_solicitacao).css('cursor', 'pointer');

                $('td', row).each(function() {
                    $(this).on('click', function () {
                        window.location.href = `${url_detalhes}${data.id_solicitacao}`;
                    });
                });

            },
            drawCallback: function () {
            }
        });
    });
</script>
</html>