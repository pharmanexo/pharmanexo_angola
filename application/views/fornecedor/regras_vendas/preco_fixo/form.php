<div class="modal fade" id="modalPrexoFixo" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left">Responsáveis Cotações</h5>
            </div>

            <div class="modal-body">
                <form id="formPrexoFixo" method="POST" action="<?php if (isset($form_action)) echo $form_action ?>">

                    <input type="hidden" name="id_estado" value="<?php echo $dados['id_estado']; ?>">
                    <input type="hidden" name="id_cliente" value="<?php echo $dados['id_cliente']; ?>">

                    <div class="row mx-auto mt-3">
                        <div class="col-2">
                            <div class="form-group">
                                <label for="">Código</label>
                                <input type="text" name="codigo" value="<?php echo $dados['codigo']; ?>" readonly class="form-control">
                            </div>
                        </div>

                        <div class="col-8">
                            <div class="form-group">
                                <label for="">Produto</label>
                                <input type="text" value="<?php echo $dados['nome_comercial']; ?>" readonly class="form-control">
                            </div>
                        </div>

                        <div class="col-2">
                            <div class="form-group">
                                <label for="">Preço</label>
                                <input type="text" name="preco" data-inputmask="money4" value="<?php echo number_format($dados['preco_base'], 4, ',', '.'); ?>" class="form-control">
                            </div>
                        </div>

                    </div>

                    <?php if (!$isUpdate) : ?>
                        <div class="row mx-auto mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <select multiple name="listElements" id="listElements">
                                        <?php foreach ($compradores as $comprador): ?>
                                            <option value="<?= $comprador['id'] ?>"><?= $comprador['cnpj'] . ' - ' . $comprador['razao_social'] ?></option>
                                        <?php endforeach; ?>

                                    </select>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" data-url="<?php echo $urlDelete; ?>" id="btnDelete" class="btn btn-outline-danger float-left"><i class="fas fa-trash"></i></button>
                <button type="submit" form="formPrexoFixo" class="btn btn-primary float-right">Salvar</button>
                <button type="button" class="btn btn-link float-right" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    var urlDelete = $('#btnDelete').data('url');
    $(function () {
        reloadPlugin();

        $('#formPrexoFixo').on('submit', function (e) {
            e.preventDefault();

            var $form = $(this);

            $.ajax({
                url: $form.attr('action'),
                type: $form.attr('method'),
                data: $form.serialize(),
                dataType: "json",
                beforeSend: function (jqXHR, settings) {

                    if ($("#preco").val() == '') {
                        formWarning({type: 'warning', message: "O campo preço é obrigatório!"});
                        return jqXHR.abort();
                    }

                },
                success: function (response) {
                    if (response.status === false) {
                        $.each(response.errors, function (i, v) {
                            formWarning({
                                type: 'warning',
                                message: v
                            });
                        });
                    } else {
                        formWarning({
                            type: 'success',
                            message: response.message
                        });

                        $('#modalPrexoFixo').modal('hide');
                    }
                }
            });

            return false;
        });

        $('#btnDelete').click(function (e) {
            e.preventDefault();

            var data = $('#formPrexoFixo').serialize();

            Swal.fire({
                title: 'Tem certeza?',
                text: "Ação não reversível!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.post(urlDelete, data, function (xhr) {
                        formWarning(xhr)
                        $('#modalPrexoFixo').modal('hide');
                    }, 'JSON');
                }
            })


        });
    });
</script>
