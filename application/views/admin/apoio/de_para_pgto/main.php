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
                <div class="table-responsive col-sm">
                    <form action="<?php echo $form_action; ?>" id="frm" name="frm" method="post" enctype="multipart/form-data">

                        <table id="data-table" class="table table-condensend table-hover" data-url="" data-update="" data-delete="">
                            <thead>
                            <tr>
                                <th>Foma Pgto Pharmanexo</th>
                                <th>Forma Pgto Apoio</th>
                            </tr>
                            </thead>
                            <?php foreach ($fp as $f) { ?>
                                <tr>
                                    <th>
                                        <input type="hidden" name="fp[<?php echo $f['id']; ?>][id_forma_pagamento]" value="<?php echo $f['id']; ?>">
                                        <input type="text" name="fp[<?php echo $f['id']; ?>][descricao]" value="<?php echo $f['descricao']; ?>" class="form-control">
                                    </th>
                                    <th>
                                        <select name="fp[<?php echo $f['id']; ?>][cd_forma_pagamento]" id="fp_apoio_<?php echo $f['id']; ?>" class="select2">
                                            <option value="">Selecione</option>
                                            <?php foreach ($fp_apoio as $fp) { ?>
                                                <option value="<?php echo $fp['id']; ?>"><?php echo $fp['descricao']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </th>
                                </tr>
                            <?php } ?>
                        </table>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $scripts; ?>
<script>

    var url_delete = $('#data-table').data('delete');
    $(function () {

    });
</script>
</body>

