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
                        if (data.type == 'success'){
                            location.reload();
                        }else{
                            formWarning(data)
                        }
                    },
                    cache: false,
                    contentType: false,
                    processData: false
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

                            if(e.type == 'image'){
                                anexos = anexos + `<a data-fancybox="gallery" href="${e.url}" class="btn btn-link"><img src="${e.url}" class="img-anexo" /></a>`
                            }else{
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