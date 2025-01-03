<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>
    <form action="<?php if (isset($action)) echo $action; ?>" id="frm_rep" enctype="multipart/form-data" method="post">
        <input type="hidden" name="clientes" id="clientes">
        <input type="hidden" name="estados" id="estados">
        <div class="content__inner">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <?php if (isset($representantes)) { ?>
                                    <label for="">Selecione o representante</label>
                                    <select name="id_representante" id="id_representante" class="select2">
                                        <option value="">Selecione...</option>

                                        <?php foreach ($representantes as $rep) { ?>
                                            <option value="<?php echo $rep['id']; ?>"><?php echo $rep['nome']; ?></option>
                                        <?php } ?>
                                    </select>
                                <?php } else { ?>
                                    <label for="">Representante</label>
                                    <input type="hidden" name="id_representante"
                                           value="<?php if (isset($representante['id'])) echo $representante['id']; ?>"
                                           class="form-control">
                                    <input type="text" readonly
                                           value="<?php if (isset($representante['nome'])) echo $representante['nome']; ?>"
                                           class="form-control">
                                <?php } ?>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="">Comissão</label>
                                <div class="input-group">
                                    <input type="text" name="comissao" data-inputmask="money"
                                           value="<?php if (isset($dados['comissao'])) echo number_format($dados['comissao'], 2, ',', '.'); ?>"
                                           class="form-control text-right">
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            %
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="">Meta Mensal</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            R$
                                        </div>
                                    </div>
                                    <input type="text" name="meta"
                                           value="<?php if (isset($dados['meta'])) echo number_format($dados['meta'], 2, ',', '.'); ?>"
                                           data-inputmask="money"
                                           class="form-control text-right">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label for="Gerente">Gerente</label>
                                <input type="text" name="gerente"
                                       value="<?php if (isset($dados['gerente'])) echo $dados['gerente'] ?>"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label for="Gerente">E-mail Gerente</label>
                                <input type="text" name="email_gerente"
                                       value="<?php if (isset($dados['email_gerente'])) echo $dados['email_gerente'] ?>"
                                       class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label for="supervisor">Supervisor</label>
                                <input type="text" name="supervisor"
                                       value="<?php if (isset($dados['supervisor'])) echo $dados['supervisor'] ?>"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label for="email_gerente">E-mail Supervisor</label>
                                <input type="text" name="email_supervisor"
                                       value="<?php if (isset($dados['email_supervisor'])) echo $dados['email_supervisor'] ?>"
                                       class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <h4 class="text-muted">Selecione os estados</h4>
                            <hr>
                            <div class="form-group">
                                <select multiple id="listElements">
                                    <?php foreach ($estados as $estado) { ?>
                                        <option value="<?php  echo (isset($estado['checked']) && $estado['checked'] == true) ? $estado['id'] . ' selected' : $estado['id'];  ?> <?php if (isset($estado['checked']) && $estado['checked'] == true) echo 'selected'; ?>"><?php echo $estado['descricao']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="text-muted">Selecione os clientes</h4>
                            <hr>
                            <div class="form-group">
                                <select multiple id="listClientes">
                                    <?php foreach ($clientes as $clientes) { ?>
                                        <option value="<?php echo $clientes['id'] ?>"><?php echo "{$clientes['cnpj']} - {$clientes['razao_social']}"; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
</body>

<?php echo $scripts; ?>

<script>

    $(function () {

        reloadPlugin();
        listCliente();
        listEstados();
    });

    function listEstados() {

        var demo = $('#listElements').bootstrapDualListbox({
            nonSelectedListLabel: 'Elementos',
            selectedListLabel: 'Elementos Selecionados',
            filterPlaceHolder: 'Pesquisar',
            filterTextClear: 'Exibir Todos',
            infoText: 'Exibindo todos {0} registros',
            infoTextFiltered: '<span class="label label-warning">Filtrado</span> {0} de {1}',
            infoTextEmpty: 'Lista vazia',
            moveAllLabel: 'Selecionar Todos',
            moveSelectedLabel: 'Adicionar Selecionado',
            removeSelectedLabel: 'Remover Selecionado',
            removeAllLabel: 'Remover Todos',
            preserveSelectionOnMove: 'moved',
            moveOnSelect: false,
            iconMove: 'Selecionar',
            iconRemove: 'Remover'
        });

        $('.move').html('Selecionar Marcados').removeClass('btn-outline-secondary').addClass('btn-primary');
        $('.moveall').html('Selecionar Todos').removeClass('btn-outline-secondary').addClass('btn-secondary');
        $('.remove').html('Remover Marcados').removeClass('btn-outline-secondary').addClass('btn-primary');
        $('.removeall').html('Remover Todos').removeClass('btn-outline-secondary').addClass('btn-secondary');


        $('#listElements').on('change', function (e) {
            e.preventDefault();

            var values = [];
            $.each($("#listElements option:selected"), function () {
                values.push($(this).val());
            });

            $('#estados').val(values.join(','));
        });

    }


    function updateListEstado()
    {
        console.log('oi');

        var values = [];
        $.each($("#listElements option:selected"), function () {
            values.push($(this).val());
        });

        console.log(values);

       // $('#estados').val(values.join(','));
    }

    function listCliente() {

        var demo = $('#listClientes').bootstrapDualListbox({
            nonSelectedListLabel: 'Elementos',
            selectedListLabel: 'Elementos Selecionados',
            filterPlaceHolder: 'Pesquisar',
            filterTextClear: 'Exibir Todos',
            infoText: 'Exibindo todos {0} registros',
            infoTextFiltered: '<span class="label label-warning">Filtrado</span> {0} de {1}',
            infoTextEmpty: 'Lista vazia',
            moveAllLabel: 'Selecionar Todos',
            moveSelectedLabel: 'Adicionar Selecionado',
            removeSelectedLabel: 'Remover Selecionado',
            removeAllLabel: 'Remover Todos',
            preserveSelectionOnMove: 'moved',
            moveOnSelect: false,
            iconMove: 'Selecionar',
            iconRemove: 'Remover'
        });

        $('.move').html('Selecionar Marcados').removeClass('btn-outline-secondary').addClass('btn-primary');
        $('.moveall').html('Selecionar Todos').removeClass('btn-outline-secondary').addClass('btn-secondary');
        $('.remove').html('Remover Marcados').removeClass('btn-outline-secondary').addClass('btn-primary');
        $('.removeall').html('Remover Todos').removeClass('btn-outline-secondary').addClass('btn-secondary');

        $('#listClientes').on('change', function (e) {
            e.preventDefault();

            var values = [];
            $.each($("#listClientes option:selected"), function () {
                values.push($(this).val());
            });

            $('#clientes').val(values.join(','));
        });


    }

</script>
</html>