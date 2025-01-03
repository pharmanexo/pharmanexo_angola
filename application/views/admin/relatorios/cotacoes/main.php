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
                            <input type="text" class="form-control" id="filtro-cliente" data-index="4">
                        </div>

                        <div class="col-md-4 col-xs-12 form-group">
                            <label for="filtro-data-emissao">Data do Pedido</label>
                            <div class="input-group date">
                                <input type="text" class="form-control" id="filter-start-date" data-index="2">

                                <div class="input-group-append">
                                    <span class="input-group-text bg-light">a</span>
                                </div>

                                <input type="text" class="form-control" id="filter-end-date" data-index="2">
                            </div>
                        </div>

                        <div class="col-md-4 col-xs-12 form-group">
                            <label for="filtro-fornecedor">Fornecedor</label>
                            <select class="form-control" id="filtro-fornecedor" data-index="7">
                                <option value="">Selecione...</option>
                                <?php foreach ($fornecedores as $k => $v) : ?>
                                <option value="<?php echo $v['id_fornecedor']; ?>"><?php echo $v['razao_social']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row mx-auto mt-3">
                        <div class="col-12 col-sm">
                            <div class="table-responsive">
                                <?php $url = (isset($datatables)) ? $datatables : ''; ?>
                                <?php $detail = (isset($url_detail)) ? $url_detail : ''; ?>

                                <table id="dataTablesCotacoes" class="table table-condensend table-hover w-100" data-url="<?php echo $url; ?>" data-detail="<?php echo $detail; ?>">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Código Cotação</th>
                                            <th>Data da Cotação</th>
                                            <th>CNPJ Comprador</th>
                                            <th>UF Comprador</th>
                                            <th>Fornecedor</th>
                                            <th>Total de Itens</th>
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
    $(function() {
        $('#filter-start-date, #filter-end-date').datepicker({
            format: "dd/mm/yyyy",
            language: "pt-BR",
            orientation: "bottom auto",
            autoclose: true
        });
        $('#filtro-fornecedor').select2({
            dropdownAutoWidth: true,
            width: '100%',
            minimumResultsForSearch: Infinity
        });

        var dt = $('#dataTablesCotacoes').DataTable({
            processing: true,
            serverSide: true,
            lengthChange: false,
            pageLength: 10,
            ajax: {
                url: $('#dataTablesCotacoes').data('url'),
                type: 'POST',
                dataType: 'json',
                data: function(data) {
                    let response = data;

                    if ($('#filter-start-date').val() !== '') {
                        let dt1 = $('#filter-start-date').val().split('/');
                        let dt2 = ($('#filter-end-date').val() !== '') ? $('#filter-end-date').val().split('/') : dt1;

                        response.columns[2].search.value = `${dt1[2]}-${dt1[1]}-${dt1[0]},${dt2[2]}-${dt2[1]}-${dt2[0]}`;
                        response.columns[2].search.type = 'date';
                    }

                    return response;
                }
            },
            columns: [
                { name: 'id', data: 'id', searchable: false, visible: false },
                { name: 'id_cotacao', data: 'id_cotacao', searchable: false, visible: false },
                { name: 'data_cotacao', data: 'data_cotacao', searchable: true },
                { name: 'cnpj_comprador', data: 'cnpj_comprador', searchable: true },
                { name: 'uf_comprador', data: 'uf_comprador', searchable: true },
                { name: 'razao_social', data: 'razao_social', searchable: true },
                { name: 'id_cotacao', data: 'total_itens', searchable: true },
                { name: 'id_fornecedor', data: 'id_fornecedor', searchable: true, visible: false }
            ],

            rowCallback: function(row, data) {
                $(row).data('id', data.id_cotacao).css('cursor', 'pointer');
            },
            drawCallback: function() {
                $(".dataTables_filter").hide();

                $('table tbody tr').each(function() {
                    $(this).on('click', function() {
                        var id = $(this).data('id');
                        if(id != null) {
                            var url = $('#dataTablesCotacoes').data('detail') + '/' + id;
                            window.location.href = url;
                        }
                    });
                });
            }
        });

        $('[data-index]').on('keyup change', function() {
            var col = $(this).data('index');
            var value = $(this).val();

            dt.columns(col).search(value).draw();
        });

        $('[data-action="reset-filter"]').click(function(e) {
            e.preventDefault();
            $('[data-index]').val(null);
            $('#data-table').columns([0, 1, 2, 4]).search('').draw();
        });
    });
</script>

</html>
