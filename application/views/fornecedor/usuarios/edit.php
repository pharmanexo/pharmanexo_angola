<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>

    <div class="card">
        <div class="card-header">
            <h6 class="card-title">Dados Pessoais</h6>
        </div>
        <div class="card-body">
            <div class="content__inner">
                <form action="<?php if (isset($form_action)) echo $form_action; ?>" method="POST" id="formUsuario">
                    <input type="hidden" id="id" name="id" value="<?php if (isset($id)) echo $id; ?>">
                    <div class="row">
                        <div class="form-group col-6">
                            <label for="">Nome</label>
                            <input type="text" id="nome" name="nome" class="form-control" value="<?php if (isset($usuario['nome'])) echo $usuario['nome']; ?>" readonly style="cursor: not-allowed">
                        </div>

                        <div class="form-group col-6">
                            <label for="">E-mail</label>
                            <input type="search" id="email" name="email" class="form-control" value="<?php if (isset($usuario['email'])) echo $usuario['email']; ?>" readonly style="cursor: not-allowed">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Telefone Comercial</label>
                                <input type="text" id="telefone" name="telefone" class="form-control text-center" data-inputmask="tel" value="<?php if (isset($usuario['telefone'])) echo $usuario['telefone']; ?>">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Celular</label>
                                <input type="text" id="celular" name="celular" class="form-control text-center" data-inputmask="cel" value="<?php if (isset($usuario['celular'])) echo $usuario['celular']; ?>">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h6 class="card-title">Trocar Senha</h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Senha</label>
                        <input type="password" id="senha" name="senha" class="form-control">
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="">Confirme a senha</label>
                        <input type="password" id="c_senha" name="c_senha" class="form-control" value="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $scripts; ?>

<script>
    $(function () {

        $('#filtro-cep').click(function() {
            var cep = $('#cep').val().replace(/\D/g, '');

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
                            $('#numero').focus();
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

        $('#formUsuario').validate({
            ignore: [],
            rules: {
                cnpj: { required: true },
                razao_social: { required: true },
                nome_fantasia: { required: true },
                telefone: { required: true },
                integracao: { required : true },
                id_tipo_venda: { required : true },
                email: { required : true },
                status: { required : true }
            },
            submitHandler: function (form) {
                $(form).ajaxSubmit({
                    dataType: 'json',
                    success: function (xhr) {
                        if (xhr.status === false) {
                            $.each(xhr.message, function(i, v) {
                                formWarning({
                                    type: 'warning',
                                    message: v
                                });
                            });
                        } else {
                            formWarning({
                                type: 'success',
                                message: xhr.message
                            });

                            setTimeout(function() {
                                window.location.href = xhr.route;
                            }, 1500);
                        }
                    },

                })
            },
            showErrors: function ($map) {
                if (this.numberOfInvalids()) {
                    $.each($map, function (k, v) {
                        formWarning({
                            type: 'warning',
                            message: v
                        });
                    });
                }
            }
        });
    });

</script>
</body>

</html>
