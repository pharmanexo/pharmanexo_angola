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
                        <div class="col-md-4 col-xs-12 form-group">
                            <label for="filtro-data-emissao">Data do Pedido</label>
                            <div class="input-group date">
                                <input type="text" class="form-control" name="data_ini" id="data_ini">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-light">a</span>
                                </div>
                                <input type="text" class="form-control" name="data_fim" id="data_fim" data-index="1">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="">UF</label>
                                <input type="hidden" id="estados" name="estados">
                                <select name="uf" id="uf" multiple="multiple" title="Selecione" data-index="1"
                                        data-live-search="true"
                                        class="form-control">
                                    <?php foreach ($estados as $uf): ?>
                                        <option <?php if (isset($uf['selected']) && $uf['selected'] == true) echo 'selected'; ?>
                                                value="<?php echo $uf['uf']; ?>"><?php echo $uf['descricao']; ?></option>
                                    <?php endforeach; ?>
                                </select>
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
                            <th>Código</th>
                            <th>Nome Comercial</th>
                            <th>Marca</th>
                            <th>Qtde. Produtos</th>
                            <th>Total vendido (unit)</th>
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

                    nw_data.data_ini = $('#data_ini').val();
                    nw_data.data_fim = $('#data_fim').val();
                    nw_data.estados = $('#uf').val().toString();




                    return nw_data;
                }
            },

            columns: [
                {
                    name: 'codigo',
                    data: 'codigo',
                    searchable: true,
                    visible: true
                },
                {
                    name: 'nome_comercial',
                    data: 'nome_comercial',
                    searchable: true,
                    visible: true
                },
                {
                    name: 'marca',
                    data: 'marca',
                    searchable: true,
                    visible: true
                },
                {
                    name: 'qtd_produtos',
                    data: 'qtd_produtos',
                    searchable: true,
                    visible: true
                },
                {
                    name: 'total_vendido',
                    data: 'total_vendido',
                    searchable: true,
                    visible: true
                }
            ],
            order: [[4, 'desc']],
            rowCallback: function (row, data) {
                $(row).data('id', data.id).css('cursor', 'pointer');
            },
            drawCallback: function () {
                $(".dataTables_filter").hide();
                $('table tbody tr').each(function () {
                    $(this).on('click', function () {
                        window.location.href = $('#data-table').data('update') + $(this).data('id')
                    })
                })
            }
        });


        $('[data-index]').on('change', function () {
            dt.ajax.reload();
        });


    });
</script>

</html>