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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>

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
<!--<script type='text/javascript' src='https://widget.freshworks.com/widgets/73000002652.js' async defer></script>-->
<script src="<?php echo THIRD_PARTY . 'theme/plugins/pace-1.0.2/pace.min.js' ?>"></script>
<script>
    $(function() {

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
        $('#loading').show();
    });
    $(document).ajaxStop(function() {
        $('#loading').hide();
    });
</script>