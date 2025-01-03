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
                    <div class="dropdown text-right">
                        <button class="btn btn-light" data-toggle="dropdown" aria-expanded="true"><i class="fas fa-download"></i> Exportar</button>
                        <div class="dropdown-menu " x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 34px, 0px);">
                            <a href="" class="dropdown-item">Planilha Excel/XLS</a>
                            <a href="" class="dropdown-item">PDF</a>
                        </div>
                    </div>

                    <table id="data-table" class="table table-condensend table-hover" data-url="<?php echo $dataTable; ?>" data-update="<?php echo $url_update ?>">
                        <thead>
                        <tr>
                            <th >Nome</th>
                            <th>E-mail</th>
                            <th>Situação</th>
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
        var dt1 = $('#data-table').DataTable({
            "processing": true,
            "serverSide": false,
            lengthChange: false,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'get',
                dataType: 'json'
            },
            columns: [
                {
                    name: 'nome',
                    data: 'nome',
                },
                {
                    name: 'email',
                    data: 'email'
                },
                {
                    name: 'ativo',
                    data: 'ativo'
                },
            ],
            rowCallback: function (row, data) {
                $(row).data('id', data.id).css('cursor', 'pointer');
                var icon = `<i class='fas fa-check text-success'></i>`;
                if (data.ativo == '0'){
                    icon = `<i class='fas fa-ban text-danger'></i>`;
                }
                $('td:eq(2)', row).html(icon);



            },
            drawCallback: function () {
                $('table tbody tr').each(function () {
                    $(this).on('click', function () {
                        console.log($(this).data('id'));
                        window.location.href = $('#data-table').data('update') + $(this).data('id')
                    })
                })
            }
        });

    });
</script>
</body>

