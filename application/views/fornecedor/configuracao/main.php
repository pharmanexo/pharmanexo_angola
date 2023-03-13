<!DOCTYPE html>
<html lang="pt-BR">
<?php echo $header; ?>
<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>
<div class="content">
    <?php echo $heading; ?>
    <div class="content__inner">


        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab"
                   aria-controls="nav-home" aria-selected="true">Empresa</a>
                <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab"
                   aria-controls="nav-profile" aria-selected="false">Notificações</a>
                <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" href="#nav-contact" role="tab"
                   aria-controls="nav-contact" aria-selected="false" hidden>Contact</a>
            </div>
        </nav>
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">


                <div class="text-right">
                    <button type="submit" id="btnAdicionar" data-toggle="tooltip" title="" form="formUpdateUserdata"
                            class="btn btn-primary pull-right" data-original-title="Salvar Registro">
                        <i class="fas fa-check"></i> Salvar
                    </button>
                </div>


                <form id="formUpdateUserdata" autocomplete="off" action="<?php echo $url_update; ?>" method="POST"
                      enctype="multipart/form-data">
                    <div class="row  mt-3">
                        <div class="col-12 col-lg-4 text-center">
                            <div class="card">
                                <div class="card-body">
                                    <div class="imgPreview">
                                        <img id="imgPrev"
                                             src="<?php if (isset($fornecedor['logo'])) echo $fornecedor['logo']; ?>"
                                             alt="Imagem" class="img-fluid rounded-circle w-50">
                                    </div>
                                    <label class="btn btn-outline-primary btn-block mt-3" for="imagem">
                                        <input type="file" name="imagem" id="imagem" class="d-none">
                                        Trocar Imagem
                                    </label>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">API Token</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group mb-2">
                                                <label for="">Token</label>
                                                <textarea name="" id="" readonly cols="30" rows="3" class="form-control"><?php if (isset($fornecedor['api_token']) && !empty($fornecedor['api_token'])) echo $fornecedor['api_token']; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Credencial Bionexo</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group mb-2">
                                                <label for="">Usuário</label>
                                                <input type="text" id="login" name="login" class="form-control"
                                                       value="<?php if (isset($fornecedor['credencial_bionexo']) && !empty($fornecedor['credencial_bionexo'])) echo json_decode($fornecedor['credencial_bionexo'], true)['login']; ?>">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="">Senha</label>
                                                <input type="text" id="password" name="password" class="form-control"
                                                       value="<?php if (isset($fornecedor['credencial_bionexo']) && !empty($fornecedor['credencial_bionexo'])) echo json_decode($fornecedor['credencial_bionexo'], true)['password']; ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <input type="hidden" name="id" id="id_fornecedor"
                                           value="<?php if (isset($fornecedor['id'])) echo $fornecedor['id']; ?>">
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <label for="usuarioNome">Dados da Empresa</label>
                                            <input type="text" class="form-control" readonly
                                                   value="<?php echo $fornecedor['razao_social'] ?>">
                                        </div>

                                        <div class="form-group col-12 col-lg-6">
                                            <label for="telefone_comercial">Telefone Comercial</label>
                                            <input type="text" class="form-control" id="telefoneComercial"
                                                   data-inputmask="tel" name="telefone"
                                                   value="<?php echo $fornecedor['telefone']; ?>">
                                        </div>
                                        <div class="form-group col-12 col-lg-6">
                                            <label for="email_contato">Email</label>
                                            <input type="email" class="form-control" name="email"
                                                   value="<?php echo $fornecedor['email']; ?>">
                                        </div>
                                    </div>
                                    <h3 class="text-muted">Endereço</h3>
                                    <div class="row">
                                        <div class="col-12 col-lg-3">
                                            <div class="form-group">
                                                <label for="">CEP</label>
                                                <input type="text" name="cep" id="cep"
                                                       value="<?php if (isset($fornecedor['cep'])) echo $fornecedor['cep']; ?>"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-7">
                                            <div class="form-group">
                                                <label for="">Logradouro</label>
                                                <input type="text" name="endereco" id="endereco"
                                                       value="<?php if (isset($fornecedor['endereco'])) echo $fornecedor['endereco']; ?>"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-2">
                                            <div class="form-group">
                                                <label for="">Número</label>
                                                <input type="text" name="numero" id="numero"
                                                       value="<?php if (isset($fornecedor['numero'])) echo $fornecedor['numero']; ?>"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <div class="form-group">
                                                <label for="">Bairro</label>
                                                <input type="text" name="bairro" id="bairro"
                                                       value="<?php if (isset($fornecedor['bairro'])) echo $fornecedor['bairro']; ?>"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <div class="form-group">
                                                <label for="">Cidade</label>
                                                <input type="text" name="cidade" id="cidade"
                                                       value="<?php if (isset($fornecedor['cidade'])) echo $fornecedor['cidade']; ?>"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-12 col-lg-4">
                                            <div class="form-group">
                                                <label for="">Estado</label>
                                                <input type="text" name="estado" id="estado"
                                                       value="<?php if (isset($fornecedor['estado'])) echo $fornecedor['estado']; ?>"
                                                       class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="">Complemento</label>
                                                <input type="text" name="complemento" id="complemento"
                                                       value="<?php if (isset($fornecedor['complemento'])) echo $fornecedor['complemento']; ?>"
                                                       class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
                <div class="text-right">
                    <button type="submit" id="btnAdicionar" data-toggle="tooltip" title="" form="formUpdateConfigMails"
                            class="btn btn-primary pull-right" data-original-title="Salvar Registro">
                        <i class="fas fa-check"></i> Salvar
                    </button>
                </div>

                <form id="formUpdateConfigMails" autocomplete="off" action="<?php echo $url_update_emails; ?>" method="POST"
                      enctype="multipart/form-data" >
                    <div class="row  mt-3">
                        <div class="col-12 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <p class="card-title">Alertas Gerais</p>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="email_geral"
                                               placeholder="Ex. fulana@mail.com, joao@mail.com" value="<?php if (isset($emails['email_geral'])) echo $emails['email_geral']; ?>">
                                    </div>
                                    <p class="small">Informe os e-mails seprados por vírgula (,). </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <p class="card-title">Alertas Ofertas Distribuidor X Distribuidor</p>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="distribuidor_distribuidor"
                                               placeholder="Ex. fulana@mail.com, joao@mail.com" value="<?php if (isset($emails['distribuidor_distribuidor'])) echo $emails['distribuidor_distribuidor']; ?>">
                                    </div>
                                    <p class="small">Informe os e-mails seprados por vírgula (,). </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <p class="card-title">Alertas Pedidos Resgatados</p>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="pedidos"
                                               placeholder="Ex. fulana@mail.com, joao@mail.com" value="<?php if (isset($emails['pedidos'])) echo $emails['pedidos']; ?>">
                                    </div>
                                    <p class="small">Informe os e-mails seprados por vírgula (,). </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <p class="card-title">Alertas Pedidos Representantes</p>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="representantes"
                                               placeholder="Ex. fulana@mail.com, joao@mail.com" value="<?php if (isset($emails['pedidos'])) echo $emails['pedidos']; ?>">
                                    </div>
                                    <p class="small">Informe os e-mails seprados por vírgula (,). </p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-6">
                            <div class="card">
                                <div class="card-header">
                                    <p class="card-title">Alertas de Estoque</p>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="estoque"
                                               placeholder="Ex. fulana@mail.com, joao@mail.com" value="<?php if (isset($emails['pedidos'])) echo $emails['pedidos']; ?>">
                                    </div>
                                    <p class="small">Informe os e-mails seprados por vírgula (,). </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
            <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">...</div>
        </div>


    </div>
</div>
<?php echo $scripts; ?>
<script>
    $(function () {
        $('#btnNewPassword').on('click', function (e) {
            e.preventDefault();
            let me = $(this);
            $.ajax({
                url: me.attr('href'),
                type: 'get',
                dataType: 'html',

                success: function (xhr) {
                    $('body').append(xhr);
                    $('.modal').modal({
                        keyboard: false
                    }, 'show').on('hide.bs.modal', function () {
                        $('.modal').remove();
                    });
                }
            });
        });

        $("#imagem").change(function () {
            readURL(this);
        });
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#imgPrev').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
</body>

</html>