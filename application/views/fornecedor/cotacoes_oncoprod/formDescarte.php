<div class="modal fade" id="descarteModal" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">Descartar Cotação</h5>
            </div>

            <div class="modal-body">
                <?php if (isset($recusa['motivo_recusa'])) { ?>
                    <p class="text-danger">Cotação descartada por <?php echo $recusa['usuario']; ?>
                        em <?php echo date('d/m/Y H:i', strtotime($recusa['data_recusa'])); ?></p>
                <?php } ?>

                <form id="formDescarte" action="<?php echo $form_action; ?>" method="post">
                    <input type="hidden" name="cotacao" value="<?php if (isset($cd_cotacao)) echo $cd_cotacao; ?>">
                    <input type="hidden" name="integrador" value="<?php if (isset($integrador)) echo $integrador; ?>">
                    <div class="form-group">
                        <label for="">Motivo do descarte</label>
                        <select name="motivo" REQUIRED <?php if (isset($recusa['motivo_recusa'])) echo 'readonly'; ?> id="motivo"
                                class="form-control">
                            <option value="">SELECIONE...</option>
                            <?php foreach (getMotivosRecusa() as $k => $motivo) { ?>
                                <option <?php if (isset($recusa['motivo_recusa']) && $recusa['motivo_recusa'] == $k) echo 'selected'; ?>
                                        value="<?php echo $k; ?>"><?php echo $motivo; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Observações</label>
                        <textarea name="obs"
                                  id="obs" <?php if (isset($recusa['obs_recusa'])) echo 'readonly'; ?> cols="30"
                                  rows="3"
                                  class="form-control"><?php if (isset($recusa['obs_recusa'])) echo $recusa['obs_recusa']; ?></textarea>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <?php if (!isset($recusa['motivo_recusa'])) { ?>
                    <button type="submit" form="formDescarte" class="btn btn-primary">Salvar</button>
                <?php } ?>

                <button type="button" class="btn btn-link" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {
        $('#formDescarte').on('submit', function (e) {
            e.preventDefault();

            var $form = $(this);

            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $form.serialize(),
                dataType: "json",

                success: function (response) {
                    formWarning({
                        type: response.type,
                        message: response.message
                    });

                    if (response.type === 'success') {
                        $('#descarteModal').modal('hide');
                    }


                }
            });

            return false;
        });
    });
</script>