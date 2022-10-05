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
                <div class="table-responsive col-sm">
                    <table id="data-table" class="table table-condensend table-hover" data-url="<?php echo $to_datatable; ?>">
                        <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Marca</th>
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

    $(function () {
        var table = $('#data-table').DataTable({
            processing: true,
            serverSide: false,
            lengthChange: false,
            pageLength: 10,
            "order": [[ 0, "asc" ]],
            ajax: {
                url: $('#data-table').data('url'),
                type: 'POST',
                dataType: 'json'
            },
            columns: [
                {name: 'PC.nome_comercial', data: 'produto'},
                {name: 'PC.marca', data: 'marca'},
            ],
            rowCallback: function (row, data) {
               
            },
            drawCallback: function () {}
        });
    });
</script>
</body>

