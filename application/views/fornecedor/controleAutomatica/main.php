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

                    <h5 class="card-title">Filtrar por</h5>

                    <div class="row">

                        <div class="col-4">
                            <div class="form-group">
                                <label for="codigo">Código</label>
                                <input type="number" class="form-control" min="1" step="1" id="codigo">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="produto">Produto</label>
                                <input type="text" class="form-control" id="produto">
                            </div>
                        </div>
                        <div class="col-4" id="campoEstado">
                            <div class="form-group">
                                <label for="id_estado">Estado</label>
                                <select class="select2" id="id_estado" data-placeholder="Selecione" data-allow-clear="true">
                                    <option></option>
                                    <?php foreach($estados as $e): ?>
                                        <option value="<?php echo $e['id']; ?>"> <?php echo $e['uf'] . ' - ' . $e['descricao']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>  
                        <div class="col-4 d-none" id="campoCnpj">
                            <div class="form-group">
                                <label for="id_cliente">Comprador</label>
                                <select class="select2" id="id_cliente" data-placeholder="Selecione" data-allow-clear="true">
                                    <option></option>
                                    <?php foreach($compradores as $c): ?>
                                        <option value="<?php echo $c['id']; ?>"> <?php echo $c['cnpj'] . ' - ' . $c['razao_social']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>  
                    </div>

                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="estado-tab" data-toggle="tab" href="#tabEstado" role="tab" aria-controls="estado" aria-selected="true">Estado</a>
                        </li>

                        <?php if ($this->session->userdata('integracao') != 0) : ?>
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
                                        <table id="data-table-estado" class="table w-100 table-hover" data-url="<?php echo $to_datatable_estado; ?>" data-delete_multiple="<?php echo $url_delete_multiple ?>" >
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="checkallEstado"></th>
                                                    <th>Código</th>
                                                    <th>Produto</th>
                                                    <th>Estado</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if ($this->session->userdata('integracao') != 0) : ?>
                        <!-- Tab CNPJ -->
                        <div class="tab-pane fade" id="tabCnpj" role="tabpanel" aria-labelledby="cnpj-tab">
                            <div class="row mx-auto mt-3">
                                <div class="col-12">
                                    <div class="table-responsive">
                                        <table id="data-table-cnpj" class="table w-100 table-hover w-100" data-url="<?php echo $to_datatable_cnpj; ?>">
                                            <thead>
                                                <tr>
                                                    <th><input type="checkbox" id="checkallCnpj"></th>
                                                    <th>Código</th>
                                                    <th>Produto</th>
                                                    <th>Comprador</th>
                                                    <th></th>
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

    var url_delete_multiple = $('#data-table-estado').data('delete_multiple');
    var url_update = "<?php if(isset($url_update)) echo $url_update; ?>";

    $(function() {

        $('#cnpj-tab').on('shown.bs.tab', function (e) {

            $('#id_cliente').val(null).trigger('change.select2')
            $('#campoEstado').addClass('d-none');
            $('#campoCnpj').removeClass('d-none');
        });

        $('#estado-tab').on('shown.bs.tab', function (e) {
            
            $('#id_estado').val(null).trigger('change.select2')
            $('#campoCnpj').addClass('d-none');
            $('#campoEstado').removeClass('d-none');
        });
        
        var dt1 = $('#data-table-estado').DataTable({
            serverSide: true,
            lengthChange: false,
            ajax: {
                url: $('#data-table-estado').data('url'),
                type: 'post',
                dataType: 'json',
                data: function(data) {

                    let nw_data = data;
                        
                    if ($('#codigo').val() != '') {
                        nw_data.columns[1].search.value = $('#codigo').val();
                        nw_data.columns[1].search.type = 'equal';
                    }

                    if ($('#produto').val() != '') {
                        nw_data.columns[2].search.value = $('#produto').val();
                    }

                    if ($('#id_estado').val() != '') {
                        nw_data.columns[4].search.value = $('#id_estado').val();
                        nw_data.columns[4].search.type = 'equal';
                    }

                    return nw_data;
                }
            },
            columns: [
                {defaultContent: '', orderable: false, searchable: false, sortable: false },
                {name: 'ca.codigo', data: 'codigo'},
                {name: 'pc.nome_comercial', data: 'nome_comercial'},
                {name: 'e.descricao', data: 'descricao'},
                {name: 'ca.id_estado', data: 'id_estado', visible: false}
            ],
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0 },
            ],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            order: [[ 2, 'asc' ]],
            rowCallback: function(row, data) {
                $(row).css('cursor', 'pointer');  
            },
            drawCallback: function() {}
        });

        var dt2 = $('#data-table-cnpj').DataTable({
            serverSide: true,
            lengthChange: false,
            ajax: {
                url: $('#data-table-cnpj').data('url'),
                type: 'post',
                dataType: 'json',
                data: function(data) {

                    let nw_data = data;
                    
                    if ($('#codigo').val() != '') {
                        nw_data.columns[1].search.value = $('#codigo').val();
                        nw_data.columns[1].search.type = 'equal';
                    }

                    if ($('#produto').val() != '') {
                        nw_data.columns[2].search.value = $('#produto').val();
                    }

                    if ($('#id_cliente').val() != '') {
                        nw_data.columns[4].search.value = $('#id_cliente').val();
                        nw_data.columns[4].search.type = 'equal';
                    }

                    return nw_data;
                }
            },
            columns: [
                {defaultContent: '', orderable: false, searchable: false, sortable: false },
                {name: 'ca.codigo', data: 'codigo'},
                {name: 'pc.nome_comercial', data: 'nome_comercial'},
                {name: 'c.razao_social', data: 'razao_social'},
                {name: 'ca.id_cliente', data: 'id_cliente', visible: false}
            ],
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0 }
            ],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            order: [[ 2, 'asc' ]],
            rowCallback: function(row, data) {
                $(row).css('cursor', 'pointer');
            },

            drawCallback: function() { }
        });

        $('#data-table-estado tbody').on('click', 'tr td:not(:first-child)', function (e) {
            e.preventDefault();
            var rowIdx = dt1.cell( this ).index().row;
            var data = dt1.row( rowIdx ).data();

            $.ajax({
                type: 'post',
                url: url_update + '/' + data.id + '/1',
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

        $('#data-table-cnpj tbody').on('click', 'tr td:not(:first-child)', function (e) {
            e.preventDefault();
            var rowIdx = dt2.cell( this ).index().row;
            var data = dt2.row( rowIdx ).data();

            $.ajax({
                type: 'post',
                url: url_update + '/' + data.id + '/2',
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

        $("#id_estado, #codigo, #produto").on("change", function () { dt1.draw(); });
        $("#id_cliente, #codigo, #produto").on("change", function () { dt2.draw(); });

        $('#btnDeleteMultiple').click(function(e) {
            e.preventDefault();
            var elementos = [];

            $.map(dt1.rows('.selected').data(), function (item) {
                elementos.push(item.id);
            });

            $.map(dt2.rows('.selected').data(), function (item) {
                elementos.push(item.id);
            });

            if (elementos.length > 0) {

                Swal.fire({
                    text: "Selecionar registros..",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'De todas as páginas',
                    cancelButtonText: 'Da página atual'
                }).then((result) => {
                    
                    if (result.isConfirmed) {
                        
                        url_delete_multiple = url_delete_multiple + "/1";

                        $.ajax({
                            url: url_delete_multiple,
                            type: 'post',
                            data: {}
                        }).done(function( xhr ) {

                            formWarning(xhr);
                        }).fail(function( jqXHR, textStatus, xhr ) {});
                    } else {

                        $.ajax({
                            url: url_delete_multiple,
                            type: 'post',
                            data: { el: elementos }
                        }).done(function( xhr ) {

                            formWarning(xhr);
                        }).fail(function( jqXHR, textStatus, xhr ) {});
                    }

                    $('#data-table-estado').DataTable().ajax.reload();
                    $('#data-table-cnpj').DataTable().ajax.reload();
                    $('#checkallEstado').prop('checked', false);
                    $('#checkallCnpj').prop('checked', false);
               }) 
            } else {
                formWarning({
                    type: 'warning',
                    message: "Nenhum registro selecionado!"
                });
            }
        });

        $('#btnAdicionar').on('click', function(e) {
            e.preventDefault();
            let me = $(this);

            $.ajax({
                url: me.attr('href'),
                type: 'get',
                dataType: 'html',
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

        $('#checkallEstado').click(function(event) {checkall(dt1, document.getElementById('checkallEstado') ); });
        $('#checkallCnpj').click(function(event) {checkall(dt2, document.getElementById('checkallCnpj') ); });
    });

    function checkall(table, checkall) 
    {
        if (checkall.checked == true) {
            table.rows({search:'applied'}).select();
        }else {
            table.rows().deselect();
        }
    }
</script>

</html>