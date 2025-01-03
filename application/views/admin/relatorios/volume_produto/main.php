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


                    <div class="table-responsive">
                        <table id="data-table" class="table table-condensed table-hover no-filtered" data-url="<?php echo $to_datatable; ?>">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="text-nowrap">ID produto</th>
                                    <th class="text-nowrap">Descrição do produto</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>
<?php echo $scripts; ?>
<script>

     var url_produtos = "<?php echo $url_produtos; ?>";

    $(function() {

        var table = $('#data-table').DataTable({
            serverSide: true,
            processing: true,
            lengthChange: false,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'post',
                dataType: 'json'
            },
            columns: [
                {defaultContent: '', width: '', orderable: false, searchable: false, sortable: false },
                {name: 'id_produto', data: 'id_produto'},
                {name: 'descricao', data: 'descricao'},
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
            },
            drawCallback: function() {}
        });
    });
</script>
</html>