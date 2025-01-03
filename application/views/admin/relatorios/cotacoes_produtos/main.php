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

                    <div class="col-12 mb-3">
                        <small>Selecione um fornecedor ou a filial para exibir os dados</small>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label>Fornecedor/Filial</label>
                            <select class="select2" id="fornecedor">
                                <option value="">Selecione</option>
                                <?php foreach ($fornecedores as $k => $v) : ?>
                                    <option value="<?php echo $v['id']; ?>"><?php echo $v['nome_fantasia']; ?></option>
                                <?php endforeach; ?>
                                <optgroup label="Filiais">
                                    <option value="oncoexo">ONCOEXO</option>
                                    <option value="oncoprod">ONCOPROD</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>

                    <div class="col-4">
                        <label>Data Inicio</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="zmdi zmdi-calendar"></i></span>
                            </div>
                            <input type="date" class="form-control hidden-md-up" placeholder="Selecione uma data">
                            <input type="text" id="data_ini" class="form-control date-picker hidden-sm-down" placeholder="Selecione">
                        </div>
                    </div>

                    <div class="col-4">
                        <label>Data Fim</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="zmdi zmdi-calendar"></i></span>
                            </div>
                            <input type="date" class="form-control hidden-md-up" placeholder="Selecione uma data">
                            <input type="text" id="data_fim" class="form-control date-picker hidden-sm-down" placeholder="Selecione">
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-12 form-group">
                        <label for="filtro-cot">Produtos</label>
                        <select class="form-control" multiple disabled data-url="<?php echo $select2; ?>" id="produtos" name="produtos[]"></select>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="data-table" class="table table-condensed table-hover no-filtered"
                           data-update="<?php echo $url_detalhes; ?>"
                           data-url="<?php echo $dataTable; ?>"
                           data-exportar="<?php echo $url_exportar; ?>"
                    >
                        <thead>
                        <tr>
                            <th>Cotação</th>
                            <th>Data da Cotação</th>
                            <th>CNPJ Comprador</th>
                            <th>Fornecedor</th>
                            <th>UF Comprador</th>
                            <th>Telefone</th>
                            <th class="text-nowrap">Valor Total</th>
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
    var url_exportar = $('#data-table').data('exportar');

    $(function () {

        newtable();

        $("#filter-start-date").flatpickr({"locale": "pt", "dateFormat": "d/m/Y", 'defaultDate': "<?php echo date('01/m/Y'); ?>"});
        $("#filter-end-date").flatpickr({"locale": "pt", "dateFormat": "d/m/Y", 'defaultDate': "<?php echo date('t/m/Y'); ?>"});

        $('#produtos').select2({
            ajax: {
                url: $('#produtos').data('url'),
                data: function (params) {

                    var query = {
                        search: params.term,
                        type: 'public',
                        id_fornecedor: $('#fornecedor').val().toString()
                    }

                    // Query parameters will be ?search=[term]&type=public
                    return query;
                },
                processResults: function (data) {
                    // Transforms the top-level key of the response object from 'items' to 'results'
                    return {
                        results: data.results
                    };
                }
            },
            placeholder: 'Search for a repository',
            minimumInputLength: 1,
        });

        $('#filter-start-date, #filter-end-date').on('change', function () {

            if ($('#fornecedor').val() != '') {

                $('#data-table').DataTable().destroy();

                newtable($(this).val());

            }
        });

        $('#fornecedor').on('change', function () {

            $('#produtos').prop('disabled', false);

        });

        $('#produtos').on('change', function () {
            $('#btnExport').attr('href', url_exportar + $(this).val());

            if ($(this).val() != "") {

                $('#data-table').DataTable().destroy();

                newtable($(this).val());
            }
        })
    });

    function newtable(id_fornecedor = null) {

        if (id_fornecedor != null) {

            var url = $('#data-table').data('url');

            var dt = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 500000,
                bFilter: false,
                searching: false,
                ajax: {
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    data: function (data) {
                        let nw_data = data;

                        data.id_fornecedor = $('#fornecedor').val().toString();
                        data.produtos = $('#produtos').val();
                        data.data_ini = $('#data_ini').val();
                        data.data_fim = $('#data_fim').val();

                        return nw_data;
                    }
                },
                order: [[1, "desc"]],
                columns: [
                    {name: 'cd_cotacao', data: 'cd_cotacao', width: '150px', searchable: true},
                    {name: 'data_cotacao', data: 'data_cotacao', searchable: true},
                    {name: 'cnpj', data: 'cnpj', width: '200px', searchable: true,},
                    {name: 'razao_social', data: 'razao_social', searchable: true,},
                    {name: 'uf_comprador', data: 'uf_comprador', searchable: true},
                    {name: 'telefone', data: 'telefone', searchable: true},
                    {name: 'total', data: 'total', searchable: true, visible: true},
                ],
                rowCallback: function (row, data) {
                    $(row).data('cd_cotacao', data.cd_cotacao).data('id_fornecedor', data.id_fornecedor).css('cursor', 'pointer');
                },
                drawCallback: function () {
                    $(".dataTables_buttons").hide();
                    $('table tbody tr').each(function () {
                        $(this).on('click', function () {
                            window.location.href = $('#data-table').data('update') + $(this).data('id_fornecedor') + '/' + $(this).data('cd_cotacao')
                        })
                    })
                }
            });
        } else {

            var dt = $('#data-table').DataTable({
                serverSide: false,
                processing: false,
                ordering: false,
                bFilter: false,
                searching: false,
                rowCallback: function (row, data) {
                },
                drawCallback: function () {
                    $(".dataTables_buttons").hide();
                }
            });
        }
    }

</script>

</html>
