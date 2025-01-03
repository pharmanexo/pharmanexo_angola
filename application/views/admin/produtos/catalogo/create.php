<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner">

        <div class="card">
            <div class="card-body">
                <form action="<?php if(isset($form_action_produto)) echo $form_action_produto ?>" method="post" id="formProdutos">
                    <input type="hidden"  name="marca" id="marca">
                    <input type="hidden"  name="id_marca" id="id_marca">
                    <div class="row mb-5">
                        <div class="col-5">
                            <label>Nome Comercial</label>
                            <input type="text" name="nome_comercial" class="form-control" id="nome_comercial">
                        </div>
                        <div class="col-7">
                            <label>Apresentação</label>
                            <input type="text" name="apresentacao" class="form-control" id="apresentacao">
                        </div>
                    </div>
                    <div class="row mb-5">
                        <div class="col-6">
                            <label>Descrição</label>
                            <input type="text" name="descricao" class="form-control" id="descricao">
                        </div>
                        <div class="col-6">
                            <label for="">Marca</label>
                            <select class="w-100 select2" id="slct_marcas" style="width: 100%" data-url="<?php echo $slct_marcas; ?>"></select>
                        </div>
                    </div>

                    <div class="row mb-5">

                        <div class="col-6">
                            <label for="id_fornecedor">Fornecedor</label>
                            <select class="select2" name="id_fornecedor" id="id_fornecedor">
                                <option value="">Selecione</option>
                                <?php foreach($fornecedores as $f): ?>
                                    <option value="<?php echo $f['id']; ?>"><?php echo $f['nome_fantasia']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-4">
                            <label>Código Interno
                                <span class="mr-0 text-right d-inline-block" data-toggle="tooltip" title="" data-original-title="Código de identificação no fornecedor"><i class="fas fa-info-circle"></i></span>
                            </label>
                            <input type="number" class="form-control" name="codigo" id="codigo">
                        </div>
                        
                    </div>

                    <div class="row mb-4">
                        <div class="col-3">
                            <label>RMS
                                <span class="mr-0 text-right d-inline-block" data-toggle="tooltip" title="" data-original-title="REGISTRO DO MINISTÉRIO DA SAÚDE">
                                        <i class="fas fa-info-circle"></i>
                                    </span>
                            </label>
                            <input type="text" class="form-control" name="rms" id="rms">
                        </div>
                        <div class="col-3">
                            <label>EAN
                                <span class="mr-0 text-right d-inline-block" data-toggle="tooltip" title="" data-original-title="Código de barra do produto">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                            </label>
                            <input type="text" class="form-control" name="ean" id="ean">
                        </div>

                        <div class="col-3">
                            <label>Unidade</label>
                            <select class="select2 w-100" name="unidade" id="unidade">
                                <option value="">Selecione</option>
                                <option value="AMP">Ampola</option>
                                <option value="CAP">Cápsulas</option>
                                <option value="CX">Caixa</option>
                                <option value="COMP">Comprimidos</option>
                                <option value="DR">Drágeas</option>
                                <option value="DZ">Duzia</option>
                                <option value="EMU">Emulsão</option>
                                <option value="ENV">Envelope</option>
                                <option value="FR">Frascos</option>
                                <option value="LIN">Linimentos</option>
                                <option value="OVU">Óvulos</option>
                                <option value="PAR">Par</option>
                                <option value="PIL">Pílulas</option>
                                <option value="PCT">Pacotes</option>
                                <option value="POM">Pomadas</option>
                                <option value="SER">Seringas</option>
                                <option value="SOL">Soluções</option>
                                <option value="SUP">Supositórios</option>
                                <option value="SUS">Suspensão</option>
                                <option value="UNID">Unidades</option>
                                <option value="OUTROS">Outros</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <label>Quantidade na embalagem</label>
                            <input type="number" class="form-control" name="quantidade_unidade" id="quantidade_unidade">
                        </div>
                    </div>
                    <?php if (!isset($produto)) { ?>
                        <div class="row mt-5">
                            <button id="inserirPrecosLotes" type="submit" class="btn btn-light btn-block" style="height: 100%">Informar Preços e Lotes</button>
                        </div>
                    <?php } ?>
                </form>
            </div>
        </div>

        <form hidden action="<?php if(isset($form_action_precosLotes)) echo $form_action_precosLotes ?>" id="formPrecosLotes" method="post">
            <input type="hidden" name="codigo_produto" id="codigo_produto" value="<?php if(isset($produto)) echo $produto['codigo']; ?>">
            <div class="row">
                <div class="col-5">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 text-left">Inserir Preços</h5>
                        </div>
                        <div class="card-body">
                            <div id="precos_fields">
                                <div class="form-row">
                                    <div class="col"><label>Estado</label></div>
                                    <div class="col"><label>Preço</label></div>
                                </div>
                                <div id="precosRow">
                                    <div class="form-row">
                                        <div class="col mb-2">
                                            <select class="select2 w-100" name="id_estado[]" id="id_estado" style="width: 100%">
                                                <option value="0">Selecione</option>
                                                <option value="30">Todos</option>
                                                <?php foreach ($estados as $estado) { ?>
                                                    <option value="<?php echo $estado['id'] ?>"><?php echo $estado['estado'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="col mb-2">
                                            <input type="text" name="preco[]" class="form-control preco" id="preco" data-inputmask="money4">
                                        </div>
                                        <div class="col-1 mt-1">
                                            <button type="button" class="btn btn-primary" id="btn-plus-preco">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-7">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0 text-left">Inserir Lotes</h5>
                        </div>
                        <div class="card-body">
                            <div id="lotes_fields">
                                <div class="form-row">
                                    <div class="col"><label>Validade</label></div>
                                    <div class="col"><label>Local (CD)</label></div>
                                    <div class="col"><label>Estoque</label></div>
                                    <div class="col"><label>Lote</label></div>
                                </div>
                                <div id="lotesRow">
                                    <div class="form-row">
                                        <div class="col mb-2">
                                            <input type="text" class="form-control validade" name="validade[]" id="validade" data-inputmask="date">
                                        </div>
                                        <div class="col mb-2">
                                            <input type="text" name="local[]" class="form-control">
                                        </div>
                                        <div class="col mb-2">
                                            <input type="number" name="estoque[]" class="form-control estoque">
                                        </div>
                                        <div class="col mb-2">
                                            <input type="text" name="lote[]" class="form-control lote">
                                        </div>
                                        <div class="col-1">
                                            <button type="button" class="btn btn-primary" id="btn-plus-lote">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

<?php if (isset($scripts)) echo $scripts; ?>

<script type="text/javascript">

    var slct_marcas;

    $(function () {

        $('#btnSave').attr('hidden', true);

        var $form = $('#formProdutos');

        slct_marcas = $('#slct_marcas');

        slct_marcas.select2({
            placeholder: 'Selecione...',
            ajax: {
                url: slct_marcas.data('url'),
                type: 'get',
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    return {
                        columns: [{
                            name: 'marca',
                            search: params.term
                        }],
                        page: params.page || 1
                    }
                }
            },
            escapeMarkup: function(markup) { return markup; },
            processResults: function (data) { return {results: data } },
            templateResult: function (data, container) {
                if (!data.id) { return data.text; }

                var ret = data.marca;

                return ret;
            },
            templateSelection: function (data, container) {
                if (!data.id) { return data.text; }

                $('#id_marca').val(data.id);
                $('#marca').val(data.marca);
               
                return (typeof data.marca !== 'undefined') ? `${data.marca}` : '';
            }
        });

        $('#btn-plus-preco').click(function (e) {
            e.preventDefault();

            var count_estados = $('#precosRow input.preco-count').length;
            var formSelect = $('#precosRow').find('select');

            if (count_estados == 27) { return; }
            if ( formSelect.val() == '0' ) {
                formWarning({type: "warning", message: "O campo preço é obrigatório!"});
                return;
            }

            var selected = formSelect.find(':selected');
            var elements = $('#precosRow').clone();

            if ( elements.find('input.preco').val() == '' ) {
                formWarning({type: "warning", message: "O campo estado é obrigatório!"});
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

        $('#btn-plus-lote').click(function (e) {
            e.preventDefault();

            var elements = $('#lotesRow').clone();

            if ( elements.find('input.validade').val() == '' ) {
                formWarning({type: "warning", message: "O campo Validade é obrigatório!"});
                return;
            }
            if ( elements.find('input.estoque').val() == '' ) {
                formWarning({type: "warning", message: "O campo estoque é obrigatório!"});
                return;
            }
            if ( elements.find('input.lote').val() == '' ) {
                formWarning({type: "warning", message: "O campo lote é obrigatório!"});
                return;
            }

            elements.find('input').attr('readonly', true);

            $('#lotesRow').find('input').val('');

            elements.find('.btn').html('<i class="fas fa-minus"></i>').addClass('btn-danger').click(function (e) {

                elements.remove();
            });

            $('#lotes_fields').append(elements);
        });

        $('#formProdutos').on('submit', function (e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: $('#formProdutos').attr('action'),
                type: 'post',
                contentType: false,
                processData: false,
                data: formData,
                beforeSend: function(jqXHR, settings) {
                    if ( $("#id_fornecedor").val() == '' ) {
                        formWarning({type: 'warning', message: 'O campo fornecedor é obrigatório' });
                        return jqXHR.abort();
                    }
                    if ( $("#codigo").val() == '' ) {
                        formWarning({type: 'warning', message: 'O campo codigo é obrigatório' });
                        return jqXHR.abort();
                    }
                    if ( $("#slct_marcas").val() == null) {
                        formWarning({type: 'warning', message: 'O campo marcas é obrigatório' });
                        return jqXHR.abort();
                    }
                    if ( $("#unidade").val() == '') {
                        formWarning({type: 'warning', message: 'O campo unidade é obrigatório' });
                        return jqXHR.abort();
                    }
                    if ( $("#quantidade_unidade").val() == '') {
                        formWarning({type: 'warning', message: 'O campo quantidade unidade é obrigatório' });
                        return jqXHR.abort();
                    }
                },
                success: function(xhr) {
                    e.preventDefault();
                    if (xhr.type === 'success') {

                        $('#formPrecosLotes').attr('hidden', false);
                        $('#inserirPrecosLotes').attr('hidden', true);

                        var id_fornecedor =  $('#id_fornecedor').val();

                        $('#nome_comercial').attr('readonly', true);
                        $('#apresentacao').attr('readonly', true);
                        $('#descricao').attr('readonly', true);
                        $('#slct_marcas').attr('disabled', true);
                        $('#rms').attr('readonly', true);
                        $('#ean').attr('readonly', true);
                        $('#codigo').attr('disabled', true);
                        $('#id_fornecedor').attr('disabled', true);
                        $('#unidade').attr('disabled', true);
                        $('#quantidade_unidade').attr('readonly', true);


                        $('#btnSave').attr('hidden', false);
                        $("#codigo_produto").val(xhr.codigo);

                        $('#formPrecosLotes').attr('action', $('#formPrecosLotes').attr('action') + '/' + id_fornecedor );

                    } else { formWarning(xhr); }
                },
                error: function(xhr) {
                    formWarning({ type: 'warning', message: "Erro ao salvar as informações!" });
                }
            });
        });

        $('#formPrecosLotes').on('submit', function (e) {

            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: $('#formPrecosLotes').attr('action'),
                type: 'post',
                contentType: false,
                processData: false,
                data: formData,
                beforeSend: function(jqXHR, settings) {
                },
                success: function(xhr) {
                    e.preventDefault();
                    if (xhr.type === 'success') {
                        formWarning(xhr);
                        setTimeout(function() { window.location.href = xhr.route; }, 1500);

                    } else { formWarning(xhr); }
                },
                error: function(xhr) {
                    formWarning({ type: 'warning', message: "Erro ao salvar as informações!" });
                }
            });
        });
    });

</script>
</body>