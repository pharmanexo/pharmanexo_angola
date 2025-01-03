<div class="modal fade" id="modalLogistica" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><?php echo $title; ?></h5>
            </div>

            <div class="modal-body">
                <form action="<?php echo $form_action; ?>" method="post" enctype="multipart/form-data" id="formUpdate">
                    <div class="form-group">
                        <select name="id_tipoassunto" id="id_tipoassunto" required class="form-control">
                            <option value="">Selecione uma categoria</option>
                            <?php foreach ($categorias as $categoria){ ?>
                            <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['nome']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" name="titulo" id="titulo" placeholder="Assunto do Chamado" required class="form-control">
                    </div>
                    <div class="form-group">
                        <textarea name="mensagem" id="mensagem" required placeholder="Conte-nos um pouco mais sobre o que está acontecendo." cols="30" rows="5" class="form-control"></textarea>
                    </div>
                    <div class="form-group">
                        <input type="file" name="anexos[]" multiple class="form-control">
                    </div>
                </form>

            </div>

            <div class="modal-footer">
                <button type="submit" id="btnSalvar" class="btn btn-link" form="formUpdate">Salvar</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('#formUpdate').submit(function (e) {
            e.preventDefault();
            $('.btn', '#modalLogistica').attr('disable', true);
            $('#btnSalvar').html("<i class='fa fa-spinner fa-spin'></i> Enviando... ");
            var formUpdate = $(this);
            var formData = new FormData(this);

            $.ajax({
                type: 'POST',
                url: formUpdate.attr('action'),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.erro === false) {
                        window.location.href = response.url;
                    } else {
                        var warn = {
                            'type': 'warning',
                            'message': 'Houve um erro ao abrir o chamado'
                        };
                        formWarning(warn);

                        $('.btn', '#modalLogistica').removeAttr('disable');
                        $('#btnSalvar').html("Salvar");
                    }
                }
            });
        });

    });
</script>