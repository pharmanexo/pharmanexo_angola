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
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="table-warning"
                             style="width: 15px; height: 15px; border-radius: 20%;  display: inline-block; "></div>
                        &nbsp;Cotação Descartada
                    </div>
                </div>
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
                            <th>Descarte</th>
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
                { name: 'cot.motivo_recusa', data: 'motivo_recusa_text'},
            ],
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0 },
            ],
            select: {style: 'multiple', selector: 'td'},
            order: [[ 4, 'asc' ]],
            rowCallback: function(row, data) {
                if (data.motivo_recusa > 0){
                    $(row).addClass('table-warning');
                }
            },
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

