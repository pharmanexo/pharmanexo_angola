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
                    <div class="col-4">
                        <div class="form-group">
                            <label for="">Filtrar por status</label>
                            <select name="" data-index="4" class="form-control" id="status">
                                <option value="">Seleciona...</option>
                                <option value="0">Ativos</option>
                                <option value="1">Inativos</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label>Legenda:</label>
                            <button type="button" class="btn btn-sm" style="background-color: #ffd6d5;">INATIVOS</button>
                            <button type="button" class="btn btn-light btn-sm">ATIVOS</button>                            
                        </div>
                    </div>
                </div>

                <div class="table-responsive col-sm">
                    <table id="data-table" class="table table-condensend table-hover" data-url="<?php echo $to_datatable; ?>" data-update="<?php echo $url_update ?>" data-delete="<?php echo $url_block ?>">
                        <thead>
                        <tr>
                            <th>Código</th>
                            <th>Descrição</th>
                            <th>Marca</th>
                            <th>Situação</th>
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

    var url_delete = $('#data-table').data('delete');
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
                { name: 'codigo', data: 'codigo', width: '120px'},
                { name: 'produto_descricao', data: 'produto_descricao' },
                { name: 'marca', data: 'marca' },
                { defaultContent: '', width: '100px', orderable: false, searchable: false },
                { name: 'bloqueado', data: 'bloqueado', visible: false },
            ],
            rowCallback: function (row, data) {
                $(row).data('id', data.codigo).css('cursor', 'pointer');

                if (data.bloqueado == '1' || data.ativo == '0') {
                    $(row).addClass('table-danger');
                    var check = "Inativo";
                } else {
                    var check = "Ativo";
                }

                $('td:eq(3)', row).html(check);
                $('td:not(:last-child)', row).each(function() {
                    $(this).on('click', function () {
                        window.location.href = $('#data-table').data('update') + data.codigo
                    });
                });

            },
            drawCallback: function () {

                $('[data-toggle="tooltip"]').tooltip();

                $('[data-block]').on('change', function (e) {
                    if ($(this).prop("checked")) {
                        var url = url_delete + $(this).data('block') + '/1';
                    } else {
                        var url = url_delete + $(this).data('block');
                    }

                    e.preventDefault();
                    $.get(url, function (xhr) {
                        formWarning(xhr);
                        if(xhr.type == 'success'){
                            $('#data-table').DataTable().ajax.reload();
                        }
                    }, 'JSON');
                });
            }
        });

        $('[data-index]').on('keyup change', function() {
            var col = $(this).data('index');
            var value = $(this).val();

            $('#data-table').DataTable().columns(col).search(value).draw();
        });

        // remove filter
        $('[data-action="reset-filter"]').click(function(e) {
            e.preventDefault();
            $('[data-index]').val(null);
            $('#data-table').columns([0, 1, 2, 4]).search('').draw();
        });
    });
</script>
</body>

