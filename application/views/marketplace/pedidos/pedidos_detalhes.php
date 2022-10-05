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
    <h3 class="titulo titulo-primary titulo-lg mb-3">Detalhes do Pedido</h3>
    <div class="row mb-3">
        <div class="col-12 col-lg-4">
            <label for="">Descrição do Produto</label>
            <input type="text" data-index="0" id="p_produto" class="form-control text-center">
        </div>
        <div class="col-12 col-lg-4">
            <label for="">Marca do Produto</label>
            <input type="text" data-index="1" id="p_marca" class="form-control text-center">
        </div>
        <div class="col-12 col-lg-4">
            <label for="">Situação do Pedido</label>
            <select name="" id="" data-index="5" class="form-control">
                <option value="Em Analise">Em análise</option>
                <option value="Recusado">Recusados</option>
            </select>
        </div>
    </div>
    <table class="table table-striped">
        <tr>
            <th>Pedido</th>
            <th>Data do Pedido</th>
            <th>Status do Pedido</th>
        </tr>
        <tr>
            <td><?php if (isset($pedido['id'])) echo $pedido['id']; ?></td>
            <td><?php if (isset($pedido['data_criacao'])) echo date("d/m/Y H:i:s", strtotime($pedido['data_criacao'])); ?></td>
            <td><?php if (isset($pedido['status'])) echo $pedido['status']; ?></td>
        </tr>
    </table>
    <div id="detalhe">
        <table id="tabela"
               class="table table-striped "
               data-src="<?php echo $url_datatable; ?>">
            <thead>
            <tr>
                <th class="text-center">Descrição</th>
                <th>Marca</th>
                <th>Quantidade</th>
                <th>Valor Unit.</th>
                <th>Total</th>
                <th>Status</th>
            </tr>
            </thead>
        </table>

    </div>
</div>
<?php if (isset($scripts)) echo $scripts; ?>
<script>
    let dt;

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
            },
            columns: [
                {data: 'produto_descricao', name: 'produto_descricao', className: 'text-left'},
                {data: 'marca', name: 'marca', className: 'text-left'},
                {data: 'quantidade', name: 'quantidade', className: 'text-center'},
                {data: 'valor', name: 'valor', className: 'text-right'},
                {data: 'total', name: 'total', className: 'text-right'},
                {data: 'status', name: 'status', className: 'text-right'},

            ],
            drawCallback: function () {
                // set filter
                $('[data-index]').on('change keyup', function () {
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

            }
        });
    });

</script>
</body>
</html>
