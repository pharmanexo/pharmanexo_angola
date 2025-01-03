<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>
    <div class="content__inner">
        <div class="row" hidden>
            <div class="col-2">
                <div class="form-group">
                    <label for="">Pesquisar por integrador</label>
                    <select name="integrador" id="id_integrador" class="form-control">
                        <option value="">Todos </option>
                        <?php foreach ($integradores as $integrador){ ?>
                            <option value="<?php echo $integrador['id']; ?>"><?php echo $integrador['desc']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="">Pesquisar por comprador</label>
                    <select class="select2" id="id_cliente" data-placeholder="Selecione" data-allow-clear="true" data-toggle="tooltip" title="Clique para selecionar">
                        <option></option>
                        <?php foreach ($compradores as $comprador): ?>
                            <option value="<?php echo $comprador['id']; ?>"><?php echo $comprador['comprador']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-4">
                <div class="form-group">
                    <label for="">Pesquisar por data</label>
                    <div class="input-group">
                        <input type="date" id="data_ini" class="form-control">
                        <div class="input-group-append">
                            <div class="input-group-text"> a</div>
                        </div>
                        <input type="date" id="data_fim" class="form-control">
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="">Pesquisar por Ordem de Compra</label>
                    <input type="text" id="f_cd_oc" class="form-control">
                </div>
            </div>
        </div>
        <div class="row" hidden>
            <div class="col-md-3 form-group">
                <label for="estados">Filtrar por Estado</label>
                <br>
                <select class="form-control" id="estados" multiple="multiple" style="heigth: 60%"
                        data-live-search="true" title="Selecione" data-actions-box="true">
                    <?php foreach ($estados as $estado): ?>
                        <option <?php if (isset($estado['selected'])) echo "selected"; ?>
                                value="<?php echo $estado['uf']; ?>"><?php echo $estado['descricao']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <div class="table-warning ml-2"
                             style="width: 15px; height: 15px; border-radius: 20%; display: inline-block"></div> Pedido Urgente
                    </div>
                </div>
                <div class="legends"></div>
                <div class="table-responsive">
                    <table id="data-table" class="table table-condensed table-hover no-filtered"
                           data-url="<?php if (isset($urlDatatables)) echo $urlDatatables; ?>"
                           data-detalhes="<?php if (isset($urlDetalhes)) echo $urlDetalhes; ?>"
                           data-change_status="<?php if (isset($urlChangeStatusPending)) echo $urlChangeStatusPending; ?>"
                    >
                        <thead>
                        <tr>
                            <th class="text-center">
                                <div class="checkbox" data-toggle="tooltip" title="Marcar todos">
                                    <input type="checkbox" id="checkall">
                                    <label class="checkbox__label" for="checkall"></label>
                                </div>
                            </th>
                            <th>ID OC</th>
                            <th>ID Solicitação</th>
                            <th>Comprador</th>
                            <th>UF</th>
                            <th>Situação</th>
                            <th>Data Criação</th>
                            <th></th>
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

    var urlDetalhes = $('#data-table').data('detalhes');
    var urlChange_status = $('#data-table').data('change_status');
    $('#estados').selectpicker();

    $(function () {
        var dt1 = $('#data-table').DataTable({
            "processing": true,
            lengthChange: false,
            stateSave: true,
            serverSide: true,
            buttons: [],
            ajax: {
                url: $('#data-table').data('url'),
                type: 'post',
                dataType: 'json',
                data: function (data) {

                /*    if ($('#f_cd_oc').val() != '') {

                        data.columns[2].search.value = $('#f_cd_oc').val().toString();
                    }

                    if ($('#data_ini').val() !== '') {
                        let dt1 = $('#data_ini').val();
                        let dt2 = ($('#data_fim').val() !== '') ? $('#data_fim').val() : dt1;
                        data.columns[1].search.value = `${dt1},${dt2}`;
                        data.columns[1].search.type = 'date';
                    }

                    if ($('#id_cliente').val() != '') {

                        data.columns[9].search.value = $('#id_cliente').val().toString();
                        data.columns[9].search.type = 'equal';
                    }
*/

                    return data;
                }
            },
            columns: [
                {defaultContent: '', width: '100px', orderable: false, searchable: false},
                {name: 'ocs.id', data: 'id', width: '150px', visible: false},
                {name: 'ocs.id_solicitacao', data: 'id_solicitacao'},
                {name: 'f.nome_fantasia', data: 'nome_fantasia'},
                {name: 'f.estado', data: 'estado'},
                {name: 'sts.descricao', data: 'descricao'},
                {name: 'ocs.data_criacao', data: 'data_criacao'},
            ],
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0},
            ],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            order: [[1, "desc"]],
            rowCallback: function (row, data) {
                $(row).css('cursor', 'pointer');

                if(data.prioridade == 1){
                    $(row).addClass('table-warning fa-pisca');
                }

            },
            drawCallback: function () {
            }
        });

        $('#data-table tbody').on('click', 'tr td:not(:first-child)', function (e) {
            e.preventDefault();
            var rowIdx = dt1.cell(this).index().row;
            var data = dt1.row(rowIdx).data();

            window.location.href = urlDetalhes + data.id;
        });

        $('#btnChangeStatus').click(function (e) {
            e.preventDefault();
            var elementos = [];
            var url = $();

            $.map(dt1.rows('.selected').data(), function (item) {
                elementos.push(item.id);
            });

            if (elementos.length > 0) {
                $.post(urlChange_status, {el: elementos}, function (xhr) {
                    $('#data-table').DataTable().ajax.reload();
                    formWarning(xhr);

                    $('[data-select]').attr('checked', false)
                }, 'JSON');
            } else {
                formWarning({
                    type: 'warning',
                    message: "Nenhum registro selecionado!"
                });
            }
        });

        $('#checkall').click(function (event) {
            checkall(dt1, document.getElementById('checkall'));
        });

        $('#f_cd_oc, #id_integrador, #id_cliente, #data_ini, #data_fim, #estados').on('change', function () {
            $('#data-table').DataTable().ajax.reload();
        });
    });

    function checkall(table, checkall) {
        if (checkall.checked == true) {
            table.rows({search: 'applied'}).select();
        } else {
            table.rows().deselect();
        }
    }
</script>

</html>
