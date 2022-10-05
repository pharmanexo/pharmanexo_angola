<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <div class="content__inner">
        <div class="messages">
            <?php echo $contatos; ?>
            <div class="messages__body">
                <div class="messages__header">
                    <div class="toolbar toolbar--inner mb-0">
                        <div class="toolbar__label"><?php if (isset($dados['nome'])) echo $dados['nome']; ?></div>

                        <div class="actions toolbar__actions">
                            <div class="dropdown actions__item">
                                <i class="actions__item zmdi zmdi-more-vert" data-toggle="dropdown"></i>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="" id="reloadMessages" class="dropdown-item">Atualizar</a>
                                    <a href="<?php if (isset($urlDeleteAll)) echo $urlDeleteAll ?>" id="deleteAllMessages" class="dropdown-item">Excluir Conversa</a>
                                </div>
                            </div>
                        </div>

                        <div class="toolbar__search">
                            <input type="text" placeholder="Pesquisar...">
                            <i class="toolbar__search__close zmdi zmdi-long-arrow-left" data-ma-action="toolbar-search-close"></i>
                        </div>
                    </div>
                </div>

                <div class="messages__content" data-urlMessages="<?php if (isset($urlLoadMessages)) echo $urlLoadMessages; ?>" data-urlReadMessages="<?php if (isset($urlReadMessages)) echo $urlReadMessages; ?>" data-urlDeleteMessages="<?php if (isset($urlDeleteMessages)) echo $urlDeleteMessages; ?>">
                    <div class="scrollbar-inner" id="container-messages">

                    </div>
                </div>

                <div class="p-3">
                    <form action="<?php if (isset($form_action)) echo $form_action; ?>" id="frmMessage" method="post">
                        <input type="hidden" name="idUsuario" value="<?php if (isset($idUsuario)) echo $idUsuario; ?>">
                        <input type="hidden" name="idContato" value="<?php if (isset($idContato)) echo $idContato; ?>">
                        <div class="form-group">
                            <div class="input-group">
                                <textarea class="form-control" name="mensagem" id="mensagem" required placeholder="Escreva sua mensagem... "></textarea>
                                <div class="input-group-append">
                                    <button type="submit" form="frmMessage" class="btn btn-primary btn-block px-5"><i class="fas fa-paper-plane"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $scripts; ?>

<script>
    $(function () {
        loadMessages();
        reloadPlugin();

        $('#frmMessage').submit(function (e) {
            e.preventDefault();
            var me = $(this);
            var data = me.serialize();
            var url = me.attr('action');

            $.post(url, data, function (xhr) {
                if (xhr.type == 'success') {
                    $('#mensagem').val('');
                    loadMessages();
                } else {
                    formWarning(xhr);
                }
            });
        });

        $('#reloadMessages').click(function (e) {
            e.preventDefault();
            loadMessages();
        });

        $('#deleteAllMessages').click(function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Excluir toda a conversa?',
                text: "Todas as mensagens serão excluidas permanentemente",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não'
            }).then((result) => {
                if (result.value) {
                    $.get($('#deleteAllMessages').attr('href'), function (xhr) {
                        if (xhr.type == 'success') {
                            loadMessages();
                        } else {
                            formWarning(xhr);
                        }
                    });
                }
            })
        });

        setInterval(loadMessages, 180000);
    });

    function loadMessages() {
        var container = $('#container-messages');
        var url = $('.messages__content').data('urlmessages');

        container.html("<h4 class='text-center text-muted'><i class='fas fa-spinner fa-spin'></i> carregando mensagens ...</h4>");

        $.get(url, function (xhr) {
            container.html('');
            $(xhr).each(function (item) {
                item = $(this)[0];

                var i = createSendMessage(item);
                container.append(i);

            });
        });

        scrolling('#container-messages');
        readAllMessages();

        reloadPlugin();
    }

    function createSendMessage(item) {

        var urlDelete = $('.messages__content').data('urldeletemessages');
        var cls = 'messages__item--right';

        if (item.send == false) {
            cls = '';
        }


        var check = '';
        var container = $(`<div class="messages__item ${cls}">`);
        var mensagens = $(`<div class="messages__details" data-toggle="tooltip" title="${item.data_enviado}">`);
        var btnDelete = $(`<button data-url="${urlDelete}${item.id}" class="btn btn-link text-danger btn-delete-message"><i class="fas fa-trash"></i></button>`);

        var mensagem = $(`<p>${item.mensagem} </p>`);

        if (item.status == '1' && item.send == true) {
            check = '<i class="fas fa-check-double"></i>'
        }

        var time = $(`<small>${check}</small>`);


        btnDelete.click(function (e) {
            e.preventDefault();
            Swal.fire({
                title: 'Excluir Mensagem?',
                text: "A mensagem será excluida permanentemente",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não'
            }).then((result) => {
                if (result.value) {
                    $.get(btnDelete.data('url'), function (xhr) {
                        if (xhr.type == 'success') {
                            loadMessages();
                        } else {
                            formWarning(xhr);
                        }
                    });
                }
            })
        });

        mensagem.append(time);
        mensagens.append(mensagem);
        mensagens.tooltip();
        container.append(mensagens);
       // container.append(btnDelete);


        return container;

    }

    function readAllMessages() {
        var url = $('.messages__content').data('urlreadmessages');

        $.get(url, function (xhr) {
            $('')
        });

    }

    function scrolling(idElem) {
        $(idElem).animate({
            scrollTop: 500000
        }, 1000);
    }
</script>
</body>

</html>
