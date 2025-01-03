<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
    <?php echo $navbar; ?>
    <?php echo $sidebar; ?>

    <div class="content">
        <?php echo $heading; ?>

        <div class="content__inner" id="printAll">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Estado</th>
                                    <th>Preço (R$)</th>
                                    <th>Tipo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <form action="<?php echo $form_action ?>" method="post" id="formPreco">
                                    <?php for($i=0; $i < 27; $i++) {?>
                                        <tr>
                                            <td><input type="hidden" name="id_estado[]" value="<?php echo $estado[$i]['id'] ?>"></td>
                                            <td style="width: 250px"><?php echo $estado[$i]['descricao'] ?></td>
                                            <td>
                                                <input type="number" min="0" step="any" class="form-control" name="preco[]" id="preco" value="<?php echo (empty($preco)) ? '' : $preco[$i]['valor'] ?>" />
                                            </td>
                                            <td>
                                                <select name="tipo[]" id="tipo" class="form-control"> 
                                                    <option value=""  <?php echo ( empty($preco) ? 'selected' : '' ) ?>>Selecione...</option> 
                                                    <option value="0" <?php echo ( empty($preco) ? '' : ($preco[$i]['tipo'] == '0' ? 'selected' : '')) ?> >Ambos</option> 
                                                    <option value="1" <?php echo ( empty($preco) ? '' : ($preco[$i]['tipo'] == 1 ? 'selected' : '')) ?> >Venda manual</option> 
                                                    <option value="2" <?php echo ( empty($preco) ? '' : ($preco[$i]['tipo'] == 2 ? 'selected' : '')) ?> >Venda automática</option> 
                                                </select>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </form>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $scripts; ?>
    <script>
        $(function () {
          
        });
    </script>
</body>

