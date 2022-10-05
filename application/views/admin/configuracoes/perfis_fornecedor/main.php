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
                <div class="row mx-auto mt-3">
                    <div class="col-12 col-sm">
                        <div class="table-responsive">
                            <table id="table" class="table table-condensend table-hover w-100" data-url=" <?php echo (isset($datasource)) ? $datasource : ''; ?>" data-update="<?php echo (isset($url_update)) ? $url_update : ''; ?>" >
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome Fantasia</th>
                                        <th>Razão Social</th>
                                        <th>CNPJ</th>
                                        <th>E-mail</th>
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
    var url_update = $('#table').data('update');

    $(function() {
        var table = $('#table').DataTable({
            processing: true,
            serverSide: false,
            lengthChange: false,
            responsive: true,
            pageLength: 10,
            ajax: {
                url: $('#table').data('url'),
                type: 'POST',
                dataType: 'json'
            },
            columns: [
                { data: 'id', name: 'id', visible: false },
                { data: 'nome_fantasia', name: 'nome_fantasia' },
                { data: 'razao_social', name: 'razao_social' },
                { data: 'cnpj', name: 'cnpj' },
                { data: 'email', name: 'email' },
            ],
            order: [[ 1, 'asc' ]],
            rowCallback: function(row, data) {
                $(row).data('id', data.id).css('cursor', 'pointer');
               
                $('td', row).each(function() {
                    $(this).on('click', function() {
                        window.location.href = `${url_update}${data.id}`
                    });
                });
            },
            drawCallback: function() { }
        });
    });
</script>

</html>
