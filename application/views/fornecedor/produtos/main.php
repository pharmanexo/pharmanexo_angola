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
                <div class="row">
                    <div class="col-12 mb-3">
                        <small>Clique nas opções para filtrar</small>
                    </div>
                    <div class="col-12">
                        <button type="button" id="btnAtivar" data-index="0" class="btn btn-light mr-3">Ativos</button>
                        <button type="button" id="btnInativar" data-index="1" class="btn" style="background-color: #ffd6d5;" >Inativos</button>
                    </div>

                </div>
                <div class="table-responsive col-sm">
                    <table id="data-table" class="table table-condensend table-hover" data-url="<?php echo $to_datatable; ?>" data-update="<?php echo $url_update ?>" data-block="<?php echo $url_block ?>">
                        <thead>
                        <tr>
                            <th>Código</th>
                            <th>Descrição</th>
                            <th></th>
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

     var url_block = $('#data-table').data('block');

    $(function () {
        var dt1 = $('#data-table').DataTable({
            "processing": true,
            "serverSide": false,
            lengthChange: false,
            buttons: [],
            ajax: {
                url: $('#data-table').data('url'),
                type: 'get',
                dataType: 'json'
            },
            columns: [
                { name: 'c.codprod', data: 'codigo'},
                { name: 'c.nome', data: 'nome' },
                { defaultContent: '', orderable: false, searchable: false },
                { name: 'cd.situacao', data: 'ativo', visible: false },
            ],
            order: [[ 1, 'asc' ]],
            rowCallback: function (row, data) {
                $(row).data('codigo', data.codigo).css('cursor', 'pointer');

                if (data.bloqueado == '1') {

                    $(row).addClass('table-danger');
                    var check = $(`<a data-href="${url_block}${data.codigo}/1" data-toggle="tooltip"  title="Ativar este produto" class="text-success" data-block='${data.codigo}'><i class="fas fa-check"></i></a>`);
                } else {

                    var check = $(`<a data-href="${url_block}${data.codigo}" data-toggle="tooltip"  title="Inativar este produto" class="text-danger" data-block='${data.codigo}'><i class="fas fa-ban"></i></a>`);
                }

                check.on('click', function (e) {
                    e.preventDefault();
                    if ($(this).prop("checked")) {
                        var url = $(this).data('href');
                    } else {
                        var url = $(this).data('href');
                    }

                    $.post(url, function (xhr) {
                        formWarning(xhr);
                        if(xhr.type == 'success'){
                            $('#data-table').DataTable().ajax.reload();
                        }
                    }, 'JSON');
                });

                $('td:eq(3)', row).html(check);
                $('td:not(:last-child)', row).each(function() {
                    $(this).on('click', function () {
                        window.location.href = $('#data-table').data('update') + data.codigo
                    });
                });
            },
            drawCallback: function () {

                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        $('#btnAtivar, #btnInativar').on('click', function() {

            var value = $(this).data('index');

            var col = 4;

            $('#data-table').DataTable().columns(col).search(value).draw();
        });
    });
</script>
</body>

