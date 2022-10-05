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
                        <div class="col-2 form-group">
                            <label for="filtro-cot">Código Cotação</label>
                            <input type="text" id="cd_cotacao" class="form-control">
                        </div>
                        <div class="col-3">
                            <label>Data Inicio</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="zmdi zmdi-calendar"></i></span>
                                </div>
                                <input type="date" class="form-control hidden-md-up" placeholder="Selecione uma data">
                                <input type="text" id="filter-start-date" class="form-control date-picker hidden-sm-down" placeholder="Selecione">
                            </div>
                        </div>  
                        <div class="col-3">
                            <label>Data fim</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="zmdi zmdi-calendar"></i></span>
                                </div>
                                <input type="date" class="form-control hidden-md-up" placeholder="Selecione uma data">
                                <input type="text" id="filter-end-date" class="form-control date-picker hidden-sm-down" placeholder="Selecione">
                            </div>
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
                                    <th class="text-nowrap">Total de Itens</th>
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

    $(function() {

        newtable();

        $("#filter-start-date").flatpickr({ "locale": "pt", "dateFormat": "d/m/Y", 'defaultDate': "<?php echo date('01/m/Y'); ?>" });
        $("#filter-end-date").flatpickr({ "locale": "pt", "dateFormat": "d/m/Y", 'defaultDate': "<?php echo date('t/m/Y'); ?>" });


        $('#cd_cotacao, #filter-start-date, #filter-end-date').on('change', function() { 

            if ( $('#fornecedor').val() != '' ) {

                $('#data-table').DataTable().draw();
            }
        });

        $('#fornecedor').on('change', function() {

            $('#btnExport').attr('href', url_exportar + $(this).val());

            if ($(this).val() != "") {

                $('#data-table').DataTable().destroy();

                newtable( $(this).val() ); 
            }
        }); 
    });

    function newtable(id_fornecedor = null) {
            
        if ( id_fornecedor != null) {

            var url = $('#data-table').data('url') + id_fornecedor;

            var dt = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 50,
                ajax: {
                    url: url,
                    type: 'post',
                    dataType: 'json',
                    data: function(data) {
                        let nw_data = data;

                        if ($('#cd_cotacao').val() != '') {
                            nw_data.columns[0].search.value = $('#cd_cotacao').val();
                            nw_data.columns[0].search.type = 'equal';
                        }
                       
                        if ($('#filter-start-date').val() !== '') {
                            let dt1 = $('#filter-start-date').val().split('/');
                            let dt2 = ($('#filter-end-date').val() !== '') ? $('#filter-end-date').val().split('/') : dt1;
                            nw_data.columns[1].search.value = `${dt1[2]}-${dt1[1]}-${dt1[0]},${dt2[2]}-${dt2[1]}-${dt2[0]}`;
                            nw_data.columns[1].search.type = 'date';
                        }

                        return nw_data;
                    }
                },
                order: [[ 1, "desc" ]],
                columns: [
                    { name: 'cd_cotacao', data: 'cd_cotacao', width: '150px', searchable: true },
                    { name: 'data_cotacao', data: 'data_cotacao', searchable: true },
                    { name: 'cnpj_comprador', data: 'cnpj_comprador', width: '200px', searchable: true, },
                    { name: 'razao_social', data: 'razao_social', searchable: true, },
                    { name: 'uf_comprador', data: 'uf_comprador',searchable: true },
                    { name: 'total_itens', data: 'total_itens', searchable: true },
                    { name: 'valor_total', data: 'valor_total', searchable: true, },
                    { name: 'id_fornecedor', data: 'id_fornecedor', searchable: true, visible: false },
                ],
                rowCallback: function(row, data) {
                    $(row).data('cd_cotacao', data.cd_cotacao).data('id_fornecedor', data.id_fornecedor).css('cursor', 'pointer');
                },
                drawCallback: function() {
                    $(".dataTables_filter").hide();
                    $('table tbody tr').each(function () {
                        $(this).on('click', function () {
                            window.location.href = $('#data-table').data('update') + $(this).data('id_fornecedor')  + '/' + $(this).data('cd_cotacao')
                        })
                    })
                }
            });
        } else {

            var dt = $('#data-table').DataTable({
                serverSide: false,
                processing: false,
                ordering: false,
                rowCallback: function(row, data) {},
                drawCallback: function() {}
            });
        }
    }

</script>

</html>