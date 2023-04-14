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
                   href="">1. Dados da Cotação</a>
            </li>
            <li class="nav-item">
                <a class="nav-link disabled" data-toggle="tooltip" title="Salve a cotação antes de inserir produtos"
                   href="">2. Produtos da Cotação</a>
            </li>
        </ul>
        <div class="card">
            <div class="card-body">
                <form action="<?php if (isset($formAction)) echo $formAction; ?>" method="post"
                      enctype="multipart/form-data" id="frmCotacao">
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label for="">Cotação</label>
                            <?php if (isset($cotacao['id'])) { ?>
                                <input type="text" name="id" id="id"
                                       value="<?php if (isset($cotacao['id'])) echo $cotacao['id']; ?>"
                                       readonly
                                       class="form-control">
                            <?php } else { ?>
                                <input type="text" name="id" id="id" value="<?php if (isset($lastId)) echo $lastId; ?>"
                                       readonly
                                       class="form-control">
                            <?php } ?>
                        </div>
                        <div class="col-md-3 form-group">
                            <label for="">Data Vencimento</label>
                            <input type="datetime-local" required name="dt_vencimento"
                                   value="<?php if (isset($cotacao['dt_vencimento'])) echo $cotacao['dt_vencimento']; ?>"
                                   id="dt_vencimento"
                                   class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="">Descrição</label>
                            <input type="text" name="ds_cotacao" required id="ds_cotacao"
                                   value="<?php if (isset($cotacao['ds_cotacao'])) echo $cotacao['ds_cotacao']; ?>"
                                   maxlength="200"
                                   placeholder="Limite 200 caracteres" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 form-group">
                            <label for="termos_condicoes">Termos e Condições</label>
                            <textarea name="termos_condicoes" id="termos_condicoes" cols="30" rows="5"
                                      class="form-control"
                                      placeholder="Utilize esse campos para os Termos e Condições para a cotação, essa informação será exibida aos distribuidores"><?php if (isset($cotacao['termos_condicoes'])) echo $cotacao['termos_condicoes']; ?></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8 form-group">
                            <label for="observacao">Observações para a cotação</label>
                            <textarea name="observacao" id="observacao" cols="30" rows="2"
                                      class="form-control"
                                      placeholder="Informe as observações para a cotação."><?php if (isset($cotacao['observacao'])) echo $cotacao['observacao']; ?></textarea>
                        </div>
                        <div class="col-4 form-group">
                            <label for="">Condição de Pagamento</label>
                            <select name="id_condicao_pagamento" id="" class="form-control">
                                <?php foreach ($formasPgto as $fp) { ?>
                                    <option value="<?php echo $fp['id']; ?>"  <?php if (isset($cotacao['id_condicao_pagamento']) && $fp['id'] == $cotacao['id_condicao_pagamento']) echo 'selected'; ?>    ><?php echo $fp['descricao']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <p class="card-title">Endereços e Contatos</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 form-group">
                        <label for="contatos">Informe telefone e e-mail</label>
                        <textarea name="contatos" id="contatos" cols="30" rows="3" class="form-control"
                                  placeholder="Informe telefone e e-mail para contato sobre a cotação"><?php if (isset($cotacao['contatos'])) echo $cotacao['contatos']; ?></textarea>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 form-group">
                        <label for="observacao">Endereço de Entrega</label>
                        <textarea name="endereco_entrega" id="endereco_entrega" cols="30" rows="3" class="form-control"
                                  placeholder="Informe o endereço de entrega para está cotação, não esqueça de informar ponto de referência."><?php if (isset($cotacao['endereco_entrega'])) echo $cotacao['endereco_entrega']; ?></textarea>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 form-group">
                        <label for="observacao">Endereço de Faturamento</label>
                        <textarea name="endereco_faturamento" id="endereco_faturamento" cols="30" rows="3"
                                  class="form-control"
                                  placeholder="Informe o endereço de faturamento"><?php if (isset($cotacao['endereco_faturamento'])) echo $cotacao['endereco_faturamento']; ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        </form>
    </div>
</div>
</body>

<?php echo $scripts; ?>

<script>

    $(function () {

    });
</script>
</html>