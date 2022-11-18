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
            <div class="col-4">
                <div class="form-group">
                    <label for="">Pesquisar por usuário</label>
                    <select id="id_usuario" data-live-search="true" class="form-control" multiple
                            style="heigth: 60%" data-placeholder="Selecione"
                            data-allow-clear="true" data-toggle="tooltip"
                            title="Clique para selecionar" data-actions-box="true">
                        <?php foreach ($usuarios as $usuario) { ?>
                            <option value="<?php echo $usuario['id']; ?>"><?php echo $usuario['nickname']; ?></option>
                        <?php } ?>
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
            <div class="col-4">
                <div class="form-group">
                    <label for="">Pesquisar por Cotação</label>
                    <input type="text" id="f_cd_oc" class="form-control">
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="row mx-auto mt-3">
                    <div class="col-12">
                        <div class="table-responsive">
                            <table id="data-table" class="table table-condensend table-hover"
                                   data-url="<?php echo $datatable; ?>">
                                <thead>
                                <tr>
                                    <th>COD USUÁRIO</th>
                                    <th>DATA OFERTA</th>
                                    <th>COTAÇÃO</th>
                                    <th>USUÁRIO</th>
                                    <th>INTEGRADOR</th>
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
</body>

<?php echo $scripts; ?>

<script>
    var url_delete = $('#data-table').data('delete_multiple');

    $('#id_usuario').selectpicker();

    $(function () {
        var table = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            lengthChange: false,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'post',
                dataType: 'json',
                data: function (data) {
                    if ($('#f_cd_oc').val() != '') {

                        data.columns[2].search.value = $('#f_cd_oc').val().toString();
                    }

                    if ($('#data_ini').val() !== '') {
                        let dt1 = $('#data_ini').val();
                        let dt2 = ($('#data_fim').val() !== '') ? $('#data_fim').val() : dt1;
                        data.columns[1].search.value = `${dt1},${dt2}`;
                        data.columns[1].search.type = 'date';
                    }

                    if ($('#id_usuario').val() != '') {

                        data.columns[0].search.value = $('#id_usuario').val().toString();
                        data.columns[0].search.type = 'in';
                    }

                    return data;
                }
            },
            columns: [
                {name: 'cp.id_usuario', data: 'id_usuario', width: '100px'},
                {name: 'cp.data_criacao', data: 'data_criacao'},
                {name: 'cp.cd_cotacao', data: 'cd_cotacao'},
                {name: 'u.nickname', data: 'nickname'},
                {name: 'cp.integrador', data: 'integrador'},
            ],
            order: [[1, 'DESC']],
            rowCallback: function (row, data) {

                $(row).css('cursor', 'pointer');
            },
            drawCallback: function () {
            }
        });

        $('#id_usuario, #data_ini, #data_fim, #f_cd_oc').on('change', function () {
            $('#data-table').DataTable().ajax.reload();
        });


    });
</script>

</html>