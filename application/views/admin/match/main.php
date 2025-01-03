<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="estados">Filtrar por Estado</label>
                    <br>
                    <select class="form-control" id="estados" multiple="multiple" style="heigth: 60%"
                            data-live-search="true" title="Selecione">
                        <?php foreach ($estados as $estado): ?>
                            <option value="<?php echo $estado['uf']; ?>"><?php echo $estado['estado']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="id_cliente">Filtrar por Comprador</label>
                    <br>
                    <select class="select2" id="id_cliente" data-placeholder="Selecione"
                            data-allow-clear="true" data-toggle="tooltip"
                            title="Clique para selecionar">
                        <option></option>
                        <?php foreach ($compradores as $comprador): ?>
                            <option value="<?php echo $comprador['id']; ?>"><?php echo $comprador['comprador']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive col-sm">
                    <table id="data-table" class="table table-condensend table-hover" data-url="<?php echo $to_datatable; ?>">
                        <thead>
                        <tr>
                            <th>
                                <div class="checkbox">
                                    <input type="checkbox" id="checkall">
                                    <label class="checkbox__label" for="checkall"></label>
                                </div>
                            </th>
                            <th>
                            </th>
                            <th>Descrição Pharmanexo</th>
                            <th>Descrição Comprador</th>
                            <th>Id Produto</th>
                            <th></th>
                            <th>Código</th>
                            <th></th>
                            <th>Comprador</th>
                            <th>Estado</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $scripts; ?>
<script>

    var url_update = $('#data-table').data('update');
    $(function () {

        $('#estados').selectpicker();

        var dt1 = $('#data-table').DataTable({
            "processing": true,
            "serverSide": true,
            lengthChange: false,
            "pageLength": 1000,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'post',
                dataType: 'json',
                data: function (data) {

                    if ($('#estados').val() != '') {

                        data.columns[9].search.value = $('#estados').val().toString();
                        data.columns[9].search.type = 'in';
                    }

                    if ($('#id_cliente').val() != '') {

                        data.columns[7].search.value = $('#id_cliente').val().toString();
                        data.columns[7].search.type = 'equal';
                    }

                    return data;
                }
            },
            columns: [
                {
                    defaultContent: '',
                    width: '100px',
                    orderable: false,
                    searchable: false
                },
                {
                    name: 'id',
                    data: 'id',
                    visible: false
                },
                {
                    name: 'descricao_sintese',
                    data: 'descricao_sintese',
                    visible: true
                },
                {
                    name: 'descricao_catalogo',
                    data: 'descricao_catalogo',
                    visible: true
                },
                {
                    name: 'id_sintese',
                    data: 'id_sintese',
                    visible: true

                },
                {
                    name: 'id_produto',
                    data: 'id_produto',
                    visible: false
                },
                {
                    name: 'cd_produto',
                    data: 'cd_produto',
                    visible: true
                },
                {
                    name: 'id_cliente',
                    data: 'id_cliente',
                    visible: false
                },
                {
                    name: 'nome_fantasia',
                    data: 'nome_fantasia',
                    visible: true
                },
                {
                    name: 'estado',
                    data: 'estado',
                    visible: true
                },
            ],
            "order": [[ 1, "desc" ]],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0 },
                {targets: [1], visible: false }
            ],
            rowCallback: function (row, data) {
                $(row).data('id', data.id).css('cursor', 'pointer');
            },
            drawCallback: function () {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });


        $('#btnAprovar').click(function (e) {
            e.preventDefault();
            var elementos = [];
            var url = $(this).attr('href');


            $.map(dt1.rows('.selected').data(), function (item) {
                elementos.push({
                    id_produto: item.id_produto,
                    id_sintese: item.id_sintese,
                    id_cliente: item.id_cliente,
                    codigo: item.cd_produto,
                });
            });

            if (elementos.length > 0) {
                $.post(url, {el: elementos}, function (xhr) {
                    $('#data-table').DataTable().ajax.reload();
                    formWarning(xhr);
                }, 'JSON');
            } else {
                formWarning({
                    type: 'warning',
                    message: "Nenhum registro selecionado!"
                });
            }
        });

        $('#btnRejeitar').click(function (e) {
            e.preventDefault();
            var elementos = [];
            var url = $(this).attr('href');


            $.map(dt1.rows('.selected').data(), function (item) {
                elementos.push({
                    id_produto: item.id_produto,
                    id_sintese: item.id_sintese,
                    id_cliente: item.id_cliente,
                    codigo: item.cd_produto,
                });
            });

            if (elementos.length > 0) {
                $.post(url, {el: elementos}, function (xhr) {
                    $('#data-table').DataTable().ajax.reload();
                    formWarning(xhr);
                }, 'JSON');
            } else {
                formWarning({
                    type: 'warning',
                    message: "Nenhum registro selecionado!"
                });
            }
        });
        $('#checkall').click(function (event) { checkall(dt1, document.getElementById('checkall')); });


        $('#estados, #id_cliente, #cd_cotacao, #integrador').on('change', function () {
            dt1.draw();
        });

    });


    function checkall(table, checkall)
    {
        if (checkall.checked == true) {
            table.rows({search:'applied'}).select();
        } else {
            table.rows().deselect();
        }
    }
</script>
</body>

