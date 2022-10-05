<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>
    <div class="content__inner">
        <form id="frmBusca" action="<?php if (isset($urlGetChamado)) echo $urlGetChamado; ?>" method="post" enctype="multipart/form-data">
            <div class="row">
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label for="">Data Início</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="zmdi zmdi-calendar"></i></span>
                            </div>
                            <input type="date" class="form-control hidden-md-up" placeholder="Selecione uma data">
                            <input type="text" id="dataini" name="dataini" class="form-control date-picker hidden-sm-down flatpickr-input active" placeholder="Selecione" readonly="readonly">
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-group">
                        <label for="">Data Fim</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="zmdi zmdi-calendar"></i></span>
                            </div>
                            <input type="date" class="form-control hidden-md-up" placeholder="Selecione uma data">
                            <input type="text" id="datafim" name="datafim" class="form-control date-picker hidden-sm-down flatpickr-input active" placeholder="Selecione" readonly="readonly">
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-4">
                    <div class="form-grop">
                        <label for="">Situação</label>
                        <select name="id_status" id="id_status" class="form-control">
                            <option value="">Todos</option>
                            <option value="1">Em aberto</option>
                            <option value="2">Em andamento</option>
                            <option value="3">Pendente</option>
                            <option value="4">Concluido</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>
        <hr>

        <div class="list"></div>


    </div>
    <?php echo $scripts; ?>

    <script>
        $(function () {

            $("#dataini").flatpickr({ "locale": "pt", "dateFormat": "d/m/Y", 'defaultDate': "<?php echo date('01/m/Y'); ?>" });
            $("#datafim").flatpickr({ "locale": "pt", "dateFormat": "d/m/Y", 'defaultDate': "<?php echo date('t/m/Y'); ?>" });

            main();

            $('#datafim, #dataini, #id_status').on('change', function (e) {
                main();
            });

            $('#btnInsert').on('click', function(e) {
                e.preventDefault();

                let me = $(this);

                $.ajax({
                    url: me.attr('href'),
                    type: 'get',
                    dataType: 'html',

                    success: function(xhr) {
                        $('body').append(xhr);
                        $('.modal').modal({
                            keyboard: false
                        }, 'show').on('hide.bs.modal', function() {
                            $('.modal').remove();
                        });
                    }
                });
            });

        });

        function main() {

            var container = $('.list');
            $('.ticket').addClass('fadeOutRight');
            container.html("<p class='text-center'><i class='fas fa-spin fa-spinner'></i> Buscando chamados no banco de dados....</p>");

            var form = $('#frmBusca');
            var data = form.serialize();
            var url = form.attr('action');

            $.post(url, data, function (xhr) {
                var classe = 'primary';
                var data = xhr.data;

                if (xhr.count > 0) {
                    container.html("");
                    $(data).each(function (i, e) {
                        //console.log(e.id_status);
                        var stts = '';
                        switch (e.id_status) {
                            case '1':
                                classe = 'secondary';
                                stts = 'Aberto';
                                break;
                            case '2':
                                classe = 'primary';
                                stts = 'Em atendimento';
                                break;
                            case '3':
                                classe = 'warning';
                                stts = 'Pendente';
                                break;
                            case '4':
                                classe = 'success';
                                stts = 'Resolvido';
                                break;
                            case '999':
                                classe = 'danger';
                                stts = 'Cancelado';
                                break;
                        }

                        var hid = 'hidden';
                        if (e.respostas > 0) {
                            hid = '';
                        }

                        var card = `
                                <div class="card bd-${classe} ticket animate bounceIn">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12 col-lg-3">
                                                <a href="${e.url}">
                                                    <span class="text-muted h5">#${e.id}</span>
                                                    <h5 data-toggle="tooltip" title="Clique para ver detalhes" class="text-primary">${e.assunto}</h5>
                                                    <p class="text-muted"><strong>Solicitante: </strong> ${e.solicitante}</p>
                                                </a>
                                            </div>
                                            <div class="col-12 col-lg-3">
                                                <p><strong>Categoria</strong> <br> Erros e Falhas</p>
                                                <p>
                                                    <strong>Criado em: </strong> ${e.criado_em} <br>
                                                    <strong>Atualizado em:</strong> ${e.updated_at}
                                                </p>
                                            </div>
                                            <div class="col-12 col-lg-3">
                                                <p>
                                                    <strong>Respostas:  </strong> <span class="badge badge-secondary">${e.respostas}</span> <br>
                                                </p>
                                                <p ${hid}>
                                                    <strong>Ultima resposta por</strong> <span class="text-primary">${e.last_user}</span> as <span class="text-primary">${e.last_update}</span>
                                                </p>
                                            </div>
                                            <div class="col-12 col-lg-3">
                                                <p><strong>Prioridade</strong> <br> ${e.prioridade}</p>
                                                <p><strong>Situação</strong> <br>
                                                    <span class="badge badge-${classe}">${stts}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div> `;

                        container.append(card);

                    });

                    $('[data-toggle="tooltip"]').tooltip();

                } else {
                    console.log('ok');
                    container.html("<p class='text-center'>Nenhum registro encontrado</p>");
                }


            });

        }

        function isEmptyObject(obj) {
            return JSON.stringify(obj) === '{}';
        }
    </script>
</body>

</html>