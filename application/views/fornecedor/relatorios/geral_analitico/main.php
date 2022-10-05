<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>
<div class="content">
    <?php echo $heading; ?>
    <div class="content__inner">
        <form action="<?php echo $dataTable; ?>" name="fitros" id="filters" method="post" enctype="multipart/form-data">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label for="">Período</label>
                                <div class="input-group mb-3">
                                    <input type="date" class="form-control"
                                           value="<?php if (isset($post['dataini'])) echo $post['dataini']; ?>"
                                           name="dataini"
                                           id="dataini">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1">a</span>
                                    </div>
                                    <input type="date" class="form-control" name="datafim"
                                           value="<?php if (isset($post['datafim'])) echo $post['datafim']; ?>"
                                           id="datafim">
                                </div>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label for="">Cliente</label>
                                <input type="hidden" id="id_clientes" name="id_clientes">
                                <select id="clientes" data-live-search="true" multiple
                                        title="Selecione" class="form-control">
                                    <option value="">Selecione...</option>
                                    <?php foreach ($clientes as $cliente) { ?>
                                        <option value="<?php echo $cliente['id']; ?>"><?php echo $cliente['cnpj'] . " - " . $cliente['nome_fantasia']; ?></option>
                                    <?php } ?>

                                </select>
                            </div>
                        </div>

                        <div class="col-3">
                            <div class="form-group">
                                <label for="">Estado</label>
                                <input type="hidden" id="estados" name="estados">
                                <select id="uf" multiple="multiple" title="Selecione" data-live-search="true"
                                        class="form-control">
                                    <?php foreach ($estados as $uf): ?>
                                        <option <?php if (isset($uf['selected']) && $uf['selected'] == true) echo 'selected'; ?>
                                                value="<?php echo $uf['uf']; ?>"><?php echo $uf['descricao']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>


                        <div class="col-3">
                            <div class="form-group text-center">
                                <label for="">Ações</label><br>
                                <button type="submit" form="filters" id="btnBuscar"
                                        class="btn pull-right mt-2 btn-primary"><i
                                            class="fas fa-search"></i></button>
                                <button type="button" form="filters" id="btnExcel"
                                        class="btn pull-right mt-2 btn-secondary"><i
                                            class="fas fa-file-excel"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </form>

    </div>
</div>


<?php echo $scripts; ?>

<script>
    var url;
    $(function () {


        $('#uf').selectpicker();
        $('#clientes').selectpicker();


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

        $('#btnBuscar, #btnExcel').click(function () {
            $('#message').removeClass('d-none').html("<i class='fa fa-spin fa-spinner'></i> Carregando informações ...");
        });


        $('#btnExcel').click(function (e) {
            e.preventDefault();
            $('#filters').prop('action', '<?php if (isset($url_export)) echo $url_export?>');

            if ($("#uf").val() != null) {
                $('#estados').val($("#uf").val().toString());
            }

            if ($("#clientes").val() != null) {
                $('#id_clientes').val($("#clientes").val().toString());
            }

            $('#filters').submit();
        });



        $('#btnBuscar').click(function (e) {
            e.preventDefault();
            $('#filters').prop('action', '<?php if (isset($dataTable)) echo $dataTable?>');

            if ($("#uf").val() != null) {
                $('#estados').val($("#uf").val().toString());
            }

            if ($("#clientes").val() != null) {
                $('#id_clientes').val($("#clientes").val().toString());
            }

            $('#filters').submit();


        });

        $('#uf').change(function () {
            $('#estados').val($(this).val().toString())
        })

        $('#clientes').change(function () {
            $('#id_clientes').val($(this).val().toString())
        })

    })
    ;
</script>
</body>
