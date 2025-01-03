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
                    <div class="row mx-auto mt-3">
                        <div id="opc_estado" class="col-12">
                            <div class="table-responsive col-sm">
                                <table id="data-table" class="table table-condensend table-hover" data-url="<?php echo $datatable_src; ?>" data-update="<?php echo $url_update ?>">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Emissão</th>
                                            <th>Ordem Compra</th>
                                            <th>CNPJ</th>
                                            <th>Razão Social</th>
                                            <th>Valor Total</th>
                                            <th>Status</th>
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
    </div>
</body>

<?php echo $scripts; ?>

<script>
    var url_update = $('#data-table').data('update');

    $(function() {
        $('#data-table').DataTable({
            serverSide: false,
            lengthChange: false,

            ajax: {
                url: $('#data-table').data('url'),
                type: 'get',
                dataType: 'json'
            },
            columns: [{
                    name: 'ordens_compra.id',
                    data: 'id',
                    visible: false
                },
                {
                    name: 'ordens_compra.data_emissao',
                    data: 'data_emissao'
                },
                {
                    name: 'ordens_compra.ordem_compra',
                    data: 'ordem_compra'
                },
                {
                    name: 'dados_usuarios.cnpj',
                    data: 'cnpj'
                },
                {
                    name: 'dados_usuarios.razao_social',
                    data: 'razao_social'
                },
                {
                    name: 'ordens_compra.valor_total',
                    data: 'valor_total'
                },
                {
                    name: 'status_ocs.descricao',
                    data: 'status_ordem_compra'
                },
                {
                    defaultContent: '',
                    width: '100px',
                    orderable: false,
                    searchable: false
                }
            ],

            rowCallback: function(row, data) {
                $(row).data('id', data.id).css('cursor', 'pointer');
                var btnModal = $(`<a href="${url_update}/${data.id}" class="text-primary openModal"><i class="fas fa-edit"></i></a>`);

                btnModal.on('click', function(e) {
                    e.preventDefault();

                    let me = $(this);

                    $.ajax({
                        url: me.attr('href'),
                        type: 'get',
                        dataType: 'html',

                        success: function(xhr) {
                            $('body').append(xhr);
                            $('.modal').modal({
                                keyboard: false
                            }, 'show').on('hide.bs.modal', function() {
                                $('.modal').remove();
                            });
                        }
                    });
                });

                $('td:eq(6)', row).append(btnModal);
            },

            drawCallback: function() {}
        });
    });
</script>

</html>