<div class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-times-circle text-danger"></i> <?php echo $title; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="frm_refuse" action="<?php echo $frm_action; ?>">
                    <div class="row">
                        <div class="col-12">
                            <p><?php echo "Cod:: " . $produto['codigo'] . "<br> Descri:: " . $produto['produto_descricao'] . "<br>Marca:: " . $produto['marca']; ?></p>
                        </div>
                        <div class="col-12 form-group">
                            <label for="justificativa">Justificativa <span class="text-danger">*</span></label>
                            <textarea name="justificativa" id="justificativa" cols="30" rows="4"
                                      class="form-control textarea-autosize"
                                      style="overflow: hidden; overflow-wrap: break-word;"></textarea>
                            <i class="form-group__bar"></i>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button id="btn_submit" type="submit" form="frm_refuse" class="btn btn-primary">Enviar</button>
            </div>
        </div>
    </div>
    <script>
        $(function () {
            $('#btn_submit').on('click', function (e) {
                e.preventDefault();
                $.ajax({
                    url: $('#frm_refuse').attr('action'),
                    type: 'post',
                    dataType: 'json',
                    data: $('#frm_refuse').serialize(),
                    success: function (xhr) {
                        toastr[xhr.type](xhr.message);
                        $('.modal').modal('hide');
                    }
                })
            });
        });
    </script>
</div>