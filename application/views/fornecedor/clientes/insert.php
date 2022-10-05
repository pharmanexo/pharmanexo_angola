<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner">
        <form id="formCliente" action="<?php echo $form_action; ?>" method="POST" enctype="multipart/form-data">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="cliente-tab" data-toggle="tab" href="#tabCliente" role="tab"
                       aria-controls="cliente" aria-selected="true">Dados</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" id="anexo-tab" data-toggle="tab" href="#tabAnexo" role="tab"
                       aria-controls="anexo" aria-selected="false">Anexos</a>
                </li>
            </ul>

            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="tabCliente" role="tabpanel" aria-labelledby="cliente-tab">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title">Logomarca</h6>
                                </div>

                                <div class="card-body text-center py-5" data-toggle="tooltip"
                                     title="Clique para alterar">
                                    <label for="logo">
                                        <img src="<?php echo base_url('images/avatar-empresa-360sites.png') ?>" alt=""
                                             style="width: 150px; height: auto">
                                        <input type="file" hidden class="form-control" id="logo" name="logo">
                                    </label>

                                    <div class="mt-3 text-left">
                                        <div class="form-group">
                                            <div class="checkbox checkbox--inline">
                                                <input name="aprovado" type="checkbox" id="aprovado">
                                                <label class="checkbox__label" for="aprovado">Cadastro Aprovado</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title">Dados Cadastrais</h6>
                                </div>

                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="razao_social">Nome/Razão Social</label>
                                                <input type="text" class="form-control" name="razao_social"
                                                       id="razao_social">
                                            </div>
                                        </div>

                                        <div class="col-6">
                                            <div class="form-group">
                                                <label for="nome_fantasia">Nome Fantasia</label>
                                                <input type="text" class="form-control" name="nome_fantasia"
                                                       id="nome_fantasia">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="cnpj">CNPJ</label>
                                                <input type="text" class="form-control" name="cnpj"
                                                       data-inputmask="cnpj">
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="inscricao_estadual">Inscrição Estadual</label>
                                                <input type="text" class="form-control" id="inscricao_estadual"
                                                       name="inscricao_estadual">
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="inscricao_municipal">Inscrição Municipal</label>
                                                <input type="text" class="form-control" name="inscricao_municipal">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title">Dados Contato</h6>
                                </div>

                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="responsavel">Responsável</label>
                                                <input type="text" class="form-control" name="responsavel">
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control" name="email">
                                            </div>
                                        </div>

                                        <div class="col-4">
                                            <div class="form-group">
                                                <label for="telefone">Telefone</label>
                                                <input type="text" class="form-control" name="telefone"
                                                       data-inputmask="tel">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title">Dados Endereço</h6>
                                </div>

                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="cep">CEP</label>
                                                <input type="text" class="form-control" name="cep" id="cep"
                                                       data-inputmask="cep">
                                            </div>
                                        </div>

                                        <div class="col-7">
                                            <div class="form-group">
                                                <label for="endereco">Endereço</label>
                                                <input type="text" class="form-control" name="endereco" id="endereco">
                                            </div>
                                        </div>

                                        <div class="col-2">
                                            <div class="form-group">
                                                <label for="numero">Número</label>
                                                <input type="number" class="form-control" name="numero" id="numero">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-row">
                                        <div class="col-5">
                                            <div class="form-group">
                                                <label for="bairro">Bairro</label>
                                                <input type="text" class="form-control" name="bairro" id="bairro">
                                            </div>
                                        </div>

                                        <div class="col-5">
                                            <div class="form-group">
                                                <label for="cidade">Cidade</label>
                                                <input type="text" class="form-control" name="cidade" id="cidade">
                                            </div>
                                        </div>

                                        <div class="col-2">
                                            <div class="form-group">
                                                <label for="estado">Estado</label>
                                                <input type="text" class="form-control" name="estado" id="estado">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="tabAnexo" role="tabpanel" aria-labelledby="anexo-tab">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Anexos</div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="btn btn-block btn-outline-secondary" for="documento_alvara"
                                               id="lb_documento_alvara">
                                            <span id="span_documento_alvara">Documento Alvará</span>
                                            <input type="file" class="form-control" id="documento_alvara"
                                                   name="documento_alvara" hidden>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <?php $default = (isset($formData['cartao_cnpj'])) ? $formData['cartao_cnpj'] : ''; ?>
                                        <label class="btn btn-block btn-outline-secondary" for="cartao_cnpj">
                                            <span id="span_cartao_cnpj">Cartão CNPJ</span>
                                            <input type="file" hidden class="form-control"
                                                   onchange="labelArquivo($('#cartao_cnpj'), $('#span_cartao_cnpj'))"
                                                   id="cartao_cnpj" name="cartao_cnpj"
                                                   value="<?php echo set_value('cartao_cnpj', $default, TRUE); ?>">
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <?php $default = (isset($formData['copia_afe'])) ? $formData['copia_afe'] : ''; ?>
                                        <label class="btn btn-block btn-outline-secondary" for="copia_afe">
                                            <span id="span_copia_afe">Cópia AFE</span>
                                            <input type="file" hidden class="form-control"
                                                   onchange="labelArquivo($('#copia_afe'), $('#span_copia_afe'))"
                                                   id="copia_afe" name="copia_afe"
                                                   value="<?php echo set_value('copia_afe', $default, TRUE); ?>">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <?php $default = (isset($formData['responsabilidade_tecnica'])) ? $formData['responsabilidade_tecnica'] : ''; ?>
                                        <label class="btn btn-block btn-outline-secondary"
                                               for="responsabilidade_tecnica">
                                            <span id="span_responsabilidade_tecnica">Responsabilidade Técnica</span>
                                            <input type="file" hidden class="form-control"
                                                   onchange="labelArquivo($('#responsabilidade_tecnica'), $('#span_responsabilidade_tecnica'))"
                                                   id="responsabilidade_tecnica" name="responsabilidade_tecnica"
                                                   value="<?php echo set_value('responsabilidade_tecnica', $default, TRUE); ?>">
                                        </label>
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


<?php echo $scripts; ?>

<script>
    $(function () {
        function limparCamposEndereco() {
            $('#endereco').val('');
            $('#numero').val('');
            $('#bairro').val('');
            $('#cidade').val('');
            $('#estado').val('');
        }

        $('#razao_social, #nome_fantasia').keyup(function () {
            $(this).val($(this).val().toUpperCase());
        });

        $('#cnpj').blur(function (v) {
            $.post('https://pharmanexo.com.br/pharmanexo_v2/fornecedor/clientes/checkCnpj', {cnpj: $(this).val()}, function (v){

            }, 'JSON')
        })


        $('#cep').blur(function () {
            var cep = $(this).val().replace(/\D/g, '');

            if (cep !== '') {
                var validacep = /^[0-9]{8}$/;

                if (validacep.test(cep)) {
                    $("#endereco").val("...");
                    $("#bairro").val("...");
                    $("#cidade").val("...");
                    $("#estado").val("...");

                    $.getJSON('https://viacep.com.br/ws/' + cep + '/json/?callback=?', function (data) {
                        if (!('erro' in data)) {
                            $("#endereco").val(data.logradouro);
                            $("#bairro").val(data.bairro);
                            $("#cidade").val(data.localidade);
                            $("#estado").val(data.uf);
                            console.log(data);
                        } else {
                            limparCamposEndereco();
                            alert('CEP não encontrado!');
                        }
                    });
                } else {
                    limparCamposEndereco();
                    alert('Formato de CEP inválido!');
                }
            } else {
                limparCamposEndereco();
            }
        });

        $('#formCliente').on('submit', function (e) {
            e.preventDefault();

            var $form = $(this);

            $.ajax({
                type: $form.attr('method'),
                url: $form.attr('action'),
                data: $form.serialize(),
                dataType: "json",

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

                        setTimeout(function () {
                            window.location.href = response.route;
                        }, 2000);
                    }
                }
            });

            return false;
        });
    });
</script>
</body>

</html>
