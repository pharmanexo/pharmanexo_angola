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
                                            <th>ID Produto</th>
                                            <th>ID Sintese</th>
                                            <th>Descrição</th>
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

        var url_update = $('#table').data('update');
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
                    processing: true,
                    serverSide: false,
                    ajax: {
                        url: $('#table').data('url') + id_fornecedor,
                        type: 'post',
                        dataType: 'json'
                    },
                    columns: [
                        {name: 'codigo', data: 'codigo', className: 'text-center'},
                        {name: 'id_produto', data: 'id_produto', visible: true },
                        {name: 'id_sintese', data: 'id_sintese', visible: true },
                        {name: 'produto_descricao', data: 'produto_descricao'},
                        {defaultContent: '', width: '100px', orderable: false, searchable: false },
                    ],
                    order: [[ 1, "desc" ]],
                    rowCallback: function (row, data) {
                        $(row).data('id', data.id).css('cursor', 'pointer');
                        var btnLinkar = $(`<button href="${url_update}" data-toggle="tooltip" title="Remover combinação dos produtos." data- class="btn btn-sm btn-danger"><i class="fas fa-unlink"></i></button>`);

                        btnLinkar.click(function (e) {
                            e.preventDefault();

                            if ( $("#fornecedor").val() != '' ) {

                                $.ajax({
                                    url: $(this).attr('href'),
                                    type: 'post',
                                    dataType: 'json',
                                    data: {
                                        id_fornecedor: $("#fornecedor").val(),
                                        codigo: data.codigo,
                                        id_sintese: data.id_sintese
                                    },
                                    success: function(xhr) {
                                        formWarning(xhr);
                                        if ( xhr.type == 'success' ) {
                                            $('#table').DataTable().ajax.reload();
                                        }
                                    }
                                });
                            } else {

                                formWarning({type: "warning", message: 'Selecione um fornecedor!'});
                            }
                        });

                        $('td:eq(4)', row).html(btnLinkar);
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
