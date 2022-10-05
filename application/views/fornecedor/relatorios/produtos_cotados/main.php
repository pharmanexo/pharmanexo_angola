<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner" id="printAll">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive col-sm">
                    <table id="data-table" class="table table-condensend table-hover" data-url="<?php echo $to_datatable; ?>">
                        <thead>
                        <tr>
                            <th>Código</th>
                            <th>Produto</th>
                            <th>Total</th>
                            <th>Preço Total</th>
                            <th class="text-nowrap">Qtd Solicitada Total</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php foreach($mais_cotados as $produto) { ?>

                                <tr>
                                    <td><?php echo $produto['id_pfv'] ?></td>
                                    <td class="text-nowrap"><?php echo $produto['produto'] ?></td>
                                    <td><?php echo $produto['total'] ?></td>
                                    <td><?php echo $produto['preco_total'] ?></td>
                                    <td><?php echo $produto['qtd_total'] ?></td>
                                </tr>

                           <?php } ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $scripts; ?>

<script>

    $(function () {
        var table = $('#data-table').DataTable({
            processing: true,
            serverSide: false,
            lengthChange: false,
            "order": [[ 2, "desc" ]],
            // ajax: {
            //     url: $('#data-table').data('url'),
            //     type: 'post',
            //     dataType: 'json',
            //     data: function (data) { return { 'start': 0, 'length': 20}; }
            // },
            
            // columns: [
            //     { name: 'id_pfv', data: 'id_pfv', width: '120px'},
            //     { name: 'produto', data: 'produto'},
            //     { name: 'total', data: 'total', width: '120px' },
            //     { name: 'preco_total', data: 'preco_total' },
            //     { name: 'qtd_total', data: 'qtd_total', className: 'text-center' },
            // ],
            rowCallback: function (row, data) {},
            drawCallback: function () {}
        });
    });
</script>
</body>

