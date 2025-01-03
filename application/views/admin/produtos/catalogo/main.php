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
                         <div class="col-12 mb-3">
                            <small>Clique nas opções para filtrar</small>
                        </div>
                        <div class="col-12">
                            <button type="button" id="btnAtivar" data-index="0" class="btn btn-light mr-3">Ativos</button>
                            <button type="button" id="btnInativar" data-index="1" class="btn" style="background-color: #ffd6d5;" >Inativos</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="table" class="table table-condensed table-hover w-100" 
                                    data-url="<?php echo $datasource; ?>"
                                    data-block="<?php echo $url_block; ?>" 
                                    data-update="<?php echo $url_update ?>"
                                    data-exportar="<?php echo $url_exportar ?>">
                                    <thead>
                                        <tr>
                                            <th>Código</th>
                                            <th>Descrição</th>
                                            <th>Marca</th>
                                            <th>Estoque</th>
                                            <th></th>
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

        var url_block = $('#table').data('block');
        var url_exportar = $('#table').data('exportar');

        $(function() {

            newtable();

            $('#btnAtivar, #btnInativar').on('click', function() {

                var value = $(this).data('index');

                var col = 5;

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
                        {name: 'pl.estoque', data: 'estoque', className: 'text-center'},
                        { defaultContent: '', orderable: false, searchable: false },
                        {name: 'pc.bloqueado', data: 'bloqueado', visible: false }
                    ],
                    order: [[ 1, 'asc' ]],
                    rowCallback: function(row, data) {
                        $(row).data('codigo', data.codigo).css('cursor', 'pointer');

                        if (data.bloqueado == '1') {

                            $(row).addClass('table-danger');
                            var check = $(`<a data-href="${url_block}${data.codigo}/${data.id_fornecedor}/1" data-toggle="tooltip"  title="Ativar este produto" class="text-success" data-block='${data.codigo}'><i class="fas fa-check"></i></a>`);
                        } else {

                            var check = $(`<a data-href="${url_block}${data.codigo}/${data.id_fornecedor}" data-toggle="tooltip"  title="Inativar este produto" class="text-danger" data-block='${data.codigo}'><i class="fas fa-ban"></i></a>`);
                        }

                        check.on('click', function (e) {
                            e.preventDefault();
                            if ($(this).prop("checked")) {
                                var url = $(this).data('href');
                            } else {
                                var url = $(this).data('href');
                            }

                            $.post(url, function (xhr) {
                                formWarning(xhr);
                                if(xhr.type == 'success'){
                                    $('#table').DataTable().ajax.reload();
                                }
                            }, 'JSON');
                        });

                        $('td:eq(4)', row).html(check);

                        $('td:not(:last-child)', row).each(function() {
                            $(this).on('click', function () {
                                window.location.href = $('#table').data('update') + data.codigo + '/' + data.id_fornecedor;
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
