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

                                <div class="col-6  form-group">
                                    <label>Marcas</label>
                                    <!--<select id="marca" class="form-control" name="marca">
                                    <?php /*if (isset($marcas)) { */ ?>
                                        <?php /*foreach ($marcas as $marca) { */ ?>
                                            <option <?php /*if (isset($produto['marca']) && strtolower($produto['marca']) == strtolower($marca['marca'])) echo 'selected' */ ?>value="<?php /*echo $marca['marca'] */ ?>"><?php /*echo $marca['marca'] */ ?></option>
                                        <?php /*} */ ?>
                                    <?php /*} */ ?>
                                </select>-->

                                    <select id="marca" name="marca" class="form-control"
                                            data-src="<?php echo $slct2_marca; ?>"
                                            data-value="<?php if (isset($produto['id_marca'])) echo $produto['id_marca']; ?>"
                                            style="width: 100%"></select>
                                </div>


                                <div class="col-6 form-group ">
                                    <label>Nome Comercial</label>
                                    <input type="text" id="produto" class="form-control" value="<?php if (isset($produto['nome_comercial'])) echo $produto['nome_comercial'] ?>" placeholder="" maxlength="100"
                                           name="nome_comercial">
                                </div>

                                <div class="col-6 form-group ">
                                    <label>Apresentação </label>
                                    <input type="text" id="apresentacao" class="form-control" value="<?php if (isset($produto['apresentacao'])) echo $produto['apresentacao'] ?>" placeholder="" maxlength="45"
                                           name="apresentacao">
                                </div>

                                <div class="col-3 form-group">
                                    <label>RMS</label>
                                    <input type="text" id="rms" class="form-control" value="<?php if (isset($produto['rms'])) echo $produto['rms'] ?>" placeholder="" maxlength="45"
                                           name="rms">
                                </div>

                                <div class="col-3 form-group">
                                    <label>Código</label>
                                    <input type="text" id="codigo" class="form-control" value="<?php if (isset($produto['codigo'])) echo $produto['codigo'] ?>" placeholder="" maxlength="45"
                                           name="codigo">
                                </div>

                                <div class="col-3 form-group ">
                                    <label>Campanha Promocional</label>
                                    <select class="form-control form-control " id="campanha_promocional" name="campanha_promocional">
                                        <option <?php if (isset($porcentagem_campanha) && $porcentagem_campanha == '0.00') ?> value="0.00" selected>Sem campanha</option>
                                        <option <?php if (isset($porcentagem_campanha) && $porcentagem_campanha == '0.50') ?> value="0.5">0,5%</option>
                                        <option <?php if (isset($porcentagem_campanha) && $porcentagem_campanha == '1.00') ?> value="1.00">1%</option>
                                        <option <?php if (isset($porcentagem_campanha) && $porcentagem_campanha == '2.00') ?> value="2.00">2%</option>
                                        <option <?php if (isset($porcentagem_campanha) && $porcentagem_campanha == '3.00') ?> value="3.00">3%</option>
                                        <option <?php if (isset($porcentagem_campanha) && $porcentagem_campanha == '4.00') ?> value="4.00">4%</option>
                                        <option <?php if (isset($porcentagem_campanha) && $porcentagem_campanha == '5.00') ?> value="5.00">5%</option>
                                        <option <?php if (isset($porcentagem_campanha) && $porcentagem_campanha == '6.00') ?> value="6.00">6%</option>
                                        <option <?php if (isset($porcentagem_campanha) && $porcentagem_campanha == '7.00') ?> value="7.00">7%</option>
                                        <option <?php if (isset($porcentagem_campanha) && $porcentagem_campanha == '8.00') ?> value="8.00">8%</option>
                                        <option <?php if (isset($porcentagem_campanha) && $porcentagem_campanha == '9.00') ?> value="9.00">9%</option>
                                        <option <?php if (isset($porcentagem_campanha) && $porcentagem_campanha == '10.00') ?> value="10.00">10%</option>
                                    </select>
                                </div>

                                <div class="col-3 form-group ">
                                    <label>Tipo de Venda</label>
                                    <select class="form-control form-control " id="tipo_venda" name="tipo_venda">
                                        <option value="1">Marketplace</option>
                                        <option value="2">Integranexo</option>
                                        <option value="3">Marketplace/Integranexo</option>
                                    </select>
                                </div>
                                <div class="col-3 form-group ">
                                    <label>Quantidade Miníma/Pedido</label>
                                    <input type="number" id="qtd_minima_pedido" class="form-control" placeholder=""
                                           maxlength="45"
                                           name="qtd_minima_pedido">
                                </div>

                                <div class="col-3 form-group ">
                                    <label>Unidade</label>
                                    <input type="text" id="unidade" class="form-control" placeholder="" maxlength="45"
                                           name="unidade">
                                </div>

                                <div class="col-3 form-group">
                                    <label>Quantidade por Unidade</label>
                                    <input type="number" id="qtd_unidade" class="form-control" placeholder="" maxlength="45"
                                           name="qtd_unidade">
                                </div>

                                <div class="col-3  form-group">
                                    <label>Quantidade (Estoque)</label>
                                    <input type="number" id="quantidade" class="form-control" placeholder="" maxlength="45"
                                           name="quantidade">
                                </div>

                                <div class="col-3 form-group ">
                                    <label>Validade</label>
                                    <input type="date" id="validade" class="form-control" placeholder="" maxlength="45"
                                           name="validade">
                                </div>

                                <div class="col-3 form-group ">
                                    <label>Lote</label>
                                    <input type="text" id="lote" class="form-control" placeholder="" maxlength="45"
                                           name="lote">
                                </div>

                                <div class="col-3 form-group ">
                                    <label>Valor final e de Revenda</label> <br>
                                    <input type="radio" name="v_final_revenda" id="v_final_revenda" value="0"> Iguais
                                    <input type="radio" name="v_final_revenda" id="v_final_revenda" class="ml-3" value="1"> Diferentes
                                </div>
                                <div class="col-3 form-group">
                                    <label>Aceita Contra proposta</label><br>
                                    <input type="radio" name="contra_proposta" id="contra_proposta" value="0"> Não
                                    <input type="radio" name="contra_proposta" id="contra_proposta" class="ml-3" value="1"> Sim
                                </div>

                                <div class="col-3 form-group ">
                                    <label>Venda Parcelada</label> <br>
                                    <input type="radio" name="venda_parcelada" id="venda_parcelada" value="0"> Não
                                    <input type="radio" name="venda_parcelada" id="venda_parcelada" class="ml-3" value="1"> Sim
                                    <!--<select class="form-control form-control " id="venda_parcelada" name="venda_parcelada">
                                        <option value="0">Não</option>
                                        <option value="1">Sim</option>
                                    </select>-->
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3 form-group ">
                                    <label>Preço Final</label>
                                    <input type="text" id="preco_final" class="form-control" placeholder="" maxlength="45"
                                           name="preco_final">
                                </div>

                                <div class="col-3 form-group ">
                                    <label>Preço de Revenda</label>
                                    <input type="text" id="preco_revenda" class="form-control" placeholder="" maxlength="45"
                                           name="preco_revenda">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php if (isset($produto['id'])) { ?>
        <div class="card mt-3">
            <div class="card-header">
                <h3 class="text-muted">Preços e Validades</h3>
            </div>
            <div class="card-body">

                <table id="data-table" class="table table-condensend table-hover" data-url="<?php echo $to_datatable_produtos; ?>" data-delete="<?php echo $url_delete ?>">
                    <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descrição</th>
                        <th>Marca</th>
                        <th>Estado</th>
                        <th>Preço</th>
                        <th>Estoque (unidade)</th>
                        <th>Validade</th>
                        <th>Lote</th>
                        <th></th>
                    </tr>
                    </thead>
                </table>
                <?php } else {
                    echo "<p>Salve o registro e após configure os preços.</p>";
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

        $('select, input').attr('disabled', true);

        /* Validate before submit form */
        $('#formProdutos').validate({
            ignore: [],
            onfocusout: false,
            onkeyup: false,
            rules: {
                'produto': 'required',
                'email': {
                    'required': true,
                }
            },
            messages: {
                'produto': 'Preencha a descrição do produto',
            },
            showErrors: function (a, b) {
                if (this.numberOfInvalids()) {
                    b.map(function (e) {
                        toastr.warning(e.message)
                    });
                }
            }
        });

        /* select2 marcas */
        $('#marca').select2();
        var id_marca = $('#marca').select2({
            allowClear: true,
            placeholder: 'Pesquise pela descrição',
            language: 'pt-BR',
            minimumInputLength: 6,
            ajax: {
                url: $('#marca').data('src'),
                type: 'get',
                dataType: 'json',
                delay: 250,
                cache: false,
                data: function (params) {
                    return {
                        columns: [
                            {name: 'marca', search: params.term},
                        ]
                    };
                }
            },
            escapeMarkup: function (markup) {
                return markup;
            },
            templateResult: function (d) {
                if (!d.marca) return d.text;
                return d.marca;
            },
            templateSelection: function (d) {
                if (!d.marca) return d.text;
                return d.marca;
            }
        });

        initCli(id_marca);

        /* datatables produtos precos validades */

        var dt1 = $('#data-table').DataTable({
            serverSide: true,
            lengthChange: false,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'get',
                dataType: 'json'
            },
            columns: [
                {
                    name: 'id',
                    data: 'id',
                    visible: false
                },
                {
                    name: 'produto_descricao',
                    data: 'produto_descricao'
                },
                {
                    name: 'marca',
                    data: 'marca'
                },
                {
                    name: 'estado',
                    data: 'estado'
                },
                {
                    name: 'preco_unidade',
                    data: 'preco_unidade'
                },
                {
                    name: 'quantidade',
                    data: 'quantidade'
                },
                {
                    name: 'validade',
                    data: 'validade'
                },
                {
                    name: 'lote',
                    data: 'lote'
                },
                {
                    defaultContent: '',
                    width: '100px',
                    orderable: false,
                    searchable: false
                }
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
                $('td:eq(7)', row).append(btnDelete);
            },
            drawCallback: function () {
            }
        });

    });

    function initCli(e) {
        let _id_marca = (e.data('value') !== '') ? e.data('value') : $('#marca').val();
        let _req_marca = $.ajax({
            url: e.data('src'),
            type: 'get',
            dataType: 'json',
            data: {
                columns: [{name: 'id', search: _id_marca, equal: true}]
            }
        });
        _req_marca.then(function (repo) {
            if (repo.results) {
                let items = repo.results;
                items.map(function (item) {
                    let option = $('<option selected></option>');
                    option.val(item.id).text(item.marca);
                    e.append(option);
                });
                e.val(_id_marca).trigger('change');
            }
        });
    }


    function mostra(opc) {
        if (opc.value == '1') $('#tabest').css('display', 'block'); else $('#tabest').css('display', 'none');
    }

</script>
</body>



