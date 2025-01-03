<?php
$integracao = $this->session->userdata("integracao");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<?php if (isset($header)) echo $header; ?>
<body class="bg-light">
<?php if (isset($navbar)) echo $navbar; ?>
<?php if (isset($sidebar)) echo $sidebar; ?>
<div class="container py-3 mb-3">
    <h3 class="titulo titulo-primary titulo-lg mb-3">Pedidos Realizados</h3>
    <p class="small">Aqui são listados os pedidos em análise e aguardando pagamento.</p>
    <p class="small">Utilize os campos abaixo para filtrar os registros.</p>
    <div class="card">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-12 col-lg-6">
                    <label for="">Data do Pedido</label>
                    <input type="date" data-index="1" id="p_data" class="form-control text-center">
                </div>
                <div class="col-12 col-lg-6">
                    <label for="">Situação do Pedido</label>
                    <select name="" id="" data-index="5" class="form-control">
                        <option value="">Em análise</option>
                        <option value="">Aguardando Pagamento</option>
                    </select>
                </div>
            </div>
            <div id="detalhe">
                <table id="tabela"
                       class="table table-striped"
                       data-src="<?php echo $url_datatable; ?>"
                       data-upd="<?php echo $url_update; ?>">
                    <thead>
                    <tr>
                        <th class="text-center">Código</th>
                        <th class="text-center">Data</th>
                        <th>Total de Itens</th>
                        <th>Valor Total</th>
                        <th>Situação</th>
                        <th></th>
                    </tr>
                    </thead>
                </table>

            </div>
        </div>
    </div>
</div>
<?php if (isset($scripts)) echo $scripts; ?>
<script>
    let dt,
        url_upd = $('#tabela').data('upd');

    $(document).ready(function () {
        dt = $('#tabela').DataTable({
            "bFilter": false,
            "bInfo": false,
            "pageLength": 7,
            "bLengthChange": false,
            "bSort": true,
            "paging": false,
            "language": {
                "sEmptyTable": "Nenhum registro encontrado",
                "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                "sInfoPostFix": "",
                "sLengthMenu": "",
                "sInfoThousands": ".",
                "sLoadingRecords": "Carregando...",
                "sProcessing": "Processando...",
                "sZeroRecords": "Nenhum registro encontrado",
                "sSearch": "Pesquisar",
                "oPaginate": {
                    "sNext": ">",
                    "sPrevious": "<",
                    "sFirst": "<<",
                    "sLast": ">>"
                }
            },
            ajax: {
                url: $('#tabela').data('src'),
                type: 'post',
                dataType: 'json',
                data: function (d) {
                    let _d = d,
                        dt_venda = $('#p_data');

                    if (dt_venda.val() !== '' && typeof dt_venda.val() !== 'undefined') {
                        let dt = dt_venda.val().split('/');
                        _d.columns[1].search.value = dt[2] + '-' + dt[1] + '-' + dt[0];
                    }

                    return _d;
                }
            },
            columns: [
                {data: 'id', name: 'id', className: 'text-center'},
                {data: 'data_criacao', name: 'data_criacao', className: 'text-left'},
                {data: 'total_itens', name: 'total_itens', className: 'text-center'},
                {data: 'total', name: 'total', className: 'text-right'},
                {data: 'status', name: 'status', className: 'text-right'},
                {defaultContent: '', width: '100px', orderable: false, searchable: false}

            ],
            drawCallback: function () {
                // set filter
                $('[data-index]').on('change', function () {
                    var col = $(this).data('index');
                    dt.columns(col).search($(this).val()).draw();
                });

                // remove filter
                $('[data-action="reset-filter"]').click(function (e) {
                    e.preventDefault();
                    $('[data-index]').val(null);
                    dt.columns([0, 1, 2, 4]).search('').draw();
                });
            },
            rowCallback: function (row, data) {

                $('td:eq(5)', row).html("<a href='<?php if (isset($url_detalhes)) echo $url_detalhes; ?>" + data.id + "' data-toggle='tooltip'  data-original-title='Ver detalhes do pedido' title='Ver detalhes do pedido' class='btn btn-sm btn-outline-primary'>Detalhes</a>");
            }
        });


    });

</script>
</body>
</html>

