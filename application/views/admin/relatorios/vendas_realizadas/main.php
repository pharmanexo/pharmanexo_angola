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
                        <div class="col-6">
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
                    </div>
                    <div class="table-responsive">
                        <table id="data-table" class="table table-condensed table-hover no-filtered" 
                            data-url="<?php echo $dataTable; ?>"
                            data-exportar="<?php echo $url_exportar; ?>">
                            <thead>
                                <tr>
                                    <th>Produto</th>
                                    <th>Marca</th>
                                    <th>Unidade</th>
                                    <th>Qtde. Total Solicitada</th>
                                    <th>Valor Total</th>
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
                serverSide: false,
                pageLength: 50,
                ajax: {
                    url: url,
                    type: 'post',
                    dataType: 'json'
                },
                order: [[ 0, "ASC" ]],
                columns: [
                    { name: 'oc_prod.Ds_Produto_Comprador', data: 'produto', className: 'text-nowrap' },
                    { name: 'oc_prod.Ds_marca', data: 'marca', className: 'text-nowrap'  },
                    { name: 'oc_prod.Ds_Unidade_Compra', data: 'unidade', className: 'text-nowrap' },
                    { name: 'qtd_total', data: 'qtd_total', searchable: false, className: 'text-center text-nowrap' },
                    { name: 'valor_total', data: 'valor_total', searchable: false },
                ],
                rowCallback: function(row, data) {
                },
                drawCallback: function() {}
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