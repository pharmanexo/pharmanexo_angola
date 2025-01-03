<?php
$integracao = $this->session->userdata("integracao");
?>
<div class="container py-3 mb-3">
    <h3 class="titulo titulo-primary titulo-lg mb-3">Produtos Sugeridos</h3>
    <p class="small">O seu pedido não atingiu o valor mínimo para um determinado fornecedor, listamos alguns produtos que passam interessar. </p>
</div>

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
                {data: 'razao_social', name: 'razao_social', className: 'text-left'},
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

                $('td:eq(6)', row).html("<a href='<?php if (isset($url_detalhes)) echo $url_detalhes; ?>" + data.id + "' data-toggle='tooltip'  data-original-title='Ver detalhes do pedido' title='Ver detalhes do pedido' class='btn btn-sm btn-outline-primary'>Detalhes</a>");
            }
        });


    });

</script>