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
                        <div class="col-12 col-lg-4">
                            <strong>CNPJ</strong> <br>
                            <?php if (isset($comprador['cnpj'])) echo $comprador['cnpj']; ?>
                        </div>
                        <div class="col-12 col-lg-4">
                            <strong>Razão Social</strong> <br>
                            <?php if (isset($comprador['razao_social'])) echo $comprador['razao_social']; ?>
                        </div>
                        <div class="col-12 col-lg-4">
                            <strong>Cidade/UF</strong> <br>
                            <?php if (isset($comprador['cidade'])) echo $comprador['cidade']; ?><?php if (isset($comprador['estado'])) echo  '/' . $comprador['estado']; ?>
                        </div>
                    </div>
                    <p class="text-muted mt-3 border-bottom"><strong>Dados da Cotação</strong></p>
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <strong>Data Cotação</strong> <br>
                            <?php if (isset($cotacao['data_cotacao'])) echo date('d/m/Y', strtotime($cotacao['data_cotacao'])); ?>
                        </div>
                        <div class="col-12 col-lg-4">
                            <strong>Total Itens</strong> <br>
                            <?php if (isset($total_itens)) echo $total_itens; ?>
                        </div>
                        <div class="col-12 col-lg-4">
                            <strong>Valor Total</strong> <br>
                            <?php if (isset($valor_total_produtos)) echo 'R$ ' . number_format($valor_total_produtos, 4, ',', '.'); ?>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="data-table" class="table table-condensed table-hover no-filtered" data-url="<?php echo $dataTable; ?>">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Produto</th>
                                    <th>Marca Solicitada</th>
                                    <th>Preço Marca</th>
                                    <th>Quantidade</th>
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
        $(function() {
            dt = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                lengthChange: false,
                responsive: true,
                pageLength: 10,
                ajax: {
                    url: $('#data-table').data('url'),
                    type: 'get',
                    dataType: 'json',
                },
                columns: [
                    { name: 'id', data: 'id', searchable: true, visible: false },
                    { name: 'produto', data: 'produto', searchable: true },
                    { name: 'marca_solicitada', data: 'marca_solicitada', searchable: true },
                    { name: 'preco_marca', data: 'preco_marca', searchable: true },
                    { name: 'qtd_solicitada', data: 'qtd_solicitada', searchable: true },
                ],
                rowCallback: function(row, data) {
                    $(row).data('id', data.id).css('cursor', 'pointer');
                },
                drawCallback: function() {}
            });
        });
    </script>
</body>

</html>