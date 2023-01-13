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
            <div class="card-body">
                <form action="<?php echo $formAction; ?>" id="frmBuscarCot" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Selecione o portal</label>
                                <select name="integrador" id="integrador" class="form-control">
                                    <option value="">Selecione</option>
                                    <?php foreach ($portais as $portal) { ?>
                                        <option value="<?php echo $portal['id']; ?>"><?php echo $portal['desc']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Informe a cotação</label>
                                <input type="text" id="cotacao" name="cotacao" class="form-control">
                            </div>
                        </div>
                    </div>
                </form>


                <div class="row">
                    <div class="col-12">
                        <button type="submit" form="frmBuscarCot" class="btn btn-primary btn-block">Buscar Cotação
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <?php if (isset($cotacao) && !empty($cotacao)) { ?>
            <div class="card">
                <div class="card-body">
                    <h3 class="text-center">Cotação localizada, confira os dados e clique no botão importar</h3>
                    <table class="table table-bordered">
                        <tr>
                            <th>Cotação</th>
                            <th>Comprador</th>
                            <th>Data Inicio</th>
                            <th>Data Fim</th>
                        </tr>
                        <tr>
                            <td><?php echo $cotacao['Id_Pdc']; ?></td>
                            <td><?php echo $cotacao['CNPJ_Hospital']; ?> - <?php echo $cotacao['Nome_Hospital']; ?></td>
                            <td><?php echo $cotacao['Data_Envio_Mercado']; ?></td>
                            <td><?php echo $cotacao['Data_Vencimento']; ?></td>
                        </tr>
                    </table>
                    <p class="text-center">
                        <button id="btnImport" data-href="<?php echo $cotacao['urlImport']; ?>" class="btn btn-success">
                            Importar Cotação
                        </button>
                    </p>
                </div>
            </div>
        <?php } ?>

    </div>
</div>

<?php echo $scripts; ?>

<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

<script>
    $(function () {
        $('#btnImport').click(function (e) {
            e.preventDefault();
            var url = $(this).data('href')
            $.get(url, function (xhr) {
                if (xhr.msg == 'sucesso') {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Importação concluída, localize-a no dashboard ou em cotações Brasil',
                        showConfirmButton: false,
                        timer: 3000
                    }).then(function () {
                        window.location = '/dashboard';
                    });
                }
            });

        });
    });
</script>
</body>

</html>