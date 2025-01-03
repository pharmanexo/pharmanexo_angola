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
                <div class="row">
                    <div class="col-md-6 col-xs-12 form-group">
                        <label for="cd_cotacao">Filtrar por Comprador</label>
                        <select class="select2" id="compradores" data-index="7">
                            <option value="">Selecione</option>
                             <?php foreach($compradores as $c) { ?>
                                <option value="<?php echo $c['id']; ?>"><?php echo $c['comprador']; ?></option>
                            <?php } ?>
                        </select>
                    </div>   
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="data-table" class="table table-condensed table-hover no-filtered" data-update="<?php echo $url_detalhes; ?>">
                        <thead>
                            <tr>
                                <th hidden></th>
                                <th>Cotação</th>
                                <th>Comprador</th>
                                <th>Data de Acionamento</th>
                                <th class="text-center">Total de itens</th>
                                <th class="text-center">Total Respondido</th>
                                <th hidden></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($cotacoes as $cotacao): ?>
                                <tr>
                                    <td hidden> <?php echo $cotacao['id_cotacao'] ?></td>
                                    <td><?php echo $cotacao['cd_cotacao']; ?></td>
                                    <td><?php echo $cotacao['comprador']; ?></td>
                                    <td><?php echo $cotacao['data']; ?></td>
                                    <td class="text-center"><?php echo $cotacao['total']; ?></td>
                                    <td class="text-center"><?php echo $cotacao['total_enviado']; ?></td>
                                    <td hidden><?php echo $cotacao['data_criacao']; ?></td>
                                    <td hidden><?php echo $cotacao['id_cliente']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<?php echo $scripts; ?>
<script>

    var url_update = $('#data-table').data('update');

    $(function () {
        
        var table = $('#data-table').DataTable({
            serverSide: false,
            lengthChange: false,
            responsive: true,
            columns: [
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null
            ],
            order: [[ 6, "desc" ]],
            rowCallback: function (row, data) {
                $(row).css('cursor', 'pointer');

                var id_cotacao = data[0];

                if (id_cotacao != undefined) {

                    $('td:not(:first-child):not(:last-child)', row).each(function () {
                        $(this).on('click', function () {
                            window.location.href = `${url_update}/${id_cotacao}`
                        });
                    });
                }
            },
            drawCallback: function () {}
        });

        $('[data-index]').on('change', function() {
            var col = $(this).data('index');
            var value = $('#compradores').val();

            table.columns(col).search(value).draw();
        });
    });
</script>

</html>