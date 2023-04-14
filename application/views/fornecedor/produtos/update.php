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
            <form method="POST" id="formAtualizar" data-url="<?php if(isset($url_update)) echo $url_update ?>">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div id="novo-produto-row-0" class="row">
                                <div class="col-12 col-lg-12 form-group ">
                                    <label>Produto</label>
                                    <input type="text" id="nome_comercial" name="nome_comercial" value="<?php echo $produto['nome']; ?>" class="form-control" readonly>
                                    <!--                                    <select id="id_produto" name="id_produto" class="form-control" data-src="--><?php //if (isset($slct2_produtos)) echo $slct2_produtos; ?><!--" data-value="--><?php //if (isset($produto['id_produto'])) echo $produto['id_produto']; ?><!--" style="width: 100%"></select>-->
                                </div>

                                <div class="col-12 col-lg-6 form-group ">
                                    <label>Substância </label>
                                    <input type="text" id="apresentacao" class="form-control" value="<?php if (isset($produto['substancia'])) echo $produto['substancia'] ?> <?php if (isset($produto['dosagem'])) echo $produto['dosagem'] ?>" placeholder="" maxlength="45" name="apresentacao" readonly>
                                </div>

                                <div class="col-12 col-lg-6  form-group">
                                    <label>Marcas</label>
                                    <input type="hidden" name="id_marca" id="id_marca" value="">
                                    <input type="text" id="marca" name="marca" value="<?php if (isset($marca)) echo $marca ?>" class="form-control" readonly>
                                </div>
                                <div class="col-12 col-lg-3 form-group" hidden>
                                    <label>EAN <span class="mr-0 text-right d-inline-block" data-toggle="tooltip" title="CÓDIGO DE BARRAS"><i class="fas fa-info-circle"></i></span></label>
                                    <input type="text" id="ean" class="form-control" value="<?php if (isset($produto['ean'])) echo $produto['ean'] ?>" placeholder="" maxlength="45" name="ean" readonly>
                                </div>

                                <div class="col-12 col-lg-3 form-group" hidden>
                                    <label>RMS <span class="mr-0 text-right d-inline-block" data-toggle="tooltip" title="REGISTRO DO MINISTÉRIO DA SAÚDE"><i class="fas fa-info-circle"></i></span></label>
                                    <input type="text" id="rms" class="form-control" value="<?php if (isset($produto['rms'])) echo $produto['rms'] ?>" placeholder="" maxlength="45" name="rms" readonly>
                                </div>

                                <div class="col-12 col-lg-2 form-group">
                                    <label>Código Interno <span class="mr-0 text-right d-inline-block" data-toggle="tooltip" title="Código de identificação no fornecedor"><i class="fas fa-info-circle"></i></span></label>
                                    <input type="text" id="codigo" class="form-control" value="<?php if (isset($produto['codprod'])) echo $produto['codprod'] ?>" placeholder="" maxlength="45" name="codigo" readonly>
                                </div>

                                <div class="col-12 col-md-6 col-lg-2 form-group ">
                                    <label>Unidade</label>
                                    <input type="text" id="unidade" value="<?php if (isset($produto['unidade'])) echo $produto['unidade'] ?>" class="form-control" placeholder="" maxlength="45" name="unidade" readonly>
                                </div>

                                <div class="col-12 col-md-6 col-lg-3 form-group">
                                    <label>Quantidade na embalagem</label>
                                    <input type="number" id="qtd_unidade" value="<?php if (isset($produto['embalagem'])) echo preg_replace('/[^0-9]/', '', $produto['embalagem']) ?>" class="form-control" placeholder="" maxlength="45" name="qtd_unidade" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col text-right">
                            <button type="submit" class="btn btn-primary" id="btnAtualizar">
                                <i class="fas fa-save"></i>
                                Salvar Alterações
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col text-left">
                                <h3 class="card-title text-muted">Tabela de Preços</h3>
                            </div>
                            <div class="col text-right">
                                <a href="<?php if(isset($url_export_preco)) echo "{$url_export_preco}" ?>" id="btn_exportar_preco" data-toggle="toggle" title="Exportar Excel" class="btn btn-primary">
                                    <i class="far fa-file-excel"></i>
                                                       
                                </a>
                                <button type="button" id="novo_preco" class="btn btn-primary d-inline-block">Novo</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="dataTablePrecos" class="table table-condensend table-hover w-100" data-url="<?php echo $dtbl_precos; ?>">
                            <thead>
                            <tr>
                                <th>Estado</th>
                                <th>Preço Unitário</th>
                                <th>Data Cadastro</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col text-left">
                                <h3 class="card-title text-muted">Estoque</h3>
                            </div>
                            <div class="col text-right">
                                <a href="<?php if(isset($url_export_lote)) echo "{$url_export_lote}" ?>" data-toggle="toggle" title="Exportar Excel" id="btn_exportar_lote" class="btn btn-primary">
                                    <i class="far fa-file-excel"></i>                
                                </a>
                                <button type="button" id="novo_lote" class="btn btn-primary d-inline-block">Novo</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="dataTable" class="table table-condensend table-hover w-100" data-url="<?php echo $dtbl_lotes; ?>">
                            <thead>
                            <tr>
                                <th>Lote</th>
                                <th>Local</th>
                                <th>Estoque</th>
                                <th>Validade</th>
                                <th></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php if (isset($scripts)) echo $scripts; ?>

<script type="text/javascript">
    let id_cliente;

    var url_modal = "<?php echo $open_modal ?>";
    var url_delete = "<?php echo $url_delete ?>";


    $(function () {

        <?php if(isset($produto)) { ?>
            $('#btnSave').remove();
        <?php } ?>

        var dt = $('#dataTable').DataTable({
            serverSide: false,
            lengthChange: false,
            displayLength: 10,
            dom: 'Bfrtip',
            ajax: {
                url: $('#dataTable').data('url'),
                type: 'GET',
                dataType: 'json'
            },
            columns: [
                {name: 'lote', data: 'lote'},
                {name: 'local', data: 'local'},
                {name: 'estoque', data: 'estoque', className: 'text-center'},
                {name: 'validade', data: 'validade', className: 'text-center'},
                { defaultContent: '', width: '', orderable: false, searchable: false }
            ],
            rowCallback: function(row, data) {

                $('td', row).css('cursor', 'pointer');
                var btnDelete = $(`<a href="${url_delete}" title="Excluir" data-toggle="tooltip" class="text-danger"><i class="fas fa-trash"></i></a>`);


                btnDelete.click(function (e) {
                    e.preventDefault();

                    Swal.fire({
                      title: 'Deseja Excluir esse Registro?',
                      text: "",
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Sim'
                    }).then((result) => {
                        if (result.value) {

                            $.ajax({
                                url: btnDelete.attr('href'),
                                type: 'post',
                                dataType: 'json',
                                data: {
                                    lote: data.lote
                                },
                                success: function(response) {
                                    formWarning(response);
                                    dt.ajax.reload();
                                },
                            })
                        }
                    })
                });
               

                $('td:eq(4)', row).html(btnDelete);


                $('td:not(:first-child):not(:last-child)', row).each(function() {
                    $(this).css('cursor', 'pointer');
                    $(this).on('click', function() {
                        open_modal(url_modal + '/' + 1, new Object({ type: 2, param: data.lote}));
                    });
                });
            },
            drawCallback: function() {}
        });

        var dtp = $('#dataTablePrecos').DataTable({
            serverSide: false,
            lengthChange: false,
            displayLength: 10,
            dom: 'Bfrtip',
            ajax: {
                url: $('#dataTablePrecos').data('url'),
                type: 'GET',
                dataType: 'json'
            },
            columns: [
                {name: 'estado', data: 'estado'},
                {name: 'preco_unitario', data: 'preco_unitario'},
                {name: 'data_criacao', data: 'data_criacao', className: 'text-center'},
            ],
            rowCallback: function(row, data) {
                if (data.estado === null) {$('td:eq(0)', row).html('Todos Estados') }
            },
            drawCallback: function() {}
        });
        $('#novo_preco').on('click', function () { open_modal(url_modal + "/" , new Object({ type: 1, param: null})); });
        $('#novo_lote').on('click', function () { open_modal(url_modal, new Object({ type: 2, param: null})); });


        $('#formAtualizar').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: $('#formAtualizar').data('url'),
                type: 'post',
                contentType: false,
                processData: false,
                data: formData,
               
                success: function(xhr) {

                    formWarning(xhr);

                    if (xhr.type === 'success') {
                        setTimeout(function() { window.location.reload(); }, 1500);
                    } 
                },
                error: function(xhr) {
                    console.log(xhr);
                    formWarning({ type: 'warning', message: "Erro ao salvar as informações!" });
                }
            })
        });
    });

    function open_modal(url, data) {
        event.preventDefault();
        $.ajax({
            url: url,
            type: 'get',
            dataType: 'html',
            data: {data}, 
            success: function(response) {
                $('body').append(response);
                $('.modal').modal({
                    keyboard: false
                }, 'show').on('hide.bs.modal', function() {
                    $('#dataTable').DataTable().ajax.reload();
                    $('#dataTablePrecos').DataTable().ajax.reload();
                    $('.modal').remove();
                }).on('shown.bs.modal', function () {
                   
                    $('#formPreco').resetForm();
                    $('#formlote').resetForm();
                });
            },
        })
    }

</script>
</body>
