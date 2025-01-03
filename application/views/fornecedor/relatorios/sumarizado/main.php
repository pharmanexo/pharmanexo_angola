<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php if (isset($heading)) echo $heading; ?>
    <form action="<?php echo $to_datatable; ?>/1" name="fitros" id="filters" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <fieldset id="group2">
                        <input type="radio" value="SINTESE" name="integrador" checked> SÍNTESE
                        <input type="radio" value="BIONEXO" name="integrador"> BIONEXO
                        <input type="radio" value="APOIO" name="integrador"> APOIO
                    </fieldset>
                </div>
            </div>
        </div>

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
            <div class="col-3">
                <div class="form-group">
                    <label for="">Gerente</label>
                    <select name="gerente" id="gerente" multiple="multiple" data-live-search="true"
                            title="Selecione" class="form-control">
                        <?php foreach ($gerentes as $gerente) { ?>
                            <option value="<?php echo $gerente['id'] ?>"><?php echo $gerente['nome'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>


            <div class="col-3">
                <div class="form-group">
                    <label for="">Cliente</label>
                    <select name="cliente" id="cliente" multiple="multiple" data-live-search="true"
                            title="Selecione" class="form-control">
                        <?php foreach ($clientes as $cliente) { ?>
                            <option value="<?php echo $cliente['id'] ?>"><?php echo $cliente['cnpj'] . ' - ' . substr($cliente['nome_fantasia'], 0, 80) ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <?php if (isset($selectMatriz)): ?>
                <div class="col-3">
                    <div class="form-group">
                        <label for="id_fornecedor">Lojas</label>
                        <select class="select2" name="loja" id="loja" data-placeholder="Selecione">
                            <?php foreach ($selectMatriz as $f): ?>
                                <option value="<?php echo $f['id']; ?>" <?php if ($this->session->id_fornecedor == $f['id']) echo 'selected'; ?> ><?php echo $f['nome_fantasia']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php endif; ?>


            <div class="col-3">
                <div class="form-group">
                    <label for="">UF</label>
                    <select name="uf" id="uf" multiple="multiple" title="Selecione" data-live-search="true" class="form-control">
                        <?php foreach ($estados as $uf): ?>
                            <option value="<?php echo $uf['uf']; ?>" ><?php echo $uf['descricao']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="col-3">
                <div class="form-group">
                    <label for="">Confirmadas</label>
                    <select name="confirmada" id="confirmada" class="form-control">
                        <option value="">Todos</option>
                        <option value="1">Sim</option>
                        <option value="0">Não</option>
                    </select>
                </div>
            </div>

            <div class="col-3">
                <div class="form-group">
                    <label for="">Exportar</label><br>
                    <button type="submit" form="filters" id="btnExcel" class="btn pull-right mt-2 btn-secondary"><i class="fas fa-file-excel"></i></button>
                </div>
            </div>
        </div>
        <br>
        <p id="msg"></p>
    </form>
    <div class="" id="printAll">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive col-sm">
                    <table id="data-table" class="table table-condensend table-hover" data-url="<?php echo $to_datatable; ?>">
                        <thead>
                        <tr>
                            <th>CNPJ</th>
                            <th width="200px">Cliente</th>
                            <th>UF</th>
                            <th width="150px">Cotação</th>
                            <th>Itens Cotação</th>
                            <th>Itens Respondidos</th>
                            <th>Confirmado</th>
                            <th>Itens OC</th>
                            <th>Ordem Compra</th>
                            <th>Total Vendido</th>
                            <th>Gerente</th>
                            <th>Data</th>
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
            "serverSide": false,
            "bFilter": false,
            lengthChange: false,
            pageLength: 100,
            language: {
                "decimal": ",",
                "thousands": "."
            },
            ajax: {
                url: url,
                type: 'POST',
                dataType: 'json',
                data: function (data) {

                    data.dataini = $('#data_ini').val();
                    data.datafim = $('#data_fim').val();
                    data.gerente = $("#gerente").val().toString();
                    data.cliente = $("#cliente").val().toString();
                    data.loja = $("#loja").val();
                    data.uf = $("#uf").val().toString();
                    data.confirmada = $("#confirmada").val();
                    data.integrador = $('input[name="integrador"]:checked').val();

                    return data

                }
            },
            columns: [
                {data: 'cd_comprador'},
                {data: 'razao_social', width: '200px'},
                {data: 'uf_cotacao'},
                {data: 'cd_cotacao', width: '200px'},
                {data: 'itens'},
                {data: 'respondidos'},
                {data: 'confirmado'},
                {data: 'itens_oc'},
                {data: 'oc'},
                {data: 'total'},
                {data: 'gerente'},
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

        $('#data_fim, #gerente, #loja, #cliente, #uf, #confirmada, #group2').change(function (e) {

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
