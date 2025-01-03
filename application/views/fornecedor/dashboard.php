<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
    <?php echo $navbar; ?>
    <?php echo $sidebar; ?>

    <div class="content">

        <div class="row">
            <div class="col-12 bg-info">
                <h3 class="text-white">Informativo</h3>
                <p class="text-white">Os servidores da sintese se encontram com instabilidade e a equipe tácnica já está atuado para sanar o problema, ainda não foi passado uma previsão.</p>
                <br>
                <p>estamos a disposição!</p>
                <br>
            </div>
        </div>

        <div class="row quick-stats">

            <div class="col-sm-6 col-md-3">
                <div class="quick-stats__item bg-blue">
                    <div class="quick-stats__info position-absolute">
                        <h2>R$ <?php echo $total_vendas; ?></h2>
                        <small>Valor total em estoque</small>
                    </div>

                    <div class="text-center text-white quick-stats__chart pr-3">
                        <i class="fas fa-chart-line fa-3x"></i>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="quick-stats__item bg-green">
                    <div class="quick-stats__info position-absolute">
                        <h2>R$ <?php echo $total_vendas; ?></h2>
                        <small>Ordens de compra (total)</small>
                    </div>

                    <div class="text-center text-white quick-stats__chart pr-3">
                        <i class="fas fa-dollar-sign fa-3x"></i>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="quick-stats__item bg-warning">
                    <div class="quick-stats__info position-absolute">
                        <h2>R$ <?php echo $total_vendas; ?></h2>
                        <small>Produtos a vencer no mês</small>
                    </div>

                    <div class="text-center text-white quick-stats__chart pr-3">
                        <i class="fas fa-boxes fa-3x"></i>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="quick-stats__item bg-pink">
                    <div class="quick-stats__info position-absolute">
                        <h2>R$ <?php echo $total_vendas; ?></h2>
                        <small>Pedido aguardando aprovação</small>
                    </div>

                    <div class="text-center text-white quick-stats__chart pr-3">
                        <i class="fas fa-money-bill fa-3x"></i>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <?php echo $scripts; ?>

    <script>
        $(function() {
            //
        });
    </script>
</body>

</html>