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
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-condensend table-hover w-100" data-url="<?php echo $dataSource; ?>">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Produto</th>
                                    <th>Marca</th>
                                    <th>Preço</th>
                                    <th>Quantidade</th>
                                    <th>Qtd Embalagem</th>
                                    <th>Validade</th>
                                    <th>Lote</th>
                                    <th></th>
                                    <th></th>
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

            var dt = $('#dataTable').DataTable({
                serverSide: false,
                processing: true,
                lengthChange: false,
                displayLength: 10,
                ajax: {
                    url: $('#dataTable').data('url'),
                    type: 'post',
                    dataType: 'json'
                },
                columns: [
                    {name: 'pc.codigo', data: 'codigo'},
                    {name: 'pc.nome_comercial', data: 'nome_comercial'},
                    {name: 'pc.marca', data: 'marca'},
                    {name: 'pc.preco_unitario', data: 'preco', searchable: false},
                    {name: 'pl.estoque', data: 'estoque'},
                    {name: 'pc.quantidade_unidade', data: 'quantidade_unidade', className: 'text-nowrap'},
                    {name: 'pl.validade', data: 'validade'},
                    {name: 'pl.lote', data: 'lote'},
                    {defaultContent: '', width: '100px', orderable: false, searchable: false },
                    {name: 'pl.validade', data: 'validade_padrao', visible: false},
                ],
                "order": [[ 9, "asc" ]],
                rowCallback: function(row, data) {
                    var button = $(`<button data-href='<?php if (isset($url_regra_venda)) echo $url_regra_venda; ?>${data.codigo}' data-toggle='tooltip' title='Configurar Vendas Diferenciadas' class='btn btn-light'><i class='fas fa-hand-holding-usd'></i></button>`);

                    button.click(function (e) {
                        e.preventDefault();
                        $.ajax({
                            type: 'get',
                            url: button.data('href'),
                            dataType: 'html',
                            data: { lote: data.lote },
                            success: function(response) {
                                $('body').append(response);
                                $('#modalVenda').modal({
                                    keyboard: false
                                }, 'show').on('hide.bs.modal', function() {
                                    $('#modalVenda').remove();
                                }).on('shown.bs.modal', function () {

                                });
                            }
                        });

                    });

                    $('td:eq(8)', row).html(button);

                },
                drawCallback: function() {
                }
            });
        });
    </script>
</body>

</html>
