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
                    <table id="data-table" class="table table-condensend table-hover" data-details="<?php echo $url_details; ?>">
                        <thead>
                        <tr>
                            <th>Cotação</th>
                            <th>Registrado em</th>
                            <th hidden></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php foreach($cotacoes as $cotacao) { ?>

                                <tr>
                                    <td><?php echo $cotacao['cd_cotacao'] ?></td>
                                    <td><?php echo $cotacao['data'] ?></td>
                                    <td hidden><?php echo $cotacao['data_criacao'] ?></td>
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

    var url_details = $('#data-table').data('details');

    $(function () {
        var table = $('#data-table').DataTable({
            processing: true,
            serverSide: false,
            pageLength: 20,
            "order": [[ 2, "desc" ]],
            rowCallback: function (row, data) {
                $(row).data('cd_cotacao', data.cd_cotacao).css('cursor', 'pointer');

                $('td', row).each(function () {
                    $(this).on('click', function () {
                        window.location.href =  url_details + '/' + data[0];
                    });
                });
            },
            drawCallback: function () {}
        });
    });
</script>
</body>

