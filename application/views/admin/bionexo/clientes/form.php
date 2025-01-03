<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner">
        <form action="<?php if (isset($formAction)) echo $formAction; ?>" method="POST" id="formComprador" enctype="multipart/form-data">

            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Dados Cadastrais</h6>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="col-6">
                            <div class="form-group">
                                <?php $default = (isset($cliente['cnpj'])) ? $cliente['cnpj'] : ''; ?>
                                <label for="cnpj">CNPJ</label>
                                <input type="text" class="form-control" name="cnpj" id="cnpj" data-inputmask="cnpj"
                                       value="<?php echo set_value('cnpj', $default, TRUE); ?>">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <?php $default = (isset($cliente['nome_fantasia'])) ? $cliente['nome_fantasia'] : ''; ?>
                                <label for="nome_fantasia">Nome Fantasia</label>
                                <input type="text" class="form-control" id="nome_fantasia" name="nome_fantasia"
                                       value="<?php echo set_value('nome_fantasia', $default, TRUE); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-6">
                            <div class="form-group">
                                <?php $default = (isset($cliente['razao_social'])) ? $cliente['razao_social'] : ''; ?>
                                <label for="razao_social">Nome/Razão Social</label>
                                <input type="text" class="form-control" name="razao_social" id="razao_social"
                                       value="<?php echo set_value('razao_social', $default, TRUE); ?>">
                            </div>
                        </div>
                        <div class="col-6"> 
                            <div class="form-group">
                                <?php $default = (isset($cliente['email'])) ? $cliente['email'] : ''; ?>
                                <label for="email">Email</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="far fa-envelope"></i></span>
                                    </div>
                                    <input type="email" class="form-control" name="email" value="<?php echo set_value('email', $default, TRUE); ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                       
                        <div class="col-6">
                            <div class="form-group">
                                <?php $default = (isset($cliente['telefone'])) ? $cliente['telefone'] : ''; ?>
                                <label for="telefone">Telefone</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-phone"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="telefone" data-inputmask="tel"value="<?php echo set_value('telefone', $default, TRUE); ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <?php $default = (isset($cliente['celular'])) ? $cliente['celular'] : ''; ?>
                                <label for="telefone">Celular</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-mobile-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="celular" data-inputmask="tel"value="<?php echo set_value('celular', $default, TRUE); ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Dados Endereço e Contato</h6>
                </div>
                <div class="card-body">
                    <div class="form-row">
                        <div class="col-3">
                            <div class="form-group">
                                <?php $default = (isset($cliente['cep'])) ? $cliente['cep'] : ''; ?>
                                <label for="cep">CEP</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="cep" id="cep" data-inputmask="cep" value="<?php echo set_value('cep', $default, TRUE); ?>">
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <a id="filtro-cep" data-toggle="tooltip" title="Buscar">
                                                <i class="fas fa-search"></i>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="form-group">
                                <?php $default = (isset($cliente['endereco'])) ? $cliente['endereco'] : ''; ?>
                                <label for="rua">Endereço</label>
                                <input type="text" class="form-control" name="endereco" id="rua"
                                       value="<?php echo set_value('endereco', $default, TRUE); ?>">
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <?php $default = (isset($cliente['numero'])) ? $cliente['numero'] : ''; ?>
                                <label for="numero">Número</label>
                                <input type="number" class="form-control" name="numero" id="numero"
                                       value="<?php echo set_value('numero', $default, TRUE); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-5">
                            <div class="form-group">
                                <?php $default = (isset($cliente['bairro'])) ? $cliente['bairro'] : ''; ?>
                                <label for="bairro">Bairro</label>
                                <input type="text" class="form-control" name="bairro" id="bairro"
                                       value="<?php echo set_value('bairro', $default, TRUE); ?>">
                            </div>
                        </div>

                        <div class="col-5">
                            <div class="form-group">
                                <?php $default = (isset($cliente['cidade'])) ? $cliente['cidade'] : ''; ?>
                                <label for="municipio">Cidade</label>
                                <input type="text" class="form-control" name="cidade" id="municipio"
                                       value="<?php echo set_value('cidade', $default, TRUE); ?>">
                            </div>
                        </div>

                        <div class="col-2">
                            <div class="form-group">
                                <?php $default = (isset($cliente['estado'])) ? $cliente['estado'] : ''; ?>
                                <label for="estado">Estado</label>
                                <input type="text" class="form-control" name="estado" id="estado"
                                       value="<?php echo set_value('estado', $default, TRUE); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-12">
                            <div class="form-group">
                                <?php $default = (isset($cliente['complemento'])) ? $cliente['complemento'] : ''; ?>
                                <label for="complemento">Complemento</label>
                                <input type="text" class="form-control" name="complemento" id="complemento"value="<?php echo set_value('complemento', $default, TRUE); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if (isset($scripts)) echo $scripts; ?>

<script>
    $(function () {

        $('#formComprador').on('submit', function(e) {
            e.preventDefault();

            var $form = $(this);

            $.ajax({
                url: $form.attr('action'),
                type: 'post',
                dataType: "json",
                data: $form.serialize(),
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
                        setTimeout(function() { window.location.href = xhr.route }, 1500);
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
</body>

</html>
