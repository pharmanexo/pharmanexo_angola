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
                <div class="row col-12">
                    <p class="text-muted">* Para alterar a configuração de algum registro basta selecionar um comprador existente em Novo Registro.</p>
                </div>               
                <div class="row mt-3">
                    <div class="col-5">
                        <label for="id_cliente">Filtrar por Comprador</label>
                        <select class="select2" id="id_cliente" data-placeholder="Selecione" data-allow-clear="true">
                            <option></option>
                            <?php foreach($compradores as $c) { ?>
                                <option value="<?php echo $c['id'] ?>"><?php echo "{$c['comprador']}" ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-sm">
                        <div class="table-responsive">
                            <table id="data-table" class="table table-condensend table-hover w-100" 
                            data-url="<?php if (isset($to_datatable)) echo $to_datatable; ?>" 
                            data-update="<?php if (isset($url_update)) echo $url_update; ?>" 
                            data-delete_multiple="<?php if (isset($url_delete_multiple)) echo $url_delete_multiple; ?>">
                                <thead>
                                    <tr>
                                        <th>
                                            <div class="checkbox">
                                                <input type="checkbox" id="checkall">
                                                <label class="checkbox__label" for="checkall"></label>
                                            </div>
                                        </th>
                                        <th>CNPJ</th>
                                        <th>Comprador</th>
                                        <th>Tipo config.</th>
                                        <th>Criado em</th>
                                        <th></th>
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

    var url_delete_multiple = $('#data-table').data('delete_multiple');

    $(function() {

        var table = $('#data-table').DataTable({
            processing: true,
            serverSide: false,
            lengthChange: false,
            responsive: true,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'POST',
                dataType: 'json'
            },
            columns: [
                {defaultContent: '', orderable: false, searchable: false, sortable: false },
                { data: 'cnpj', name: 'cnpj', className: 'text-nowrap'},
                { data: 'comprador', name: 'comprador', className: 'text-nowrap'},
                { data: 'tipo', name: 'tipo', searchable: false },
                { data: 'data_criacao', name: 'data_criacao', className: 'text-nowrap' },
                { data: 'id_cliente', name: 'id_cliente', searchable: true, visible: false }
            ],
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0 }
            ],
            select: { style: 'multi', selector: 'td:first-child'},
            order: [[ 1, 'asc' ]],
            rowCallback: function(row, data) {
                // $(row).css('cursor', 'pointer');

                $('td:eq(2)', row).html(`<a data-toggle="tooltip" title="${data.razao_social}">${data.comprador}</a>`);

                // $('td:not(:first-child)', row).each(function () {
                //     $(this).on('click', function () {

                //         window.location.href = $('#data-table').data('update') + '/' + data.id;
                //     });
                // });
            },
            drawCallback: function() {},
            initComplete: function (settings, json) {

                $(this).closest('.dataTables_wrapper').prepend('');
            }
        });

        $("#id_cliente").on('change', function () {

            var value = $(this).val();

            table.columns(5).search(value).draw();
        });

        $('#btnDeleteMultiple').click(function(e) {
            e.preventDefault();
            var elementos = [];
            var url = $();

            $.map(table.rows('.selected').data(), function (item) {
                elementos.push(item.id);
            });

            if (elementos.length > 0) {
                $.post(url_delete_multiple, {
                    el: elementos
                }, function(xhr) {
                    $('#data-table').DataTable().ajax.reload();
                    formWarning(xhr);
                }, 'JSON');
            } else {
                
                formWarning({type: 'warning', message: "Nenhum registro selecionado!"});
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
