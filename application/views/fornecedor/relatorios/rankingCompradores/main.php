<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php if (isset($heading)) echo $heading; ?>
    <form action="<?php echo $dataTable; ?>" name="fitros" id="filters" method="post" enctype="multipart/form-data">

        <div class="row">
            <div class="col-3">
                <div class="form-group">
                    <label for="">Período</label>
                    <div class="input-group mb-3">
                        <input type="date" class="form-control"
                               value="<?php if (isset($post['dataini'])) echo $post['dataini']; ?>" name="dataini"
                               id="dataini">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="basic-addon1">a</span>
                        </div>
                        <input type="date" class="form-control" name="datafim"
                               value="<?php if (isset($post['datafim'])) echo $post['datafim']; ?>" id="datafim">
                    </div>
                </div>
            </div>

            <div class="col-3">
                <div class="form-group">
                    <label for="">Lojas</label>
                    <select id="id_fornecedor" data-live-search="true"
                            title="Selecione" name="id_fornecedor" class="form-control">
                        <option value="">Selecione...</option>
                        <option <?php if (isset($post['id_fornecedor']) && $post['id_fornecedor'] == 'ALL') echo 'selected'; ?>
                                value="ALL">TODAS
                        </option>
                        <option <?php if (isset($post['id_fornecedor']) && $post['id_fornecedor'] == 'ONLY') echo 'selected'; ?>
                                value="ONLY">LOJA ATUAL
                        </option>

                    </select>
                </div>
            </div>

            <div class="col-3">
                <div class="form-group">
                    <label for="">UF</label>
                    <input type="hidden" id="estados" name="estados">
                    <select name="uf" id="uf" multiple="multiple" title="Selecione" data-live-search="true"
                            class="form-control">
                        <?php foreach ($estados as $uf): ?>
                            <option <?php if (isset($uf['selected']) && $uf['selected'] == true) echo 'selected'; ?> value="<?php echo $uf['uf']; ?>"><?php echo $uf['descricao']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>


            <div class="col-3">
                <div class="form-group">
                    <label for="">Ações</label><br>
                    <button type="submit" form="filters" id="btnBuscar" class="btn pull-right mt-2 btn-primary"><i
                                class="fas fa-search"></i></button>
                    <button type="button" form="filters" id="btnExcel" class="btn pull-right mt-2 btn-secondary"><i
                                class="fas fa-file-excel"></i></button>
                </div>
            </div>
        </div>
    </form>
    <p class="p-4 text-center d-none" id="message"></p>
    <div class="" id="printAll">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive col-sm">
                    <table id="data-table" class="table table-condensend table-hover"
                           data-url="<?php echo $dataTable; ?>">
                        <thead>
                        <tr>
                            <th></th>
                            <th>CNPJ</th>
                            <th>Cliente</th>
                            <th>TOTAL VENDIDO</th>
                            <th>ESTADO</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (isset($consulta) && !empty($consulta)) { $i = 1; ?>
                            <?php foreach ($consulta as $item) { ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $item['cnpj']; ?></td>
                                    <td><?php echo $item['nome_fantasia']; ?></td>
                                    <td style="text-align: right"><?php echo number_format($item['total'], 2, ',', '.'); ?></td>
                                    <td><?php echo $item['estado']; ?></td>
                                </tr>
                            <?php $i++;} ?>
                        <?php } ?>
                        </tbody>
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


        $('#uf').selectpicker();
        $('#id_fornecedor').selectpicker();


        $('#data_fim, #loja').change(function (e) {

            if ($('#data_ini').val() == '') {
                formWarning({type: 'warning', 'message': 'Informe da data de início'});
                return false;
            }

            if ($('#data_fim').val() == '') {
                formWarning({type: 'warning', 'message': 'Informe da data fim'});
                return false;
            }


        });

        $('#btnBuscar, #btnExcel').click(function (){
            $('#message').removeClass('d-none').html("<i class='fa fa-spin fa-spinner'></i> Carregando informações ...");
        });


        $('#btnExcel').click(function (e){
            e.preventDefault();
            $('#filters').prop('action', '<?php if (isset($url_export)) echo $url_export?>');

            if ($("#uf").val() != null) {
                $('#estados').val($("#uf").val().toString());
            }

            $('#filters').submit();
        });


        $('#btnBuscar').click(function (e){
            e.preventDefault();
            $('#filters').prop('action', '<?php if (isset($dataTable)) echo $dataTable?>');

            if ($("#uf").val() != null) {
                $('#estados').val($("#uf").val().toString());
            }

            $('#filters').submit();


        });

        $('#uf').change(function (){
            $('#estados').val($(this).val().toString())
        })

    })
    ;
</script>
</body>
