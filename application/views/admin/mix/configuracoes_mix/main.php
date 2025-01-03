<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner" id="printAll">
        <div class="card">
            <div class="card">
                <div class="card-body">
                    <p class="card-title">Filtros</p>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="fornecedores">Fornecedor</label>
                                <select class="select2" id="fornecedores" data-placeholder="Selecione" data-allow-clear="true">
                                    <option></option>
                                    <?php foreach($fornecedores as $f) { ?>
                                        <option value="<?php echo $f['id']; ?>"><?php echo $f['fornecedor']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-6" id="campoEstado">
                            <div class="form-group">
                                <label for="estado">Estado</label>
                                <select class="select2" id="estado" data-placeholder="Selecione" data-allow-clear="true">
                                    <option></option>
                                    <?php foreach($estados as $estado) { ?>
                                        <option value="<?php echo $estado['id']; ?>"><?php echo $estado['estado']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="" id="campoCnpj" hidden>
                            <div class="form-group">
                                <label for="compradores">Comprador</label>
                                <select class="select2" id="compradores" data-placeholder="Selecione" data-allow-clear="true">
                                    <option></option>
                                    <?php foreach($compradores as $comprador) { ?>
                                        <option value="<?php echo $comprador['id']; ?>"><?php echo $comprador['comprador']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="estado-tab" data-toggle="tab" href="#tabEstado" role="tab" aria-controls="estado" aria-selected="true">Estado</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" id="cnpj-tab" data-toggle="tab" href="#tabCnpj" role="tab" aria-controls="cnpj" aria-selected="false">CNPJ</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="tabEstado" role="tabpanel" aria-labelledby="estado-tab">
                            <div class="row mx-auto mt-3">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="data-table-estado" class="table table-condensend table-hover" style="width: 100%;"
                                               data-url="<?php echo $to_datatable_estado; ?>"
                                               data-update="<?php echo $url_update_estado; ?>"
                                               data-delete_multiple="<?php echo $url_delete ?>"
                                        >
                                            <thead>
                                            <tr>
                                                <th>
                                                    <div class="checkbox">
                                                        <input type="checkbox" id="checkall-estados">
                                                        <label class="checkbox__label" for="checkall-estados"></label>
                                                    </div>
                                                </th>
                                                <th>Estado</th>
                                                <th>Fornecedor</th>
                                                <th>Prioridade</th>
                                                <th>Desconto (%)</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="tab-pane fade" id="tabCnpj" role="tabpanel" aria-labelledby="cnpj-tab">
                            <div class="row mx-auto mt-3">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="data-table-cnpj" class="table table-condensend table-hover" style="width: 100%;"
                                               data-url="<?php echo $to_datatable_cnpj; ?>"
                                               data-update="<?php echo $url_update_cnpj; ?>"
                                        >
                                            <thead>
                                            <tr>
                                                <th>
                                                    <div class="checkbox">
                                                        <input type="checkbox" id="checkall-cnpjs">
                                                        <label class="checkbox__label" for="checkall-cnpjs"></label>
                                                    </div>
                                                </th>
                                                <th>Comprador</th>
                                                <th>Fornecedor</th>
                                                <th>Prioridade</th>
                                                <th>Desconto (%)</th>
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
        </div>
    </div>
</div>

<?php echo $scripts; ?>

<script>

    var url_delete_multiple = $('#data-table-estado').data('delete_multiple');

    $(function () {

        var dt1 = $('#data-table-estado').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: $('#data-table-estado').data('url'),
                type: 'post',
                dataType: 'json',
                data: function(data) {

                    if ($('#fornecedores').val() != '') {

                        data.columns[5].search.value = $('#fornecedores').val().toString();
                        data.columns[5].search.type = 'equal';
                    }

                    if ($('#estado').val() != '') {

                        data.columns[6].search.value = $('#estado').val().toString();
                        data.columns[6].search.type = 'equal';
                    }
                    return data;
                }
            },
            columnDefs: [
                { orderable: false, className: 'select-checkbox', targets: 0 }
            ],
            select: {
                style: "multi",
                selector: "td:first-child"
            },
            order: [[ 1, "asc" ]],
            columns: [
                {defaultContent: '', orderable: false, searchable: false},
                { name: 'e.descricao', data: 'estado' },
                { name: 'f.nome_fantasia', data: 'fornecedor' },
                { name: 'mix.prioridade', data: 'prioridade', className: 'text-center' },
                { name: 'mix.desconto_mix', data: 'desconto_mix', className: 'text-center' },
                { name: 'mix.id_fornecedor', data: 'id_fornecedor', visible: false, ordenable: false },
                { name: 'mix.id_estado', data: 'id_estado', visible: false, ordenable: false }

            ],
            rowCallback: function (row, data) {
                $(row).css('cursor', 'pointer');

                $('td:not(:first-child):not(:last-child)', row).each(function() {
                    $(this).on('click', function() {
                        $.ajax({
                            type: 'post',
                            url: $('#data-table-estado').data('update') + '/' + data.id,
                            dataType: 'html',
                            success: function(response) {
                                $('body').append(response);
                                $('.modal').modal({
                                    keyboard: false
                                }, 'show').on('hide.bs.modal', function() {
                                    $('#data-table-estado').DataTable().ajax.reload();
                                    $('.modal').remove();
                                });
                            }
                        });
                    });
                });
            },
            drawCallback: function () {}
        });

        var dt2 = $('#data-table-cnpj').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: $('#data-table-cnpj').data('url'),
                type: 'post',
                dataType: 'json',
                data: function(data) {

                    if ($('#fornecedores').val() != '') {

                        data.columns[5].search.value = $('#fornecedores').val().toString();
                        data.columns[5].search.type = 'equal';
                    }

                    if ($('#compradores').val() != '') {

                        data.columns[6].search.value = $('#compradores').val().toString();
                        data.columns[6].search.type = 'equal';
                    }

                    return data;
                }
            },
            columnDefs: [
                { orderable: false, className: 'select-checkbox', targets: 0 }
            ],
            select: {
                style: "multi",
                selector: "td:first-child"
            },
            order: [[ 1, "asc" ]],
            columns: [
                {defaultContent: '', orderable: false, searchable: false},
                { name: 'c.razao_social', data: 'comprador' },
                { name: 'f.nome_fantasia', data: 'fornecedor' },
                { name: 'mix.prioridade', data: 'prioridade', className: 'text-center' },
                { name: 'mix.desconto_mix', data: 'desconto_mix', className: 'text-center' },
                { name: 'mix.id_fornecedor', data: 'id_fornecedor', visible: false, ordenable: false },
                { name: 'mix.id_cliente', data: 'id_cliente', visible: false, ordenable: false }

            ],
            rowCallback: function (row, data) {
                $(row).css('cursor', 'pointer');

                $('td:not(:first-child):not(:last-child)', row).each(function() {
                    $(this).on('click', function() {
                        $.ajax({
                            type: 'post',
                            url: $('#data-table-cnpj').data('update') + '/' + data.id,
                            dataType: 'html',
                            success: function(response) {
                                $('body').append(response);
                                $('.modal').modal({
                                    keyboard: false
                                }, 'show').on('hide.bs.modal', function() {
                                    $('#data-table-cnpj').DataTable().ajax.reload();
                                    $('.modal').remove();
                                });
                            }
                        });
                    });
                });

            },
            drawCallback: function () {}
        });

        $('#btnAdicionar').on('click', function(e) {
            e.preventDefault();
            let me = $(this);

            $.ajax({
                url: me.attr('href'),
                type: 'get',
                dataType: 'html',
                data: {
                    tipo: 1
                },
                success: function(response) {
                    $('body').append(response);
                    $('.modal').modal({
                        keyboard: false
                    }, 'show').on('hide.bs.modal', function() {
                        $('.modal').remove();
                        $('#data-table-estado').DataTable().ajax.reload();
                        $('#data-table-cnpj').DataTable().ajax.reload();
                    });
                }
            })
        });


        $('#btnDeleteMultiple').click(function (e) {
            e.preventDefault();
            var elementos = [];
            var url = $();

            $.map(dt1.rows('.selected').data(), function (item) {
                elementos.push({
                    id: item.id
                });
            });

            $.map(dt2.rows('.selected').data(), function (item) {
                elementos.push({
                    id: item.id
                });
            });

            if (elementos.length > 0) {
                $.post(url_delete_multiple, {itens: elementos}, function (xhr) {
                    $('#data-table-estado').DataTable().ajax.reload();
                    $('#data-table-cnpj').DataTable().ajax.reload();
                    formWarning(xhr);

                    $('[data-select]').attr('checked', false)
                }, 'JSON');
            } else {
                formWarning({type: 'warning', message: "Nenhum registro selecionado!"});
            }
        });

        $('#cnpj-tab').on('shown.bs.tab', function (e) {
            $('#estado').val('');
            $('#campoCnpj').attr('hidden', false).addClass('col-6');
            $('#campoEstado').attr('hidden', true);
        });

        $('#estado-tab').on('shown.bs.tab', function (e) {
            $('#compradores').val('');
            $('#campoCnpj').attr('hidden', true).removeClass('col-6');
            $('#campoEstado').attr('hidden', false);
        });


        $('#estado').on('change', function() { dt1.draw(); });
        $('#compradores').on('change', function() { dt2.draw(); });
        $('#fornecedores').on('change', function() {
            dt1.draw();
            dt2.draw();
        });

        $('#checkall-estados').click(function (event) { checkall(dt1, document.getElementById('checkall-estados')); });
        $('#checkall-cnpjs').click(function (event) { checkall(dt2, document.getElementById('checkall-cnpjs')); });
    });

    function checkall(table, checkall) {
        if (checkall.checked == true) {
            table.rows({search:'applied'}).select();
        } else {
            table.rows().deselect();
        }
    }
</script>
</body>