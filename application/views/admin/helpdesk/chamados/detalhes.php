<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>
    <div class="content__inner bootstrap snippets bootdeys">
        <div class="row">
            <div class="col-md-8 ">
                <?php if ($chamado['id_status'] != '999'){ ?>
                <div id="accordion">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Escrever uma resposta
                                </button>
                            </h5>
                        </div>

                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">
                                <form id="frmResposta" action="<?php echo $formAction; ?>" method="post" enctype="multipart/form-data">
                                    <input type="hidden" id="id" name="id_chamado" value="<?php echo $chamado['id'] ?>">
                                    <div class="form-group">
                                        <textarea name="ds_chamado" id="descricao" required placeholder="Escreva sua resposta aqui" cols="30" rows="5" class="form-control"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <input type="file" name="anexos[]" multiple class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-outline-primary btn-block" value="Enviar Resposta">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <hr>
                <div id="timeline" data-url="<?php echo $urlHistorico; ?>" class="timeline-centered timeline-sm">
                    <!--<article class="timeline-entry">
                        <div class="timeline-entry-inner">
                            <time datetime="2014-01-10T03:45" class="timeline-time"><span>12:45 AM</span><span>Today</span></time>
                            <div class="timeline-icon bg-info"><i class="fa fa-exclamation"></i></div>
                            <div class="timeline-label"><h4 class="timeline-title">New Project</h4>

                                <p>Tolerably earnestly middleton extremely distrusts she boy now not. Add and offered prepare how cordial.</p></div>
                        </div>
                    </article>
                    <article class="timeline-entry left-aligned">
                        <div class="timeline-entry-inner">
                            <time datetime="2014-01-10T03:45" class="timeline-time"><span>9:15 AM</span><span>Today</span></time>
                            <div class="timeline-icon bg-white"></i></div>
                            <div class="timeline-label bg-grey"><h4 class="timeline-title">Job Meeting</h4>

                                <p>Caulie dandelion maize lentil collard greens radish arugula sweet pepper water spinach kombu courgette.</p></div>
                        </div>
                        <div class="timeline-entry-inner">
                            <div style="-webkit-transform: rotate(-90deg); -moz-transform: rotate(-90deg);" class="timeline-icon"><i class="fa fa-plus"></i></div>
                        </div>
                    </article>-->
                </div>
            </div>
            <div class="offset-1 col-md-3 ">
                <div class="card">
                    <div class="card-header">
                        <p class="card-title">Dados do Chamado</p>
                    </div>
                    <div class="card-body">
                        <p>
                            <strong>Data Abetura</strong> <br>
                            <?php echo date('d/m/Y H:i:s', strtotime($chamado['created_at'])); ?>
                        </p>
                        <?php if ($chamado['id_status'] != '999'){ ?>
                        <form action="<?php if (isset($urlUpdStts)) echo $urlUpdStts; ?>" id="frmUpdStts" method="post">
                            <input type="hidden" id="chamado" name="chamado" value="<?php echo $chamado['id'] ?>">
                            <div class="form-group">
                                <label for=""><strong>Situação</strong> <br></label>
                                <select name="id_status" id="id_status" class="form-group w-100 d-block">
                                    <?php foreach ($status as $statu) { ?>
                                        <option <?php if ($statu['id'] == $chamado['id_status']) echo 'selected'; ?> value="<?php echo $statu['id']; ?>"><?php echo $statu['ds_status']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </form>
                        <?php } ?>
                        <p>
                            <strong>Categoria</strong> <br>
                            <?php echo $chamado['categoria'] ?>
                        </p>
                        <p>
                            <strong>Prioridade</strong> <br>
                            <?php echo $chamado['prioridade'] ?>
                        </p>
                        <hr>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <?php echo $scripts; ?>

    <script>
        $(function () {
            main();

            $('#frmResposta').submit(function (e) {
                e.preventDefault();
                var url = $(this).attr('action');
                var formData = new FormData(this);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function (data) {
                        if (data.type == 'success') {
                            location.reload();
                        } else {
                            formWarning(data)
                        }
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });

            });

            $('#id_status', '#frmUpdStts').change(function (e) {
                var me = $('#frmUpdStts');
                var data = me.serialize();
                var url = me.attr('action');

                $.post(url, data, function (xhr) {
                    formWarning(xhr);
                    location.reload();
                });

            });

        });

        function main() {
            var container = $('#timeline');
            var url = container.data('url');
            container.html("<p class='text-center'><i class='fas fa-spin fa-spinner'></i> Buscando histórico do chamado ....</p>");

            $.get(url, function (xhr) {
                var data = xhr.data;

                if (xhr.count > 0) {
                    container.html("");

                    $(data).each(function (i, e) {
                        var classe = '';
                        var color = 'bg-white';
                        var text_color = 'bg-grey';
                        var nome = e.usuario;
                        var anexos = '';
                        var files = e.anexos;

                        $(files).each(function (i, e) {

                            if (e.type == 'image') {
                                anexos = anexos + `<a data-fancybox="gallery" href="${e.url}" class="btn btn-link"><img src="${e.url}" class="img-anexo" /></a>`
                            } else {
                                anexos = anexos + `<a target="_blank" class="btn btn-link" href="${e.url}"><img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAOEAAADhCAMAAAAJbSJIAAAAZlBMVEX///8AAADf39/i4uLz8/PR0dGlpaXKysrv7+9hYWGBgYE6OjplZWVCQkL8/PwLCwsvLy92dnaxsbHGxsaWlpaOjo4UFBRPT0++vr62trafn59UVFSHh4fo6OgoKChvb28eHh5RUVGikOkUAAADEklEQVR4nO3d0VLqQBBF0YkSRSFBkIuggPr/P3l90DCARKBP0ylqr0cpi96FkjBaMykBAAAAAAAAAAAAAAAAAAAAOFpd3pvcRAf8ZVJYVbfRDa3sgV/uoyta1GtFYfEc3XHYrSSwKObRIQfdiAqLRXTJIbLCYhKdcoCusLiLbvmdsLCjiU1h2eufrDfeTpxG1/ymKTzrqv248yr+U48noC0slur57MSFxaN6QDN1YfGuntBKUzjLEofqEY00hVV+//6gntFGUzhIiyzxpVZPaaEqTPMssepSoqwwPeeJPfGYBrrCdJ8lDrqTKCxMZZb40ZeOaaAsTOUoS1wpxzSQFm59Ull3ZH1KW5hW2bV/1o1lRnFhWj1lL2MnEtWFqZ8nlqoxDeSFqZcndmAhVV+Y6ipLjF9IdShM9UuWGL6Qaiucfn/zaOfreWL0QqqtsPlEsXuT9pYlBi+k2gqb+7S9hcT3LDF2ldFW2P+5TZvtPZQvNL6axzSwFW5+Gsd7D+WJkQupxsLN6sV+4jJLDFxlNBbWm4j1fPft5jVLjFtlNBZu/Ql59Dnckr/b/PIaX4i1MOX3aK2iFlLNhatRW1Yu6NJvLtxanmlVKec+nr3w+ETh2CcQFKbbI/+fQzf1KRSFX9eFo34ZVTOfRlOYetOXtrYrKPyymkyXw4d9w+paCg+6o9AXhQIUOqNQgEJnFApQ6IxCAQqdUShAoTMKBSh0RqEAhc4oFKDQGYUCFDqjUIBCZxQKUOiMQgEKnVEoQKEzCgUodEahAIXOKBSg0BmFAhQ6o1CAQmcUClDojEIBCp1RKEChMwoFKHRGoQCFzigUoNDZBQqnV1/4s63Zk9sztPIvrAffz/Dm9Qzt/AubrcyDDr9wL9xs2Bq0Ia17YbPv4CxoK/qm0Gf77XKznVvUCS1N4aKUm08/m75iFLVJu/I0pFZh219eqjBoO7rLFc7izkq4TGHkBvSq0wFbVZFHQTT3VI6Cj7k6el/Hc43DD7ooq7+nPNNo8D7vxFklPTddOjMIAAAAAAAAAAAAAAAAuBb/ATZkJu/lzVS7AAAAAElFTkSuQmCC" class="img-anexo" /></a>`
                            }

                        });

                        if (e.nivel == '1') {
                            classe = 'left-aligned';
                            color = 'bg-grey';
                            text_color = '';
                            nome = e.analista;
                        }

                        var content = `<article class="timeline-entry ${classe} animate bounceIn">
                            <div class="timeline-entry-inner">
                                <div class="timeline-icon"><i class="fa fa-exclamation"></i></div>
                                    <div class="timeline-label ${color}">
                                        <p><strong>Data: </strong> ${e.dt_criacao}</p>
                                        <h5 class="timeline-title">${nome}</h5>
                                        <p>${e.descricao}</p>
                                        <hr>
                                        ${anexos}
                                    </div>
                                </div>
                        </article>`;

                        $('#timeline').append(content);

                    });

                    container.append('<article class="timeline-entry">' +
                        '<div class="timeline-entry-inner">' +
                        '<div style="-webkit-transform: rotate(-90deg); -moz-transform: rotate(-90deg);" class="timeline-icon">' +
                        '<i class="fa fa-plus"></i>' +
                        '</div>' +
                        '</div>' +
                        '</article>');

                    $('[data-toggle="tooltip"]').tooltip();
                    $('[data-fancybox="gallery"]').fancybox();

                } else {
                    container.html("<p class='text-center'>Nenhum registro encontrado</p>");
                }


            })
        }

    </script>
</body>

</html>