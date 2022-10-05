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
                            data-url="<?php if (isset($to_datatable)) echo $to_datatable; ?>" 
                            data-update="<?php echo $url_update; ?>"
                            data-status="<?php echo $url_status; ?>"
                            >
                                <thead>
                                    <tr>
                                        <th>Titulo</th>
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
    
    var url_status = $('#data-table').data('status');

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
                { data: 'data_criacao', name: 'data_criacao', className: 'text-nowrap' },
                {defaultContent: '', orderable: false, searchable: false, sortable: false },
            ],
            order: [[ 0, 'asc' ]],
            rowCallback: function(row, data) { 
                var icon = '';

                if ( data.ativo == 0 ) {
                    $(row).addClass('table-danger');
                    icon = `<a href="${url_status}/${data.id}/1" class="text-success" data-toogle="tooltip" title="Ativar notificação" data-msg="ativar"><i class="fas fa-thumbs-up"></i></a>`;
                } else {

                    icon = `<a href="${url_status}/${data.id}" class="text-danger" data-toogle="tooltip" title="Inativar notificação" data-msg="inativar"><i class="fas fa-ban"></i></a>`;
                }

                $('td:eq(2)', row).html(icon);
            },
            drawCallback: function() {
                $('[data-toggle="tooltip"]').tooltip();

                $('[data-msg]').click(function (e) {
                    e.preventDefault();
                    var url = $(this).attr('href');

                    var text = $(this).data('msg');

                    Swal.fire({
                        title: `Tem certeza que deseja ${text} esta notificação?`,
                        text: "",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: `Sim, ${text}`,
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {

                        if (result.value) {
                            $.ajax({
                                url: url,
                                type: 'post',
                                contentType: false,
                                processData: false,
                                data: {},
                                success: function(xhr) {

                                    formWarning(xhr);
                                    $('#data-table').DataTable().ajax.reload();
                                },
                                error: function(xhr) { console.log(xhr);}
                            });
                        }
                    })
                });
            }
        });
    });
</script>

</html>
