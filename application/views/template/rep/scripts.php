<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<script src="<?php echo THIRD_PARTY . 'plugins/bootstrap-4.2.1/js/bootstrap.bundle.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'plugins/toastr/toastr.min.js' ?>"></script>

<script src="<?php echo THIRD_PARTY . 'theme/plugins/popper.js/popper.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'theme/plugins/jquery-scrollbar/jquery.scrollbar.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'theme/plugins/jquery-scrollLock/jquery-scrollLock.min.js' ?>"></script>

<script src="<?php echo THIRD_PARTY . 'theme/plugins/datatables/jquery.dataTables.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'theme/plugins/datatables-buttons/dataTables.buttons.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'theme/plugins/datatables-buttons/buttons.print.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'theme/plugins/jszip/jszip.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'theme/plugins/datatables-buttons/buttons.html5.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'plugins/dataTables.select.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'plugins/jquery.inputmask.bundle.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'plugins/jquery.maskMoney.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'plugins/sweetalert/sweetalert.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'plugins/select2-4.0.7/dist/js/select2.full.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'plugins/bootstrap-datepicker-1.9.0/js/bootstrap-datepicker.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'plugins/bootstrap-datepicker-1.9.0/locales/bootstrap-datepicker.pt-BR.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'plugins/isotope.pkgd.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'theme/js/app.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'theme/js/scripts.js' ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
<?php if (isset($_SESSION['id_usuario'])) { ?>
    <script src="<?php echo THIRD_PARTY . 'theme/js/notifications.js' ?>"></script>
<?php } ?>
<script src="https://kit.fontawesome.com/e94dc38f5a.js" crossorigin="anonymous"></script>
<?php
/**
 * Created by PhpStorm.
 * User: dutra
 * Date: 10/06/2019
 * Time: 23:22
 */
if (isset($scripts))
    foreach ($scripts as $script) {
        echo "<script src='{$script}'></script>";
    }
?>

<?php if (isset($warning)) { ?>
    <script>
        formWarning(JSON.parse('<?php echo $warning; ?>'));
    </script>
<?php } ?>

<script src="<?php echo THIRD_PARTY . 'theme/plugins/pace-1.0.2/pace.min.js' ?>"></script>

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

            $('#renovarSessao').click(function(e) {
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

    });
    

    $(document).ajaxStart(function() {
        Pace.restart();
    });
</script>