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
                <form method="POST" action="<?php if (isset($formAction)) echo $formAction; ?>" id="formProdutos">
                    <div class="row">
                        <div class="col-12">
                            <div id="novo-produto-row-0" class="row">
                                <div class="col-12 col-lg-12 form-group ">
                                    <label>Produto</label>
                                    <input type="text" id="nome_comercial" name="nome_comercial" value="<?php echo $produto['nome_comercial']; ?>" class="form-control" readonly>
<!--                                    <select id="id_produto" name="id_produto" class="form-control" data-src="--><?php //if (isset($slct2_produtos)) echo $slct2_produtos; ?><!--" data-value="--><?php //if (isset($produto['id_produto'])) echo $produto['id_produto']; ?><!--" style="width: 100%"></select>-->
                                </div>

                                <div class="col-12 col-lg-6 form-group ">
                                    <label>Apresentação </label>
                                    <input type="text" id="apresentacao" class="form-control" value="<?php if (isset($produto['apresentacao'])) echo $produto['apresentacao'] ?>" placeholder="" maxlength="45" name="apresentacao" readonly>
                                </div>

                                <div class="col-12 col-lg-6  form-group">
                                    <label>Marcas</label>
                                    <input type="hidden" name="id_marca" id="id_marca" value="">
                                    <input type="text" id="marca" name="marca" value="<?php if (isset($marca)) echo $marca ?>" class="form-control" readonly>
                                </div>

                                <div class="col-12 col-lg-3 form-group">
                                    <label>RMS <span class="mr-0 text-right d-inline-block" data-toggle="tooltip" title="REGISTRO DO MINISTÉRIO DA SAÚDE"><i class="fas fa-info-circle"></i></span></label>
                                    <input type="text" id="rms" class="form-control" value="<?php if (isset($produto['rms'])) echo $produto['rms'] ?>" placeholder="" maxlength="45" name="rms" readonly>
                                </div>

                                <div class="col-12 col-lg-3 form-group">
                                    <label>Código Interno <span class="mr-0 text-right d-inline-block" data-toggle="tooltip" title="Código de identificação no fornecedor"><i class="fas fa-info-circle"></i></span></label>
                                    <input type="text" id="codigo" class="form-control" value="<?php if (isset($produto['codigo'])) echo $produto['codigo'] ?>" placeholder="" maxlength="45" name="codigo" readonly>
                                </div>

                                <div class="col-12 col-md-6 col-lg-3 form-group ">
                                    <label>Unidade</label>
                                    <input type="text" id="unidade"  value="<?php if (isset($produto['unidade'])) echo $produto['unidade'] ?>"  class="form-control" placeholder="" maxlength="45" name="unidade" readonly>
                                </div>

                                <div class="col-12 col-md-6 col-lg-3 form-group">
                                    <label>Quantidade por Unidade</label>
                                    <input type="number" id="qtd_unidade"  value="<?php if (isset($produto['quantidade_unidade'])) echo $produto['quantidade_unidade'] ?>"  class="form-control" placeholder="" maxlength="45" name="qtd_unidade" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (!isset($produto['id'])) { ?>
                        <div id="produto_fields">
                            <div class="form-row">
                                <div class="col"><label for="estados">Estado</label></div>
                                <div class="col"><label for="validades">Validade</label></div>
                                <div class="col"><label for="estoques">Estoque</label></div>
                                <div class="col"><label for="precos_venda">Preço Venda</label></div>
                                <div class="col"><label for="precos_revenda">Preço Revenda</label></div>
                            </div>

                            <div id="formRows">
                                <div class="form-row">
                                    <div class="col">
                                        <select class="form-control" name="estados">
                                            <?php foreach ($estados as $row) : ?>
                                                <option value="<?php echo $row['id']; ?>"><?php echo $row['descricao']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col">
                                        <input type="text" class="form-control text-center" name="validades[]" data-inputmask="date" placeholder="##/##/####">
                                    </div>

                                    <div class="col">
                                        <input type="number" class="form-control" name="estoques[]" placeholder="0">
                                    </div>

                                    <div class="col">
                                        <input type="text" class="form-control" name="precos_venda[]" data-inputmask="money" placeholder="0,00">
                                    </div>

                                    <div class="col">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control text-right" name="precos_revenda[]" data-inputmask="money" placeholder="0,00">
                                            <div class="input-group-append">
                                                <button type="button" class="btn btn-primary" id="btn-plus">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                </form>
            </div>
            <?php if (!isset($produto['id'])) { ?>
                <div class="card-footer">
                    <p class='text-info'>O cadastro de produtos passam por uma aprovação, somente após confirmado o cadastro que o produto estará disponível para configurar preços e estoque.</p>
                </div>
            <?php } ?>
        </div>
        <?php if (isset($produto['id'])) { ?>
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="text-muted">ESTOQUE</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="form-group">
                            <label for="">Filtro por estado</label>
                            <select id="idEstado" type="text" data-index="4" class="form-control">
                                <option  value="">Selecione... </option>
                                <option value="1">Acre</option>
                                <option value="2">Alagoas</option>
                                <option value="3">Amapá</option>
                                <option value="4">Amazonas</option>
                                <option value="5">Bahia</option>
                                <option value="6">Ceará</option>
                                <option value="7">Distrito Federal</option>
                                <option value="8">Espírito Santo</option>
                                <option value="9">Goiás</option>
                                <option value="10">Maranhão</option>
                                <option value="11">Mato Grosso</option>
                                <option value="12">Mato Grosso do Sul</option>
                                <option value="13">Minas Gerais</option>
                                <option value="14">Pará</option>
                                <option value="15">Paraíba</option>
                                <option value="16">Paraná</option>
                                <option value="17">Pernambuco</option>
                                <option value="18">Piauí</option>
                                <option value="19">Rio de Janeiro</option>
                                <option value="20">Rio Grande do Norte</option>
                                <option value="21">Rio Grande do Sul</option>
                                <option value="22">Rondônia</option>
                                <option value="23">Roraima</option>
                                <option value="24">Santa Catarina</option>
                                <option value="25">São Paulo</option>
                                <option value="26">Sergipe</option>
                                <option value="27">Tocantins</option>
                            </select>
                        </div>
                    </div>
                </div>
                <table id="data-table" class="table table-condensend table-hover" data-url="<?php echo $to_datatable_produtos; ?>" data-delete="<?php echo $url_delete ?>">
                    <thead>
                    <tr>
                        <th>Marca</th>
                        <th>Estado</th>
                        <th>Preço Ref. Estado</th>
                        <th>Estoque (unidade)</th>
                        <th>ID Estado</th>
                        <th>Lote</th>
                    </tr>
                    </thead>
                </table>
                <?php } else {
                    echo "";
                } ?>
            </div>
        </div>
    </div>
</div>

<?php if (isset($scripts)) echo $scripts; ?>

<script type="text/javascript">
    let id_cliente;
    var url_delete = $('#data-table').data('delete');

    $(function () {
        var $form = $('#formProdutos');

        $('#formProdutos').on('submit', function (e) {
            e.preventDefault();

            $.ajax({
                type: $form.attr('method'),
                url: $form.attr('action'),
                data: $form.serialize(),
                dataType: "json",

                success: function (response) {
                    alert(response.status);
                }
            });

            return false;
        });

        /* select2 marcas */
        $('#id_produto').select2();
        var id_marca = $('#id_produto').select2({
            allowClear: true,
            placeholder: 'Pesquise pela descrição',
            language: 'pt-BR',
            minimumInputLength: 6,
            "language": {
                "noResults": function () {
                    return "<p>Não encontramos o produto solicitado.</p> <a href='<?php if (isset($url_new_prod)) echo $url_new_prod; ?>' class='btn btn-block btn-secondary'>Solicite o Cadastro</a>";
                }
            },
            ajax: {
                url: $('#id_produto').data('src'),
                type: 'get',
                dataType: 'json',
                delay: 250,
                cache: false,
                data: function (params) {
                    return {
                        columns: [{
                            name: 'produto_descricao',
                            search: params.term
                        },],
                        page: params.page || 1
                    };
                }
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            templateResult: function (d) {
                if (!d.produto_descricao) return d.text;
                return `${d.produto_descricao} <br><small>Marca: ${d.marca}</small>`;
            },
            templateSelection: function (d) {
                if (!d.produto_descricao) return d.text;
                $('#marca').val(d.marca).attr('readonly', true);
                $('#apresentacao').val(d.apresentacao).attr('readonly', true);
                $('#id_marca').val(d.id_marca).attr('readonly', true);
                return d.produto_descricao;
            }
        });

        /* datatables produtos precos validades */

        var buttonCommon = {
            exportOptions: {
                format: {
                    body: function ( data, row, column, node ) {
                        return column === 2 ? data.replace( /[.]/g, '' ).replace( /[,]/g, '.' ) : data;
                    }
                }
            }
        };

        var dt1 = $('#data-table').DataTable({
            serverSide: true,
            lengthChange: false,
            dom: 'Bfrtip',
            buttons: [ $.extend( true, {}, buttonCommon, { extend: 'excelHtml5'} ) ],
            ajax: {
                url: $('#data-table').data('url'),
                type: 'get',
                dataType: 'json',
                data: function(data) {
                    let nw_data = data;

                    if ($('#idEstado').val() !== '') {
                        nw_data.columns[4].search.value = $('#idEstado').val();
                        nw_data.columns[4].search.type = 'equal';
                    }

                    return nw_data;
                }
            },
            columns: [
                {name: 'marca', data: 'marca'},
                {name: 'estado', data: 'estado'},
                {name: 'preco_unidade', data: 'preco_unidade'},
                {name: 'estoque_unitario', data: 'estoque_unitario'},
                {name: 'id_estado', data: 'id_estado', visible: false },
                {name: 'lote', data: 'lote' },
            ],
            rowCallback: function (row, data) {
                $(row).data('id', data.id).css('cursor', 'pointer');
                var btnDelete = $(`<a href="${url_delete}${data.id}" class="text-danger"><i class="fas fa-trash"></i></a>`);
                // Confirma remoção de registro
                btnDelete.showConfirm({
                    title: 'Deseja Excluir esse Registro?',
                    closeOnConfirm: true,
                    ajax: {
                        type: 'post',
                        url: btnDelete.attr('href'),
                        dataType: 'json',
                        success: function (xhr) {
                            formWarning(xhr);
                            $('#data-table').DataTable().ajax.reload();
                        }
                    }
                });
            },
            drawCallback: function () {
            }
        });

        $('#btn-plus').click(function (e) {
            e.preventDefault();
            var formSelect = $('#formRows').find('select');
            var selected = formSelect.find(':selected');
            var elements = $('#formRows').clone();

            elements.find('select').val(selected.val()).attr('readonly', true);
            elements.find('input').attr('readonly', true);
            elements.find('select option:not(:selected)').remove();

            $('#formRows').find('input').val('');

            elements.find('.btn').html('<i class="fas fa-minus"></i>').addClass('btn-danger').click(function (e) {
                var elemetSelected = elements.find('select').find(':selected');
                var option = $('<option></option>');
                option.val(elemetSelected.val());
                option.text(elemetSelected.text());

                formSelect.append(option);

                formSelect.html($("option", formSelect).sort(function (a, b) {
                    return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
                }));

                formSelect.find('option:first-child').attr('selected', true);

                elements.remove();
            });

            $('#produto_fields').append(elements);
            selected.remove();
        });


        $('[data-index]').on('keyup change', function() {
            var col = $(this).data('index');
            var value = $(this).val();

            dt1.columns(col).search(value).draw();
        });

        // remove filter
        $('[data-action="reset-filter"]').click(function(e) {
            e.preventDefault();
            $('[data-index]').val(null);
            $('#data-table').columns([0, 1, 2, 4]).search('').draw();
        });

    });



    function mostra(opc) {
        if (opc.value == '1') $('#tabest').css('display', 'block');
        else $('#tabest').css('display', 'none');
    }

    function ordenarSelect() {
        $("#select").html($("option", $("#select")).sort(function (a, b) {
            return a.text == b.text ? 0 : a.text < b.text ? -1 : 1;
        }));
    }
</script>
</body>