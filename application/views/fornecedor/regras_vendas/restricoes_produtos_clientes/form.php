<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>

<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner">
        <form action="<?php if (isset($form_action)) echo $form_action; ?>" method="POST" id="formRestricoes">
            <input type="hidden" name="opcoes" id="opcoes">
            <input type="hidden" name="produtos" id="produtos">

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="text-muted">Integradores</h4>
                            <small>Selecione os integradores para aplicar a regra</small>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($integradores)) { ?>
                                <?php foreach ($integradores as $integrador) { ?>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" name="integrador[]" type="checkbox" id="inlineCheckbox<?php echo $integrador['id']; ?>" value="<?php echo $integrador['id']; ?>">
                                        <label class="form-check-label" for="inlineCheckbox<?php echo $integrador['id']; ?>"> <?php echo $integrador['desc']; ?></label>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <table id="table-restricao" class="table table-condensend table-hover w-100"
                                   data-url="<?php echo $datatable_produtos; ?>"
                                   data-selected=" <?php if (isset($labelSubtitle)) echo $labelSubtitle ?>">
                                <thead>
                                <tr>
                                    <th>
                                        <div class="checkbox">
                                            <input type="checkbox" id="checkall">
                                            <label class="checkbox__label" for="checkall"></label>
                                        </div>
                                    </th>
                                    <th>Código</th>
                                    <th>Descrição</th>
                                    <th>Marca</th>
                                </tr>
                                </thead>
                                <tbody id="myListProdutos"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Row Estados/Clientes -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="text-muted"><?php if (isset($subtitle)) echo $subtitle ?></h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="selectElements"><?php echo $labelSubtitle; ?></label>
                                <select class="select2" name="selectElements" id="selectElements"
                                        data-url="<?php echo (isset($modal_elementos)) ? $modal_elementos : ''; ?>"
                                        data-placeholder="Selecione" data-allow-clear="true">
                                    <option></option>
                                    <option value="ESTADOS">Estados</option>
                                    <?php if ($this->session->userdata('id_tipo_venda') != 1) : ?>
                                        <option value="CLIENTES">CNPJs</option>
                                    <?php endif; ?>
                                </select>

                                <br>
                                <a href="#" id="ancora"></a>
                                <select class="form-control mt-3" id="selectedElements" multiple></select>
                            </div>


                        </div>
                    </div>
                </div>
            </div>


        </form>
    </div>
</div>


<?php echo $scripts; ?>

<script>

    var inputSelect = $("#formRestricoes").data('selected');
    var table
    $(function () {

        table = $('#table-restricao').DataTable({
            serverSide: false,
            lengthChange: false,
            ajax: {
                url: $('#table-restricao').data('url'),
                type: 'POST',
                dataType: 'json'
            },
            columns: [
                {defaultContent: '', orderable: false, searchable: false, width: '90px'},
                {name: 'codigo', data: 'codigo'},
                {name: 'apresentacao', data: 'produto_descricao'},
                {name: 'marca', data: 'marca'},
            ],
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0},
            ],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            order: [[2, 'asc']],
            rowCallback: function (row, data) {
            },
            drawCallback: function () {
            }
        });

        table.on('select', function (e, dt, type, indexes) {
            $('#produtos').val('');
            var ids = [];

            var selectedRows = $.map(table.rows('.selected').data(), function (item) {
                ids.push(item.codigo);
            });

            $('#produtos').val(ids.join(','));
        }).on('deselect', function (e, dt, type, indexes) {
            $('#produtos').val('');
            var ids = [];

            var selectedRows = $.map(table.rows('.selected').data(), function (item) {
                ids.push(item.codigo);
            });

            $('#produtos').val(ids.join(','));
        });

        $('#selectElements').on('change', function (e) {
            e.preventDefault();

            var me = $(this);

            $.ajax({
                url: me.data('url') + '/' + me.val(),
                type: 'GET',
                dataType: 'html',

                success: function (response) {
                    $('body').append(response);
                    $('.modal').modal({
                        keyboard: false
                    }, 'show').on('hide.bs.modal', function () {
                        $('.modal').remove();
                    });
                }
            });
        });

        $('#formRestricoes').on('submit', function (e) {

            var ids = [];

            $.map(table.rows('.selected').data(), function (item) {
                ids.push(item.codigo);
            });

            if (ids.length < 1) {
                e.preventDefault();
                formWarning({type: 'warning', message: "Nenhum Produto selecionado!"});
            } else {
                if ($('#selectElements').val() == '') {
                    e.preventDefault();
                    ancora();
                    var msg = "O campo " + inputSelect + " é obrigatório!";
                    formWarning({type: 'warning', message: msg});
                } else {


                }
            }
        });

        $('#checkall').click(function (event) {
            checkall();
        });
    });

    function checkall() {
        var checkall = document.getElementById('checkall');
        if (checkall.checked == true) {
            table.rows({search: 'applied'}).select();
        } else {
            table.rows().deselect();
        }
    }

    function ancora() {
        var target_offset = $("#ancora").offset();
        var target_top = target_offset.top;
        $('html,body').animate({scrollTop: target_top}, 'slow');
    }
</script>
</body>

</html>
