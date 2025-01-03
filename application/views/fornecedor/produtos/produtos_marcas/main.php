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
                    <table id="data-table" class="table table-condensend table-hover" data-url="<?php echo $to_datatable; ?>" data-link="<?php echo $url_link; ?>">
                        <thead>
                        <tr>
                            <th>Marca</th>
                            <th>Sintese</th>
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

    var url_link = $('#data-table').data('link');

    $(function () {

        var dt1 = $('#data-table').DataTable({
            "processing": true,
            "serverSide": false,
            lengthChange: false,
            paginate: false,
            buttons: [],
            ajax: {
                url: $('#data-table').data('url'),
                type: 'get',
                dataType: 'json'
            },
            columns: [
                { name: 'marca', data: 'marca' },
                { defaultContent: '', orderable: false, searchable: false },
            ],
            rowCallback: function (row, data) {
                var btn = $(`<button href="${url_link}" data-marca="${data.marca}" data-toggle="tooltip" title="Combinar Marcas" data- class="btn btn-sm btn-secondary"><i class="fas fa-link"></i></button>`);


                btn.click(function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: btn.attr('href'),
                        type: 'get',
                        dataType: 'html',
                        success: function(xhr) {
                            $('body').append(xhr);
                            $('.modal').modal({
                                keyboard: false
                            }, 'show').on('hide.bs.modal', function() {
                                $('.modal').remove();
                                dt1.ajax.reload();
                            }).on('shown.bs.modal', function() {
                                var marca = btn.data('marca');
                                $('#desc_marca', '#modalMarca').val(marca);
                            });
                        }
                    });
                   
                });

                $('td:eq(1)', row).html(btn);
                

            },
            drawCallback: function () {

                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    });
</script>
</body>

