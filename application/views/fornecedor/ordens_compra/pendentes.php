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
                <div class="legends"></div>
                <div class="table-responsive">
                    <table id="data-table" class="table table-condensed table-hover no-filtered" data-url="<?php if (isset($urlDatatables)) echo $urlDatatables; ?>" data-detalhes="<?php if (isset($urlDetalhes)) echo $urlDetalhes; ?>">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Data de Criação</th>
                            <th>Ordem Compra</th>
                            <th>Empresa</th>
                            <th>Valor (R$)</th>
                            <th>Entrega Acordada</th>
                            <th>Cotação</th>
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
    var dt;
    var urlDetalhes = $('#data-table').data('detalhes');
    $(function () {
        var dt1 = $('#data-table').DataTable({
            "processing": true,
            "serverSide": false,
            lengthChange: false,
            buttons: [],
            ajax: {
                url: $('#data-table').data('url'),
                type: 'get',
                dataType: 'json'
            },
            columns: [
                { name: 'ocs_sintese.id', data: 'id', width: '150px', visible: false},
                { name: 'ocs_sintese.Dt_Ordem_Compra', data: 'Dt_Ordem_Compra', width: '150px'},
                { name: 'ocs_sintese.Cd_Ordem_Compra', data: 'Cd_Ordem_Compra' },
                { name: 'compradores.razao_social', data: 'razao_social' },
                { name: 'valor', data: 'valor', searchable: false },
                { name: 'ocs_sintese.Dt_Previsao_Entrega', data: 'Dt_Previsao_Entrega' },
                { name: 'ocs_sintese.Cd_Cotacao', data: 'Cd_Cotacao' },
            ],
            "order": [[ 0, "desc" ]],
            rowCallback: function (row, data) {
                $(row).data('id', data.id).css('cursor', 'pointer');

                $('td', row).click(function (e) {
                    e.preventDefault();
                    let id = $(row).data('id');

                    window.location.href = urlDetalhes + id;

                })
            },
            drawCallback: function () {


            }
        });

    });
</script>

</html>
