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
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label for="">Selecionar fornecedor</label>
                                <select name="" id="" class="form-control"></select>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="dataTable" class="table table-condensend table-hover w-100" data-url="<?php echo $dataSource; ?>">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Produto</th>
                                    <th>Marca</th>
                                    <th>Preço</th>
                                    <th>Quantidade</th>
                                    <th>Validade</th>
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

            var buttonCommon = {
                exportOptions: {
                    format: {
                        body: function ( data, row, column, node ) {
                            return column === 3 ? data.replace( /[.]/g, '' ).replace( /[,]/g, '.' ) : data;
                        }
                    }
                }
            };

            var dt = $('#dataTable').DataTable({
                serverSide: false,
                lengthChange: false,
                displayLength: 10,
                dom: 'Bfrtip',
                buttons: [ $.extend( true, {}, buttonCommon, { extend: 'excelHtml5'} ) ],
                ajax: {
                    url: $('#dataTable').data('url'),
                    type: 'GET',
                    dataType: 'json'
                },

                columns: [
                    {
                        name: 'vw_produtos_fornecedores_sintese.codigo',
                        data: 'codigo'
                    },
                    {
                        name: 'vw_produtos_fornecedores_sintese.nome_comercial',
                        data: 'nome_comercial'
                    },
                    {
                        name: 'vw_produtos_fornecedores_sintese.marca',
                        data: 'marca'
                    },
                    {
                        name: 'vw_produtos_fornecedores_sintese.preco',
                        data: 'preco'
                    },
                    {
                        name: 'vw_produtos_fornecedores_sintese.estoque',
                        data: 'estoque'
                    },
                    {
                        name: 'vw_produtos_fornecedores_sintese.validade',
                        data: 'validade'
                    },
                    {
                        defaultContent: '',
                        width: '100px',
                        orderable: false,
                        searchable: false
                    }
                ],
                "order": [[ 5, "asc" ]],
                rowCallback: function(row, data) {
                    var button = $(`<button data-href='<?php if (isset($url_regra_venda)) echo $url_regra_venda; ?>${data.id}' data-toggle='tooltip' data-title='Configurar Vendas Diferenciadas' class='btn btn-light'><i class='fas fa-hand-holding-usd'></i></button>`);

                    button.click(function (e) {
                        e.preventDefault();
                        $.ajax({
                            type: 'get',
                            url: button.data('href'),
                            dataType: 'html',

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

                    $('td:eq(6)', row).html(button);

                },
                drawCallback: function() {
                }
            });
        });
    </script>
</body>

</html>
