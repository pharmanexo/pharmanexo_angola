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
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6 col-xs-12 form-group">
                        <label for="filtro-cliente">Cliente</label>
                        <input type="text" class="form-control" id="filtro-cliente" data-index="3">
                    </div>

                    <div class="col-md-6 col-xs-12 form-group">
                        <label for="filtro-data-emissao">Data do Pedido</label>
                        <div class="input-group date">
                            <input type="text" class="form-control" id="filter-start-date">
                            <div class="input-group-append">
                                <span class="input-group-text bg-light">a</span>
                            </div>
                            <input type="text" class="form-control" id="filter-end-date" data-index="1">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="data-table" class="table table-condensed table-hover no-filtered"
                           data-update="<?php echo $url_detalhes; ?>" data-url="<?php echo $dataTable; ?>">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data da Criação</th>
                            <th>CODIGO OC</th>
                            <th>CNPJ</th>
                            <th>Razao Social</th>
                            <th>Itens</th>
                            <th>Valor Total</th>
                            <th>Integrador</th>
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
    $(function () {
        $('#filter-start-date, #filter-end-date').datepicker({
            format: "dd/mm/yyyy",
            language: "pt-BR",
            orientation: "bottom auto",
            autoclose: true
        });

        dt = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            lengthChange: false,
            responsive: true,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'post',
                dataType: 'json',
                data: function (data) {
                    let nw_data = data;

                    if ($('#filter-start-date').val() !== '') {
                        let dt1 = $('#filter-start-date').val().split('/');
                        let dt2 = ($('#filter-end-date').val() !== '') ? $('#filter-end-date').val().split('/') : dt1;


                        nw_data.columns[1].search.value = `${dt1[2]}-${dt1[1]}-${dt1[0]},${dt2[2]}-${dt2[1]}-${dt2[0]}`;
                        nw_data.columns[1].search.type = 'date';
                    }

                    return nw_data;
                }
            },

            columns: [
                {
                    name: 'id',
                    data: 'id',
                    searchable: true,
                    visible: false
                },
                {
                    name: 'Dt_Ordem_Compra',
                    data: 'Dt_Ordem_Compra',
                    searchable: true
                },
                {
                    name: 'Cd_Ordem_Compra',
                    data: 'Cd_Ordem_Compra',
                    searchable: true
                },
                {
                    name: 'cnpj',
                    data: 'cnpj',
                    searchable: true
                },
                {
                    name: 'razao_social',
                    data: 'razao_social',
                    searchable: true
                },
                {
                    name: 'total_itens',
                    data: 'total_itens',
                    searchable: true
                },
                {
                    name: 'total',
                    data: 'total',
                    searchable: true
                },
                {
                    name: 'integrador',
                    data: 'integrador',
                    searchable: true
                }
            ],
            rowCallback: function (row, data) {
                $(row).data('id', data.id).css('cursor', 'pointer');
            },
            drawCallback: function () {
                $(".dataTables_filter").hide();
                $('table tbody tr').each(function () {
                    $(this).on('click', function () {
                        window.location.href = $('#data-table').data('update') + $(this).data('id')
                    })
                })
            }
        });

        $('[data-index]').on('change', function () {
            dt.ajax.reload();
        });

        /*       // remove filter
               $('[data-action="reset-filter"]').click(function (e) {
                   e.preventDefault();
                   $('[data-index]').val(null);
                   $('#data-table').columns([0, 1, 2, 4]).search('').draw();
               });*/
    });
</script>

</html>