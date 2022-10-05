<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner">
        <?php if (isset($tipo_cadastro) && $tipo_cadastro == 2 && empty($rep_comissao)) { ?>
            <div class="alert alert-warning alert-dismissible fade show text-dark">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                Configure as comissões dos fornecedores na aba Configurar Comissões
            </div>
        <?php } ?>
        <form id="formRepresentante" enctype="multipart/form-data"
              method="POST"
              data-url="<?php echo $form_action; ?>"
              data-type="<?php echo $tipo_cadastro ?>"
              data-route_success="<?php echo $url_route_success; ?>"
        >
            <?php if (isset($representante) && !empty($representante)) : ?>
                <input type="hidden" name="id" value="<?php echo $representante['id']; ?>" id="id_representante">
            <?php endif; ?>
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-representante"
                       role="tab" aria-controls="nav-home" aria-selected="true">Dados Representante</a>
                    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-documentos"
                       role="tab" aria-controls="nav-profile" aria-selected="false">Documentos</a>

                    <?php if (!isset($representante['id'])) { ?>
                        <p class="nav-item nav-link " id="nav-comissao-tab" data-toggle="tooltip" title="Salve o registro para habilitar o configurar" href="" >Configurar</p>
                    <?php } else { ?>
                        <a class="nav-item nav-link" id="nav-comissao-tab"
                           href="<?php echo base_url("/fornecedor/representantes/configurar/update/{$representante['id']}"); ?>" role="tab" aria-controls="nav-comissao"
                           aria-selected="false">Configurar</a>
                    <?php } ?>


                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-representante" role="tabpanel"
                     aria-labelledby="nav-home-tab">
                    <!--Card Dados Cadastrais-->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title">Dados Cadastrais</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <?php $default = (isset($representante['cnpj'])) ? $representante['cnpj'] : ''; ?>
                                        <label for="cnpj">CNPJ</label>
                                        <input type="text" class="form-control" name="cnpj" id="cnpj"
                                               data-inputmask="cnpj"
                                               value="<?php echo set_value('cnpj', $default, TRUE); ?>">
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="form-group">
                                        <?php $default = (isset($representante['nome'])) ? $representante['nome'] : ''; ?>
                                        <label for="nome">Nome</label>
                                        <input type="text" class="form-control" id="razao_social" name="nome"
                                               value="<?php echo set_value('nome', $default, TRUE); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <?php $default = (isset($representante['email'])) ? $representante['email'] : ''; ?>
                                        <label for="email">E-mail</label>
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
                                        <?php $default = (isset($representante['telefone_comercial'])) ? $representante['telefone_comercial'] : ''; ?>
                                        <label for="telefone_comercial">Telefone Comercial</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i
                                                            class="fas fa-phone"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="telefone"
                                                   name="telefone_comercial" data-inputmask="tel"
                                                   value="<?php echo set_value('telefone_comercial', $default, TRUE); ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <?php $default = (isset($representante['telefone_celular'])) ? $representante['telefone_celular'] : ''; ?>
                                        <label for="telefone_celular">Telefone Celular</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i
                                                            class="fas fa-mobile-alt"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="celular" name="telefone_celular"
                                                   data-inputmask="tel"
                                                   value="<?php echo set_value('telefone_celular', $default, TRUE); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Card endereço -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title">Dados Endereço</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <?php $default = (isset($representante['cep'])) ? $representante['cep'] : ''; ?>
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
                                        <?php $default = (isset($representante['rua'])) ? $representante['rua'] : ''; ?>
                                        <label for="rua">Endereço</label>
                                        <input type="text" class="form-control" name="rua" id="rua"
                                               value="<?php echo set_value('rua', $default, TRUE); ?>">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <?php $default = (isset($representante['numero'])) ? $representante['numero'] : ''; ?>
                                        <label for="numero">Número</label>
                                        <input type="number" class="form-control" name="numero" id="numero"
                                               value="<?php echo set_value('numero', $default, TRUE); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-5">
                                    <div class="form-group">
                                        <?php $default = (isset($representante['bairro'])) ? $representante['bairro'] : ''; ?>
                                        <label for="bairro">Bairro</label>
                                        <input type="text" class="form-control" name="bairro" id="bairro"
                                               value="<?php echo set_value('bairro', $default, TRUE); ?>">
                                    </div>
                                </div>
                                <div class="col-5">
                                    <div class="form-group">
                                        <?php $default = (isset($representante['municipio'])) ? $representante['municipio'] : ''; ?>
                                        <label for="municipio">Municipio</label>
                                        <input type="text" class="form-control" name="municipio" id="municipio"
                                               value="<?php echo set_value('municipio', $default, TRUE); ?>">
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="form-group">
                                        <?php $default = (isset($representante['estado'])) ? $representante['estado'] : ''; ?>
                                        <label for="estado">Estado</label>
                                        <input type="text" class="form-control" name="estado" id="estado"
                                               value="<?php echo set_value('estado', $default, TRUE); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <?php $default = (isset($representante['complemento'])) ? $representante['complemento'] : ''; ?>
                                        <label for="complemento">Complemento</label>
                                        <input type="text" class="form-control" name="complemento" id="complemento"
                                               value="<?php echo set_value('complemento', $default, TRUE); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="tab-pane fade" id="nav-documentos" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <!-- Card documentos-->
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Documentos</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                        <?php $default = (isset($representante['copia_social'])) ? $representante['copia_social'] : ''; ?>
                                        <label class="btn btn-block btn-outline-secondary" for="copia_social"
                                               id="lb_copia_social">
                                            <span id="span_copia_social">Contrato Social</span>
                                            <input type="file" hidden class="form-control"
                                                   onchange="labelArquivo($('#copia_social'), $('#span_copia_social'))"
                                                   id="copia_social" name="copia_social"
                                                   value="<?php echo set_value('copia_social', $default, TRUE); ?>">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <?php $default = (isset($representante['copia_cnpj'])) ? $representante['copia_cnpj'] : ''; ?>
                                        <label class="btn btn-block btn-outline-secondary" for="copia_cnpj">
                                            <span id="span_copia_cnpj">Cartão CNPJ</span>
                                            <input type="file" hidden class="form-control"
                                                   onchange="labelArquivo($('#copia_cnpj'), $('#span_copia_cnpj'))"
                                                   id="copia_cnpj" name="copia_cnpj"
                                                   value="<?php echo set_value('copia_cnpj', $default, TRUE); ?>">
                                        </label>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <?php $default = (isset($representante['copia_id'])) ? $representante['copia_id'] : ''; ?>
                                        <label class="btn btn-block btn-outline-secondary" for="copia_id">
                                            <span id="span_copia_id">Cópia Identidade</span>
                                            <input type="file" hidden class="form-control"
                                                   onchange="labelArquivo($('#copia_id'), $('#span_copia_id'))"
                                                   id="copia_id" name="copia_id"
                                                   value="<?php echo set_value('copia_id', $default, TRUE); ?>">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php if (isset($representante['copia_social']) && !empty($representante['copia_social'])) { ?>
                            <div class="col-12 col-lg-4">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">Contrato Social</div>
                                    </div>
                                    <div class="card-body">
                                        <?php $default = (isset($representante['copia_social'])) ? $file_url . $representante['copia_social'] : null; ?>
                                        <a href="<?php echo $default ?>" class="btn btn-block btn-primary"
                                           data-toggle="tooltip" title="Visuaizar Arquivo" target="_blank"><i
                                                    class="fas fa-external-link-alt"></i></a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (isset($representante['copia_cnpj']) && !empty($representante['copia_cnpj'])) { ?>
                            <div class="col-12 col-lg-4">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">Cartão CNPJ</div>
                                    </div>
                                    <div class="card-body">
                                        <?php $default = (isset($representante['copia_cnpj'])) ? $file_url . $representante['copia_cnpj'] : null; ?>
                                        <a href="<?php echo $default ?>" class="btn btn-block btn-primary"
                                           data-toggle="tooltip" title="Visuaizar Arquivo" target="_blank"><i
                                                    class="fas fa-external-link-alt"></i></a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if (isset($representante['copia_id']) && !empty($representante['copia_id'])) { ?>
                            <div class="col-12 col-lg-4">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">Cópia Identidade</div>
                                    </div>
                                    <div class="card-body">
                                        <?php $default = (isset($representante['copia_id'])) ? $file_url . $representante['copia_id'] : null; ?>
                                        <a href="<?php echo $default ?>" class="btn btn-block btn-primary"
                                           data-toggle="tooltip" title="Visuaizar Arquivo" target="_blank"><i
                                                    class="fas fa-external-link-alt"></i></a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="tab-pane fade" id="nav-comissao" role="tabpanel" aria-labelledby="nav-comissao-tab">
                    <div class="card">
                        <div class="card-header text-right">
                            <button type="button" id="delete_comissao" class="btn btn-danger d-inline-block">Excluir
                                Selecionados
                            </button>
                            <button type="button" id="nova_comissao" class="btn btn-primary d-inline-block">Novo
                            </button>
                        </div>
                        <div class="card-body">

                            <table id="table-comissoes" class="table table-condensend table-hover w-100"
                                   data-modalcomissao="<?php echo $modal; ?>"
                                   data-delete="<?php echo $delete; ?>"
                                   data-datatable="<?php echo $datatable; ?>">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Fornecedor</th>
                                    <th>Comissão</th>
                                    <th>Criado em</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php echo $scripts; ?>

<script>

    var type = $('#formRepresentante').data('type');
    var url = $('#formRepresentante').data('url');
    var datatable = $('#table-comissoes').data('datatable');

    var url_modal = $('#table-comissoes').data('modalcomissao');
    var url_delete = $('#table-comissoes').data('delete');

    $(function () {


        $('li.select2-search').find('input').css('opacity', "0");

        $('#formRepresentante').submit(function (e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: url,
                type: 'post',
                contentType: false,
                processData: false,
                data: formData,
                beforeSend: function (jqXHR, settings) {

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
                            window.location.href = $('#formRepresentante').data('route_success');
                        }, 1500);
                    }
                },
                error: function (xhr) {
                    console.log(xhr);
                    formWarning({type: 'warning', message: "Erro ao salvar as informações!"});
                }
            })
        });



        <?php if (isset($tipo_cadastro) && $tipo_cadastro == 2) { ?>
        var table = $('#table-comissoes').DataTable({
            pageLength: 25,
            processing: false,
            responsive: true,
            serverSide: false,
            ajax: {
                url: datatable,
                type: 'post',
                dataType: 'json',
            },
            columns: [
                {defaultContent: '', width: '100px', orderable: false, searchable: false},
                {name: 'representantes_fornecedores.id', data: 'id', visible: false},
                {name: 'fornecedores.razao_social', data: 'razao_social'},
                {name: 'representantes_fornecedores.comissao', data: 'comissao'},
                {name: 'representantes_fornecedores.data_criacao', data: 'data_criacao'},
            ],
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0},
                {targets: [1], visible: false}
            ],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            order: [[1, 'asc']],
            rowCallback: function (row, data) {
                $(row).data('id', data.id).css('cursor', 'pointer');

                $('td:not(:first-child):not(:last-child)', row).each(function () {
                    $(this).on('click', function () {
                        open_modal(url_modal + '/' + data.id);
                    });
                });


            },
            drawCallback: function () {
            }
        });
        <?php } ?>

        $('#delete_comissao').click(function (e) {
            e.preventDefault();
            var elementos = [];
            var url = $();

            $.map(table.rows('.selected').data(), function (item) {
                elementos.push(item.id);
            });

            if (elementos.length > 0) {
                $.post(url_delete, {
                    el: elementos
                }, function (xhr) {
                    formWarning(xhr);
                    table.ajax.reload();
                }, 'JSON');
            } else {
                formWarning({type: 'warning', message: "Nenhum registro selecionado!"});
            }
        });

        $('#nova_comissao').on('click', function () {
            open_modal(url_modal);
        });

    });

    function validaForm() {
        if (type == 1) {

            var resp = validaPassword($('#senha').val(), $('#c_senha').val());

            if (resp != 1) {
                return false;
            } else {
                return true;
            }
        } else {

            var resp = validaPassword($('#senha').val(), $('#c_senha').val());

            if ($('#senha').val() != "" && resp != 1) {
                return false;
            } else {
                return true;
            }
        }
    }

    function open_modal(url) {
        event.preventDefault();
        $.ajax({
            url: url,
            type: 'get',
            dataType: 'html',
            success: function (response) {
                $('body').append(response);
                $('.modal').modal({
                    keyboard: false
                }, 'show').on('hide.bs.modal', function () {
                    $('#table-comissoes').DataTable().ajax.reload();
                    $('.modal').remove();
                }).on('shown.bs.modal', function () {
                    var id_representante = $('#id_representante', '#formRepresentante').val();
                    $('#id_representante', '#modalComissao').val(id_representante);
                    $('#formComissao').resetForm();
                });
            },
        })
    }

    function labelArquivo(campo, span) {
        span.text(campo.val());
    }
</script>
</body>

</html>
