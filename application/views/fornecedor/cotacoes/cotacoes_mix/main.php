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
                        <label for="comprador">Comprador</label>
                        <select class="select2" id="comprador" data-index="6">
                            <option value="">Selecione</option>
                            <?php foreach($compradores as $comprador) { ?>
                             <option value="<?php echo $comprador['id'] ?>"><?php echo "{$comprador['razao_social']} - {$comprador['cnpj']}" ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-md-6 col-xs-12 form-group">
                        <label for="cd_cotacao">Cotação</label>
                        <select class="select2" id="cd_cotacao" data-index="1">
                            <option value="">Selecione</option>
                             <?php foreach($select_cotacoes as $cot) { ?>
                             <option value="<?php echo $cot['cd_cotacao'] ?>"><?php echo $cot['cd_cotacao'] ?></option>
                            <?php } ?>
                        </select>
                    </div>    
                </div>
               <!--  <div class="row">
                     <div class="col-md-12 col-xs-12 form-group">
                        <label for="filtro-data-emissao">Data da Cotação</label>
                        <div class="input-group date">
                            <input type="text" class="form-control" id="filter-start-date">
                            <div class="input-group-append">
                                <span class="input-group-text bg-light">a</span>
                            </div>
                            <input type="text" class="form-control" id="filter-end-date">
                        </div>
                    </div>
                </div> -->
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
                            <th hidden></th>
                            <th hidden></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php if( isset($cotacoes) && !empty($cotacoes) ): ?>

                                <?php foreach($cotacoes as $cotacao): ?>
                                    <tr>
                                        <td hidden><?php echo $cotacao['id_cotacao']; ?></td>
                                        <td><?php echo $cotacao['cd_cotacao']; ?></td>
                                        <td><?php echo $cotacao['comprador']; ?></td>
                                        <td><?php echo date("d/m/Y H:i:s", strtotime($cotacao['data_criacao'])); ?></td>
                                        <td class="text-center"><?php echo $cotacao['total']; ?></td>
                                        <td hidden><?php echo $cotacao['data_criacao']; ?></td>
                                        <td hidden><?php echo $cotacao['id_cliente']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>

                                <tr><td colspan="6">Nenhum registro encontrado</td></tr>
                            <?php endif; ?>
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
        $('#filter-start-date, #filter-end-date').datepicker({
            format: "dd/mm/yyyy",
            language: "pt-BR",
            orientation: "bottom auto",
            autoclose: true
        });

        var dt = $('#data-table').DataTable({
            processing: true,
            serverSide: false,
            columns: [
                null,
                null,
                null,
                null,
                null,
                null,
                null
            ],
            rowCallback: function (row, data) {
                $(row).css('cursor', 'pointer');

                var id_cotacao = data[0];

                $('td:not(:first-child):not(:last-child)', row).each(function () {
                    $(this).on('click', function () {
                        window.location.href = `${url_update}/${id_cotacao}`
                    });
                });
            },
            drawCallback: function () {
                // $(".dataTables_filter").hide(); 
            }
        });

        $('[data-index]').on('change', function() {
            var col = $(this).data('index');
            var value = $(this).val();

            dt.columns(col).search(value).draw();
        });
    });
</script>

</html>