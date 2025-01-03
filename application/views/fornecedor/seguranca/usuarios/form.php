<div class="modal fade" id="modalLogistica" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><?php echo $title; ?></h5>
            </div>

            <div class="modal-body">
                <form action="<?php echo $form_action; ?>" method="POST" id="formUpdate">

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
        var formUpdate = $('#formUpdate');

        formUpdate.on("submit", function(event) {
            event.preventDefault();

            $.ajax({
                type: 'POST',
                url: formUpdate.attr('action'),
                data: formUpdate.serialize(),
                dataType: 'json',

                success: function(response) {
                    console.log(response);
                    if (response.status === false) {
                        $.each(response.errors, function(i, v) {
                            formWarning({
                                type: 'warning',
                                message: v
                            });
                        });
                    } else {
                    }
                }
            });

            return false;
        });
    });
</script>