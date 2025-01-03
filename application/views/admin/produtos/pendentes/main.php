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
                                <label for="id_fornecedor">Fornecedor</label>
                                <select class="select2" id="id_fornecedor">
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
                                    data-url="<?php echo $datasource; ?>"
                                    data-activate="<?php echo $url_activate; ?>"
                                    data-exportar="<?php echo $url_exportar ?>">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Descrição</th>
                                            <th>Marca</th>
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

        var url_activate = $('#table').data('activate');
        var url_exportar = $('#table').data('exportar');

        $(function() {

            newtable();

            $('#id_fornecedor').on('change', function() {

                $('#btnExport').attr('href', url_exportar + $(this).val());

                if ($(this).val() != "") {

                    $('#table').DataTable().destroy();

                    newtable( $(this).val() ); 
                }
            });  
        });

        function newtable(id_fornecedor = null) {
            
            if ( id_fornecedor != null) {

                var url_produtos = $('#table').data('url') + id_fornecedor;

                var dt = $('#table').DataTable({
                    processing: false,
                    serverSide: false,
                    ajax: {
                        url: url_produtos,
                        type: 'post',
                        dataType: 'json'
                    },
                    columns: [
                        {name: 'pc.codigo', data: 'codigo'},
                        {name: 'pc.nome_comercial', data: 'produto_descricao'},
                        {name: 'pc.marca', data: 'marca'},
                        { defaultContent: '', width: '100px', orderable: false, searchable: false },
                    ],
                    rowCallback: function(row, data) {
                        $(row).data('codigo', data.codigo).css('cursor', 'pointer');

                        var btn = $(`<a class="aprovar" data-codigo="${data.codigo}" data-toggle="tooltip" title="Aprovar Produto" class="text-success"><i class="far fa-thumbs-up"></i></a>`);
                        
                        $('td:eq(3)', row).html(btn);
                    },
                    drawCallback: function() {
                        $('[data-toggle="tooltip"]').tooltip();

                        $('.aprovar').on('click', function (e) {

                            e.preventDefault();

                            $.ajax({
                                url: url_activate,
                                type: 'post',
                                data: {
                                    codigo: $(this).data('codigo'),
                                    id_fornecedor: $('#id_fornecedor').val()
                                },
                                success: function(xhr) {
                                    formWarning(xhr);
                                    dt.ajax.reload();
                                },
                                error: function(xhr) {
                                    formWarning({ type: 'warning', message: "Erro ao salvar as informações!" });
                                }
                            });
                        });
                        
                    }
                });
            } else {

                var dt = $('#table').DataTable({
                    serverSide: false,
                    processing: false,
                    columns: [
                        null,
                        null,
                        null,
                        null
                    ],
                    rowCallback: function(row, data) {},
                    drawCallback: function() {}
                });
            }
        }
    </script>
</body>

</html>
