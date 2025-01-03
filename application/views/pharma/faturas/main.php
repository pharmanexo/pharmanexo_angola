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
                <table class="table table-striped table-bordered">
                    <tr>
                        <th>Vencimento</th>
                        <th>Descrição</th>
                        <th>Valor total</th>
                        <th>Boleto</th>
                    </tr>
                    <tr>
                        <td>22/04/2022</td>
                        <td>Pedido Pharma_1245-22</td>
                        <td>R$ 4289,99</td>
                        <td>
                            <a href="<?php echo base_url('public/boleto-facil-exemplo.pdf'); ?>" target="_blank"
                               class="btn btn-sm btn-primary"><i class="fa fa-print"></i></a>
                            <button type="button" class="btn btn-sm btn-secondary" data-toggle="modal"
                                    data-target="#exampleModal">
                                PIX
                            </button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Pagamento por Pix</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="<?php echo base_url('public/pix-qrcode.png'); ?>" alt=""> <br>
                <p>Chave PIX: asdsadhi=A-29EQQWD90QDSNADIKA9R2</p>
                <p>Nome: Distribuidor de Medicamen</p>
                <p>Cidade: São Paulo</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<?php if (isset($scripts)) echo $scripts; ?>

<script>
    $(function () {


    }
</script>
</body>

</html>
