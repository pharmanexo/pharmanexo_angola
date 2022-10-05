<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner">
        <form id="formCliente" enctype="multipart/form-data" data-url="<?php echo $form_action; ?>"
              data-route_success="<?php echo $url_route_success; ?>" data-type="<?php echo $tipo_cadastro ?>"
              method="POST">
            <?php if (isset($cliente) && !empty($cliente)) : ?>
                <input type="hidden" name="id" value="<?php echo $cliente['id']; ?>">
            <?php endif; ?>
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-cliente"
                       role="tab" aria-controls="nav-home" aria-selected="true">Dados Comprador</a>
                    <a class="nav-item nav-link" hidden id="nav-profile-tab" data-toggle="tab" href="#nav-documentos"
                       role="tab" aria-controls="nav-profile" aria-selected="false">Documentos</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">

                <div class="tab-pane fade show active" id="nav-cliente" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="row">
                        <div class="col-12 col-lg-3">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Logomarca</div>
                                </div>
                                <div class="card-body text-center py-5" >
                                    <label for="logo" data-toggle="tooltip"
                                           title="Clique para alterar">
                                        <img id="logo_img" src="<?php echo $src_logo ?>" alt=""
                                             style="width: 150px; height: auto">
                                        <input type="file" hidden class="form-control" id="logo" name="logo">
                                    </label>
                                    <div class="mt-3 text-left">
                                        <div class="form-group">
                                            <div class="checkbox checkbox--inline">
                                                <?php $default = (isset($cliente['aprovado'])) ? $cliente['aprovado'] : '1'; ?>
                                                <input type="checkbox"
                                                       id="aprovado" <?php echo ($default == 1) ? "checked" : "" ?>
                                                       name="aprovado"
                                                       value="<?php echo set_value('aprovado', $default, TRUE); ?>">
                                                <label class="checkbox__label" for="aprovado">Cadastro Aprovado</label>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <label><?php echo isset($cliente['data_criacao']) ? 'Registrado em: ' . date('d/m/Y', strtotime($cliente['data_criacao'])) : '' ?></label>
                                        </div>
                                        <?php if (isset($pharma) && $pharma == 0){ ?>
                                            <hr>
                                            <div class="text-center">
                                                <a href="<?php if (isset($url_copy)) echo $url_copy; ?>" id="copyToPharma" class="btn btn-block btn-primary">COPIAR PARA FARMA</a>
                                            </div>
                                        <?php }else{ ?>
                                            <hr>
                                            <div class="text-center">
                                                <p href="" class="text-primary"><i class="fa fa-check"></i> COMPRADOR HABILITADO PARA FARMA</p>
                                            </div>
                                            <hr>
                                            <div class="text-center">
                                                <a href="" class="btn btn-block btn-info">ENVIAR SENHA DE ACESSO</a>
                                            </div>
                                        <?php } ?>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-lg-9">
                            <!--Card Dados Cadastrais-->
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
                                                <input type="text" class="form-control" name="cnpj" id="cnpj"
                                                       data-inputmask="cnpj"
                                                       value="<?php echo set_value('cnpj', $default, TRUE); ?>">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <?php $default = (isset($cliente['nome_fantasia'])) ? $cliente['nome_fantasia'] : ''; ?>
                                                <label for="nome_fantasia">Nome Fantasia</label>
                                                <input type="text" class="form-control" id="nome_fantasia"
                                                       name="nome_fantasia"
                                                       value="<?php echo set_value('nome_fantasia', $default, TRUE); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <?php $default = (isset($cliente['razao_social'])) ? $cliente['razao_social'] : ''; ?>
                                                <label for="razao_social">Nome/Razão Social</label>
                                                <input type="text" class="form-control" name="razao_social"
                                                       id="razao_social"
                                                       value="<?php echo set_value('razao_social', $default, TRUE); ?>">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <?php $default = (isset($cliente['inscricao_estadual'])) ? $cliente['inscricao_estadual'] : ''; ?>
                                                <label for="inscricao_estadual">Inscrição Estadual</label>
                                                <input type="text" class="form-control" id="inscricao_estadual"
                                                       name="inscricao_estadual"
                                                       value="<?php echo set_value('inscricao_estadual', $default, TRUE); ?>">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <?php $default = (isset($cliente['inscricao_municipal'])) ? $cliente['inscricao_municipal'] : ''; ?>
                                                <label for="inscricao_municipal">Inscrição Municipal</label>
                                                <input type="text" class="form-control" id="inscricao_municipal"
                                                       name="inscricao_municipal"
                                                       value="<?php echo set_value('inscricao_municipal', $default, TRUE); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row" hidden>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <?php $default = (isset($cliente['protocolo_alvara'])) ? $cliente['protocolo_alvara'] : ''; ?>
                                                <label for="protocolo_alvara">Protocolo Alvará</label>
                                                <input type="text" class="form-control" id="protocolo_alvara"
                                                       name="protocolo_alvara"
                                                       value="<?php echo set_value('protocolo_alvara', $default, TRUE); ?>">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <?php $default = (isset($cliente['validade_alvara']) && $cliente['validade_alvara'] != '0000-00-00 00:00:00') ? date('d/m/Y', strtotime($cliente['validade_alvara'])) : ''; ?>
                                                <label for="validade_alvara">Validade Alvará</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text"><i
                                                                    class="far fa-calendar-check"></i></span>
                                                        <input type="text" class="form-control" name="validade_alvara"
                                                               id="validade_alvara" data-inputmask="date"
                                                               value="<?php echo set_value('validade_alvara', $default, TRUE); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                    </div>
                                    <div class="form-row" hidden>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <?php $default = (isset($cliente['motivo_recusa'])) ? $cliente['motivo_recusa'] : ''; ?>
                                                <label for="motivo_recusa">Motivo Recusa</label>
                                                <input type="text" class="form-control" id="motivo_recusa"
                                                       name="motivo_recusa"
                                                       value="<?php echo set_value('motivo_recusa', $default, TRUE); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <?php $default = (isset($cliente['numero_afe'])) ? $cliente['numero_afe'] : ''; ?>
                                                <label for="numero_afe">Numero AFE</label>
                                                <input type="text" class="form-control" name="numero_afe"
                                                       value="<?php echo set_value('numero_afe', $default, TRUE); ?>">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <?php $default = (isset($cliente['responsavel'])) ? $cliente['responsavel'] : ''; ?>
                                                <label for="responsavel">Responsável</label>
                                                <input type="text" class="form-control" name="responsavel"
                                                       value="<?php echo set_value('responsavel', $default, TRUE); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row" hidden>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <?php $default = (isset($cliente['integracao'])) ? $cliente['integracao'] : ''; ?>
                                                <label for="integracao">Integração</label>
                                                <select class="form-control" id="integracao" name="integracao">
                                                    <option value="">Selecione</option>
                                                    <option value="0" <?php if (isset($cliente['integracao']) && $cliente['integracao'] == '0') echo 'selected' ?> >
                                                        Não integrado e não Automatizado
                                                    </option>
                                                    <option value="1" <?php if (isset($cliente['integracao']) && $cliente['integracao'] == '1') echo 'selected' ?> >
                                                        Integrado e não Automatizado
                                                    </option>
                                                    <option value="2" <?php if (isset($cliente['integracao']) && $cliente['integracao'] == '2') echo 'selected' ?> >
                                                        Integrado e Automatizado
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <?php $default = (isset($cliente['id_tipo_venda'])) ? $cliente['id_tipo_venda'] : ''; ?>
                                                <label for="id_tipo_venda">Tipo de Venda</label>
                                                <select class="form-control" name="id_tipo_venda" id="id_tipo_venda">
                                                    <option value="">Selecione</option>
                                                    <?php foreach ($tipos_venda as $tipo) { ?>
                                                        <option value="<?php echo $tipo['id'] ?>" <?php if (isset($cliente['id_tipo_venda']) && $tipo['id'] == $cliente['id_tipo_venda']) echo 'selected' ?> ><?php echo $tipo['descricao'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Card endereço e contato -->
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
                                                    <input type="text" class="form-control" name="cep" id="cep"
                                                           data-inputmask="cep"
                                                           value="<?php echo set_value('cep', $default, TRUE); ?>">
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
                                                <input type="text" class="form-control" name="complemento"
                                                       id="complemento"
                                                       value="<?php echo set_value('complemento', $default, TRUE); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <?php $default = (isset($cliente['email'])) ? $cliente['email'] : ''; ?>
                                                <label for="email">Email</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i
                                                                    class="far fa-envelope"></i></span>
                                                    </div>
                                                    <input type="email" class="form-control" name="email"
                                                           value="<?php echo set_value('email', $default, TRUE); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <?php $default = (isset($cliente['telefone'])) ? $cliente['telefone'] : ''; ?>
                                                <label for="telefone">Telefone</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-phone"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" name="telefone"
                                                           data-inputmask="tel"
                                                           value="<?php echo set_value('telefone', $default, TRUE); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <?php $default = (isset($cliente['celular'])) ? $cliente['celular'] : ''; ?>
                                                <label for="telefone">Celular</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-mobile-alt"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" name="celular"
                                                           data-inputmask="tel"
                                                           value="<?php echo set_value('celular', $default, TRUE); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="nav-documentos" hidden role="tabpanel" aria-labelledby="nav-profile-tab">
                    <!-- Card documentos-->
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Documentos</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <?php $default = (isset($cliente['documento_alvara'])) ? $cliente['documento_alvara'] : ''; ?>
                                        <label class="btn btn-block btn-outline-secondary" for="documento_alvara"
                                               id="lb_documento_alvara">
                                            <span id="span_documento_alvara">Documento Alvará</span>
                                            <input type="file" hidden class="form-control"
                                                   onchange="labelArquivo($('#documento_alvara'), $('#span_documento_alvara'))"
                                                   id="documento_alvara" name="documento_alvara"
                                                   value="<?php echo set_value('documento_alvara', $default, TRUE); ?>">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <?php $default = (isset($cliente['cartao_cnpj'])) ? $cliente['cartao_cnpj'] : ''; ?>
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
                                        <?php $default = (isset($cliente['copia_afe'])) ? $cliente['copia_afe'] : ''; ?>
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
                                        <label class="btn btn-block btn-outline-secondary"
                                               for="responsabilidade_tecnica">
                                            <span id="span_responsabilidade_tecnica">Responsabilidade Técnica</span>
                                            <input type="file" hidden class="form-control"
                                                   onchange="labelArquivo($('#responsabilidade_tecnica'), $('#span_responsabilidade_tecnica'))"
                                                   id="responsabilidade_tecnica" name="responsabilidade_tecnica">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php if (isset($cliente['documento_alvara']) && !empty($cliente['documento_alvara'])) { ?>
                            <div class="col-12 col-lg-3">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">Documento Alvará</div>
                                    </div>
                                    <div class="card-body">
                                        <?php $default = (isset($cliente['documento_alvara'])) ? $file_url . $cliente['documento_alvara'] : null; ?>
                                        <a href="<?php echo $default ?>" class="btn btn-block btn-primary"
                                           data-toggle="tooltip" title="Visuaizar Arquivo" target="_blank"><i
                                                    class="fas fa-external-link-alt"></i></a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (isset($cliente['cartao_cnpj']) && !empty($cliente['cartao_cnpj'])) { ?>
                            <div class="col-12 col-lg-3">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">Cartão CNPJ</div>
                                    </div>
                                    <div class="card-body">
                                        <?php $default = (isset($cliente['cartao_cnpj'])) ? $file_url . $cliente['cartao_cnpj'] : null; ?>
                                        <a href="<?php echo $default ?>" class="btn btn-block btn-primary"
                                           data-toggle="tooltip" title="Visuaizar Arquivo" target="_blank"><i
                                                    class="fas fa-external-link-alt"></i></a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (isset($cliente['copia_afe']) && !empty($cliente['copia_afe'])) { ?>
                            <div class="col-12 col-lg-3">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">Cópia AFE</div>
                                    </div>
                                    <div class="card-body">
                                        <?php $default = (isset($cliente['copia_afe'])) ? $file_url . $cliente['copia_afe'] : null; ?>
                                        <a href="<?php echo $default ?>" class="btn btn-block btn-primary"
                                           data-toggle="tooltip" title="Visuaizar Arquivo" target="_blank"><i
                                                    class="fas fa-external-link-alt"></i></a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (isset($cliente['responsabilidade_tecnica']) && !empty($cliente['responsabilidade_tecnica'])) { ?>
                            <div class="col-12 col-lg-3">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">Responsabilidade Técnica</div>
                                    </div>
                                    <div class="card-body">
                                        <?php $default = (isset($cliente['responsabilidade_tecnica'])) ? $file_url . $cliente['responsabilidade_tecnica'] : null; ?>
                                        <a href="<?php echo $default ?>" class="btn btn-block btn-primary"
                                           data-toggle="tooltip" title="Visuaizar Arquivo" target="_blank"><i
                                                    class="fas fa-external-link-alt"></i></a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
        </form>
    </div>
</div>

<?php echo $scripts; ?>

<script>

    var type = $('#formCliente').data('type');
    var url = $('#formCliente').data('url');
    var block = '<?php echo (isset($blocked)) ? $blocked : 0; ?>';

    $(function () {

        if (block == 1){

            $('input').prop('disabled', true);

        }

        password_popover('#senha', '#c_senha');

        $('#copyToPharma').click(function (e){
            e.preventDefault();
            var url = $(this).attr('href');

            Swal.fire({
                title: 'Copiar Comprador',
                text: "Deseja habilitar este comprador para o módulo Pharma?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.get(url, function (xhr){
                        if (xhr.type == 'success'){
                            window.location = "https://pharmanexo.com.br/pharmanexo_v2/fornecedor/clientes/atualizar/" + xhr.id
                        }
                    }, 'JSON')
                }
            })

        });

        $('#cnpj').blur(function (v) {
            $.post('https://pharmanexo.com.br/pharmanexo_v2/fornecedor/clientes/checkCnpj', {cnpj: $(this).val()}, function (v) {
                if (v.encontrado == true) {

                    Swal.fire({
                        title: 'Comprador Cadastrado',
                        text: "Este comprador já está cadastrado, deseja abrir o cadastro?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sim',
                        cancelButtonText: 'Não'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location = "https://pharmanexo.com.br/pharmanexo_v2/fornecedor/clientes/atualizar/" + v.id
                        }
                    })

                }
            }, 'JSON')
        })

        $('#formCliente').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: url,
                type: 'post',
                contentType: false,
                processData: false,
                data: formData,
                success: function (xhr) {

                    if (xhr.type == 'warning') {

                        if (typeof xhr.message == 'string') {
                            xhr.message = {message: xhr.message};
                        }

                        $.each(xhr.message, function (i, v) {
                            formWarning({type: 'warning', message: v});
                        });
                    } else {
                        formWarning(xhr);
                        setTimeout(function () {
                            window.location.href = $('#formCliente').data('route_success');
                        }, 1500);
                    }
                },
                error: function (xhr) {
                    formWarning({type: 'warning', message: "Erro ao salvar as informações!"});
                }
            })
        });

        $('#id_tipo_venda, #integracao').select2({
            dropdownAutoWidth: true,
            width: '100%',
            minimumResultsForSearch: Infinity
        });
        $('#aprovado').on("change", function () {
            if ($(this).prop("checked")) {
                $(this).val(1);
            } else {
                $(this).val(0);
                $('#motivo_recusa').focus();
            }
        });
        $("#logo").change(function () {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#logo_img').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            }
        });
        $("#id_tipo_venda").change(function () {
            if ($('#id_tipo_venda').val() == 1 || $('#id_tipo_venda').val() == 3) {
                $('senha').val("");
                $('c_senha').val("");
                $('#mkt').attr('hidden', false);
            } else {
                $('#mkt').attr('hidden', true);
            }
        });
    });


    function labelArquivo(campo, span) {
        span.text(campo.val());
    }
</script>
</body>

</html>
