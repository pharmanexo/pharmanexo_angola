<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner">
        <form id="formFornecedor" enctype="multipart/form-data" data-url="<?php echo $form_action; ?>"
              data-route_success="<?php echo $url_route_success; ?>" data-type="<?php echo $tipo_cadastro ?>"
              method="POST">
            <?php if (isset($fornecedor) && !empty($fornecedor)) : ?>
                <input type="hidden" name="id" value="<?php echo $fornecedor['id']; ?>">
            <?php endif; ?>
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-fornecedor"
                       role="tab" aria-controls="nav-home" aria-selected="true">Dados Fornecedor</a>
                    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-documentos"
                       role="tab" aria-controls="nav-profile" aria-selected="false">Documentos</a>
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-fornecedor" role="tabpanel"
                     aria-labelledby="nav-home-tab">
                    <div class="row">
                        <div class="col-12 col-lg-3">
                            <div class="card">
                                <div class="card-body">

                                    <div class="form-group text-center" data-toggle="tooltip"
                                         title="Clique para alterar">
                                        <label for="logo">
                                            <img id="logo_img" src="<?php echo $src_logo ?>" alt=""
                                                 style="width: 150px; height: auto">
                                            <input type="file" hidden class="form-control" id="logo" name="logo">
                                        </label>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <div class="checkbox checkbox--inline">
                                            <?php $default = (isset($fornecedor['aprovado'])) ? $fornecedor['aprovado'] : '1'; ?>
                                            <input type="checkbox"
                                                   id="aprovado" <?php echo ($default == 1) ? "checked" : "" ?>
                                                   name="aprovado"
                                                   value="<?php echo set_value('aprovado', $default, TRUE); ?>">
                                            <label class="checkbox__label" for="aprovado">Cadastro Aprovado</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="checkbox checkbox--inline">
                                            <input type="checkbox"
                                                   id="matriz" <?php echo (isset($fornecedor['id_matriz'])) ? "checked" : "" ?>>
                                            <label class="checkbox__label" for="matriz">Fornecedor MATRIZ</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="checkbox checkbox--inline">
                                            <input type="checkbox" name="sintese" id="sintese"
                                                   value="1" <?php echo (isset($fornecedor['sintese']) && $fornecedor['sintese'] == 1) ? "checked" : "" ?>>
                                            <label class="checkbox__label" for="sintese">Integrado Sintese</label>
                                        </div>
                                    </div>

                                    <div class="text-center">
                                        <label><?php echo isset($fornecedor['data_criacao']) ? 'Registrado em: ' . date('d/m/Y', strtotime($fornecedor['data_criacao'])) : '' ?></label>
                                    </div>

                                    <hr>
                                    <?php if (empty($fornecedor['api_token'])) { ?>
                                        <a href="<?php echo $url_token; ?>" class="btn btn-block btn-primary">GERAR API TOKEN</a>
                                    <?php } else { ?>
                                        <div class="form-group">
                                            <label for="">API TOKEN</label>
                                            <input type="text" class="form-control"
                                                   value="<?php echo $fornecedor['api_token']; ?>">
                                        </div>


                                    <?php } ?>

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
                                                <?php $default = (isset($fornecedor['cnpj'])) ? $fornecedor['cnpj'] : ''; ?>
                                                <label for="cnpj">CNPJ</label>
                                                <input type="text" class="form-control is-valid" name="cnpj" id="cnpj"
                                                       data-inputmask="cnpj"
                                                       value="<?php echo set_value('cnpj', $default, TRUE); ?>">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <?php $default = (isset($fornecedor['nome_fantasia'])) ? $fornecedor['nome_fantasia'] : ''; ?>
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
                                                <?php $default = (isset($fornecedor['razao_social'])) ? $fornecedor['razao_social'] : ''; ?>
                                                <label for="razao_social">Nome/Razão Social</label>
                                                <input type="text" class="form-control is-valid" name="razao_social"
                                                       id="razao_social"
                                                       value="<?php echo set_value('razao_social', $default, TRUE); ?>">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <?php $default = (isset($fornecedor['inscricao_estadual'])) ? $fornecedor['inscricao_estadual'] : ''; ?>
                                                <label for="inscricao_estadual">Inscrição Estadual</label>
                                                <input type="text" class="form-control" id="inscricao_estadual"
                                                       name="inscricao_estadual"
                                                       value="<?php echo set_value('inscricao_estadual', $default, TRUE); ?>">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <?php $default = (isset($fornecedor['inscricao_municipal'])) ? $fornecedor['inscricao_municipal'] : ''; ?>
                                                <label for="inscricao_municipal">Inscrição Municipal</label>
                                                <input type="text" class="form-control" id="inscricao_municipal"
                                                       name="inscricao_municipal"
                                                       value="<?php echo set_value('inscricao_municipal', $default, TRUE); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <?php $default = (isset($fornecedor['protocolo_alvara'])) ? $fornecedor['protocolo_alvara'] : ''; ?>
                                                <label for="protocolo_alvara">Protocolo Alvará</label>
                                                <input type="text" class="form-control" id="protocolo_alvara"
                                                       name="protocolo_alvara"
                                                       value="<?php echo set_value('protocolo_alvara', $default, TRUE); ?>">
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <?php $default = (isset($fornecedor['validade_alvara']) && $fornecedor['validade_alvara'] != '0000-00-00 00:00:00') ? date('d/m/Y', strtotime($fornecedor['validade_alvara'])) : ''; ?>
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
                                        <div class="col-4">
                                            <div class="form-group">
                                                <?php $default = (isset($fornecedor['numero_afe'])) ? $fornecedor['numero_afe'] : ''; ?>
                                                <label for="numero_afe">Numero AFE</label>
                                                <input type="text" class="form-control" name="numero_afe"
                                                       value="<?php echo set_value('numero_afe', $default, TRUE); ?>">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <?php $default = (isset($fornecedor['usuarios_permitidos'])) ? $fornecedor['usuarios_permitidos'] : '3'; ?>
                                                <label for="usuarios_permitidos">Usuários Permitidos</label>
                                                <input type="number" any="1" min="0" class="form-control"
                                                       name="usuarios_permitidos" id="usuarios_permitidos"
                                                       value="<?php echo set_value('usuarios_permitidos', $default, TRUE); ?>">
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="form-group">
                                                <?php $default = (isset($fornecedor['permitir_cadastro_prod'])) ? $fornecedor['permitir_cadastro_prod'] : '0'; ?>
                                                <label for="usuarios_permitidos">Permitir cadastro de produtos?</label>
                                                <select class="select2" name="permitir_cadastro_prod">
                                                    <option value="1" <?php echo ($default == 1) ? 'selected' : '' ?> >
                                                        Sim
                                                    </option>
                                                    <option value="0" <?php echo ($default == 0) ? 'selected' : '' ?> >
                                                        Não
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <?php $default = (isset($fornecedor['integracao'])) ? $fornecedor['integracao'] : ''; ?>
                                                <label for="integracao">Integração</label>
                                                <select class="form-control is-valid" id="integracao" name="integracao">
                                                    <option value="">Selecione</option>
                                                    <option value="0" <?php if (isset($fornecedor['integracao']) && $fornecedor['integracao'] == '0') echo 'selected' ?> >
                                                        Não integrado e não Automatizado
                                                    </option>
                                                    <option value="1" <?php if (isset($fornecedor['integracao']) && $fornecedor['integracao'] == '1') echo 'selected' ?> >
                                                        Integrado e não Automatizado
                                                    </option>
                                                    <option value="2" <?php if (isset($fornecedor['integracao']) && $fornecedor['integracao'] == '2') echo 'selected' ?> >
                                                        Integrado e Automatizado
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <?php $default = (isset($fornecedor['id_tipo_venda'])) ? $fornecedor['id_tipo_venda'] : ''; ?>
                                                <label for="id_tipo_venda">Tipo de Venda</label>
                                                <select class="form-control is-valid" name="id_tipo_venda"
                                                        id="id_tipo_venda">
                                                    <option value="">Selecione</option>
                                                    <?php foreach ($tipos_venda as $tipo) { ?>
                                                        <option value="<?php echo $tipo['id'] ?>" <?php if (isset($fornecedor['id_tipo_venda']) && $tipo['id'] == $fornecedor['id_tipo_venda']) echo 'selected' ?> ><?php echo $tipo['descricao'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row"
                                         id="campoMatriz" <?php if (!isset($fornecedor['id_matriz'])) echo 'hidden'; ?> >
                                        <div class="col-6">
                                            <div class="form-group">

                                                <label for="integracao">Matriz</label>
                                                <select class="select2 is-valid" id="id_matriz" name="id_matriz"
                                                        data-placeholder="Selecione">
                                                    <option></option>
                                                    <?php foreach ($matrizes as $matriz): ?>
                                                        <option value="<?php echo $matriz['id']; ?>" <?php if (isset($fornecedor['id_matriz']) && $fornecedor['id_matriz'] == $matriz['id']) echo 'selected' ?> ><?php echo $matriz['nome']; ?></option>
                                                    <?php endforeach; ?>
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
                                                <?php $default = (isset($fornecedor['cep'])) ? $fornecedor['cep'] : ''; ?>
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
                                                <?php $default = (isset($fornecedor['endereco'])) ? $fornecedor['endereco'] : ''; ?>
                                                <label for="rua">Endereço</label>
                                                <input type="text" class="form-control" name="endereco" id="rua"
                                                       value="<?php echo set_value('endereco', $default, TRUE); ?>">
                                            </div>
                                        </div>

                                        <div class="col-2">
                                            <div class="form-group">
                                                <?php $default = (isset($fornecedor['numero'])) ? $fornecedor['numero'] : ''; ?>
                                                <label for="numero">Número</label>
                                                <input type="number" class="form-control" name="numero" id="numero"
                                                       value="<?php echo set_value('numero', $default, TRUE); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-5">
                                            <div class="form-group">
                                                <?php $default = (isset($fornecedor['bairro'])) ? $fornecedor['bairro'] : ''; ?>
                                                <label for="bairro">Bairro</label>
                                                <input type="text" class="form-control" name="bairro" id="bairro"
                                                       value="<?php echo set_value('bairro', $default, TRUE); ?>">
                                            </div>
                                        </div>

                                        <div class="col-5">
                                            <div class="form-group">
                                                <?php $default = (isset($fornecedor['cidade'])) ? $fornecedor['cidade'] : ''; ?>
                                                <label for="municipio">Cidade</label>
                                                <input type="text" class="form-control" name="cidade" id="municipio"
                                                       value="<?php echo set_value('cidade', $default, TRUE); ?>">
                                            </div>
                                        </div>

                                        <div class="col-2">
                                            <div class="form-group">
                                                <?php $default = (isset($fornecedor['estado'])) ? $fornecedor['estado'] : ''; ?>
                                                <label for="estado">Estado</label>
                                                <input type="text" class="form-control" name="estado" id="estado"
                                                       value="<?php echo set_value('estado', $default, TRUE); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <?php $default = (isset($fornecedor['complemento'])) ? $fornecedor['complemento'] : ''; ?>
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
                                                <?php $default = (isset($fornecedor['email'])) ? $fornecedor['email'] : ''; ?>
                                                <label for="email">Email</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i
                                                                    class="far fa-envelope"></i></span>
                                                    </div>
                                                    <input type="email" id="email" class="form-control is-valid"
                                                           name="email"
                                                           value="<?php echo set_value('email', $default, TRUE); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <?php $default = (isset($fornecedor['telefone'])) ? $fornecedor['telefone'] : ''; ?>
                                                <label for="telefone">Telefone</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-phone"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control is-valid" id="telefone"
                                                           name="telefone" data-inputmask="tel"
                                                           value="<?php echo set_value('telefone', $default, TRUE); ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <?php $default = (isset($fornecedor['celular'])) ? $fornecedor['celular'] : ''; ?>
                                                <label for="celular">Celular</label>
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="basic-addon1"><i
                                                                    class="fas fa-mobile-alt"></i></span>
                                                    </div>
                                                    <input type="text" class="form-control" id="celular" name="celular"
                                                           data-inputmask="tel"
                                                           value="<?php echo set_value('celular', $default, TRUE); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Card email e senha-->
                            <!--                            <div class="card">-->
                            <!--                                <div class="card-header">-->
                            <!--                                    <h6 class="card-title">Dados Pessoais</h6>-->
                            <!--                                </div>-->
                            <!--                                <div class="card-body">-->
                            <!--                                    <div class="form-row">-->
                            <!--                                        <div class="col-6">-->
                            <!--                                            <div class="form-group">-->
                            <!--                                                <label for="senha">Senha</label>-->
                            <!--                                                <input type="password" id="senha" class="form-control" name="senha">-->
                            <!--                                            </div>-->
                            <!--                                        </div>-->
                            <!--                                        <div class="col-6">-->
                            <!--                                            <div class="form-group">-->
                            <!--                                                <label for="c_senha">Confirmar Senha</label>-->
                            <!--                                                <input type="password" id="c_senha" class="form-control" name="c_senha">-->
                            <!--                                            </div>-->
                            <!--                                        </div>-->
                            <!--                                    </div>-->
                            <!--                                </div>-->
                            <!--                            </div>-->
                            <!--                        </div>-->
                            <!--                    </div>-->
                            <!--                </div>-->

                            <div class="tab-pane fade" id="nav-documentos" role="tabpanel"
                                 aria-labelledby="nav-profile-tab">
                                <!-- Card documentos-->
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">Documentos</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <?php $default = (isset($fornecedor['documento_alvara'])) ? $fornecedor['documento_alvara'] : ''; ?>
                                                    <label class="btn btn-block btn-outline-secondary"
                                                           for="documento_alvara"
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
                                                    <?php $default = (isset($fornecedor['cartao_cnpj'])) ? $fornecedor['cartao_cnpj'] : ''; ?>
                                                    <label class="btn btn-block btn-outline-secondary"
                                                           for="cartao_cnpj">
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
                                                    <?php $default = (isset($fornecedor['copia_afe'])) ? $fornecedor['copia_afe'] : ''; ?>
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
                                                               id="responsabilidade_tecnica"
                                                               name="responsabilidade_tecnica">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <?php if (isset($fornecedor['documento_alvara']) && !empty($fornecedor['documento_alvara'])) { ?>
                                        <div class="col-12 col-lg-3">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="card-title">Documento Alvará</div>
                                                </div>
                                                <div class="card-body">
                                                    <?php $default = (isset($fornecedor['documento_alvara'])) ? $file_url . $fornecedor['documento_alvara'] : null; ?>
                                                    <a href="<?php echo $default ?>" class="btn btn-block btn-primary"
                                                       data-toggle="tooltip" title="Visuaizar Arquivo"
                                                       target="_blank"><i
                                                                class="fas fa-external-link-alt"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if (isset($fornecedor['cartao_cnpj']) && !empty($fornecedor['cartao_cnpj'])) { ?>
                                        <div class="col-12 col-lg-3">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="card-title">Cartão CNPJ</div>
                                                </div>
                                                <div class="card-body">
                                                    <?php $default = (isset($fornecedor['cartao_cnpj'])) ? $file_url . $fornecedor['cartao_cnpj'] : null; ?>
                                                    <a href="<?php echo $default ?>" class="btn btn-block btn-primary"
                                                       data-toggle="tooltip" title="Visuaizar Arquivo"
                                                       target="_blank"><i
                                                                class="fas fa-external-link-alt"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if (isset($fornecedor['copia_afe']) && !empty($fornecedor['copia_afe'])) { ?>
                                        <div class="col-12 col-lg-3">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="card-title">Cópia AFE</div>
                                                </div>
                                                <div class="card-body">
                                                    <?php $default = (isset($fornecedor['copia_afe'])) ? $file_url . $fornecedor['copia_afe'] : null; ?>
                                                    <a href="<?php echo $default ?>" class="btn btn-block btn-primary"
                                                       data-toggle="tooltip" title="Visuaizar Arquivo"
                                                       target="_blank"><i
                                                                class="fas fa-external-link-alt"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                    <?php if (isset($fornecedor['responsabilidade_tecnica']) && !empty($fornecedor['responsabilidade_tecnica'])) { ?>
                                        <div class="col-12 col-lg-3">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="card-title">Responsabilidade Técnica</div>
                                                </div>
                                                <div class="card-body">
                                                    <?php $default = (isset($fornecedor['responsabilidade_tecnica'])) ? $file_url . $fornecedor['responsabilidade_tecnica'] : null; ?>
                                                    <a href="<?php echo $default ?>" class="btn btn-block btn-primary"
                                                       data-toggle="tooltip" title="Visuaizar Arquivo"
                                                       target="_blank"><i
                                                                class="fas fa-external-link-alt"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
        </form>
    </div>
</div>

<?php echo $scripts; ?>

<script>

    var type = $('#formFornecedor').data('type');
    var url = $('#formFornecedor').data('url');

    $(function () {
        // password_popover('#senha', '#c_senha');

        $('#formFornecedor').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: url,
                type: 'post',
                contentType: false,
                processData: false,
                data: formData,
                beforeSend: function (jqXHR, settings) {
                    // if (validaForm() == false) {
                    //     formWarning({type: 'warning', message: "Senha inválida!"});
                    //     return jqXHR.abort();
                    // }

                    if ($("#matriz").prop("checked") && $("#id_matriz").val() == '') {
                        formWarning({type: 'warning', message: "O campo matriz é obrigatório!"});
                        return jqXHR.abort();
                    }
                },
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
                            window.location.href = $('#formFornecedor').data('route_success');
                        }, 1500);
                    }
                },
                error: function (xhr) {
                    console.log(xhr);
                    formWarning({type: 'warning', message: "Erro ao salvar as informações!"});
                }
            })
        });

        $('#id_tipo_venda, #integracao').select2({
            dropdownAutoWidth: true,
            width: '100%',
            minimumResultsForSearch: Infinity
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

        $("#matriz").on('change', function () {

            if ($("#matriz").prop("checked")) {

                $('#campoMatriz').removeAttr('hidden');
                $('#id_matriz').focus();
            } else {

                $('#id_matriz').val('');
                $('#id_matriz').trigger('change');

                $('#campoMatriz').attr("hidden", true);
            }
        });

        // var senha = document.getElementById("senha");
        // var c_senha = document.getElementById("c_senha");
        //
        // senha.addEventListener('keyup', function () {
        //     checkPassword(senha.value, c_senha.value);
        // });
        // senha.addEventListener('focus', function () {
        //     checks(null, senha.value, c_senha.value);
        // });

    });

    // function validaForm() {
    //     if (type == 1) {
    //
    //         var resp = validaPassword($('#senha').val(), $('#c_senha').val());
    //
    //         if (resp != 1) {
    //             return false;
    //         } else {
    //             return true;
    //         }
    //     } else {
    //
    //         var resp = validaPassword($('#senha').val(), $('#c_senha').val());
    //
    //         if ($('#senha').val() != "" && resp != 1) {
    //             return false;
    //         } else {
    //             return true;
    //         }
    //     }
    // }

    function labelArquivo(campo, span) {
        span.text(campo.val());
    }
</script>
</body>

</html>
