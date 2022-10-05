<?php if(isset($produto['preco_unitario'])) { ?>
    <div class="modal fade" id="modalVenda" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title pull-left"><?php if (isset($title)) echo $title; ?></h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <p><strong>Produto:</strong> <?php if (isset($produto)) echo $produto['codigo'] . " - " . $produto['nome_comercial'];?></p>
                        </div>
                        <div class="col-2">
                            <p><strong>Preço Unitário</strong></p>
                            R$ <?php echo (isset($produto['preco_unitario'])) ? number_format($produto['preco_unitario'], 4, ',', '.') : number_format(0, 4, ',', '.'); ?>
                        </div>
                        <div class="col-3">
                            <p><strong>Marca</strong></p>
                            <small><?php echo $produto['marca']; ?></small>
                        </div>
                        <div class="col-2">
                            <p><strong>Validade</strong></p>
                            <?php if (isset($produto['validade'])) echo date("d/m/Y", strtotime($produto['validade'])); ?>
                        </div>
                        <div class="col-2">
                            <p><strong>Lote</strong></p>
                            <?php echo $produto['lote']; ?>
                        </div>
                        <div class="col-3">
                            <p><strong>Estoque Atual</strong></p>
                            <?php echo (isset($produto['estoque']) && isset($produto['quantidade_unidade'])) ? (intval($produto['estoque']) * intval($produto['quantidade_unidade'])) : intval($produto['estoque']); ?>
                        </div>
                    </div>
                    <hr class="my-3">
                    <form action="<?php if (isset($form_action)) echo $form_action; ?>" method="POST" id="formVenda">

                        <input type="hidden" name="lote" value="<?php echo $produto['lote']; ?>">

                        <input type="hidden" name="elementos" id="elementos">

                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="desconto">Preço Unitário c/ Desconto</label>
                                    <input type="text" class="form-control" id="preco" data-preco="<?php if(isset($produto['preco_unitario'])) echo $produto['preco_unitario']  ?>" value="<?php if(isset($produto['preco_unitario'])) echo number_format($produto['preco_unitario'], 4, ',', '.'); ?>">
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="form-group">
                                    <label for="desconto">Desconto Percentual</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="desconto_percentual" value="0,00" id="desconto" data-inputmask="money">
                                        <div class="input-group-append">
                                            <div class="input-group-text">%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-3">
                                <label for="regra_venda">Regra de Venda</label>
                                <div class="form-group">
                                    <select class="select2 w-100" style="width: 100%;" name="regra_venda" data-placeholder="Selecione" data-allow-clear="true" id="regra_venda">
                                        <option></option>
                                        <option value="0">Todos os tipos</option>
                                        <option value="1">Manual</option>
                                        <option value="2">Automático</option>
                                        <option value="3">Manual e Automático</option>
                                        <option value="4">Distribuidor x Distribuidor</option>
                                        <option value="5">Distribuidor e Manual</option>
                                        <option value="6">Distribuidor e Automático</option>
                                        <option value="7">Farma</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-3">
                                <div class="form-group">
                                    <label for="selectType">Estados ou CNPJs</label>
                                    <select class="select2 w-100" name="selectType" style="width: 100%;" data-placeholder="Selecione" data-allow-clear="true" id="selectType">
                                        <option></option>
                                        <option value="1">Estados</option>
                                        <option value="2">CNPJs</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="campoSelect" hidden>
                            <div class="row mx-auto mt-3">
                                <div class="col-12">
                                    <div class="form-group">
                                        <select multiple name="listElements" id="listElements"></select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btnSalvar" class="btn btn-primary" form="formVenda">Salvar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<script>

    var url_select = "<?php if(isset($url_select)) echo $url_select; ?>";

    $(function () {

        reloadPlugin();

        $('#regra_venda, #selectType').select2({dropdownParent: $('#modalVenda') });

        var demo = $('#listElements').bootstrapDualListbox({
            nonSelectedListLabel: 'Estados ou CNPJs',
            selectedListLabel: 'Selecionados',
            filterPlaceHolder: 'Pesquisar',
            filterTextClear: 'Exibir Todos',
            infoText: 'Exibindo todos {0} registros',
            infoTextFiltered: '<span class="label label-warning">Filtrado</span> {0} de {1}',
            infoTextEmpty: 'Lista vazia',
            preserveSelectionOnMove: 'moved',
            moveOnSelect: false,
            nonSelectedFilter: ''
        });

        $('.move').html('Selecionar Marcados').removeClass('btn-outline-secondary').addClass('btn-primary');
        $('.moveall').html('Selecionar Todos').removeClass('btn-outline-secondary').addClass('btn-secondary');
        $('.remove').html('Remover Marcados').removeClass('btn-outline-secondary').addClass('btn-primary');
        $('.removeall').html('Remover Todos').removeClass('btn-outline-secondary').addClass('btn-secondary');

        $('#listElements').on('change', function(e) {
            e.preventDefault();

            var values = [];
            $.each($("#listElements option:selected"), function() {
                values.push($(this).val());
            });

            $('#elementos').val(values.join(','));
        });

        $('#selectType').on('change', function(e) {
            e.preventDefault();

            if ( $(this).val() != '' ) {

                 $("#campoSelect").prop('hidden', false);

                $.ajax({
                    type: 'POST',
                    url: url_select + '/' + $(this).val(),
                    dataType: 'json',
                    success: function(response) {
                        $('#listElements').children().remove();

                        $.each(response, function(i, v) {
                            $('<option>').text(v.descricao).val(v.id).appendTo("#listElements");
                        });

                        $("#listElements").bootstrapDualListbox('refresh', true);
                    }
                });
            } else {

                $("#campoSelect").prop('hidden', true);
            }
        });

        $('#formVenda').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: $(this).attr("action"),
                type: 'post',
                contentType: false,
                processData: false,
                data: formData,
                beforeSend: function(jqXHR, settings) {

                    if ( $('#regra_venda').val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo regra de venda é obrigatório."});
                        return jqXHR.abort();
                    }

                    if ( $('#selectType').val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo Estados ou CNPJs é obrigatório."});
                        return jqXHR.abort();
                    }   

                    if ( $('#elementos').val() == '' ) {
                        formWarning({ type: 'warning', message: "É obrigatório selecionar pelo menos um Estado ou CNPJ"});
                        return jqXHR.abort();
                    }  
                },
                success: function(xhr) {
                    formWarning(xhr);
                    if (xhr.type == 'success') {
                        $('.modal').modal('hide');
                    }
                },
                error: function(xhr) {
                    console.log(xhr);
                    formWarning({ type: 'warning', message: "Erro ao salvar as informações!" });
                }
            })
        });

        $('#desconto').on('blur', function () {

            var preco = $('#preco').data('preco');
            var desconto = $(this).val();
            var target = $('#preco');

            desconto = desconto.replace(".","").replace(",",".");
            var resultado = eval(`${preco} - (${preco} * (${desconto} / 100) )`);

            target.val(mascaraValor(resultado.toFixed(4)));
            target.maskMoney({
                thousands: ".",
                decimal: ",",
                precision: 4
            }).maskMoney( 'mask' );
        });

        $('#preco').on('blur', function () {

            var preco_inicial = $('#preco').data('preco'); 
            var preco = $(this).val();
            var target = $('#desconto');

            preco = preco.replace(".","").replace(",",".");
           
            var subtracao = eval(`${preco_inicial} - ${preco}`);
            var divisao = eval(`${subtracao} / ${preco_inicial}`);
            var resultado = eval(`${divisao} * 100`);
        
            target.val(jsMaskMoney(resultado.toFixed(2)));
        });
    });

    function mascaraValor(valor) 
    {
        valor = valor.toString().replace(/\D/g,"");
        valor = valor.toString().replace(/(\d)(\d{8})$/,"$1.$2");
        valor = valor.toString().replace(/(\d)(\d{5})$/,"$1.$2");
        valor = valor.toString().replace(/(\d)(\d{2})$/,"$1,$2");
        return valor;                   
    }
</script>