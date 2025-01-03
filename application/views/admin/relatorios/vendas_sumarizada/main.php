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
                <form action="" id="frm_search" method="get">
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label>Grupo</label>
                                <select class="select2" name="matriz" id="matriz">
                                    <option value="">Selecione</option>
                                    <?php foreach ($matrizes as $k => $v) : ?>
                                        <option value="<?php echo $v['id']; ?>"><?php echo $v['nome']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label>Fornecedor</label>
                                <select class="select2" name="id_fornecedor" id="fornecedor">
                                    <option value="">Selecione</option>
                                    <?php foreach ($fornecedores as $k => $v) : ?>
                                        <option value="<?php echo $v['id']; ?>"><?php echo $v['nome_fantasia']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>


        <?php foreach ($vendas as $k => $ano) { ?>
            <div class="row">
                <div class="col-12"><h3 class="text-center"><?php echo $k; ?></h3>
                    <hr>
                </div>
            </div>
            <div class="row">
                <?php foreach ($ano as $j => $forn) { ?>
                    <div class="col-4">
                        <div class="card">
                            <div class="card-header">
                                <p class="card-title"><?php echo $j; ?></p>
                            </div>
                            <div class="card-body">
                                <table class="table table-condensed table-striped table-sm">
                                    <tr>
                                        <td>FORNECEDOR</td>
                                        <td>QTD</td>
                                        <td>TOTAL</td>
                                    </tr>
                                    <?php $total = 0; $qtd_total = 0; ?>
                                    <?php foreach ($forn as $item) { ?>
                                        <tr>
                                            <td><?php echo $item['nome_fantasia']; ?></td>
                                            <td><?php echo $item['qtd']; ?></td>
                                            <td>R$ <?php echo number_format($item['total'], 2, ',', '.'); ?></td>
                                        </tr>
                                        <?php $total = $total + $item['total'] ?>
                                        <?php $qtd_total = $qtd_total + $item['qtd'] ?>
                                    <?php } ?>

                                    <tr class="table-success">
                                        <td>Total</td>
                                        <td><?php echo $qtd_total; ?></td>
                                        <td>R$ <?php echo number_format($total, 2, ',', '.'); ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>

    </div>
</div>
</body>
<?php echo $scripts; ?>
<script>
    $(function () {

        $('#matriz, #fornecedor').on('change', function () {

            $('#frm_search').submit();
        });
    });

</script>

</html>