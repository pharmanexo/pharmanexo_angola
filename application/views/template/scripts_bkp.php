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
<script src="<?php echo THIRD_PARTY . 'plugins/Material-Design-Calculator-jQuery/js/calculate.js' ?>"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src='https://cdn.jsdelivr.net/npm/apexcharts'></script>

<script src="<?php echo THIRD_PARTY . 'plugins/jquery.vmap/jquery.vmap.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'plugins/jquery.vmap/maps/jquery.vmap.brazil.js' ?>"></script>

<!-- Scripts bootstrap-select -->
<script src="<?php echo THIRD_PARTY . 'plugins/bootstrap-select-1.13.14/dist/js/bootstrap-select.min.js' ?>"></script>
<script src="<?php echo THIRD_PARTY . 'plugins/bootstrap-select-1.13.14/dist/js/i18n/defaults-pt_BR.js' ?>"></script>

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
<script>
    window.fwSettings = {
        'widget_id': 73000002652
    };
    ! function() {
        if ("function" != typeof window.FreshworksWidget) {
            var n = function() {
                n.q.push(arguments)
            };
            n.q = [], window.FreshworksWidget = n
        }
    }()
</script>
<script type='text/javascript' src='https://widget.freshworks.com/widgets/73000002652.js' async defer></script>
<script src="<?php echo THIRD_PARTY . 'theme/plugins/pace-1.0.2/pace.min.js' ?>"></script>
<script>
    $(function() {

        <?php if (isset($this->session->id_sessao)) :
            $tempo = $this->db->select("timestamp")->from('ci_sessions')->where("id= '{$this->session->id_sessao}'")->get()->row_array();
        ?>
            /*var verificaSessao = setInterval(tempo_sessao, 1000);
            var alertar = 'true';
            var tempoSessao = "<?php echo date('H:i:s', $tempo['timestamp'] + 3600) ?>";
            var tempoAlerta = "<?php echo date('H:i:s', $tempo['timestamp'] + 3000) ?>";
            var id = "<?php echo $this->session->id_sessao ?>";
            var idUser = "<?php echo $this->session->id_usuario ?>";
            var tempo = "<?php echo $tempo['timestamp'] ?>";

            function tempo_sessao() {
                var timeOut = new Date();
                var hora = timeOut.toLocaleTimeString();
                $("#tempo_sessao").html(hora);
                if (tempoAlerta <= hora) {
                    clearInterval(verificaSessao)
                    let timerInterval
                    Swal.fire({
                        title: 'Sua sessão irá expirar em breve!',
                        confirmButtonText: 'Atualizar',
                        html: 'Restam <strong></strong><br/>',
                        icon: "warning",
                        allowOutsideClick: false,
                        timer: 10 * 60 * 1000, // 10 minutos
                        onOpen: () => {
                            const content = Swal.getHtmlContainer()
                            const $ = content.querySelector.bind(content)
                            timerInterval = setInterval(() => {
                                getSeconds = (Swal.getTimerLeft() / 1000).toFixed();
                                minutes = Math.floor(getSeconds / 60);
                                seconds = getSeconds - (minutes * 60);

                                timeString = minutes.toString().padStart(2, '0') + ':' +
                                    seconds.toString().padStart(2, '0');

                                Swal.getHtmlContainer().querySelector('strong')
                                    .textContent = timeString
                            }, 1000)
                        },
                        onClose: () => {
                            clearInterval(timerInterval)
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
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
                        } else {
                            timeoutSessao()
                        }
                    })
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
                        id: id,
                        id_usuario: idUser,
                        timestamp: tempo
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
            };*/
            
            $('.renovarSessao').click(function(e) {
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
                })
            });
        <?php endif; ?>


        <?php if (isset($this->session->id_fornecedor)) : ?>
            var urlCorreio = "<?php echo base_url('global/comunicacao/checkAllMessages'); ?>";

            getMessagesUnread(urlCorreio);
            setInterval(function() {
                getMessagesUnread(urlCorreio);
            }, 200000)
        <?php endif; ?>

        $('#btnGetCotacao').click(function(e) {
            e.preventDefault();
            var int = $('#s_integrador').val();

            <?php if (isset($this->session->id_fornecedor)) { ?>
                <?php if (in_array($this->session->id_fornecedor, explode(',', ONCOPROD))) { ?>

                    var url = "<?php echo base_url('fornecedor/cotacoes_oncoprod/detalhes/'); ?>" + int + '/';
                <?php } elseif (in_array($this->session->id_fornecedor, explode(',', ONCOEXO))) { ?>

                    var url = "<?php echo base_url('fornecedor/cotacoes_oncoexo/detalhes/'); ?>" + int + '/';
                <?php } else { ?>

                    var url = "<?php echo base_url('fornecedor/cotacoes/detalhes/'); ?>" + int + '/';
                <?php } ?>

            <?php } ?>

            var cot = $('#inptGetCotacao').val();


            if (cot.length > 4 && int.length > 0) {
                window.location.replace(url + cot);
            } else {
                formWarning({
                    type: 'warning',
                    'message': 'Informe o número da cotação e selecione o integrador'
                });
            }
        });


        $('.modalOpen').on('click', function(e) {
            e.preventDefault();
            let me = $(this);

            $.ajax({
                url: me.attr('href'),
                type: 'get',
                dataType: 'html',
                success: function(response) {
                    $('body').append(response);
                    $('.modal').modal({
                        keyboard: false
                    }, 'show').on('hide.bs.modal', function() {
                        $('.modal').remove();
                        $('#data-table').DataTable().ajax.reload();
                    });
                }
            })
        });


    });


    $(document).ajaxStart(function() {
        Pace.restart();
    });
</script>