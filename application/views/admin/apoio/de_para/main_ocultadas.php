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


                    <table id="data-table" class="table table-condensend table-hover" 
                    data-url="<?php echo $to_datatable; ?>"
                    data-desocultar="<?php echo $url_desocultar; ?>">
                        <thead>
                        <tr>
                            <th>
                                <div class="checkbox">
                                    <input type="checkbox" id="checkall">
                                    <label class="checkbox__label" for="checkall"></label>
                                </div>
                            </th>
                            <th>Código</th>
                            <th>produto</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $scripts; ?>
<script>

    var desocultar = $('#data-table').data('desocultar');

    $(function () {
        var table = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            lengthChange: false,
            pageLength: 5000,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'get',
                dataType: 'json'
            },
            columns: [
                {defaultContent: '', width: '100px', orderable: false, searchable: false },
                {name: 'codigo', data: 'codigo', className: 'text-center'},
                {name: 'descricao', data: 'descricao'},
            ],
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0 },
            ],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            rowCallback: function (row, data) {
               
            },
            drawCallback: function () {
            }
        });

        $('#btnDesocultar').click(function (e) {
            e.preventDefault();
            var elementos = [];
            var url = $();

            $.map(table.rows('.selected').data(), function (item) {   
                elementos.push({
                    id_cliente: item.id_cliente,
                    codigo: item.codigo
                });
            });

            if (elementos.length > 0) {
                $.post(desocultar, {el: elementos}, function (xhr) {
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
</body>

