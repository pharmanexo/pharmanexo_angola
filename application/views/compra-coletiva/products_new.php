<?php if (isset($header)) echo $header ?>
<body>

<?php if (isset($navbar)) echo $navbar; ?>
<div class="container">
    <?php if (isset($heading)) echo $heading; ?>
    <?php if (isset($produtos)) { ?>
        <?php foreach ($produtos as $produto) { ?>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <?php echo strtoupper($produto['descricao']); ?> <br>
                            <small>Início adesão: <?php echo date("d/m/Y", strtotime($produto['inicio_adesao'])); ?> | Fim adesão: <?php echo date("d/m/Y", strtotime($produto['fim_adesao'])); ?></small>
                        </div>
                        <div class="col-3 text-right">
                            R$ <?php echo number_format($produto['valor'], 2, ',', '.'); ?>
                            <small>und.</small>
                        </div>
                        <div class="col-3 text-center">
                            <a href="<?php echo $urlDetalhes . $produto['id']; ?>"
                               class="btn btn-outline-secondary" data-toggle="tooltip"
                               data-title="Ver Detalhes"><i class="fa fa-search-plus"></i> Detalhes</a>
                        </div>
                    </div>
                </div>
            </div>

        <?php } ?>
    <?php } ?>

</div>
<?php if (isset($scripts)) echo $scripts ?>

<script>
    $(function () {
        $("[data-product]").click(function (e) {
            e.preventDefault();
            var me = $(this);

            Swal.fire({
                text: 'Informe a quantidade que deseja adquirir',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Gerar',
                showLoaderOnConfirm: true,
            }).then((result) => {
                if (result.value) {

                    if (result.value.includes(',')) {
                        Swal.fire({
                            text: 'Não utilize vírgula'
                        });
                    } else {
                        let url = me.attr('href') + '/' + result.value;
                        window.location.replace(url);
                    }


                } else {
                    Swal.fire({
                        text: 'Informe um valor.'
                    });
                }
            })


        })


        $(function () {
            $("#txtBusca").keyup(function () {
                var texto = $(this).val();

                $("#table tr td:eq(0)").each(function () {
                    if ($(this).text().indexOf(texto) < 0) {

                    }
                });
            });
        });
    })
</script>

<?php if (isset($footer)) echo $footer ?>
