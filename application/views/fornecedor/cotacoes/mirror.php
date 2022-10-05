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
                <h6 class="card-title"></h6>
            </div>
            <div class="card-body">

                <div class="alert alert-secondary" role="alert" id="avisoEnvio">
                    Essa cotação ainda não foi transmitida. Clique no botão 
                    <a href="" title="" data-toggle="tooltip" class="btn btn-sm btn-primary btnEnvioSintese float-right mb-3">
                        <i class="fas fa-paper-plane"></i> Transmitir agora
                    </a>
                </div>


                <?php echo $mirror ?>
            </div>
        </div>
    </div>
</div>

<?php echo $scripts; ?>

<script>

    var url = "<?php echo $form_action; ?>";

    $(function () {

        $(".btnEnvioSintese").on('click', function(e) {

            e.preventDefault();

            $.ajax({
                url : url,
                type : 'post',
                beforeSend: function() {
                    $(".btnEnvioSintese").html("<i class='fa fa-spin fa-spinner'></i> Transmitindo...");
                    $(".btnEnvioSintese").css('pointer-events', 'none');
                }
            })
            .done(function(msg){ 

                if (msg['type'] == 'success') {
                    $(".btnEnvioSintese").html("<i class='fa fa-paper-plane'></i> Transmitido");

                    $("#avisoEnvio").remove();

                } else {

                    $(".btnEnvioSintese").css('pointer-events', '');
                    $(".btnEnvioSintese").html("<i class='fa fa-paper-plane'></i> Transmitir");
                }

                formWarning(msg);
            })
            .fail(function(jqXHR, textStatus, msg) {

                $(".btnEnvioSintese").css('pointer-events', '');
                $(".btnEnvioSintese").html("<i class='fa fa-paper-plane'></i> Transmitir");
            });
        });
    });

</script>
</body>

</html>