<!-- Vendor JS Files -->
<script src="<?php echo ASSETS_PATH ?>/vendor/jquery/jquery.min.js"></script>
<script src="<?php echo ASSETS_PATH ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo ASSETS_PATH ?>/vendor/jquery.easing/jquery.easing.min.js"></script>
<script src="<?php echo ASSETS_PATH ?>/vendor/php-email-form/validate.js"></script>
<script src="<?php echo ASSETS_PATH ?>/vendor/waypoints/jquery.waypoints.min.js"></script>
<script src="<?php echo ASSETS_PATH ?>/vendor/counterup/counterup.min.js"></script>
<script src="<?php echo ASSETS_PATH ?>/vendor/venobox/venobox.min.js"></script>
<script src="<?php echo ASSETS_PATH ?>/vendor/owl.carousel/owl.carousel.min.js"></script>
<script src="<?php echo ASSETS_PATH ?>/vendor/isotope-layout/isotope.pkgd.min.js"></script>
<script src="<?php echo ASSETS_PATH ?>/vendor/aos/aos.js"></script>
<script src="https://kit.fontawesome.com/e94dc38f5a.js" crossorigin="anonymous"></script>

<!-- Template Main JS File -->
<script src="<?php echo ASSETS_PATH ?>/js/main.js"></script>
<?php

if (isset($scripts))
    foreach ($scripts as $script) {
        echo "<script src='{$script}'></script>";
    }
?>

<script>
    $(function() {
        
        <?php if (isset($this->session->id_sessao)) :
            $tempo = $this->db->select("timestamp")->from('ci_sessions')->where("id= '{$this->session->id_sessao}'")->get()->row_array();
        ?>
            var verificaSessao = setInterval(tempo_sessao, 1000);
            var alertar = 'true';
            var tempoSessao = "<?php echo date('H:i:s', $tempo['timestamp'] + 300) ?>";
            var tempoAlerta = "<?php echo date('H:i:s', $tempo['timestamp'] + 150) ?>";
            var id = "<?php echo $this->session->id_sessao ?>";
            var idUser = "<?php echo $this->session->id_usuario ?>";
            var tempo = "<?php echo $tempo['timestamp'] ?>";


            function tempo_sessao() {
                var timeOut = new Date();
                var hora = timeOut.toLocaleTimeString();
                $("#tempo_sessao").html(hora);
                if (tempoAlerta <= hora && alertar == 'true') {
                    $('#modalAlerta').modal({
                        backdrop: false
                    })
                    $("#myModal").modal({
                        backdrop: true
                    })
                    alertar = 'false'
                }
                if (tempoSessao <= hora) {
                    timeoutSessao()
                    clearInterval(verificaSessao)
                }
            };

            function timeoutSessao() {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>/login/timeout_sessao",
                    data: {
                        id_usuario: idUser,
                    },
                    success: function(response) {
                        console.log(response)
                        if (response.type === 'error') {
                            formWarning(response);
                            localStorage.setItem("alertaSessao", true)
                            window.location.replace('<?php echo base_url('login'); ?>');
                        } else {
                            formWarning(response)
                        }
                    },
                });
            }
            if (localStorage.getItem("alertaSessao")) {
                $("#alertaSessao").attr("hidden", false);
                localStorage.clear();
            }

            $('. renovarSessao').click(function(e) {
                $.ajax({
                    type: "POST",
                    url: "<?php echo base_url(); ?>/login/renovar_sessao",
                    data: {
                        id: id,
                        timestamp: tempo
                    },
                    success: function(response) {
                        console.log(response)
                        if (response.type === 'success') {
                            formWarning(response);
                            window.location.reload(true);
                        } else {
                            formWarning(response)
                        }
                    }
                });

            });

        <?php endif; ?>

    })
</script>