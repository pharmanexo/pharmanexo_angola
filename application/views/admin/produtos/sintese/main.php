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
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Grupo</label>
                                <select class="select2" id="grupos">
                                    <option value="">selecione</option>
                                    <?php foreach ($grupos as $grupo) { ?>
                                         <option value="<?php echo $grupo['id_grupo'] ?>"><?php echo $grupo['grupo'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="">Marca</label>
                            <select class="w-100 select2" id="slct_marcas" style="width: 100%" data-url="<?php echo $slct_marcas; ?>"></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <?php $url = (isset($datasource)) ? $datasource : ''; ?>
                                <table id="dataTable" class="table table-condensed table-hover w-100" data-url="<?php echo $url; ?>" data-delete_multiple="<?php echo $url_delete_multiple ?>">
                                    <thead>
                                        <tr>
                                            <th>
                                                <div class="checkbox">
                                                    <input type="checkbox" id="checkall">
                                                    <label class="checkbox__label" for="checkall"></label>
                                                </div>
                                            </th>
                                            <th></th>
                                            <th>ID Produto</th>
                                            <th>Produto</th>
                                            <th>Marca</th>
                                            <th>Grupo</th>
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

        var slct_marcas;

        var url_delete_multiple = $("#dataTable").data('delete_multiple');

        $(function() {

            var dt = $('#dataTable').DataTable({
                serverSide: true,
                lengthChange: false,
                ajax: {
                    url: $('#dataTable').data('url'),
                    type: 'post',
                    dataType: 'json',
                    data: function (data) {
                        let nw_data = data;
                        if ($('#slct_marcas').val() !== '') {
                            let filtro1 = $('#slct_marcas').val();

                            nw_data.columns[6].search.value =  filtro1;
                            nw_data.columns[6].search.type = 'equal';
                        }

                        if ($('#grupos').val() !== '') {
                            let filtro2 = $('#grupos').val();

                            nw_data.columns[7].search.value =  filtro2;
                            nw_data.columns[7].search.type = 'equal';
                        }

                        return nw_data;
                    }
                },
                columns: [
                    {defaultContent: '', orderable: false, searchable: false, sortable: false },
                    {name: 'id', data: 'id', visible: false},
                    {name: 'id_produto', data: 'id_produto', className: 'text-nowrap'},
                    {name: 'descricao', data: 'descricao', className: 'text-nowrap' },
                    {name: 'marca', data: 'marca'},
                    {name: 'grupo', data: 'grupo', searchable: false },
                    {name: 'id_marca', data: 'id_marca', searchable: true, visible: false },
                    {name: 'id_grupo', data: 'id_grupo', searchable: true, visible: false }
                ],
                columnDefs: [
                    {orderable: false, className: 'select-checkbox', targets: 0 },
                    {targets: [1], visible: false }
                ],
                select: {
                    style: 'multi',
                    selector: 'td:first-child'
                },
                order: [[ 1, "asc" ]],
                rowCallback: function(row, data) {
                    $(row).data('id', data.id).css('cursor', 'pointer');  
                },
                drawCallback: function() {}
            });

            slct_marcas = $('#slct_marcas');

            slct_marcas.select2({
                placeholder: 'Selecione...',
                ajax: {
                    url: slct_marcas.data('url'),
                    type: 'get',
                    dataType: 'json',
                    delay: 300,
                    data: function (params) {
                        return {
                            columns: [{
                                name: 'marca',
                                search: params.term
                            }],
                            page: params.page || 1
                        }
                    }
                },
                escapeMarkup: function(markup) { return markup; },
                processResults: function (data) { return {results: data } },
                templateResult: function (data, container) {
                    if (!data.id) { return data.text; }

                    var ret = data.marca;

                    return ret;
                },
                templateSelection: function (data, container) {
                    if (!data.id) { return data.text; }
                   
                    return (typeof data.marca !== 'undefined') ? `${data.marca}` : '';
                }
            });

            $('#btnDeleteMultiple').click(function(e) {
                e.preventDefault();
                var elementos = [];
                var url = $();

                $.map(dt.rows('.selected').data(), function (item) {
                    elementos.push(item.id);
                });

                if (elementos.length > 0) {

                    Swal.fire({
                        title: 'Informe o motivo para exclusão dos produtos',
                        input: 'text',
                        inputAttributes: {
                            autocapitalize: 'off'
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Confirmar',
                        cancelButtonText: 'Fechar',
                        showLoaderOnConfirm: true,
                        allowOutsideClick: false,
                    }).then((result) => {
                        if (result.value) {
                            var text = result.value;

                            if (text.length > 5) {
                                $.post(url_delete_multiple, {motivo: result.value, itens: elementos}, function (xhr) {

                                    if ( xhr.type == 'success' ) {
                                        
                                        $('#dataTable').DataTable().ajax.reload();
                                        formWarning(xhr);
                                    }
                                })
                            } else {

                                formWarning({type: 'warning', message: 'Não informou o motivo'})
                            }
                        }
                    })
                } else {

                    formWarning({type: 'warning', message: "Nenhum registro selecionado!"});
                }
            });

            $('#slct_marcas, #grupos').on('change', function() { dt.ajax.reload(); });
            $('#checkall').click(function(event) {checkall(dt, document.getElementById('checkall') ); });
        });

        function checkall(table, checkall) {
            if (checkall.checked == true) {
                table.rows({search:'applied'}).select();
            }else {
                table.rows().deselect();
            }
        }

    </script>

</html>