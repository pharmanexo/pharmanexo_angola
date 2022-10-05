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
                        <table id="data-table" class="table table-condensed table-hover no-filtered" data-url="<?php echo $dataTable; ?>" data-cot="<?php echo $url_cotacao ?>">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Código</th>
                                    <th>Produto</th>
                                    <th>Marca Oferta</th>
                                    <th>Preço Oferta</th>
                                    <th>Qtde. Solicitada</th>
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

            var buttonCommon = {
                exportOptions: {
                    format: {
                        body: function ( data, row, column, node ) {
                            return column === 3 ? data.replace( /[.]/g, '' ).replace( /[,]/g, '.' ) : data;
                        }
                    }
                }
            };

            var dt = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                lengthChange: false,
                responsive: true,
                pageLength: 10,
                dom: 'Bfrtip',
                buttons: [ $.extend( true, {}, buttonCommon, { extend: 'excelHtml5'} ) ],
                ajax: {
                    url: $('#data-table').data('url'),
                    type: 'get',
                    dataType: 'json',
                },
                columns: [
                    { name: 'id', data: 'id', searchable: true, visible: false },
                    { name: 'id_pfv', data: 'id_pfv' },
                    { name: 'produto', data: 'produto', searchable: true, className: 'center' },
                    { name: 'marca', data: 'marca', searchable: true },
                    { name: 'preco_marca', data: 'preco_marca', searchable: true },
                    { name: 'qtd_solicitada', data: 'qtd_solicitada', searchable: true },
                ],
                rowCallback: function(row, data) {
                   
                },
                drawCallback: function() {}
            });


            $('#btnEspelho').on('click', function(e) {
                e.preventDefault();

                newwindow=window.open($("#data-table").data('cot'),"cotacao_enviada",'height=400,width=800');
                if (window.focus) {newwindow.focus()}
                return false;

            });
            
            $('#btnEnvia').click(function (e) {
                e.preventDefault();
                
                $.get($(this).attr('href'), function (e) {
                    var mailToLink = "mailto:x@y.com?body=" + encodeURIComponent(e);
                    window.location.href = mailToLink;
                })
                
            });

        });
    </script>
</body>

</html>
