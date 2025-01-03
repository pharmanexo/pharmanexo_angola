<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner">
        <ul class="nav nav-tabs pull-left">
            <li class="nav-item">
                <a class="nav-item nav-link active"
                   href="">1. Dados da Cotação <i class="fa fa-check"></i></a>
            </li>
            <li class="nav-item">
                <a class="nav-item nav-link active" data-toggle="tooltip"
                   title="Salve a cotação antes de inserir produtos"
                   href="">2. Produtos da
                    Cotação <?php if (isset($produtosCotacao) && !empty($produtosCotacao)) echo "<i class='fa fa-check'></i>" ?></a>
            </li>
        </ul>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-2 border-right">
                        <span class="text-primary">Cotação ID</span> <br>
                        <?php if (isset($cotacao['id'])) echo $cotacao['id']; ?>
                    </div>
                    <div class="col-2 border-right">
                        <span class="text-primary">Abertura</span> <br>
                        <?php if (isset($cotacao['dt_abertura'])) echo date("d/m/Y H:i", strtotime($cotacao['dt_abertura'])); ?>
                    </div>
                    <div class="col-2 border-right">
                        <span class="text-primary">Vencimento</span> <br>
                        <?php if (isset($cotacao['dt_vencimento'])) echo date("d/m/Y H:i", strtotime($cotacao['dt_vencimento'])); ?>
                    </div>
                    <div class="col-4 border-right ">
                        <span class="text-primary">Condição Pagamento</span> <br>
                        <?php if (isset($cotacao['condicao']['id'])) echo $cotacao['condicao']['descricao']; ?>
                    </div>
                    <div class="col-2 ">
                        <span class="text-primary">Região</span> <br>
                        <?php if (isset($cotacao['estado'])) echo $cotacao['estado']; ?>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-4">
                        <span class="text-primary">Descrição</span> <br>
                        <?php if (isset($cotacao['ds_cotacao'])) echo $cotacao['ds_cotacao']; ?>
                    </div>
                    <div class="col-4">
                        <span class="text-primary">Observação</span> <br>
                        <?php if (isset($cotacao['observacao'])) echo $cotacao['observacao']; ?>
                    </div>
                    <div class="col-4 text-center">
                        <br>
                        <a href="" type="button" class="btn btn-primary" data-toggle="modal"
                           data-target="#exampleModal">Acessar Termos e Condições</a>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($cotacao['situacao'] == 1) { ?>
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo $formAction; ?>" method="post" id="frmProd" name="frmProd">
                        <input type="hidden" name="id_cotacao" value="<?php echo $cotacao['id']; ?>">
                        <div class="row">
                            <div class="col-4 form-group">
                                <label for="id_produto_catalogo">Produto</label>
                                <select name="id_produto_catalogo" id="id_produto_catalogo"
                                        class="form-control select2">
                                    <?php foreach ($produtos as $produto) { ?>
                                        <option value="<?php echo $produto['id_produto']; ?>"><?php echo $produto['descricao']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-2 form-group">
                                <label for="quantidade">Quantidade</label>
                                <input type="number" min="1" name="quantidade" class="form-control">
                            </div>
                            <div class="col-4 form-group">
                                <label for="quantidade">Marcas</label>
                                <input type="text" name="marcas" class="form-control">
                            </div>
                        </div>
                        <div class="text-right">
                            <button type="submit" form="frmProd" id="btnProdAdd" class="btn btn-outline-primary">
                                Adicionar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php } ?>

        <?php if (isset($produtosCotacao) && !empty($produtosCotacao)) { ?>

            <?php foreach ($produtosCotacao as $prodCot) { ?>

                <div class="card">
                    <div class="card-header">
                        <p class="text-right">
                            <a href="" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></a>
                        </p>
                    </div>
                    <div class="card-body">
                        <p><strong>Produto Solicitado</strong></p>
                        <div class="row">
                            <div class="col-2 form-group">
                                <label for="">Id Produto</label><br>
                                <?php echo $prodCot['id_produto_catalogo']; ?>
                            </div>
                            <div class="col-6 form-group">
                                <label for="">Descrição</label> <br>
                                <?php echo $prodCot['descricao']; ?>
                            </div>
                            <div class="col-2 form-group">
                                <label for="">Marcas</label> <br>
                                <?php echo $prodCot['marcas_favoritas']; ?>
                            </div>
                            <div class="col-2 form-group">
                                <label for="">Quantidade</label> <br>
                                <?php echo $prodCot['quantidade']; ?>
                            </div>
                        </div>

                        <?php if (isset($prodCot['ofertas'])) { ?>
                            <hr>
                            <p><strong>Ofertas Recebidas</strong></p>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Descrição</th>
                                    <th>Qtd Embalagem</th>
                                    <th>Valor Unit.</th>
                                    <th>Valor Emb.</th>
                                    <th>Ofertado por</th>
                                    <th></th>
                                </tr>
                                <?php foreach ($prodCot['ofertas'] as $oferta) { ?>
                                    <tr>
                                        <td><?php echo $oferta['descricao']; ?></td>
                                        <td class="text-center"><?php echo $oferta['qtd_embalagem']; ?></td>
                                        <td class="text-right">R$ <?php echo number_format($oferta['valor_oferta'], 4, ',', '.'); ?></td>
                                        <td class="text-center">R$ <?php echo number_format(($oferta['valor_oferta'] * $oferta['qtd_embalagem']), 4, ',', '.'); ?></td>
                                        <td class=""><?php echo $oferta['nome_fantasia']; ?> / <?php echo $oferta['estado']; ?></td>
                                        <td>
                                            <button data-toggle="tooltip" type="Rejeitar oferta"
                                                    class="btn btn-sm btn-danger">
                                                <i
                                                        class="fa fa-ban"></i></button>
                                            <button data-toggle="tooltip" type="Negociar oferta"
                                                    class="btn btn-sm btn-info"><i
                                                        class="fa fa-dollar"></i></button>
                                            <button data-toggle="tooltip" type="Aceitar oferta"
                                                    class="btn btn-sm btn-success"><i
                                                        class="fa fa-check"></i></button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        <?php } ?>
                    </div>
                </div>

            <?php } ?>
        <?php } else { ?>
            <div class="card">
                <div class="card-body">
                    <p>Nenhum produto adicionado na cotação</p>
                </div>
            </div>
        <?php } ?>
    </div>
</div>


<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Termos e Condições</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><?php if (isset($cotacao['termos_condicoes'])) echo $cotacao['termos_condicoes']; ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

</body>

<?php echo $scripts; ?>

<script>

    $(function () {
        $('[data-toggle="tooltip"]').tooltip();

        $('#frmProd').submit(function (e) {


            $('#btnProdAdd').html("<i class='fa fa-spin fa-spinner'></i> Adicionando...");

            return true;

        });

    });
</script>
</html>