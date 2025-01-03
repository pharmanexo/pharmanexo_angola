<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner" id="printAll">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive col-sm">
                    <table id="data-table" class="table table-condensend table-hover" data-url="<?php echo $to_datatable; ?>" data-desocultar="<?php echo $url_desocultar; ?>">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Cotação</th>
                            <th>Comprador</th>
                            <th>Data inicio</th>
                            <th>Data Fim</th>
                            <th>Estado</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $scripts; ?>

<script>

    var url_desocultar = $('#data-table').data('desocultar');

    $(function () {

        var table = $('#data-table').DataTable({
            serverSide: false,
            lengthChange: false,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'post',
                dataType: 'json',
            },
            columns: [
                {defaultContent: '', orderable: false, searchable: false},
                { name: 'cot.cd_cotacao', data: 'cd_cotacao' },
                { name: 'c.razao_social', data: 'comprador' },
                { name: 'cot.dt_inicio_cotacao', data: 'dataini'},
                { name: 'cot.dt_fim_cotacao', data: 'datafim'},
                { name: 'cot.uf_cotacao', data: 'uf_cotacao'},
            ],
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0 },
            ],
            select: {style: 'multiple', selector: 'td'},
            order: [[ 4, 'asc' ]],
            rowCallback: function(row, data) {},
            drawCallback: function() {}
        });

        $('#btnDesocultar').click(function (e) {
            e.preventDefault();

            var dados = [];

            var selectedRows = $.map(table.rows('.selected').data(), function (item) {
               
                dados.push({
                    cd_cotacao: item.cd_cotacao,
                    integrador: item.integrador
                });
            });

            if (dados.length > 0) {
                $.post(url_desocultar, {dados}, function (xhr) {
                    table.ajax.reload();
                    formWarning(xhr);
                }, 'JSON')
                .fail(function(xhr) {
                    formWarning(xhr);
                    location.reload();
                });
            } else {

                formWarning({type: 'warning', message: "Nenhum registro selecionado!"});
            }
        });
    });
</script>
</body>

