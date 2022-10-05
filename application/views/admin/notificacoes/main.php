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
                            <table id="data-table" class="table table-condensend table-hover w-100" 
                            data-url="<?php if (isset($datasource)) echo $datasource; ?>" 
                            data-update="<?php echo $url_update; ?>"
                            data-status="<?php echo $url_status; ?>"
                            data-delete_multiple="<?php echo $url_delete_multiple; ?>">
                                <thead>
                                    <tr>
                                        <th>Titulo</th>
                                        <th>Mensagem</th>
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
    
    var url_update = $('#data-table').data('update');
    var url_status = $('#data-table').data('status');
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
                { data: 'titulo', name: 'titulo', className: 'text-nowrap' },
                { data: 'mensagem', name: 'mensagem' },
                { data: 'data_criacao', name: 'data_criacao', className: 'text-nowrap' },
                {defaultContent: '', orderable: false, searchable: false, sortable: false },
            ],
            order: [[ 1, 'asc' ]],
            rowCallback: function(row, data) { 
                $(row).css('cursor', 'pointer');

                if ( data.ativo == 0 ) {
                    $(row).addClass('table-danger');
                } 

                $('td:not(:first-child):not(:last-child)', row).each(function() {
                    $(this).on('click', function() {
                        $.ajax({
                            type: 'post',
                            url: $('#data-table').data('update') + '/' + data.id,
                            dataType: 'html',
                            success: function(response) {
                                $('body').append(response);
                                $('.modal').modal({
                                    keyboard: false
                                }, 'show').on('hide.bs.modal', function() {
                                    $('#data-table').DataTable().ajax.reload();
                                    $('.modal').remove();
                                });
                            }
                        });
                    });
                });
            },
            drawCallback: function() { }
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
    });
</script>
</html>
