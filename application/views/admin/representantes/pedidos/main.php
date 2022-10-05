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
                        <table id="data-table" class="table w-100 table-hover" 
                            data-url="<?php echo $datatables; ?>" 
                            data-detalhe="<?php echo $url_detalhes; ?>"
                            data-exportar="<?php echo $url_exportar; ?>"
                            >
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Representante</th>
                                <th>Comprador</th>
                                <th>Prazo Entrega</th>
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
                processing: false,
                serverSide: true,
                ajax: {
                    url: url,
                    type: 'post',
                    dataType: 'json'
                },
                columns: [
                    { name: 'pr.id', data: 'id', width: '80px' },
                    { name: 'representantes.nome', data: 'representante' },
                    { name: 'compradores.razao_social', data: 'comprador' },
                    { name: 'pr.prazo_entrega', data: 'prazo_entrega', className: 'text-nowrap' },
                    { name: 'pr.data_abertura', data: 'data', className: 'text-nowrap' },
                ],
                order: [[ 1, 'asc' ]],
                rowCallback: function(row, data) {
                    $(row).css('cursor', 'pointer');

                    $('td:not(:first-child):not(:last-child)', row).each(function() {
                        $(this).on('click', function () {
                            window.location.href = `${url_detalhes}${data.id_fornecedor}/${data.id}`
                        });
                    });
                },
                drawCallback: function() {
                    $('[data-toggle="tooltip"]').tooltip();
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