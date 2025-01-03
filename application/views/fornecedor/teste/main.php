<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>

    <div class="card">
        <div class="card-body">

               
            <form action="<?php if(isset($url_filtros)) echo $url_filtros; ?>" method="post" id="formFiltro">
                <div class="row">
                    <div class="col-2" <?php echo ( $this->session->has_userdata('credencial_bionexo') ) ? '' : 'hidden'; ?>>
                        <div class="form-group">
                            <label for="integrador">Integrador</label>
                            <br>
                            <select class="select2" name="integrador" id="integrador" data-placeholder="Todas" data-allow-clear="true">
                                <option></option>
                                <option value="SINTESE" <?php echo ( isset($_SESSION['filtros']['integrador']) && $_SESSION['filtros']['integrador'] == 'SINTESE') ? 'selected' : ''; ?>>Sintese</option>
                                <option value="BIONEXO" <?php echo ( isset($_SESSION['filtros']['integrador']) && $_SESSION['filtros']['integrador'] == 'BIONEXO') ? 'selected' : ''; ?>>Bionexo</option>
                            </select>
                        </div>
                    </div>
                    <div class="<?php echo ( $this->session->has_userdata('credencial_bionexo') ) ? 'col-2' : 'col-4'; ?>">
                        <div class="form-group">
                            <label for="estados">Filtrar por Estado</label>
                            <select class="select2" id="estados" data-placeholder="Todas" data-allow-clear="true">
                                <option data-url="<?php echo base_url("fornecedor/cotacoes"); ?>"></option>
                                <?php foreach ($estados as $e): ?>
                                    <option data-url="<?php echo base_url("fornecedor/cotacoes?uf={$e['uf']}"); ?>" <?php echo ( isset($_GET['uf']) && $e['uf'] == strtoupper($_GET['uf']) ) ? 'selected' : ''; ?> ><?php echo $e['estado']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="cd_cotacao">Filtrar por Cotação</label>
                            <br>
                            <select class="select2" id="cd_cotacao" name="cd_cotacao" data-placeholder="Todas" data-allow-clear="true">
                                <option></option>
                                <?php foreach ($cotacoes as $cotacao): ?>
                                    <option value="<?php echo $cotacao['cd_cotacao']; ?>" <?php echo ( isset($_SESSION['filtros']['cd_cotacao']) && $_SESSION['filtros']['cd_cotacao'] == $cotacao['cd_cotacao']) ? 'selected' : ''; ?> ><?php echo $cotacao['cd_cotacao']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="id_cliente">Filtrar por Comprador</label>
                            <br>
                            <select class="select2" id="id_cliente" name="id_cliente" data-placeholder="Todas" data-allow-clear="true" data-toggle="tooltip" title="Clique para selecionar">
                                <option></option>
                                <?php foreach ($compradores as $comprador): ?>
                                    <option value="<?php echo $comprador['id']; ?>" <?php echo ( isset($_SESSION['filtros']['id_cliente']) && $_SESSION['filtros']['id_cliente'] == $comprador['id']) ? 'selected' : ''; ?> ><?php echo $comprador['comprador']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </form>

            <div class="row mb-3">
                <div class="col-12">
                    <div class="enviado" style="width: 15px; height: 15px; border-radius: 20%; border: 1px solid; display: inline-block; background-color: #C1E2FC"></div>
                    &nbsp;Respondida&nbsp;
                </div>
            </div>
            <div class="table-responsive">
                <table id="data-table" class="table table-condensend table-hover"
                       data-url="<?php echo $to_datatable; ?>"
                       data-cotacao="<?php echo $url_cotacao; ?>"
                       data-ocultar="<?php echo $url_ocultar; ?>"
                       data-info="<?php echo $url_info; ?>"
                >
                    <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Numero</th>
                        <th>Encerramento</th>
                        <th>Cliente</th>
                        <th>Estado</th>
                        <th>Itens</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <?php echo $scripts; ?>

    <script>

        var url_cotacao = $('#data-table').data('cotacao');
        var url_ocultar = $('#data-table').data('ocultar');
        var url_info = $('#data-table').data('info');

        $(function () {

            var table = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                searching: false,
                ajax: {
                    url: $('#data-table').data('url'),
                    type: 'post',
                    dataType: 'json'
                },
                order: [[9, "DESC"], [10, "ASC"]],
                columns: [
                    { defaultContent: '', orderable: false, searchable: false },
                    {name: 'cot.oferta', data: 'oferta', orderable: false, searchable: false},
                    {name: 'cot.cd_cotacao', data: 'cd_cotacao'},
                    {name: 'cot.dt_fim_cotacao', data: 'datafim', searchable: false},
                    {name: 'c.razao_social', data: 'comprador'},
                    {name: 'cot.uf_cotacao', data: 'uf_cotacao'},
                    {name: 'total_itens', data: 'total_itens', searchable: false},
                    {defaultContent: '', orderable: false, searchable: false},
                    {name: 'respondido', data: 'respondido', visible: false, searchable: false},
                    {name: 'cot.oferta', data: 'oferta', visible: false, searchable: false},
                    {name: 'cot.dt_fim_cotacao', data: 'dt_fim_cotacao', visible: false, searchable: false},
                    {name: 'cot.id_cliente', data: 'id_cliente', visible: false},
                    {name: 'cot.integrador', data: 'integrador', visible: false},
                ],
                rowCallback: function (row, data) {
                    $(row).css('cursor', 'pointer');

                    var icon = "";
                    if (data.oferta == 1) {

                        icon = '<a data-toggle="tooltip" title="Está cotação possui itens com de/para que podem ser respondidos" ><i class="fas fa-circle" style="font-size: 12px; color: #28a745" ></i></a>';
                    }

                    var integradorBtn;

                    switch (data.integrador) {
                        case 'SINTESE':
                            integradorBtn = '<a data-toggle="tooltip" title="Sintese" ><i class="fab fa-stripe-s" style="font-size: 20px"></i></a>';
                            break;
                        case 'BIONEXO':
                            integradorBtn = '<a data-toggle="tooltip" title="Bionexo" ><i class="fab fa-bootstrap" style="font-size: 20px"></i></a>';
                            break;
                    }

                    $('td:eq(0)', row).html(integradorBtn);
                    $('td:eq(1)', row).html(icon);

                    var id_aleatorio = new Date().getTime();

                    var icons = `
                        <div class="dropdown-demo mr-4">
                            <a href="#" data-toggle="dropdown" title="" id="dropdownMenuLink_${id_aleatorio}" class="dropdown-toggle position-absolute">
                                <i class="fas fa-list-ul" role="button"  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                            </a>
                            <div class="dropdown-menu">
                                <a data-action="list" data-href="${url_info}${data.integrador}/${data.cd_cotacao}/1" class="dropdown-item">Listar Produtos</a>
                                <a data-action="details" data-href="${url_info}${data.integrador}/${data.cd_cotacao}" class="dropdown-item">Dados Cotação</a>
                                <a href="${url_ocultar}${data.integrador}/${data.cd_cotacao}" data-ocultar="" class="dropdown-item">Ocultar Cotação</a>
                            </div>
                        </div>
                    `;

                    $('td:eq(7)', row).html(icons);

                    $('td:not(:eq(7))', row).each(function () {

                        $(this).on('click', function (e) {
                            e.preventDefault();

                            window.location.href = `${url_cotacao}${data.cd_cotacao}`;
                        })
                    });

                    if (data.respondido > 0) {

                        $(row).addClass('table-primary');
                    }
                },
                drawCallback: function () {
                    $('[data-toggle="tooltip"]').tooltip();
                    $('.dataTables_buttons').remove();

                    $('[data-action]').click(function (e) {
                        e.preventDefault();

                        var url = $(this).data('href');

                        newwindow = window.open(url, "dados cotação", 'height=400,width=800');
                        if (window.focus) {
                            newwindow.focus()
                        }

                        return false;
                    });

                    $('[data-ocultar]').click(function (e) {
                        e.preventDefault();

                        var url = $(this).attr('href');

                        $.ajax({
                            url: url,
                            type: 'post',
                            contentType: false,
                            processData: false,
                            data: {},
                            success: function(xhr) {

                                formWarning(xhr);
                                table.draw();
                            },
                            error: function(xhr) {}
                        });
                    });
                }
            });

            $('#id_cliente, #cd_cotacao, #integrador').on('change', function () { 

                var form = $("#formFiltro");

                console.log(form.serialize());

                $.ajax({
                    url: form.attr('action'),
                    type: 'post',
                    data: form.serialize(),
                    success: function(xhr) {

                        table.draw();    
                    },
                    error: function(xhr) {}
                });
            });

            $('#estados').on('change', function (e) {
                e.preventDefault();

                var url = $(this).children("option:selected").data('url');

                window.location.replace(url);
            });
        });
    </script>
</body>
</html>