<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
    <?php echo $navbar; ?>
    <?php echo $sidebar; ?>

    <div class="content">
        <div class="row quick-stats">
            <div class="col-sm-6 col-md-3">
                <div class="quick-stats__item bg-secondary">
                    <div class="quick-stats__info position-absolute">
                        <h2>R$ <?php echo isset($total_pedidos_abertos) ? number_format($total_pedidos_abertos, 2, ',', '.') : '0,00'?></h2>
                        <small>Pedidos em aberto</small>
                    </div>

                    <div class="text-center text-white quick-stats__chart pr-3">
                        <i class="fas fa-chart-line fa-3x"></i>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="quick-stats__item bg-blue">
                    <div class="quick-stats__info position-absolute">
                        <h2>R$ <?php echo isset($total_pedidos_enviados) ? number_format($total_pedidos_enviados, 2, ',', '.') : '0,00'?></h2>
                        <small>Pedidos aguardando faturamento</small>
                    </div>

                    <div class="text-center text-white quick-stats__chart pr-3">
                        <i class="fas fa-chart-line fa-3x"></i>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="quick-stats__item bg-green">
                    <div class="quick-stats__info position-absolute">
                        <h2>R$ <?php echo isset($total_pedidos_faturados) ? number_format($total_pedidos_faturados, 2, ',', '.') : '0,00'?></h2>
                        <small>Pedidos Faturados</small>
                    </div>

                    <div class="text-center text-white quick-stats__chart pr-3">
                        <i class="fas fa-chart-line fa-3x"></i>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="quick-stats__item bg-orange">
                    <div class="quick-stats__info position-absolute">
                        <h2>R$ <?php echo isset($total_pedidos_cancelados) ? number_format($total_pedidos_cancelados, 2, ',', '.') : '0,00'?></h2>
                        <small>Pedidos Cancelados</small>
                    </div>

                    <div class="text-center text-white quick-stats__chart pr-3">
                        <i class="fas fa-chart-line fa-3x"></i>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php echo $scripts; ?>

    <script>

        $(function() {


        }); 


    </script>
</body>

</html>