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
                    <table id="data-table" class="table table-condensend table-hover" data-desocultar="<?php echo $url_desocultar; ?>">
                        <thead>
                        <tr>
                            <th>Cotação</th>
                            <th hidden></th>
                            <th>Data inicio</th>
                            <th>Data Fim</th>
                            <th>Estado</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php foreach($cotacoes as $cotacao) { ?>

                                <tr>
                                    <td><?php echo $cotacao['cd_cotacao']; ?></td>
                                    <td hidden><?php echo $cotacao['dt_fim_cotacao'] ?></td>
                                    <td><?php echo date('d/m/Y H:i:s', strtotime($cotacao['dt_inicio_cotacao'])) ?></td>
                                    <td><?php echo date('d/m/Y H:i:s', strtotime($cotacao['dt_fim_cotacao'])) ?></td>
                                    <td><?php echo $cotacao['uf_cotacao'] ?></td>
                                    <td>
                                        <a  
                                            data-toggle="tooltip" 
                                            title="Desfazer ocultação" 
                                            id="<?php echo $cotacao['id']; ?>"
                                            data-cotacao="<?php echo $cotacao['cd_cotacao']; ?>" 
                                        >
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
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

    var url_desocultar = $('#data-table').data('desocultar');

    $(function () {
        var table = $('#data-table').DataTable({
            processing: true,
            serverSide: false,
            lengthChange: false,
            "order": [[ 4, "desc" ]],
           
            rowCallback: function (row, data) {},
            drawCallback: function () {}
        });


        $('[data-cotacao]').each(function (i, v) {
            var me = $(v);

            me.on('click', function () {

                $.ajax({
                    url: url_desocultar,
                    type: 'post',
                    data: { cd_cotacao: $(this).data('cotacao') },
                    success: function(xhr) {
                        
                        formWarning(xhr);
                        location.reload();
                    },
                    error: function(xhr) { console.log(xhr); }
                })
            });
        });
    });
</script>
</body>

