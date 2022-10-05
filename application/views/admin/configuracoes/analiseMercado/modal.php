<div class="modal fade" id="modalAnaliseMercado" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><?php echo $page_title; ?></h5>
            </div>

            <div class="modal-body">
                
                <form id="formAnaliseMercado"method="POST"action="<?php if (isset($formAction)) echo $formAction ?>"data-select_produto="<?php echo $urlSelectprodutos; ?>">

                    <div class="row mt-3">

                        <div class="col-6">
                            <div class="form-group">
                                <label for="id_fornecedor">Fornecedor/Filial</label>
                                <select class="select2 w-100" style="width: 100%" name="id_fornecedor" id="id_fornecedor" data-placeholder="Selecione" data-allow-clear="true">
                                    <option></option>
                                    <?php foreach ($fornecedores as $f): ?>
                                        <option value="<?php echo $f['id']; ?>"><?php echo $f['nome_fantasia']; ?></option>
                                    <?php endforeach; ?>
                                     <optgroup label="Filiais">
                                        <option value="oncoprod">ONCOPROD</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>

                        <div class="col-5">
                            <div class="form-group">
                                <label for="codigo">Produto</label>
                                <select class="select2 w-100" style="width: 100%" name="codigo" id="codigo" disabled data-placeholder="Selecione" data-allow-clear="true"></select>
                            </div>
                        </div>
                    </div>

                    <h5 class="text-left mb-3">Cadastrar Preços</h5>

                    <div id="precos_fields">
                        <div class="row">
                            <div class="col-5"><label>Estado</label></div>
                            <div class="col-3"><label>Preço Mínimo</label></div>
                            <div class="col-3"><label>Preço Médio</label></div>
                            <div class="col-1"></div>
                        </div>

                        <div class="row"  id="precosRow">
                            <div class="col-5">
                                <div class="form-group">
                                    <select class="select2 w-100" name="estado[]" id="estado" style="width: 100%" data-placeholder="Selecione">
                                        <option></option>
                                        <?php foreach ($estados as $estado) { ?>
                                            <option value="<?php echo $estado['uf'] ?>"><?php echo $estado['uf'] . ' - ' . $estado['descricao'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <input type="text" name="preco_minimo[]" class="form-control precomin" id="preco_minimo" data-inputmask="money4">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <input type="text" name="preco_medio[]" class="form-control precomedio" id="preco_medio" data-inputmask="money4">
                                </div>
                            </div>
                            <div class="col-1 mt-1">
                                <button type="button" class="btn btn-primary" id="btn-plus-preco">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                </form>
            </div>

            <div class="modal-footer">
                <button type="submit" form="formAnaliseMercado" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>

    var urlSelectprodutos = $("#formAnaliseMercado").data('select_produto');

    $(function() {
        reloadPlugin()

        $("#id_fornecedor, #codigo, #estado").select2({ dropdownParent: $('#modalAnaliseMercado') });

        $("#id_fornecedor").on('change', function () {

            $('#codigo').val(null).trigger('change.select2');

            if ( $(this).val() != '' ) {

                $.post(urlSelectprodutos, {
                    id_fornecedor: $("#id_fornecedor").val()
                }, function(xhr) {

                    $('#codigo').val(null).trigger('change.select2');

                    Object.entries(xhr).forEach(([key, row]) => {

                        var text = "COD. " + row.codigo + ' - ' + row.nome_comercial;

                        var newOption = new Option(text, row.codigo, false, false);
                        $('#codigo').append(newOption).trigger('change.select2');
                    });

                    $('#codigo').val(null).trigger('change.select2')
                    $("#codigo").prop("disabled", false);
                }, 'JSON');
            } else {

                $("#codigo").prop("disabled", true);
            }
        });

        $('#btn-plus-preco').click(function (e) {
            e.preventDefault();

            var count_estados = $('#precosRow input.preco-count').length;
            var formSelect = $('#precosRow').find('select');

            if (count_estados == 27) { return; }

            if ( formSelect.val() == '' ) {
                formWarning({type: "warning", message: "O campo estado é obrigatório!"});
                return;
            }

            var selected = formSelect.find(':selected');
            var elements = $('#precosRow').clone();

            console.log(elements.find('input.preco'));

            if ( elements.find('input.precomedio').val() == '' ) {

                formWarning({type: "warning", message: "O campo preço médio é obrigatório!"});
                return;
            }

            if ( elements.find('input.precomin').val() == '' ) {

                formWarning({type: "warning", message: "O campo preço mínimo é obrigatório!"});
                return;
            }

            elements.find('select').val(selected.val()).attr('readonly', true);
            elements.find('input').attr('readonly', true);
            elements.find('select option:not(:selected)').remove();


            $('#precosRow').find('input').val('');

            elements.find('.btn').html('<i class="fas fa-minus"></i>').addClass('btn-danger').click(function (e) {
                var elementSelected = elements.find('select').find(':selected');

                var option = $('<option></option>');
                option.val(elementSelected.val());
                option.text(elementSelected.text());

                formSelect.append(option);

                formSelect.html($("option", formSelect).sort(function (a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));


                formSelect.find('option:first-child').attr('selected', true);

                elements.remove();
            });

            $('#precos_fields').append(elements);
            selected.remove();
        });

        $('#formAnaliseMercado').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);

            $.ajax({
                url: $form.attr('action'),
                type: 'post',
                dataType: "json",
                data: $form.serialize(),
                beforeSend: function(jqXHR, settings) {

                    if ( $("#id_fornecedor").val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo fornecedor é obrigatório"});
                        return jqXHR.abort();
                    }  

                    if ( $("#codigo").val() == null ) {
                        formWarning({ type: 'warning', message: "O campo produto é obrigatório"});
                        return jqXHR.abort();
                    }

                    if ( ($("input#preco").length == 1 && ($("#preco").val() == '' || $("#estado").val() == '')) ) {

                        formWarning({ type: 'warning', message: "É necessário informar um preço"});
                        return jqXHR.abort();
                    }  
                },
                success: function(xhr) {

                    if (xhr.type == 'warning') {

                        if ( typeof xhr.message == 'string' ) {
                            xhr.message = { message: xhr.message };
                        }

                        $.each(xhr.message, function(i, v) {
                            formWarning({ type: 'warning', message: v });
                        });
                    } else {
                        
                        formWarning(xhr);
                        $('#modalAnaliseMercado').modal('hide');
                    }
                },
                error: function(xhr) {

                    formWarning({ type: 'warning', message: "Erro ao salvar as informações!" });
                }
            })

            return false;
        });
    });
</script>