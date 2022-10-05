<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <div class="row quick-stats">
        <div class="col-sm-6 col-md-4">
            <div class="quick-stats__item bg-blue">
                <div class="quick-stats__info position-absolute">
                    <h2>R$ <?php if (isset($total_cotacoes_aberto)) echo $total_pedidos_aberto; ?></h2>
                    <small>Valor total pedidos em aberto no marketplace</small>
                </div>

                <div class="text-center text-white quick-stats__chart pr-3">
                    <i class="fas fa-chart-line fa-3x"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-4">
            <div class="quick-stats__item bg-green">
                <div class="quick-stats__info position-absolute">
                    <h2>R$ <?php if (isset($total_ordem_compras)) echo $total_ordem_compras; ?></h2>
                    <small>Ordens de compra Síntese</small>
                </div>

                <div class="text-center text-white quick-stats__chart pr-3">
                    <i class="fas fa-dollar-sign fa-3x"></i>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-md-4">
            <div class="quick-stats__item bg-warning">
                <div class="quick-stats__info position-absolute">
                    <h2>R$ <?php echo (isset($total_cotacoes_aberto)) ? $total_cotacoes_aberto : '0'; ?></h2>
                    <small>Valor das cotações respondidas</small>
                </div>

                <div class="text-center text-white quick-stats__chart pr-3">
                    <i class="fas fa-boxes fa-3x"></i>
                </div>
            </div>
        </div>
    </div>

    <?php echo $scripts; ?>

    <script>
        $(function () {

        });
    </script>
</body>

</html>