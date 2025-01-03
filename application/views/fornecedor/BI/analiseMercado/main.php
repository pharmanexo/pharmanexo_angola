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
                            data-detail="<?php if (isset($urlDetalhes)) echo $urlDetalhes; ?>"
                            >
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Produto</th>
                                        <th>Estados</th>
                                        <th>Fornecedor</th>
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

    var urlDetalhes = $('#data-table').data('detail');

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
                { data: 'codigo', name: 'codigo' },
                { data: 'nome_comercial', name: 'nome_comercial', className: 'text-nowrap' },
                { data: 'data', name: 'data', className: 'text-nowrap' },
                { data: 'nome_fantasia', name: 'nome_fantasia' },
                { defaultContent: '', orderable: false, searchable: false, sortable: false },
            ],
            order: [[ 1, 'asc' ]],
            rowCallback: function(row, data) {

                var estados = data.data.split(",");

                var array = [];

                Object.entries(estados).forEach(([key, estado]) => {

                    array.push(`<span class='badge badge-primary mt-1'>${estado}</span>`);
                });

                $('td:eq(2)', row).html(array.join(' ')); 

                var btnDetalhes = $(`<a href="${urlDetalhes}/${data.id}" data-toggle="tooltip"  title="Visualizar detalhes"><i class="fas fa-eye" style="color: #000000"></i></i></a>`);

                $('td:eq(4)', row).html(btnDetalhes); 
            },
            drawCallback: function() {}
        });

        $("#estados").on('change', function () {
           
            var col = 2;
            var value = $(this).val();

            table.columns(col).search(value).draw();
        });

    });
</script>

</html>
