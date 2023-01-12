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
                <div class="row">
                    <div class="col-2">
                        <div class="form-group">
                            <label for="">PEDIDO</label>
                            <input type="text" class="form-control" readonly
                                   value="<?php if (isset($pedido['id'])) echo $pedido['id']; ?>">
                        </div>
                    </div>
                    <div class="col-10">
                        <div class="form-group">
                            <label for="">COMPRADOR</label>
                            <input type="text" class="form-control" readonly
                                   value="<?php if (isset($_SESSION['dados']['cnpj'])) echo "{$_SESSION['dados']['cnpj']} - {$_SESSION['dados']['razao_social']}"; ?>">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="">FORNECEDOR</label>
                            <input type="text" class="form-control" readonly
                                   value="<?php if (isset($pedido['fornecedor']['cnpj'])) echo "{$pedido['fornecedor']['cnpj']} - {$pedido['fornecedor']['nome_fantasia']}"; ?>">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="">FORNECEDOR TELEFONE</label>
                            <input type="text" class="form-control" readonly
                                   value="<?php if (isset($pedido['fornecedor']['telefone'])) echo "{$pedido['fornecedor']['telefone']}"; ?>">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="">FORNECEDOR E-MAIL</label>
                            <input type="text" class="form-control" readonly
                                   value="<?php if (isset($pedido['fornecedor']['email'])) echo "{$pedido['fornecedor']['email']}"; ?>">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="">Data Pedido</label>
                            <input type="text" readonly class="form-control"
                                   value="<?php if (isset($pedido['data_pedido'])) echo date("d/m/Y H:i", strtotime($pedido['data_pedido'])); ?>">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="">Data Envio</label>
                            <input type="text" readonly class="form-control"
                                   value="<?php echo (isset($pedido['data_envio']) && !empty($pedido['data_envio'])) ? date("d/m/Y H:i", strtotime($pedido['data_pedido'])) : 'Pendente de Envio'; ?>">
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="">Situação</label>
                            <input type="text" readonly class="form-control"
                                   value="<?php echo $pedido['situacao']['descricao']; ?>">
                        </div>

                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <form action="<?php echo $formAction; ?>" id="frmPedido" method="post"
                              enctype="multipart/form-data">
                            <div class="form-group">
                                <input type="hidden" id="idpedido" name="idpedido"
                                       value="<?php if (isset($pedido['id'])) echo $pedido['id']; ?>">
                                <label for="">Observação</label>
                                <input type="text"
                                       class="form-control" <?php if (isset($pedido['observacao'])) echo 'readonly'; ?>
                                       value="<?php if (isset($pedido['observacao'])) echo $pedido['observacao']; ?>"
                                       placeholder="Escreva aqui as observações do pedido" name="obs" id="obs">
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <div class="table-danger ml-2"
             style="width: 15px; height: 15px; border-radius: 20%; display: inline-block"></div>
        Item Rejeitado
        <?php if (!empty($pedido['produtos'])) { ?>

            <div class="card mt-3">
                <div class="card-body">
                    <table class="table table-striped">
                        <tr>
                            <thead>
                            <th>CODIGO</th>
                            <th>PRODUTO</th>
                            <th>QTDE</th>
                            <th>VALOR</th>
                            <th>TOTAL</th>
                            <th></th>
                            </thead>
                        </tr>
                        <?php foreach ($pedido['produtos'] as $produto) { ?>
                            <tr class="<?php if ($produto['situacao'] == 9) echo 'table-danger'; ?>" <?php if ($produto['situacao'] == 9) echo "data-toggle='tooltip' title='MOTIVO: {$produto['motivo_situacao']}' "; ?>>
                                <td><?php echo $produto['codigo']; ?></td>
                                <td><?php echo $produto['descricao']; ?></td>
                                <td><?php echo $produto['quantidade']; ?></td>
                                <td><?php echo number_format($produto['preco_unitario'], 2, ',', '.'); ?></td>
                                <td><?php echo number_format(($produto['preco_unitario'] * $produto['quantidade']), 2, ',', '.'); ?></td>
                                <th>
                                    <?php if ($pedido['fechado'] == 0) { ?>
                                        <a href="<?php echo $urlDelete . $produto['id']; ?>" data-toggle="tooltip"
                                           title="Excluir Item" class="btn btn-danger btn-sm"><i
                                                    class="fa fa-trash"></i></a>
                                    <?php } ?>
                                </th>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-6 text-center ">
                            <h4 class="text-danger">Total Cancelado
                                R$ <?php echo number_format($pedido['totalCancelado'], 2, ',', '.'); ?></h4>
                        </div>
                        <div class="col-6 text-center">
                            <h4>Total do Aprovado R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></h4>
                        </div>
                    </div>
                </div>
            </div>

        <?php } ?>
    </div>
</div>


<?php if (isset($scripts)) echo $scripts; ?>

<script type="text/javascript">

    $(function () {


    });

    function mascaraValor(valor) {
        valor = valor.toString().replace(/\D/g, "");
        valor = valor.toString().replace(/(\d)(\d{8})$/, "$1.$2");
        valor = valor.toString().replace(/(\d)(\d{5})$/, "$1.$2");
        valor = valor.toString().replace(/(\d)(\d{2})$/, "$1,$2");
        return valor
    }
</script>
</body>
