<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
    <?php echo $navbar; ?>
    <?php echo $sidebar; ?>

    <div class="content">
        <div class="row quick-stats">
            <div class="col-sm-6 col-md-3">
                <div class="quick-stats__item bg-secondary">
                    <div class="quick-stats__info position-absolute">
                        <h2>R$ <?php echo isset($total_pedidos_abertos) ? number_format($total_pedidos_abertos, 2, ',', '.') : '0,00'?></h2>
                        <small>Pedidos em aberto</small>
                    </div>

                    <div class="text-center text-white quick-stats__chart pr-3">
                        <i class="fas fa-chart-line fa-3x"></i>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="quick-stats__item bg-blue">
                    <div class="quick-stats__info position-absolute">
                        <h2>R$ <?php echo isset($total_pedidos_enviados) ? number_format($total_pedidos_enviados, 2, ',', '.') : '0,00'?></h2>
                        <small>Pedidos aguardando faturamento</small>
                    </div>

                    <div class="text-center text-white quick-stats__chart pr-3">
                        <i class="fas fa-chart-line fa-3x"></i>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="quick-stats__item bg-green">
                    <div class="quick-stats__info position-absolute">
                        <h2>R$ <?php echo isset($total_pedidos_faturados) ? number_format($total_pedidos_faturados, 2, ',', '.') : '0,00'?></h2>
                        <small>Pedidos Faturados</small>
                    </div>

                    <div class="text-center text-white quick-stats__chart pr-3">
                        <i class="fas fa-chart-line fa-3x"></i>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="quick-stats__item bg-orange">
                    <div class="quick-stats__info position-absolute">
                        <h2>R$ <?php echo isset($total_pedidos_cancelados) ? number_format($total_pedidos_cancelados, 2, ',', '.') : '0,00'?></h2>
                        <small>Pedidos Cancelados</small>
                    </div>

                    <div class="text-center text-white quick-stats__chart pr-3">
                        <i class="fas fa-chart-line fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Promoções</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col text-right">
                                <a href="<?php if(isset($url_promocoes)) echo "{$url_promocoes}" ?>" data-toggle="toggle" title="Exportar Excel" id="btn_exportar_promocoes" class="btn btn-primary">
                                    <i class="far fa-file-excel"></i>                
                                </a>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id="table-promocoes" class="table w-100 table-hover" data-promocoes="<?php echo $to_datatable_promocoes; ?>">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Código</th>
                                    <th>EAN</th>
                                    <th>Desconto (%)</th>
                                    <th style="width: 200px;">Produto</th>
                                    <th>Preço</th>
                                    <th>Preço Desconto</th>
                                    <th></th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo $scripts; ?>

    <script>
        var urlAdd = '<?php if (isset($url_add_prod)) echo $url_add_prod; ?>'
        var urlOpen = '<?php if (isset($url_open)) echo $url_open; ?>'
        $(function() {



            var table_promocoes = $('#table-promocoes').DataTable({
                serverSide: false,
                lengthChange: false,
                responsive: true,
                ajax: {
                    url: $('#table-promocoes').data('promocoes'),
                    type: 'post',
                    dataType: 'json',
                },
                columns: [
                    { name: 'promocoes.id', data: 'id', visible: false },
                    { name: 'promocoes.codigo', data: 'codigo' },
                    { name: 'produtos_catalogo.ean', data: 'ean', className: 'text-nowrap' },
                    { name: 'promocoes.desconto_percentual', data: 'desconto_percentual', className: 'text-nowrap' },
                    { name: 'produtos_catalogo.produto_descricao', data: 'produto_descricao', className: 'text-nowrap' },
                    { name: 'produtos_preco.preco_unitario', data: 'preco' },
                    { name: 'produtos_preco.preco_unitario', data: 'preco_desconto', className: 'text-nowrap' },
                    {defaultContent: '', width: '100px', orderable: false, searchable: false},

                ],
                order: [[ 1, 'asc' ]],
                rowCallback: function(row, data) {
                    var btnModal = $(`<a onclick="" data-toggle="tooltip" data-codigo='${data.codigo}' data-price='${data.preco_desconto}' title="ADICIONAR A UM PEDIDO" class="btn btn-sm btn-primary text-white"><i class="fas fa-cart-plus"></i></a>`);

                    btnModal.click(async function (e){
                        e.preventDefault();
                        var codigo = $(this).data('codigo');
                        var price = $(this).data('price');

                        const { value: quantidade } = await Swal.fire({
                            title: 'ADICIONAR PRODUTO AO PEDIDO',
                            input: 'text',
                            inputLabel: 'Informe a quantidade que deseja adicionar',
                            inputPlaceholder: 'Quantidade'
                        })

                        if (quantidade) {

                            var data = {
                                'codigo': codigo,
                                'quantidade': quantidade,
                                'price': price
                            };

                            $.post(urlAdd, data, function (xhr){
                                if (xhr.type == 'success'){
                                    Swal.fire({
                                        title: 'Produto Adicionado',
                                        text: "O que deseja fazer agora?",
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#bbb2af',
                                        confirmButtonText: 'Abrir o pedido',
                                        cancelButtonText: 'Adicionar outros itens'
                                    }).then((result) => {
                                        if (result) {
                                           window.location = urlOpen + xhr.id_pedido
                                        }
                                    })
                                }
                            }, 'JSON')

                        }

                    });


                    $('td:eq(6)', row).html(btnModal);
                },
                drawCallback: function() {}
            });



        }); 


    </script>
</body>

</html>