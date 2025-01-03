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
                        <option value="">Selecione...</option>
                        <?php foreach ($gerentes as $gerente) { ?>
                            <option value="<?php echo $gerente['id'] ?>"><?php echo $gerente['nome'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="col-3">
                <div class="form-group">
                    <label for="">Consultor</label>
                    <select name="consultor" id="consultor" multiple="multiple" data-live-search="true"
                            title="Selecione" class="form-control">
                        <?php foreach ($consultores as $consultor) { ?>
                            <option value="<?php echo $consultor['id'] ?>"><?php echo $consultor['nome'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="">Assistente</label>
                    <select name="assistente" id="assistente" multiple="multiple" data-live-search="true"
                            title="Selecione" class="form-control">
                        <?php foreach ($assistentes as $assistente) { ?>
                            <option value="<?php echo $assistente['id'] ?>"><?php echo $assistente['nome'] ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="">Produto</label>
                    <input name="produto" id="produto" class="form-control">
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
                    <label for="">Status</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">Todos</option>
                        <option value="1">Respondidos</option>
                        <option value="0">Não respondidos</option>
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
    <p class="small text-muted">Este relatório pode demorar alguns minutos para processar os dados.</p>
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
                            <th>Gerente</th>
                            <th>Consultor</th>
                            <th>Assistente</th>
                            <th>Produto</th>
                            <th>Qtde</th>
                            <th>Preço</th>
                            <th>Total</th>
                            <th>Data</th>
                            <th>Status</th>
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
        $('#consultor').selectpicker();
        $('#assistente').selectpicker();

        url = $('#data-table').data('url');

        var table = $('#data-table').DataTable({
            "processing": true,
            "serverSide": false,
            "bFilter": false,
            lengthChange: false,
            pageLength: 100,
            ajax: {
                url: url,
                type: 'POST',
                dataType: 'json',
                data: function (data) {

                    data.dataini = $('#data_ini').val();
                    data.datafim = $('#data_fim').val();
                    data.gerente = $("#gerente").val().toString();
                    data.consultor = $("#consultor").val().toString();
                    data.assitente = $("#assistente").val().toString();
                    data.produto = $("#produto").val();
                    data.loja = $("#loja").val();
                    data.status = $("#status").val();

                    return data

                }
            },
            columns: [
                {data: 'cd_comprador'},
                {data: 'nome_fantasia', width: '200px'},
                {data: 'uf_cotacao'},
                {data: 'cd_cotacao', width: '200px'},
                {data: 'gerente'},
                {data: 'consultor'},
                {data: 'assistente'},
                {data: 'ds_produto_comprador'},
                {data: 'qt_produto_total'},
                {data: 'preco'},
                {data: 'total'},
                {data: 'dt_inicio_cotacao'},
                {data: 'status'},
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

        $('#data_fim, #assistente, #gerente, #consultor, #loja, #produto, #status').change(function (e) {

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
