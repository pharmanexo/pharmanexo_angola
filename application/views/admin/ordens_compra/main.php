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
                            <small>Selecione um fornecedor para exibir os dados</small>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label>Fornecedor</label>
                                <select class="select2" id="fornecedor">
                                    <option value="">Selecione</option>
                                    <?php foreach ($fornecedores as $k => $v) : ?>
                                    <option value="<?php echo $v['id']; ?>"><?php echo $v['nome_fantasia']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-6 form-group">
                            <label for="comprador">Comprador</label>
                            <select class="select2" id="comprador" data-index="7">
                                <option value="">Selecione</option>
                                <?php foreach($compradores as $c): ?>
                                    <option value="<?php echo $c['id']; ?>"><?php echo ( !empty($c['nome_fantasia']) ) ? $c['nome_fantasia'] : $c['razao_social']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="table" class="table table-condensed table-hover w-100" 
                                    data-url="<?php echo $datasource; ?>" 
                                    data-update="<?php echo $url_update ?>"
                                    data-exportar="<?php echo $url_exportar ?>">
                                    <thead>
                                        <tr>
                                            <th>Data de Criação</th>
                                            <th>Ordem Compra</th>
                                            <th>Comprador</th>
                                            <th>Valor (R$)</th>
                                            <th>Entrega Acordada</th>
                                            <th>Cotação</th>
                                            <th>Pendente</th>
                                            <th></th>
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

    <?php echo $scripts; ?>

    <script>

        var url_exportar = $('#table').data('exportar');

        $(function() {

            newtable();

            $('#comprador').on('change', function() {

                var value = $(this).val();

                var col = $(this).data('index');

                $('#table').DataTable().columns(col).search(value).draw();
            });

            $('#fornecedor').on('change', function() {

                $('#btnExport').attr('href', url_exportar + $(this).val());

                if ($(this).val() != "") {

                    $('#table').DataTable().destroy();

                    newtable( $(this).val() ); 
                }
            });  
        });

        function newtable(id_fornecedor = null) {
            
            if ( id_fornecedor != null) {

                var url = $('#table').data('url') + id_fornecedor;

                var dt = $('#table').DataTable({
                    processing: false,
                    serverSide: false,
                    ajax: {
                        url: url,
                        type: 'post',
                        dataType: 'json'
                    },
                    columns: [
                        { name: 'ocs.Dt_Ordem_Compra', data: 'Dt_Ordem_Compra'},
                        { name: 'ocs.Cd_Ordem_Compra', data: 'Cd_Ordem_Compra' },
                        { name: 'compradores.razao_social', data: 'razao_social' },
                        { name: 'valor', data: 'valor', searchable: false },
                        { name: 'ocs.Dt_Previsao_Entrega', data: 'Dt_Previsao_Entrega' },
                        { name: 'ocs.Cd_Cotacao', data: 'Cd_Cotacao' },
                        { name: 'ocs.pendente', data: 'pendente' },
                        { name: 'ocs.id_comprador', data: 'id_cliente', visible: false },
                    ],
                    order: [[ 1, 'asc' ]],
                    rowCallback: function(row, data) {
                        $(row).css('cursor', 'pointer');

                        $('td:not(:last-child)', row).each(function() {
                            $(this).on('click', function () {
                                window.location.href = $('#table').data('update') + data.id;
                            });
                        });
                    },
                    drawCallback: function() {
                        $('[data-toggle="tooltip"]').tooltip();
                    }
                });
            } else {

                var dt = $('#table').DataTable({
                    serverSide: false,
                    processing: false,
                    ordering: false,
                    rowCallback: function(row, data) {},
                    drawCallback: function() {}
                });
            }
        }
    </script>
</body>

</html>
