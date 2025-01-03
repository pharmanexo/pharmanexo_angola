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
            <div class="card-header">
                <form name="formFilter" id="formFilter" action="<?php echo $url_export; ?>" method="post">
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="">Filtro por UF</label>
                                <input type="hidden" id="estados" name="estados">
                                <select name="uf" id="uf" multiple="multiple" title="Selecione" data-index="1" data-live-search="true"
                                        class="form-control">
                                    <?php foreach ($estados as $uf): ?>
                                        <option <?php if (isset($uf['selected']) && $uf['selected'] == true) echo 'selected'; ?> value="<?php echo $uf['uf']; ?>"><?php echo $uf['descricao']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="">Filtro por portal</label>
                                <input type="hidden" id="portais" name="portais">
                                <select name="portal" id="portal" multiple="multiple" title="Selecione" data-index="1" data-live-search="true"
                                        class="form-control">
                                    <?php foreach ($portais as $portal): ?>
                                        <option value="<?php echo $portal['id']; ?>"><?php echo $portal['desc']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="col-12">
                                <div class="cot-info" style="width: 15px; height: 15px; border-radius: 20%; border: 1px solid; display: inline-block; "></div>
                                Comprador já é cliente

                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="data-table" class="table table-condensed table-hover no-filtered"
                           data-update="<?php echo $url_detalhes; ?>" data-url="<?php echo $dataTable; ?>">
                        <thead>
                        <tr>
                            <th>CNPJ</th>
                            <th>Nome Fantasia</th>
                            <th>Razão Social</th>
                            <th>Estado</th>
                            <th>Portal</th>
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
    var dt;
    $(function () {

        $('#data_ini, #data_fim').datepicker({
            format: "dd/mm/yyyy",
            language: "pt-BR",
            orientation: "bottom auto",
            autoclose: true
        });

        $('#uf').selectpicker();
        $('#portal').selectpicker();

        dt = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            lengthChange: false,
            responsive: true,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'post',
                dataType: 'json',
                data: function (data) {
                    let nw_data = data;

                    if ($("#uf").val() != null) {
                        $('#estados').val($("#uf").val().toString());
                    }

                    if ($("#portal").val() != null) {
                        $('#portais').val($("#portal").val().toString());
                    }

                    nw_data.estados = $('#uf').val().toString();
                    nw_data.portais = $('#portal').val().toString();
                    return nw_data;
                }
            },
            columns: [
                {
                    name: 'c.cnpj',
                    data: 'cnpj',
                    searchable: true,
                    visible: true
                },
                {
                    name: 'c.nome_fantasia',
                    data: 'nome_fantasia',
                    searchable: true,
                    visible: true
                },
                {
                    name: 'c.razao_social',
                    data: 'razao_social',
                    searchable: true,
                    visible: true
                },
                {
                    name: 'c.estado',
                    data: 'estado',
                    searchable: true,
                    visible: true
                },
                {
                    name: 'i.desc',
                    data: 'integrador',
                    searchable: true,
                    visible: true
                }
            ],
            order: [[2, 'desc']],
            rowCallback: function (row, data) {
                $(row).data('id', data.id).css('cursor', 'pointer');
                if (data.venda == 1){
                    $(row).addClass('table-info');
                }
            },
            drawCallback: function () {


            }
        });

        if ($("#uf").val() != null) {
            $('#estados').val($("#uf").val().toString());
        }

        if ($("#portal").val() != null) {
            $('#portais').val($("#portal").val().toString());
        }

        $('[data-index]').on('change', function () {
            dt.ajax.reload();
        });


    });
</script>

</html>