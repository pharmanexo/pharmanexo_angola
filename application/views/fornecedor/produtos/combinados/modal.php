<div class="modal fade" id="modalNotFound" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><?php echo $title; ?></h5>
            </div>

            <div class="modal-body">
                <form action="<?php echo $url_send; ?>" method="POST" id="formUpdate">
                    <input type="hidden" name="produto" id="produto">
                    <div class="form-group">
                        <label for="">Escreva sua mensagem abaixo</label>
                        <textarea name="mensagem" id="mensagem" cols="30" rows="10" class="form-control"></textarea>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="submit" id="btnSalvar" class="btn btn-primary" form="formUpdate">Enviar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        $('#formUpdate').submit(function (e) {
            e.preventDefault();

            $.post($(this).attr('action'), $(this).serialize(), function (xhr) {
                formWarning(xhr);
                $('#modalNotFound').modal('hide');
            }, 'JSON')

        });
    });
</script>