<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
    <?php echo $navbar; ?>
    <?php echo $sidebar; ?>

    <div class="content">
        <?php echo $heading; ?>

        <div class="row quick-stats">
            <div class="col-sm-6 col-md-3">
                <div class="quick-stats__item bg-blue">
                    <div class="quick-stats__info">
                        <h2><?php echo $total_vendas; ?></h2>
                        <small>Valor Total em Vendas</small>
                    </div>

                    <div class=""></div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="quick-stats__item bg-amber">
                    <div class="quick-stats__info">
                        <h2>356,785K</h2>
                        <small>Total Website Impressions</small>
                    </div>

                    <div class=""></div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="quick-stats__item bg-purple">
                    <div class="quick-stats__info">
                        <h2>$58,778</h2>
                        <small>Total Online Sales</small>
                    </div>

                    <div class=""></div>
                </div>
            </div>

            <div class="col-sm-6 col-md-3">
                <div class="quick-stats__item bg-red">
                    <div class="quick-stats__info">
                        <h2>214</h2>
                        <small>Total Support Tickets</small>
                    </div>

                    <div class=""></div>
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
