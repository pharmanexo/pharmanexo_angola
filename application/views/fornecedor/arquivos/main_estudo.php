<div class="modal fade" id="modalArquivos" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><?php echo $title; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <form action="<?php if (isset($urlImport)) echo $urlImport; ?>"  class="formSend" id="formEstoque" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-8">
                            <div class="form-group">
                                <label for="">Título</label>
                                <input type="text" name="titulo" id="titulo" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Arquivo</label>
                                <input type="file" name="file" id="file" class="form-control">
                            </div>
                        </div>
                    </div>
                </form>

                <div class="row">
                    <div class="col-12 text-right">
                        <button type="submit" form="formEstoque" class="btn btn-primary">Enviar Arquivo</button>
                    </div>
                </div>

                <p class="msg small"></p>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {


        $('form').on("submit", function (event) {
            event.preventDefault();
            var form = $(this);

            var formUpdate = $(this);
            var formData = new FormData(this);

            $('#modalArquivos .btn').html("<i class='fas fa-spinner'></i> Processando... ");

            $.ajax({
                type: 'POST',
                url: formUpdate.attr('action'),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {

                    if (response.type == 'success'){
                        $('#modalArquivos .btn').prop('disabled', 'disabled').html("Arquivo enviado!");
                        $('.msg').html("Por motivo de segurança bloqueamos o botão após a importação, para realizar uma nova importação, feche a tela e abra novamente.");
                    }else{
                        $('#modalArquivos .btn').html("Enviar Arquivo");
                    }
                    formWarning(response);

                }
            });
            return false;
        });
    });
</script>