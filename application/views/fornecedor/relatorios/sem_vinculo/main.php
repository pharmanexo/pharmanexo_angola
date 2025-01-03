<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php if (isset($heading)) echo $heading; ?>
    <form action="<?php echo $to_datatable; ?>/1" name="fitros" id="filters" method="post"
          enctype="multipart/form-data">

        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label for="">Período</label>
                    <div class="input-group mb-3">
                        <input type="date" class="form-control" name="data_ini" id="data_ini">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">a</span>
                        </div>
                        <input type="date" class="form-control" name="data_fim" id="data_fim">
                    </div>
                </div>
            </div>

            <div class="col-3 pull-right">
                <div class="form-group">
                    <label for="">Exportar</label><br>
                    <button type="submit" form="filters" id="btnExcel" class="btn pull-right mt-2 btn-secondary"><i
                                class="fas fa-file-excel"></i></button>
                </div>
            </div>
        </div>
        <br>
        <p id="msg"></p>
    </form>
    <p class="small text-muted">Este relatório pode demorar alguns minutos para processar os dados.</p>
    <div class="" id="printAll">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive col-sm">
                    <table id="data-table" class="table table-condensend table-hover"
                           data-url="<?php echo $to_datatable; ?>">
                        <thead>
                        <tr>
                            <th>CNPJ</th>
                            <th width="200px">Cliente</th>
                            <th>UF</th>
                            <th>Produto</th>
                            <th>Quantidade</th>
                            <th>Unidade Medida</th>
                            <th>Data Cotação</th>
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
    var url;
    $(function () {

        $('#gerente').selectpicker();
        $('#cliente').selectpicker();
        $('#uf').selectpicker();

        url = $('#data-table').data('url');

        var table = $('#data-table').DataTable({
            "processing": true,
            "serverSide": true,
            "bFilter": false,
            lengthChange: false,
            pageLength: 100,
            ajax: {
                url: url,
                type: 'POST',
                dataType: 'json',
                data: function (data) {


                    return data

                }
            },
            columns: [
                {data: 'cnpj'},
                {data: 'nome_fantasia', width: '200px'},
                {data: 'estado'},
                {data: 'ds_produto_comprador'},
                {data: 'qt_produto_total'},
                {data: 'ds_unidade_compra'},
                {data: 'dt_inicio_cotacao'},
            ],
            order: [[1, 'asc']],
            rowCallback: function (row, data) {
            },
            drawCallback: function () {
            },
            initComplete: function (settings, json) {
                $(this).closest('.dataTables_wrapper').prepend(''); // Add custom button (fullscreen, print and export)
            }
        });

        $('#data_fim, #gerente, #loja, #cliente, #uf, #confirmada').change(function (e) {

            if ($('#data_ini').val() == '') {
                formWarning({type: 'warning', 'message': 'Informe da data de início'});
                return false;
            }

            if ($('#data_fim').val() == '') {
                formWarning({type: 'warning', 'message': 'Informe da data fim'});
                return false;
            }

            console.log($("#gerente").val().toString());
            table.ajax.reload();
        });

    })
    ;
</script>
</body>
