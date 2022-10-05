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
                    <div class="table-responsive">
                        <table id="data-table" class="table table-condensed table-hover no-filtered" 
                            data-update="<?php echo $url_cotacao; ?>" 
                            data-url="<?php echo $datatables; ?>">
                            <thead>
                                <tr>
                                    <th>Cotação</th>
                                    <th>Descricao</th>
                                    <th>Comprador</th>
                                    <th>UF</th>
                                    <th class="text-nowrap">Total de Itens respondidos</th>
                                    <th class="text-nowrap">Valor Total respondido</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<?php echo $scripts; ?>
<script>

    $(function() {

        var dt = $('#data-table').DataTable({
            processing: true,
            serverSide: false,
            pageLength: 50,
            ajax: {
                url: $("#data-table").data('url'),
                type: 'post',
                dataType: 'json'
            },
            order: [[ 1, "desc" ]],
            columns: [
                { name: 'cp.cd_cotacao', data: 'cd_cotacao', width: '150px' },
                { name: 'cot.ds_cotacao', data: 'ds_cotacao' },
                { name: 'c.razao_social', data: 'comprador' },
                { name: 'cot.uf_cotacao', data: 'uf_cotacao', className: 'text-center' },
                { name: 'total_itens_respondidos', data: 'total_itens_respondidos', className: 'text-center' },
                { name: 'valor_total_respondido', data: 'valor_total_respondido', className: 'text-center' },
            ],
            rowCallback: function(row, data) {
                $(row).css('cursor', 'pointer');

                 $('td', row).each(function () {
                    $(this).on('click', function (e) {

                        e.preventDefault();

                        if ( data.cd_cotacao != undefined ) {

                            window.location.href = $('#data-table').data('update') + data.cd_cotacao
                        } 
                    });
                });
            },
            drawCallback: function() {
                $(".dataTables_filter").hide();
            }
        });
    });
</script>
</html>