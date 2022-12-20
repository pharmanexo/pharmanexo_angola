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
                    <?php if (isset($pedidoAberto)){ ?>
                        <p class="alert alert-info">Existem pedidos abertos, não deixe de verificar.</p>
                    <?php } ?>

                        <table id="data-table" class="table w-100 table-hover" data-url="<?php echo $to_datatable; ?>">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Código</th>
                                <th style="width: 500px;">Produto</th>
                                <th>Unidade</th>
                                <th>Marca</th>
                                <th>Estoque</th>
                                <th>Lote</th>
                                <th>Validade</th>
                                <th>Preço</th>
                                <th>Fornecedor</th>
                                <th>Data Cadastro</th>
                                <th>Situação</th>
                                <th></th>
                            </tr>
                            </thead>
                        </table>

                </div>
            </div>
        </div>
    </div>
</body>

<?php echo $scripts; ?>

<script>

    $(function() {


        var dt1 = $('#data-table').DataTable({
            serverSide: false,
            pageLength: 100,
            lengthChange: false,
            dom: 'Bfrtip',
            ajax: {
                url: $('#data-table').data('url'),
                type: 'post',
                dataType: 'json',
            },
            columns: [
                { name: 'pc.id', data: 'id', visible: false },
                { name: 'pc.codigo', data: 'codigo', visible: false },
                { name: 'pc.descricao', data: 'descricao' },
                { name: 'pc.unidade', data: 'unidade' },
                { name: 'pc.marca', data: 'marca' },
                { name: 'pc.quantidade', data: 'quantidade' },
                { name: 'pc.lote', data: 'lote', visible: false  },
                { name: 'pc.validade', data: 'validade' },
                { name: 'pc.preco', data: 'preco' },
                { name: 'f.fornecedor', data: 'fornecedor',  visible: false },
                { name: 'pc.data_cadastro', data: 'data_cadastro', visible: false  },
                { name: 'pc.situacao', data: 'situacao', visible: false  },
                { name: 'f.id', data: 'id_fornecedor', visible: false  },
                {defaultContent: '', width: '100px', orderable: false, searchable: false},
            ],
            order: [[ 2, 'asc' ]],
            rowCallback: function(row, data) {
                var btnModal = $(`<a data-toggle="tooltip" title="Incluir no Pedido" data-idprod="${data.id}" class="btn btn-sm btn-info text-white"><i class="fas fa-cart-plus"></i></a>`);
                $('td:eq(5)', row).html(btnModal);


                btnModal.click(function (e){
                    e.preventDefault();
                    var prod = $(this).data('idprod');
                    var urlAddItem = '<?php echo base_url('convidados/pedidos/addItem/'); ?>';

                    Swal.fire({
                        title: 'Informe a quantidade desejada',
                        input: 'number',
                        inputAttributes: {
                            autocapitalize: 'off'
                        },
                        showCancelButton: true,
                        confirmButtonText: 'Enviar',
                        showLoaderOnConfirm: true,
                        allowOutsideClick: () => !Swal.isLoading()
                    }).then((result) => {
                       if (result.value > 0){
                           $.get(`${urlAddItem}${prod}/${result.value}`, function (xhr){
                               Swal.fire({
                                   position: 'center',
                                   icon: xhr.type,
                                   title: xhr.message,
                                   showConfirmButton: false,
                                   timer: 3000
                               })
                           })
                       }
                    })

                });


            },
            drawCallback: function() {

                $('[data-toggle="tooltip"]').tooltip();

            }
        });
    });
</script>
</html>