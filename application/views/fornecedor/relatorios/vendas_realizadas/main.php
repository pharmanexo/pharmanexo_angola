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
                        <div class="col-md-4 col-xs-12 form-group">
                            <label for="filtro-cliente">Cliente</label>
                            <input type="text" class="form-control" id="filtro-cliente" data-index="3">
                        </div>

                        <div class="col-md-4 col-xs-12 form-group">
                            <label for="filtro-data-emissao">Data Emissão</label>
                            <div class="input-group date">
                                <input type="text" class="form-control" id="filter-start-date" data-index="7">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-light">a</span>
                                </div>
                                <input type="text" class="form-control" id="filter-end-date" data-index="7">
                            </div>
                        </div>

                        <div class="col-md-4 col-xs-12 form-group">
                            <label for="filtro-status">Status</label>
                            <select class="form-control" id="filtro-status" data-index="8">
                                <option value="">Selecione...</option>
                                <?php foreach ($options as $item) : ?>
                                    <option value="<?php echo $item['id']; ?>"><?php echo $item['status_ordem_compra']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="data-table" class="table table-condensend table-hover" data-url="<?php echo $dataTable; ?>" data-detail="<?php echo $url_detalhes ?>">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Ordem Compra</th>
                                    <th>CNPJ</th>
                                    <th>Razão Social</th>
                                    <th>Valor Total</th>
                                    <th>Status</th>
                                    <th>Forma de Pagamento</th>
                                    <th>Data de Emissão</th>
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
</body>

<?php echo $scripts; ?>

<script>
    var url_detalhes = $('#data-table').data('detail');
    var dt;

    $(function() {
        $('#filter-start-date, #filter-end-date').datepicker({
            format: "dd/mm/yyyy",
            language: "pt-BR",
            orientation: "bottom auto",
            autoclose: true
        });

        dt = $('#data-table').DataTable({
            serverSide: false,
            lengthChange: false,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'get',
                dataType: 'json',
                data: function(data) {
                    let nw_data = data;

                    if ($('#filter-start-date').val() !== '') {
                        let dt1 = $('#filter-start-date').val().split('/');
                        let dt2 = ($('#filter-end-date').val() !== '') ? $('#filter-end-date').val().split('/') : dt1;
                        nw_data.columns[7].search.value = `${dt1[2]}-${dt1[1]}-${dt1[0]},${dt2[2]}-${dt2[1]}-${dt2[0]}`;
                        nw_data.columns[7].search.type = 'date';
                    }

                    return nw_data;
                }
            },
            columns: [{
                    name: 'ordens_compra.id',
                    data: 'id',
                    visible: false
                },
                {
                    name: 'ordens_compra.ordem_compra',
                    data: 'ordem_compra',
                    searchable: true
                },
                {
                    name: 'dados_usuarios.cnpj',
                    data: 'cnpj',
                    searchable: true
                },
                {
                    name: 'dados_usuarios.razao_social',
                    data: 'razao_social',
                    searchable: true
                },
                {
                    name: 'ordens_compra.valor_total',
                    data: 'valor_total',
                    searchable: true
                },
                {
                    name: 'status_ocs.descricao',
                    data: 'status_ordem_compra',
                    searchable: true
                },
                {
                    name: 'ordens_compra.condicao_pagamento',
                    data: 'condicao_pagamento',
                    searchable: true
                },
                {
                    name: 'ordens_compra.data_emissao',
                    data: 'data_emissao',
                    searchable: true
                },
                {
                    name: 'ordens_compra.id_status_ordem_compra',
                    data: 'id_status_ordem_compra',
                    searchable: true,
                    visible: false
                },
                {
                    defaultContent: '',
                    width: '100px',
                    orderable: false,
                    searchable: false
                }
            ],

            rowCallback: function(row, data) {
                $(row).data('id', data.id).css('cursor', 'pointer');
                var btnView = $(`<a href="${url_detalhes}/${data.id}" class="text-primary openModal"><i class="fas fa-search"></i></a>`);
                $('td:eq(7)', row).append(btnView);
            },

            drawCallback: function() {
                $(".dataTables_filter").hide();
            }
        });

        $('[data-index]').on('keyup change', function() {
            var col = $(this).data('index');
            var value = $(this).val();

            dt.columns(col).search(value).draw();
        });

        // remove filter
        $('[data-action="reset-filter"]').click(function(e) {
            e.preventDefault();
            $('[data-index]').val(null);
            $('#data-table').columns([0, 1, 2, 4]).search('').draw();
        });
    });
</script>

</html>