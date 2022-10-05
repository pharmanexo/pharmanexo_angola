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
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="bg-light mr-2"
                             style="width: 15px; height: 15px; border-radius: 20%; display: inline-block"></div>
                        Em aberto
                        <div class="table-secondary mr-2 ml-2"
                             style="width: 15px; height: 15px; border-radius: 20%; display: inline-block"></div>
                        Enviado para análise
                        <div class="table-ocre mr-2 ml-2"
                             style="width: 15px; height: 15px; border-radius: 20%; display: inline-block"></div>
                        Aguardando comprador
                        <div class="table-warning ml-2"
                             style="width: 15px; height: 15px; border-radius: 20%; display: inline-block"></div>
                        Aprovado parcialmente
                        <div class="table-info mr-2 ml-2"
                             style="width: 15px; height: 15px; border-radius: 20%; display: inline-block"></div>
                        Aguardando Faturar
                        <div class="table-success mr-2 ml-2"
                             style="width: 15px; height: 15px; border-radius: 20%; display: inline-block"></div>
                        Faturado
                        <div class="table-danger ml-2"
                             style="width: 15px; height: 15px; border-radius: 20%; display: inline-block"></div>
                        Cancelado

                    </div>
                </div>

                <div class="table-responsive">
                    <table id="data-table" class="table w-100 table-hover" data-url="<?php echo $datatables; ?>"
                           data-detalhe="<?php echo $url_detalhes; ?>">
                        <thead>
                        <tr>
                            <th></th>
                            <th>ID</th>
                            <th>Representante</th>
                            <th>Comprador</th>
                            <th>Prazo Entrega</th>
                            <th>Situação</th>
                            <th>Data Abertura</th>
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

    var url_detalhes = $('#data-table').data('detalhe');

    $(function () {

        var table = $('#data-table').DataTable({
            serverSide: true,
            lengthChange: false,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'post',
                dataType: 'json'
            },
            columns: [
                {defaultContent: '', width: '100px', orderable: false, searchable: false},
                {name: 'pedidos_representantes.id', data: 'id', width: '80px'},
                {name: 'representantes.nome', data: 'representante'},
                {name: 'compradores.razao_social', data: 'comprador'},
                {name: 'pedidos_representantes.prazo_entrega', data: 'prazo_entrega', className: 'text-nowrap'},
                {name: 'status_situacao', data: 'status_situacao', className: 'text-nowrap'},
                {name: 'pedidos_representantes.data_abertura', data: 'data', className: 'text-nowrap'},
            ],
            "order": [[1, "desc"]],
            rowCallback: function (row, data) {
                $(row).data('id', data.id).css('cursor', 'pointer');

                if (data.prioridade == 1) {
                    $('td:eq(0)', row).html('<span class="fa-pisca"><i class="fa fa-fast-forward " data-toggle="tooltip" title="PEDIDO URGENTE"></i></span');
                    $(row).addClass('fa-pisca');
                    $(row).attr('data-toggle', 'tooltip');
                    $(row).prop('title', 'PEDIDO URGENTE');
                }

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


                $('td:not(:first-child):not(:last-child)', row).each(function () {
                    $(this).on('click', function () {
                        window.location.href = `${url_detalhes}${data.id}`
                    });
                });
            },
            drawCallback: function () {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    });

</script>
</html>