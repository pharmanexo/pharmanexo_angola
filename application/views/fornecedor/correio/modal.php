<div class="modal fade" id="modalLogistica" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><?php echo $title; ?></h5>
            </div>

            <div class="modal-body">
                <form action="<?php echo $url_update; ?>" method="POST" id="formUpdate">
                    <input type="hidden" id="idOrdemCompra" name="id_ordem_compra" value="<?php echo $row['id']; ?>">

                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <?php $disabled = (!empty($row['codigo_rastreio'])) ? 'disabled' : ''; ?>
                                <label for="codigoRastreio">Código de Rastreio</label>
                                <input type="text" class="form-control" name="codigo_rastreio" <?php echo $disabled ?> id="codigoRastreio" value="<?php if (isset($row['codigo_rastreio'])) echo $row['codigo_rastreio']; ?>">
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="form-group">
                                <?php $disabled = (!empty($row['transportadora'])) ? 'disabled' : ''; ?>
                                <label for="transportadora">Transportadora</label>
                                <input type="text" class="form-control" name="transportadora" id="transportadora" <?php echo $disabled; ?> value="<?php echo $row['transportadora']; ?>">
                            </div>
                        </div>
                    </div>
                </form>

                <?php $hidden = (empty($row['codigo_rastreio']) && empty($row['transportadora'])) ? 'none;' : 'block;'; ?>
                <div id="rowHistorico" style="display: <?php echo $hidden; ?>">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="historico">Histórico</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="descricao" id="descricao">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-primary" id="btnSalvarHistorico" data-url="<?php echo $url_salvar_historico; ?>">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="table-responsive col-sm">
                            <table id="table-historico" class="table table-condensend table-hover">
                                <thead>
                                    <tr>
                                        <th>Data</th>
                                        <th>Histórico</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($historicos as $h) : ?>
                                        <tr>
                                            <td><?php echo $h['data_criacao']; ?></td>
                                            <td><?php echo $h['descricao']; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <?php $disabled = (empty($row['codigo_rastreio']) && empty($row['transportadora'])) ? '' : 'disabled'; ?>
                <?php $hidden = (empty($row['codigo_rastreio']) && empty($row['transportadora'])) ? '' : 'hidden'; ?>
                <button type="submit" id="btnSalvar" class="btn btn-link" <?php echo $disabled ?> <?php echo $hidden ?> form="formUpdate">Salvar</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {
        var btnSalvarHistorico = $('#btnSalvarHistorico');
        var formUpdate = $('#formUpdate');

        btnSalvarHistorico.on('click', function(e) {
            e.preventDefault();

            $.ajax({
                type: 'POST',
                url: `${btnSalvarHistorico.data('url')}`,
                data: {
                    "id_ordem_compra": $('#idOrdemCompra').val(),
                    "descricao": $('#descricao').val()
                },
                dataType: "json",
                success: function(response) {
                    if (response.status === false) {
                        $.each(response.errors, function(i, v) {
                            formWarning({
                                type: 'warning',
                                message: v
                            });
                        });
                    } else {
                        $('#descricao').val('');
                        $('#table-historico').append(`<tr><td>${response.row.data_criacao}</td><td>${response.row.descricao}</td></tr>`);
                    }
                }
            });

            return false;
        });

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
                        $('#btnSalvar').attr('disabled', true);
                        $('#codigoRastreio').attr('disabled', true);
                        $('#transportadora').attr('disabled', true);
                        $('#rowHistorico').css("display", "block");
                    }
                }
            });

            return false;
        });
    });
</script>