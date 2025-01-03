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
                    <div class="col-12">
                        <div class="table-reponsive">
                            <table id="data-table" class="table table-condensed table-hover" data-url="<?php echo $datatable; ?>" data-update="<?php echo $url_update; ?>">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Data da Criação</th>
                                        <th>CNPJ</th>
                                        <th>Razao Social</th>
                                        <th>Cidade</th>
                                        <th>UF</th>
                                        <th>Total de Itens</th>
                                        <th>Total do Pedido (R$)</th>
                                        <th>Status</th>
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
    $(document).ready(function () {
        var table = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            lengthChange: false,
            responsive: true,
            pageLength: 10,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'POST',
                dataType: 'json'
            },
            columns: [
                {name: 'id', data: 'id'},
                {name: 'data_criacao', data: 'data_criacao', className: 'text-nowrap'},
                {name: 'cnpj', data: 'cnpj'},
                {name: 'razao_social', data: 'razao_social'},
                {name: 'cidade', data: 'cidade'},
                {name: 'uf', data: 'uf'},
                {name: 'total_itens', data: 'total_itens'},
                {name: 'total', data: 'total'},
                {name: 'status', data: 'status'}
            ],
            rowCallback: function (row, data) {
                $(row).data('id', data.id).css('cursor', 'pointer');
                $('td:eq(8)', row).html((data.status == 1) ? "Finalizado" : (data.status == 2) ? "Recusado" : "Aberto");
            },
            drawCallback: function () {
                $('table tbody tr').each(function () {
                    $(this).on('click', function () {
                        console.log($(this).data('id'));
                        window.location.href = $('#data-table').data('update') + $(this).data('id')
                    })
                })
            }
        });
    });
</script>
</html>
