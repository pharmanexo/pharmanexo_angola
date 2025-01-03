<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>


<div class="content">

    <div class="alert alert-warning" role="alert" id="informativo">
        <button type="button" class="close" id="closeInforme">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="alert-heading">Informações Importantes</h4>
        <p>Foi publicado uma atualização de segurança da Síntese visando melhorar a comunicação com a
            integração, está atualização impacta diretamente o processo de envio das ofertas.</p>
        <a href="https://pharmanexo.com.br/homolog/Documentos_sintese.pdf" target="_blank" class="btn btn-secondary">Abrir
            Informativo</a>
    </div>
    <?php if (isset($_SESSION['id_fornecedor']) && $_SESSION['id_fornecedor'] == 104) { ?>
        <div class="alert alert-primary" role="alert">
            <h3 class="text-white">Informativo</h3>
            <p class="text-white">O acesso desta empresa será bloqueado em 24 horas: faça contato com o administrativo.
                <br><br>
            <p>estamos a disposição!</p>
            <br>
        </div>
    <?php } ?>
    <div class="alert alert-primary" role="alert" hidden>
        <h3 class="text-white">Informativo</h3>
        <p class="text-white">Os servidores da sintese encontram-se com instabilidade e a equipe tácnica já está atuando
            para sanar o problema, ainda não foi passado uma previsão. <br> Com essa instabilidade o envio e recebimento
            de cotações ficam prejudicados.</p>
        <br>
        <p>estamos a disposição!</p>
        <br>
    </div>


    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <div class="row mb-3" >
                <div class="col-12">
                    <ul class="nav nav-tabs pull-left">
                        <li class="nav-item">
                            <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab"
                               href="#nav-home" role="tab"
                               aria-controls="nav-home" aria-selected="true">Cotações em aberto</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="<?php echo base_url("fornecedor/ordens_compra/pendentes"); ?>">Ordens de
                                Compra Pendentes</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link"
                               href="<?php echo base_url("fornecedor/ordens_compra/resgatadas"); ?>">Ordens de
                                Compra Resgatadas</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo base_url("fornecedor/cotacoesPorProduto"); ?>">Buscar
                                cotações
                                por produto</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-header">

                    <div class="row">
                        <div class="col-2">
                            <div class="form-group">
                                <label for="integrador">Integrador</label>
                                <br>
                                <select class="form-control" id="integrador">
                                    <option value="">Todas</option>
                                    <option value="SINTESE">Sintese</option>
                                    <option value="BIONEXO">Bionexo</option>
                                    <option value="APOIO">Apoio</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="id_cliente">Filtrar por Comprador</label>
                                <br>
                                <select class="form-control" id="id_cliente" data-actions-box="true" multiple="multiple" style="width: 100px !important;"
                                        data-live-search="true" title="Selecione">
                                    <?php foreach ($compradores as $comprador): ?>
                                        <option  <?php if (isset($comprador['selected'])) echo "selected"; ?> value="<?php echo $comprador['id']; ?>"><?php echo $comprador['comprador']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="estados">Filtrar por Estado</label>
                                <br>
                                <select class="form-control" id="estados" data-actions-box="true" multiple="multiple" style="heigth: 60%"
                                        data-live-search="true" title="Selecione">
                                    <?php foreach ($estados as $estado): ?>
                                        <option <?php if (isset($estado['selected'])) echo "selected"; ?> value="<?php echo $estado['uf']; ?>"><?php echo $estado['estado']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label  for="cd_cotacao">Filtrar por Cotação</label>
                                <br>
                                <select class="form-control" multiple data-actions-box="true" id="cd_cotacao" data-placeholder="Selecione"
                                        data-allow-clear="true">
                                    <?php foreach ($cotacoes as $cotacao): ?>
                                        <option value="<?php echo $cotacao['cd_cotacao']; ?>"><?php echo $cotacao['cd_cotacao']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card-body">


                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="cot-info"
                                 style="width: 15px; height: 15px; border-radius: 20%;  display: inline-block; "></div>
                            &nbsp;Respondida&nbsp;Manual |
                            <div class="cot-success"
                                 style="width: 15px; height: 15px; border-radius: 20%;  display: inline-block; "></div>
                            &nbsp;Respondida&nbsp;Automática |
                            <div class="cot-success"
                                 style="width: 15px; height: 15px; background-color: #ffefc1 !important; border-radius: 20%;  display: inline-block; "></div>
                            &nbsp;Cotação com erro
                            <div class="cot-success"
                                 style="width: 15px; height: 15px; background-color: #dddfe2 !important; border-radius: 20%;  display: inline-block; "></div>
                            &nbsp;Cotação revisada




                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="data-table" class="table table-condensend table-hover"
                               data-url="<?php echo $to_datatable; ?>"
                               data-cotacao="<?php echo $url_cotacao; ?>"
                               data-ocultar="<?php echo $url_ocultar; ?>"
                               data-info="<?php echo $url_info; ?>"
                               data-review="<?php echo $url_review; ?>"
                        >
                            <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Numero</th>
                                <th>Encerramento</th>
                                <th>Cliente</th>
                                <th>Estado</th>
                                <!-- <th>Descrição</th> -->
                                <th>Itens</th>
                                <th>Revisada</th>
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

        </div>
    </div>
    <span class="badge pull-right badge-secondary"><?php if (isset($dt_cotacaoes)) echo "Atualizado em: " . date('d/m/Y H:i', strtotime($dt_cotacaoes)); ?></span>

    <?php echo $scripts; ?>

    <script>

        var url_cotacao = $('#data-table').data('cotacao');
        var url_ocultar = $('#data-table').data('ocultar');
        var url_info = $('#data-table').data('info');
        var url_review = $('#data-table').data('review');

        $(function () {

            <?php if (isset($alertOC) && !empty($alertOC)){ ?>

            Swal.fire(
                '<?php echo $alertOC['message']; ?>'
            )

            <?php } ?>

            const info = localStorage.getItem(`informe`);

            if (info == 'off') {
                $('#informativo').remove();
            }

            $('#closeInforme').click(function (e) {
                localStorage.setItem(`informe`, 'off');
                $('#informativo').remove();
            })


            ///Function para manter a sessão no select2
            function sessionToSelect2(id) {

                //Pegar os estados gravados da session
                const GetItens = localStorage.getItem(`DataTablesCotacoes${id}`);
                if (GetItens) {
                    $.each(GetItens.split(","), function (i, e) {
                        $(`${id} option[value='${e}']`).prop("selected", true);
                    });
                    $(id).trigger('change'); //atualiza o select2
                }
                //Atualizar Session
                $(id).on("change", function () {
                    const values = $(this).val();
                    localStorage.setItem(`DataTablesCotacoes${id}`, values);
                });
            }

            sessionToSelect2("#estados");
            sessionToSelect2("#cd_cotacao");
            sessionToSelect2("#id_cliente");


            $('.content__inner').find('[data-toggle="dropdown"]').dropdown();

            $('#estados').selectpicker();
            $('#id_cliente').selectpicker();
            $('#integrador').selectpicker();
            $('#cd_cotacao').selectpicker();

            $('li.select2-search').find('input').css('opacity', "0");

            var table = $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                searching: false,
                stateSave: true,
                buttons: [
                    {extend: "excel", className: "buttonsToHide"},
                    {extend: "pdf", className: "buttonsToHide"},
                    {extend: "print", className: "buttonsToHide"}
                ],
                "dom": 'p',
                ajax: {
                    url: $('#data-table').data('url'),
                    type: 'post',
                    dataType: 'json',
                    data: function (data) {

                        if ($('#estados').val() != '') {

                            data.columns[5].search.value = $('#estados').val().toString();
                            data.columns[5].search.type = 'or_in';
                        }

                        if ($('#cd_cotacao').val() != '') {

                            data.columns[2].search.value = $('#cd_cotacao').val().toString();
                            data.columns[2].search.type = 'in';
                        }

                        if ($('#id_cliente').val() != '') {

                            data.columns[12].search.value = $('#id_cliente').val().toString();
                            data.columns[12].search.type = 'or_in';
                        }

                        if ($("#integrador").val() != '') {

                            data.columns[13].search.value = $('#integrador').val().toString();
                            data.columns[13].search.type = 'equal';
                        }

                        return data;
                    }
                },
                order: [[11, "ASC"]],
                columns: [
                    {defaultContent: '', orderable: false, searchable: false},
                    {name: 'cot.oferta', data: 'oferta', orderable: false, searchable: false},
                    {name: 'cot.cd_cotacao', data: 'cd_cotacao'},
                    {name: 'cot.dt_fim_cotacao', data: 'datafim', searchable: false},
                    {name: 'c.razao_social', data: 'comprador'},
                    {name: 'cot.uf_cotacao', data: 'uf_cotacao'},
                    {name: 'total_itens', data: 'total_itens', searchable: false},
                    {name: 'cot.revisada', data: 'revisada', searchable: false, orderable: false,},
                    {defaultContent: '', orderable: false, searchable: false},
                    {name: 'respondido', data: 'respondido', visible: false, searchable: false},
                    {name: 'cot.oferta', data: 'oferta', visible: false, searchable: false},
                    {name: 'cot.dt_fim_cotacao', data: 'dt_fim_cotacao', visible: false, searchable: false},
                    {name: 'cot.id_cliente', data: 'id_cliente', visible: false},
                    {name: 'cot.integrador', data: 'integrador', visible: false},

                ],
                rowCallback: function (row, data) {
                    $(row).css('cursor', 'pointer');
                    var revisada = 'NÃO';

                    if (data.revisada == 1) {
                        revisada = 'SIM';
                        if (data.respondido == 0) {
                            $(row).addClass('table-secondary');
                        }

                    }

                    $('td:eq(7)', row).html(revisada);

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
                        case 'APOIO':
                            integradorBtn = '<a data-toggle="tooltip" title="Apoio" ><i class="fab fa-atlassian" style="font-size: 20px"></i></a>';
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
                                <a data-rev="review" data-integrador="${data.integrador}" data-href="${url_review}${data.cd_cotacao}" class="dropdown-item">Marcar Revisada</a>
                                <a href="${url_ocultar}/${data.id}/${data.integrador}" class="dropdown-item">Ocultar Cotação</a>
                            </div>
                        </div>
                    `;

                    $('td:eq(8)', row).html(icons);

                    $('td:not(:eq(8))', row).each(function () {

                        $(this).on('click', function (e) {
                            e.preventDefault();
                            console.log(`${url_cotacao}/${data.integrador}/${data.cd_cotacao}`);
                            window.location.href = `${url_cotacao}/${data.integrador}/${data.cd_cotacao}`;
                        })
                    });

                    if (data.respondido > 0) {


                        if (data.envios > 0 && data.total_oferta_aut > 0) {
                            icon = '<a data-toggle="tooltip" title="Houve um erro no envio automático, veja o log da cotação" ><i class="fas fa-exclamation" style="font-size: 12px; color: #990707" ></i></a>';
                            $('td:eq(1)', row).html(icon);
                            $(row).addClass('table-warning');
                        } else {
                            if (data.total_oferta_manual > 0) {
                                $(row).addClass('cot-info');
                            } else if (data.total_oferta_aut > 0) {
                                $(row).addClass('cot-success');
                            }
                        }


                    }


                },
                drawCallback: function () {
                    $('[data-toggle="tooltip"]').tooltip();



                    $('[data-action]').click(function (e) {
                        e.preventDefault();

                        var url = $(this).data('href');

                        newwindow = window.open(url, "dados cotação", 'height=400,width=800');
                        if (window.focus) {
                            newwindow.focus()
                        }

                        return false;
                    });


                    $('[data-rev]').click(function (e) {
                        e.preventDefault();

                        var url = $(this).data('href');
                        var int = $(this).data('integrador');

                        $.post(url, {status: 1, integrador: int}, function (xhr) {
                            if (xhr.type == 'success') {
                                table.draw();
                            }
                        });

                        return false;
                    });

                }
            });

            $(window).on("load", function(){
                $('.actions').remove();
            });

            $('#estados, #id_cliente, #cd_cotacao, #integrador').on('change', function () {
                table.draw();
            });
        });
    </script>
</body>
</html>
