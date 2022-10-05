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
                <?php $value = (isset($formData['id'])) ? $formData['id'] : ''; ?>
                <input type="hidden" name="id" value="<?php echo $value; ?>">

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="form-tab" data-toggle="tab" href="#tabForm" role="tab" aria-controls="form" aria-selected="true">Dados</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" id="file-tab" data-toggle="tab" href="#tabFile" role="tab" aria-controls="file" aria-selected="true">Anexos</a>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="tabForm" role="tabpanel" aria-labelledby="form-tab">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">Dados Cadastrais</h6>
                            </div>

                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <?php $value = (isset($formData['razao_social'])) ? $formData['razao_social'] : ''; ?>
                                            <label for="razao_social">Nome/Razão Social</label>
                                            <input type="text" class="form-control" name="razao_social" id="razao_social" value="<?php echo $value; ?>">
                                        </div>
                                    </div>

                                    <div class="col-6">
                                        <div class="form-group">
                                            <?php $value = (isset($formData['nome_fantasia'])) ? $formData['nome_fantasia'] : ''; ?>
                                            <label for="nome_fantasia">Nome Fantasia</label>
                                            <input type="text" class="form-control" name="nome_fantasia" id="nome_fantasia" value="<?php echo $value; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <?php $value = (isset($formData['cnpj'])) ? $formData['cnpj'] : ''; ?>
                                            <label for="cnpj">CNPJ</label>
                                            <input type="text" class="form-control" name="cnpj" data-inputmask="cnpj" value="<?php echo $value; ?>">
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="form-group">
                                            <?php $value = (isset($formData['inscricao_estadual'])) ? $formData['inscricao_estadual'] : ''; ?>
                                            <label for="inscricao_estadual">Inscrição Estadual</label>
                                            <input type="text" class="form-control" id="inscricao_estadual" name="inscricao_estadual" value="<?php echo $value; ?>">
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="form-group">
                                            <?php $value = (isset($formData['inscricao_municipal'])) ? $formData['inscricao_municipal'] : ''; ?>
                                            <label for="inscricao_municipal">Inscrição Municipal</label>
                                            <input type="text" class="form-control" name="inscricao_municipal" value="<?php echo $value; ?>">
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
                                            <?php $value = (isset($formData['responsavel'])) ? $formData['responsavel'] : ''; ?>
                                            <label for="responsavel">Responsável</label>
                                            <input type="text" class="form-control" name="responsavel" value="<?php echo $value; ?>">
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="form-group">
                                            <?php $value = (isset($formData['email'])) ? $formData['email'] : ''; ?>
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" name="email" value="<?php echo $value; ?>">
                                        </div>
                                    </div>

                                    <div class="col-4">
                                        <div class="form-group">
                                            <?php $value = (isset($formData['telefone'])) ? $formData['telefone'] : ''; ?>
                                            <label for="telefone">Telefone</label>
                                            <input type="text" class="form-control" name="telefone" data-inputmask="tel" value="<?php echo $value; ?>">
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
                                            <?php $value = (isset($formData['cep'])) ? $formData['cep'] : ''; ?>
                                            <label for="cep">CEP</label>
                                            <input type="text" class="form-control" name="cep" id="cep" data-inputmask="cep" value="<?php echo $value; ?>">
                                        </div>
                                    </div>

                                    <div class="col-7">
                                        <div class="form-group">
                                            <?php $value = (isset($formData['endereco'])) ? $formData['endereco'] : ''; ?>
                                            <label for="endereco">Endereço</label>
                                            <input type="text" class="form-control" name="endereco" id="endereco" value="<?php echo $value; ?>">
                                        </div>
                                    </div>

                                    <div class="col-2">
                                        <div class="form-group">
                                            <?php $value = (isset($formData['numero'])) ? $formData['numero'] : ''; ?>
                                            <label for="numero">Número</label>
                                            <input type="number" class="form-control" name="numero" id="numero" value="<?php echo $value; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="col-5">
                                        <div class="form-group">
                                            <?php $value = (isset($formData['bairro'])) ? $formData['bairro'] : ''; ?>
                                            <label for="bairro">Bairro</label>
                                            <input type="text" class="form-control" name="bairro" id="bairro" value="<?php echo $value; ?>">
                                        </div>
                                    </div>

                                    <div class="col-5">
                                        <div class="form-group">
                                            <?php $value = (isset($formData['cidade'])) ? $formData['cidade'] : ''; ?>
                                            <label for="cidade">Cidade</label>
                                            <input type="text" class="form-control" name="cidade" id="cidade" value="<?php echo $value; ?>">
                                        </div>
                                    </div>

                                    <div class="col-2">
                                        <div class="form-group">
                                            <?php $value = (isset($formData['estado'])) ? $formData['estado'] : ''; ?>
                                            <label for="estado">Estado</label>
                                            <input type="text" class="form-control" name="estado" id="estado" value="<?php echo $value; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="tabFile" role="tabpanel" aria-labelledby="file-tab">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">Anexos</h6>
                            </div>

                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col-12">
                                        <div class="dz-default dz-message"><span>Drop files here to upload</span></div>
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
        $(function() {
            function limparCamposEndereco() {
                $('#endereco').val('');
                $('#numero').val('');
                $('#bairro').val('');
                $('#cidade').val('');
                $('#estado').val('');
            }

            $('#razao_social, #nome_fantasia').keyup(function() {
                $(this).val($(this).val().toUpperCase());
            });

            $('#cep').blur(function() {
                var cep = $(this).val().replace(/\D/g, '');

                if (cep !== '') {
                    var validacep = /^[0-9]{8}$/;

                    if (validacep.test(cep)) {
                        $("#endereco").val("...");
                        $("#bairro").val("...");
                        $("#cidade").val("...");
                        $("#estado").val("...");

                        $.getJSON('https://viacep.com.br/ws/' + cep + '/json/?callback=?', function(data) {
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

            $('#formCliente').on('submit', function(e) {
                e.preventDefault();

                var $form = $(this);

                $.ajax({
                    type: $form.attr('method'),
                    url: $form.attr('action'),
                    data: $form.serialize(),
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
                            formWarning({
                                type: 'success',
                                message: response.message
                            });

                            setTimeout(function() {
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
