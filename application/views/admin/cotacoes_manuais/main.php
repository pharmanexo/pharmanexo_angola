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
                            <label for="filtro-cliente">Fornecedor</label>
                            <select class="form-control" name="id_fornecedor" id="filtro-fornecedor" data-index="6">
                                <option value="">Selecione</option>
                                <?php foreach($fornecedores as $fornecedor) {?>
                                    <option value="<?php echo $fornecedor['id'] ?>"><?php echo $fornecedor['razao_social'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-6 col-xs-12 form-group">
                            <label for="filtro-data-emissao">Data da Cotação</label>
                            <div class="input-group date">
                                <input type="text" class="form-control" id="filter-start-date" data-index="2">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-light">a</span>
                                </div>
                                <input type="text" class="form-control" id="filter-end-date" data-index="2">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="data-table" class="table table-condensed table-hover no-filtered" data-update="<?php echo $url_detalhes; ?>" data-url="<?php echo $dataTable; ?>">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>ID Cotação</th>
                                    <th>Data da Cotação</th>
                                    <th>CNPJ Comprador</th>
                                    <th>UF Comprador</th>
                                    <th>Total de Itens</th>
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
        $('#filter-start-date, #filter-end-date').datepicker({
            format: "dd/mm/yyyy",
            language: "pt-BR",
            orientation: "bottom auto",
            autoclose: true
        });
        $('#id_fornecedor').select2({
            dropdownAutoWidth: true,
            width: '100%',
            minimumResultsForSearch: Infinity
        });

        var dt = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            lengthChange: false,
            responsive: true,
            pageLength: 10,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'get',
                dataType: 'json',
                data: function(data) {
                    let nw_data = data;
                   
                    if ($('#filter-start-date').val() !== '') {
                        let dt1 = $('#filter-start-date').val().split('/');
                        let dt2 = ($('#filter-end-date').val() !== '') ? $('#filter-end-date').val().split('/') : dt1;
                        nw_data.columns[2].search.value = `${dt1[2]}-${dt1[1]}-${dt1[0]},${dt2[2]}-${dt2[1]}-${dt2[0]}`;
                        nw_data.columns[2].search.type = 'date';
                    }

                    return nw_data;
                }
            },
            columns: [
                { name: 'id', data: 'id', searchable: true },
                { name: 'id_cotacao', data: 'id_cotacao', searchable: true },
                { name: 'data_cotacao', data: 'data_cotacao', searchable: true },
                { name: 'cnpj_comprador', data: 'cnpj_comprador', searchable: true },
                { name: 'uf_comprador', data: 'uf_comprador',searchable: true },
                { name: 'total_itens', data: 'total_itens', searchable: true },
                { name: 'id_fornecedor', data: 'id_fornecedor', searchable: true, visible: false },
            ],
            rowCallback: function(row, data) {
                $(row).data('id_cotacao', data.id_cotacao).data('submetido', data.submetido).css('cursor', 'pointer');
            },
            drawCallback: function() {
                $(".dataTables_filter").hide();
                $('table tbody tr').each(function () {
                    $(this).on('click', function () {
                        window.location.href = $('#data-table').data('update') + $(this).data('id_cotacao')
                    })
                })
            }
        });

        $('#filtro-fornecedor').on('change', function() {
            var col = $(this).data('index');
            var value = $(this).val();

            dt.columns(col).search(value).draw();
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