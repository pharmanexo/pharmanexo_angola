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
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="estado-tab" data-toggle="tab" href="#tabEstado" role="tab" aria-controls="estado" aria-selected="true">Estado</a>
                    </li>

                    <?php if ($this->session->userdata('id_tipo_venda') != 1) : ?>
                        <li class="nav-item">
                            <a class="nav-link" id="cnpj-tab" data-toggle="tab" href="#tabCnpj" role="tab" aria-controls="cnpj" aria-selected="false">CNPJ</a>
                        </li>
                    <?php endif; ?>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <!-- Tab Estado -->
                    <div class="tab-pane fade show active" id="tabEstado" role="tabpanel" aria-labelledby="estado-tab">
                        <div class="row mx-auto mt-3">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table id="data-table-estado" class="table table-condensend table-hover w-100" data-delete_multiple="<?php echo $url_delete_multiple ?>" data-url="<?php echo $to_datatable_estado; ?>" >
                                        <thead>
                                        <tr>
                                            <th class="text-center">
                                                <div class="checkbox">
                                                    <input type="checkbox" id="checkall-estados">
                                                    <label class="checkbox__label" for="checkall-estados"></label>
                                                </div>
                                            </th>
                                            <th>ID</th>
                                            <th>Estado</th>
                                            <th>Produto</th>
                                            <th></th>
                                            <th>Integrador</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab CNPJ -->
                    <?php if ($this->session->userdata('id_tipo_venda') != 1) : ?>
                        <div class="tab-pane fade" id="tabCnpj" role="tabpanel" aria-labelledby="cnpj-tab">
                            <div class="row mx-auto mt-3">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="data-table-cnpj" class="table table-condensend table-hover w-100" data-url="<?php echo $to_datatable_cnpj; ?>">
                                            <thead>
                                            <tr>
                                                <th class="text-center">
                                                    <div class="checkbox">
                                                        <input type="checkbox" id="checkall-cnpjs">
                                                        <label class="checkbox__label" for="checkall-cnpjs"></label>
                                                    </div>
                                                </th>
                                                <th>ID</th>
                                                <th>Razao Social</th>
                                                <th>CNPJ</th>
                                                <th>Produto</th>
                                                <th></th>
                                                <th>Integrador</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

<?php echo $scripts; ?>

<script>
    var url_delete_estado = $('#data-table-estado').data('delete');
    var url_delete_cnpj = $('#data-table-cnpj').data('delete');
    var url_delete_multiple = $('#data-table-estado').data('delete_multiple');

    $(function () {
        var dt1 = $('#data-table-estado').DataTable({
            processing: true,
            serverSide: false,
            lengthChange: false,
            ajax: {
                url: $('#data-table-estado').data('url'),
                type: 'post',
                dataType: 'json'
            },
            columns: [
                { defaultContent: '', width: '120px', orderable: false, searchable: false},
                { name: 'restricoes_produtos_clientes.id', data: 'id', visible: false},
                { name: 'estados.descricao', data: 'descricao'},
                { name: 'pc.apresentacao', data: 'produto_descricao'},
                { name: 'pc.nome_comercial', data: 'nome_comercial', visible: false, searchable: true },
                { name: 'i.desc', data: 'integrador', visible: true, searchable: true },
            ],
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0 },
                {targets: [1], visible: false }
            ],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            order: [[ 2, 'asc' ]],
            rowCallback: function (row, data) {},

            drawCallback: function () {
            }
        });

        var dt2 = $('#data-table-cnpj').DataTable({
            processing: true,
            serverSide: true,
            lengthChange: false,

            ajax: {
                url: $('#data-table-cnpj').data('url'),
                type: 'post',
                dataType: 'json'
            },
            columns: [
                { defaultContent: '', width: '120px', orderable: false, searchable: false },
                { name: 'restricoes_produtos_clientes.id', data: 'id', visible: false },
                { name: 'compradores.razao_social', data: 'razao_social'},
                { name: 'compradores.cnpj', data: 'cnpj'},
                { name: 'pc.apresentacao', data: 'produto_descricao'},
                { name: 'pc.nome_comercial', data: 'nome_comercial', visible: false, searchable: true },
                { name: 'i.desc', data: 'integrador', visible: true, searchable: true },
            ],
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0 },
                {targets: [1], visible: false }
            ],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            order: [[ 2, 'asc' ]],
            rowCallback: function (row, data) {},
            drawCallback: function () {}
        });

        $('#btnDeleteMultiple').click(function (e) {
            e.preventDefault();
            var elementos = [];
            var url = $();

            $.map(dt1.rows('.selected').data(), function (item) {   
                elementos.push(item.id);
            });

            $.map(dt2.rows('.selected').data(), function (item) {
                elementos.push(item.id);
            });

            if (elementos.length > 0) {
                $.post(url_delete_multiple, {el: elementos}, function (xhr) {
                    $('#data-table-estado').DataTable().ajax.reload();
                    $('#data-table-cnpj').DataTable().ajax.reload();
                    formWarning(xhr);
                }, 'JSON');
            } else {
                formWarning({
                    type: 'warning',
                    message: "Nenhum registro selecionado!"
                });
            }
        });

        $('#checkall-estados').click(function (event) { checkall(dt1, document.getElementById('checkall-estados')); });
        $('#checkall-cnpjs').click(function (event) { checkall(dt2, document.getElementById('checkall-cnpjs')); });

        function checkall(table, checkall) 
        {
            if (checkall.checked == true) {

                table.rows({search:'applied'}).select();
            } else {

                table.rows().deselect();
            }
        }

    });
</script>

</html>