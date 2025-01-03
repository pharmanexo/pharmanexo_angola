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
                <form id="frm_se" action="<?php echo $form_action; ?>" method="post">
                    <div class="row">

                        <div class="col-6 col-xs-6 form-group">
                            <label for="filtro-data-emissao">Data</label>
                            <div class="input-group date">
                                <input type="date" class="form-control"
                                       value="<?php if (isset($post['dataini'])) echo $post['dataini']; ?>"
                                       name="dataini" id="filter-start-date"
                                       data-index="2">

                                <div class="input-group-append">
                                    <span class="input-group-text bg-light">a</span>
                                </div>

                                <input type="date" class="form-control"
                                       value="<?php if (isset($post['datafim'])) echo $post['datafim']; ?>"
                                       name="datafim" id="filter-end-date"
                                       data-index="2">

                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="submit" form="frm_se">Filtrar
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php if (isset($btnExport)) { ?>
                            <div class="col-6 col-xs-6 form-groupv text-right">
                                <?php echo $btnExport; ?>
                            </div>
                        <?php } ?>
                    </div>
                </form>
            </div>

            <div class="card-body">
                <div class="row mx-auto mt-3">
                    <div class="col-12 col-sm">
                        <table id="dataTablesCotacoes" class="table table-condensend table-hover w-100">
                            <thead>
                            <tr>
                                <th>MES/ANO</th>
                                <th class="text-center">COTAÇÕES RESPONDIDAS</th>
                                <th class="text-right">TOTAL COTADO</th>
                                <th class="text-right">TOTAL CONVERTIDO</th>
                            </tr>
                            </thead>
                            <?php foreach ($dados as $k => $dado) { ?>
                                <tr>
                                    <td>
                                        <div id="<?php echo 'dpd_' . $k; ?>" class="btn-group dropup">
                                            <a type="button" class="dropdown-toggle" data-toggle="dropdown"
                                               aria-haspopup="true" aria-expanded="false">
                                                <?php echo $dado['MES_ANO']; ?>
                                            </a>
                                            <div class="dropdown-menu">
                                                <table>
                                                    <tr>
                                                        <th>INTEGRADOR</th>
                                                        <th>COTAÇÕES</th>
                                                        <th>TOTAL</th>
                                                    </tr>
                                                    <?php foreach ($dado['DETALHADO'] as $detalhe) { ?>

                                                        <tr>
                                                            <td>
                                                                <?php echo $detalhe['integrador']; ?>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="text-center"><?php echo $detalhe['COTACOES']; ?></span>
                                                            </td>
                                                            <td class="text-right">
                                                                <?php echo $detalhe['TOTAL_COTADO']; ?>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </table>

                                            </div>
                                        </div>

                                    </td>
                                    <td class="text-center"><?php echo $dado['COTACOES']; ?></td>
                                    <td class="text-right">R$ <?php echo $dado['TOTAL_COTADO']; ?></td>
                                    <td class="text-right">R$ <?php echo $dado['TOTAL_CONVERTIDO']; ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>


<?php echo $scripts; ?>

<script>
    $(function () {

    });
</script>

</html>
