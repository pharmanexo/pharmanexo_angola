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

                    <p class="text-muted border-bottom"><strong>Dados do Comprador</strong></p>
                    <div class="row">
                        <div class="col-12 col-lg-3">
                            <strong>CNPJ</strong> <br>
                            <?php if (isset($comprador['cnpj'])) echo $comprador['cnpj']; ?>
                        </div>
                        <div class="col-12 col-lg-6">
                            <strong>Razão Social</strong> <br>
                            <?php if (isset($comprador['razao_social'])) echo $comprador['razao_social']; ?>
                        </div>
                        <div class="col-12 col-lg-3">
                            <strong>Cidade/UF</strong> <br>
                            <?php if (isset($comprador['cidade'])) echo $comprador['cidade']; ?><?php if (isset($comprador['estado'])) echo  '/' . $comprador['estado']; ?>
                        </div>
                    </div>

                    <br>

                    <p class="text-muted border-bottom"><strong>Dados do Fornecedor</strong></p>
                    <div class="row">
                        <div class="col-12 col-lg-3">
                            <strong>CNPJ</strong> <br>
                            <?php if (isset($fornecedor['cnpj'])) echo $fornecedor['cnpj']; ?>
                        </div>
                        <div class="col-12 col-lg-6">
                            <strong>Razão Social</strong> <br>
                            <?php if (isset($fornecedor['razao_social'])) echo $fornecedor['razao_social']; ?>
                        </div>
                        <div class="col-12 col-lg-3">
                            <strong>Cidade/UF</strong> <br>
                            <?php if (isset($fornecedor['cidade'])) echo $fornecedor['cidade']; ?><?php if (isset($fornecedor['estado'])) echo  '/' . $fornecedor['estado']; ?>
                        </div>
                    </div>
                    
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="data-table" class="table table-condensed table-hover no-filtered" data-url="<?php echo $datatables; ?>" data-details="<?php echo $url_detalhes; ?>">
                            <thead>
                                <tr>
                                    <th>Cotação</th>
                                    <th>Descrção</th>
                                    <th>Data Inicio</th>
                                    <th>Data Término</th>
                                    <th>UF</th>
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

        var url_details = $('#data-table').data('details');

        $(function() {

            var table = $('#data-table').DataTable({
                processing: true,
                serverSide: false,
                lengthChange: false,
                ajax: {
                    url: $('#data-table').data('url'),
                    type: 'post',
                    dataType: 'json',
                },
                columns: [
                    { name: 'cd_cotacao', data: 'cd_cotacao' },
                    { name: 'ds_cotacao', data: 'ds_cotacao' },
                    { name: 'dt_inicio_cotacao', data: 'dt_inicio_cotacao' },
                    { name: 'dt_fim_cotacao', data: 'dt_fim_cotacao' },
                    { name: 'uf_cotacao', data: 'uf_cotacao' },
                ],
                rowCallback: function(row, data) {
                    $(row).css('cursor', 'pointer');
                    
                    $('td', row).each(function () {
                        $(this).on('click', function () {
                            window.location.href = `${url_details}/${data.cd_cotacao}`
                        });
                    });
                },
                drawCallback: function() {}
            });

        });
    </script>
</body>

</html>
