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
                    <div class="col-5">
                        <div class="form-group">
                            <label>Estados</label>
                            <select class="select2" id="estados" data-allow-clear="true" data-placeholder="Selecione">
                                <option></option>
                                <?php foreach ($estados as $estado): ?>
                                    <option value="<?php echo $estado['uf']; ?>"><?php echo $estado['estado']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mx-auto mt-3">
                    <div class="col-12 col-sm">
                        <div class="table-responsive">
                            <table id="data-table" class="table table-condensend table-hover w-100" 
                            data-url="<?php if (isset($datasource)) echo $datasource; ?>" 
                            data-delete_multiple="<?php echo $urlDelete; ?>"
                            >
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="checkbox">
                                                <input type="checkbox" id="checkall">
                                                <label class="checkbox__label" for="checkall"></label>
                                            </div>
                                        </th>
                                        <th>Código</th>
                                        <th>Produto</th>
                                        <th>Estados</th>
                                        <th>Fornecedor</th>
                                        <th>Criado em</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>

<?php echo $scripts; ?>

<script>

    $(function() {

        var table = $('#data-table').DataTable({
            processing: true,
            serverSide: false,
            responsive: true,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'POST',
                dataType: 'json'
            },
            columns: [
                {defaultContent: '', orderable: false, searchable: false, sortable: false },
                { data: 'codigo', name: 'codigo' },
                { data: 'nome_comercial', name: 'nome_comercial', className: 'text-nowrap' },
                { data: 'data', name: 'data', className: 'text-nowrap' },
                { data: 'nome_fantasia', name: 'nome_fantasia' },
                { data: 'data_criacao', name: 'data_criacao', className: 'text-nowrap' }
            ],
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0 }
            ],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            order: [[ 2, 'asc' ]],
            rowCallback: function(row, data) {

                var estados = data.data.split(",");

                var array = [];

                Object.entries(estados).forEach(([key, estado]) => {

                    array.push(`<span class='badge badge-primary mt-1'>${estado}</span>`);
                });

                $('td:eq(3)', row).html(array.join(' ')); 
            },
            drawCallback: function() {}
        });

        $("#estados").on('change', function () {
           
            var col = 3;
            var value = $(this).val();

            table.columns(col).search(value).draw();
        });

        $('#btnAdicionar').on('click', function(e) {
            e.preventDefault();
            let me = $(this);

            $.ajax({
                url: me.attr('href'),
                type: 'get',
                dataType: 'html',
                success: function(response) {
                    $('body').append(response);
                    $('.modal').modal({
                        keyboard: false
                    }, 'show').on('hide.bs.modal', function() {
                        $('.modal').remove();
                        $('#data-table').DataTable().ajax.reload();
                    });
                }
            })
        });

        $('#btnDeleteMultiple').click(function(e) {
            e.preventDefault();
            var elementos = [];
            var url = $();

            $.map(table.rows('.selected').data(), function (item) {
                elementos.push(item.id);
            });

            if (elementos.length > 0) {
                $.post($('#data-table').data('delete_multiple'), {
                    el: elementos
                }, function(xhr) {
                    $('#data-table').DataTable().ajax.reload();
                    formWarning(xhr);
                }, 'JSON');
            } else {
                formWarning({
                    type: 'warning',
                    message: "Nenhum registro selecionado!"
                });
            }
        });

        $('#checkall').click(function(event) {checkall(table, document.getElementById('checkall') ); });
    });

    function checkall(table, checkall) 
    {
        if (checkall.checked == true) {
            table.rows({search:'applied'}).select();
        }else {
            table.rows().deselect();
        }
    }
</script>

</html>
