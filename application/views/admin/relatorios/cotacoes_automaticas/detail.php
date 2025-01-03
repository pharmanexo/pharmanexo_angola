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
                                    <th>Marca</th>
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

            var buttonCommon = {
                exportOptions: {
                    format: {
                        body: function ( data, row, column, node ) {
                            return column === 3 ? "R$ " + data : data;
                        }
                    }
                }
            };

            var oldExportAction = function (self, e, dt, button, config) {
                if (button[0].className.indexOf('buttons-excel') >= 0) {
                    if ($.fn.dataTable.ext.buttons.excelHtml5.available(dt, config)) {
                        $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config);
                    }
                    else {
                        $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
                    }
                } else if (button[0].className.indexOf('buttons-print') >= 0) {
                    $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
                }
            };

            var newExportAction = function (e, dt, button, config) {
                var self = this;
                var oldStart = dt.settings()[0]._iDisplayStart;

                dt.one('preXhr', function (e, s, data) {
                    // Just this once, load all data from the server...
                    data.start = 0;
                    data.length = 2147483647;

                    dt.one('preDraw', function (e, settings) {
                        // Call the original action function 
                        oldExportAction(self, e, dt, button, config);

                        dt.one('preXhr', function (e, s, data) {
                            // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                            // Set the property to what it was before exporting.
                            settings._iDisplayStart = oldStart;
                            data.start = oldStart;
                        });

                        // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
                        setTimeout(dt.ajax.reload, 0);

                        // Prevent rendering of the full data to the DOM
                        return false;
                    });
                });

                // Requery the server with the new one-time export settings
                dt.ajax.reload();
            };

            var dt = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                lengthChange: false,
                responsive: true,
                pageLength: 10,
                
                ajax: {
                    url: $('#data-table').data('url'),
                    type: 'post',
                    dataType: 'json',
                },
                columns: [
                    { name: 'id', data: 'id', searchable: true, visible: false },
                    { name: 'produto', data: 'produto'},
                    { name: 'marca', data: 'marca'},
                    { name: 'preco_marca', data: 'preco_marca'},
                    { name: 'qtd_solicitada', data: 'qtd_solicitada'},
                ],
                rowCallback: function(row, data) {
                },
                drawCallback: function() {}
            });
        });
    </script>
</body>

</html>