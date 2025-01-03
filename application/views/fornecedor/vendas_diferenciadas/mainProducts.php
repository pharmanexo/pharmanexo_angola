<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>

<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner">
        <form id="formVendasDiferenciadas" action="<?php echo (isset($form_action)) ? $form_action : ''; ?>" method="POST" data-selected=" <?php if(isset($labelSubtitle)) echo $labelSubtitle ?>">
            <input type="hidden" name="all" id="all">
            <input type="hidden" name="selecionados" id="selecionados">
            <input type="hidden" name="produtos" id="produtos">

            <!-- Row Produtos -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="data-table-produtos" class="table table-condensend table-hover w-100" data-url="<?php echo (isset($datatable_produtos)) ? $datatable_produtos : ''; ?>">
                                    <thead>
                                    <tr>
                                        <th>
                                            <div class="checkbox">
                                                <input type="checkbox" id="allProducts">
                                                <label class="checkbox__label" for="allProducts"></label>
                                            </div>
                                        </th>
                                        <th>Código</th>
                                        <th>Descrição</th>
                                        <th>Marca</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Row Estados/Clientes -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="text-muted"><?php if(isset($subtitle)) echo $subtitle ?></h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="selectElements"><?php echo $labelSubtitle; ?></label>
                                <select class="select2" name="selectElements" id="selectElements" data-url="<?php echo (isset($modal_elementos)) ? $modal_elementos : ''; ?>" data-placeholder="Selecione" data-allow-clear="true">
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

    var inputSelect = $("#formVendasDiferenciadas").data('selected');

    $(function () {

        $('#formVendasDiferenciadas').submit(function(e) {
            var ids = [];

            $.map(table.rows('.selected').data(), function (item) {
                ids.push(item.codigo);
            });

            if (ids.length < 1  ) {
                e.preventDefault();
                formWarning({type: 'warning', message: "Nenhum Produto selecionado!"});
            } else {
                if ($('#selectElements').val() == '') {
                    e.preventDefault();
                    ancora();
                    var msg = "O campo " + inputSelect + " é obrigatório!";
                    formWarning({type: 'warning', message: msg});
                }
            }
        });

        var table = $('#data-table-produtos').DataTable({
            pageLength: 10,
            lengthChange: false,
            processing: true,
            responsive: true,
            serverSide: false,
            ajax: {
                url: $('#data-table-produtos').data('url'),
                type: 'post',
                dataType: 'json'
            },
            columns: [
                {defaultContent: '', orderable: false, searchable: false, sortable: false },
                {name: 'codigo', data: 'codigo'},
                {name: 'produto_descricao', data: 'produto_descricao'},
                {name: 'marca', data: 'marca'},
            ],
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0 }
            ],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            order: [[2, 'asc'] ],
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

        $("#allProducts").change(function() {
            if ($(this).prop("checked") == true) { 

                table.rows({search:'applied'}).select();
                $('#all').val('1');
            } else {
                table.rows().deselect();
                $('#all').val('');
            }
        });

        $('#selectElements').on('change', function (e) {
            e.preventDefault();

            var open = $(this).data('isOpen');
            var me = $('#selectElements option:selected').val();

            $.get({
                url: $('#selectElements').data('url') + '/' + me,
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

            $('#selectElements').data('isOpen', !open);
        });
    });

    function ancora() 
    {
        var target_offset = $("#ancora").offset();
        var target_top = target_offset.top;
        $('html,body').animate({scrollTop: target_top},'slow');
    }
</script>
</body>

</html>
