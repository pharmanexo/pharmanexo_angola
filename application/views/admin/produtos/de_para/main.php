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
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="table" class="table table-condensed table-hover w-100" 
                                    data-url="<?php echo $to_datatable; ?>"
                                    data-update="<?php echo $url_update; ?>"
                                    data-exportar="<?php echo $url_exportar; ?>">
                                    <thead>
                                        <tr>
                                            <th >Código</th>
                                            <th>Descrição</th>
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

                var dt = $('#table').DataTable({
                    processing: false,
                    serverSide: false,
                    ajax: {
                        url: $('#table').data('url') + id_fornecedor,
                        type: 'post',
                        dataType: 'json'
                    },
                    columns: [
                        {name: 'codigo', data: 'codigo', className: 'text-center'},
                        {name: 'nome_comercial', data: 'produto_descricao'},
                    ],
                    order: [[ 1, 'asc' ]],
                    rowCallback: function (row, data) {
                        $(row).css('cursor', 'pointer');
                        
                        $('td', row).each(function() {
                            $(this).on('click', function () {

                                window.location.href = $('#table').data('update') + data.codigo + '/' + data.id_fornecedor
                            });
                        });
                    },
                    drawCallback: function () {

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
