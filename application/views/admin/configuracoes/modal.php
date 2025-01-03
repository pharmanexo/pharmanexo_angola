<div class="modal fade" id="modalConfig" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog <?php if( isset($dados) && $dados['json'] == 1 ) echo 'modal-lg'; ?> ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><?php echo $title; ?></h5>
            </div>

            <div class="modal-body">
                <form id="formConfig" method="POST" action="<?php if (isset($form_action)) echo $form_action ?>">

                    <div class="row mt-3">
                        <div class="col">
                            <div class="form-group">
                                <label for="acao">Chave</label>
                                <input type="text" class="form-control" name="chave" id="chave" value="<?php if(isset($dados)) echo $dados['chave']; ?>" <?php if(isset($dados)) echo 'readonly'; ?>>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <div class="checkbox">
                                    <input type="checkbox" name="json" id="json" <?php if ( isset($dados) && $dados['json'] == 1 ) echo 'checked'; ?>>
                                    <label class="checkbox__label" for="json">JSON ?</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="campoRow" <?php if(isset($dados) && $dados['json'] == 1) echo 'hidden'; ?> >
                         <div class="col">
                            <div class="form-group">
                                <label>Valor</label>
                                <input type="text" name="valorSemJson" id="valorSemJson" class="form-control" value="<?php if(isset($dados) && $dados['json'] != 1) echo $dados['valor']; ?>">
                            </div>
                        </div>
                    </div>

                    <div id="campoRowJson" <?php if( !isset($dados) || (isset($dados) && $dados['json'] != 1) ) echo 'hidden'; ?> >
                        <div class="form-row">
                            <div class="col"><label>Nome</label></div>
                            <div class="col"><label>Valor</label></div>
                        </div>
                        <div id="valoresRow">

                            <?php if( isset($dados) && $dados['json'] == 1 ): ?>

                                <div class="form-row">
                                    <div class="col mb-2">
                                        <input type="text" name="nome[]" class="form-control nome" id="nome">
                                    </div>
                                    <div class="col mb-2">
                                        <input type="text" name="valor[]" class="form-control valor" id="valor">
                                    </div>
                                    <div class="col-1 mt-1">
                                        <button type="button" class="btn btn-primary" id="btn-plus">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <?php foreach(json_decode($dados['valor']) as $nome => $valor  ): ?>

                                    <div class="form-row">
                                        <div class="col mb-2">
                                            <input type="text" name="nome[]" class="form-control nome" id="nome" value="<?php echo $nome; ?>" >
                                        </div>
                                        <div class="col mb-2">
                                            <input type="text" name="valor[]" class="form-control valor" id="valor" value="<?php echo $valor; ?>" >
                                        </div>
                                        <div class="col-1 mt-1">
                                            <button type="button" class="btn btn-danger" data-minus="1">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>

                                <?php endforeach; ?>
                            <?php else: ?>

                                <div class="form-row">
                                    <div class="col mb-2">
                                        <input type="text" name="nome[]" class="form-control nome" id="nome">
                                    </div>
                                    <div class="col mb-2">
                                        <input type="text" name="valor[]" class="form-control valor" id="valor">
                                    </div>
                                    <div class="col-1 mt-1">
                                        <button type="button" class="btn btn-primary" id="btn-plus">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="submit" form="formConfig" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>

    $(function() {
        reloadPlugin();

        $("#json").on('change', function () {

            if ( $(this).prop('checked') ) {

                $(".modal-dialog").addClass("modal-lg");
                $("#campoRow").prop('hidden', true);
                $("#campoRowJson").prop('hidden', false);
            } else {

                $(".modal-dialog").removeClass("modal-lg");
                $("#valorSemJson").val("");
                $("#campoRow").prop('hidden', false);
                $("#campoRowJson").prop('hidden', true);
            }
        });

        $('#btn-plus').click(function (e) {
            e.preventDefault();

            var elements = $('#valoresRow').clone();

            if ( elements.find('input.nome').val() == '' ) {
                formWarning({type: "warning", message: "O campo nome é obrigatório!"});
                return;
            }

            if ( elements.find('input.valor').val() == '' ) {
                formWarning({type: "warning", message: "O campo valor é obrigatório!"});
                return;
            }

            elements.find('input').attr('readonly', true);

            $('#valoresRow').find('input').val('');

            elements.find('.btn').html('<i class="fas fa-minus"></i>').addClass('btn-danger').click(function (e) {

                elements.remove();
            });

            $('#campoRowJson').append(elements);
        });

        $("[data-minus]").click(function (e) {
            e.preventDefault();
            $(this).parent().parent().remove();
        });

        $('#formConfig').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);

            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $form.serialize(),
                dataType: "json",
                beforeSend: function(jqXHR, settings) {

                    if ( $('#chave').val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo chave é obrigatório!"});
                        return jqXHR.abort();
                    }
                },
                success: function(response) {
                    formWarning(response);
                    if (response.type === 'success') { $('#modalConfig').modal('hide'); }
                }
            });

            return false;
        });
    });
</script>