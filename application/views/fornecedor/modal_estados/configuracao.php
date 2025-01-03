<!DOCTYPE html>
<html lang="pt-BR">
<?php echo $header; ?>
<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>
<div class="content">
    <?php echo $heading; ?>
    <div class="content__inner">

        <form action="">
            <div class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            Estados Habilitados
                            <a href="" data-toggle="modal" data-target="#modalEstados" class="btn btn-sm btn-primary">Incluir</a>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <input type="text" id="buscarEstados" class="form-control" placeholder="Buscar...">
                            </div>
                            <?php foreach ($estados as $estado) { ?>
                                <div class="row border-bottom mb-2">
                                    <div class="col-10">
                                        <?php echo $estado['descricao']; ?>
                                    </div>
                                    <div class="col-2 text-center">
                                        <a data-href="<?php if (isset($urlDeleteEstado)) echo "{$urlDeleteEstado}/{$estado['id_estado']}"; ?>" class="btn btn-link btn-sm text-danger btnDelete"><i
                                                    class="fa fa-trash"></i></a>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            Clientes Habilitados
                            <a href="" type="button" class="btn btn-sm btn-primary">Incluir</a>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <input type="text" id="buscarCompradores" class="form-control" placeholder="Buscar...">
                            </div>
                            <div id="listComp" class="">
                                <?php foreach ($compradores as $comprador) { ?>

                                    <div class="row border-bottom mb-2">
                                        <div class="col-10 item">
                                            <?php echo $comprador['cnpj'] . " - " . $comprador['razao_social']; ?>
                                        </div>
                                        <div class="col-2 text-center">
                                            <a href="<?php if (isset($urlDelete)) echo "{$urlDelete}/{$comprador['id_cliente']}"; ?>" class="btn btn-link btn-sm text-danger btnDelete"><i
                                                        class="fa fa-trash"></i></a>
                                        </div>
                                    </div>

                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal" id="modalEstados" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Incluir Estados</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Salvar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<?php echo $scripts; ?>
<script>
    $(function () {
        $('#estados').selectpicker();
        $('#id_cliente').selectpicker();


        $('#buscarCompradores').on('keyup', function () {
            var val = $(this).val().toUpperCase();

            $("#listComp .item").each(function () {
                var text = $(this).html().toUpperCase();

                console.log(text);

                if (text.includes(val) == false) {
                    $(this).parent().hide();
                } else {
                    $(this).parent().show();
                }


            });

        })


        $('.btnDelete').click(function (e){
            e.preventDefault();
            var btn = $(this);

            var url = $(this).data('href');

            Swal.fire({
                title: 'Deletar Registro',
                text: "Você tem certeza que deseja excluir ?",
                icon: '',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.get(url, function (xhr){
                        if (xhr.type == 'success'){
                            btn.parent().parent().remove();
                        }
                        Swal.fire(
                            '',
                            xhr.message,
                            xhr.type
                        )
                    })
                }
            })

        });
    });
</script>
</body>

</html>