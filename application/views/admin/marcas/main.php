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
                    <div class="col-7">
                        <div class="form-group">
                            <label for="fornecedores">Fornecedor</label>
                            <select class="select2" id="fornecedores">
                                <option value="">Selecione</option>
                                <?php foreach($fornecedores as $f) { ?>
                                    <option value="<?php echo $f['id'] ?>"><?php echo "{$f['cnpj']} - {$f['razao_social']}" ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="table-responsive col-sm">
                    <table id="data-table" class="table table-condensend table-hover" data-url="<?php echo $to_datatable; ?>" data-link="<?php echo $url_link; ?>" data-exportar="<?php echo $url_exportar ?>">
                        <thead>
                        <tr>
                            <th>Código</th>
                            <th>Produto</th>
                            <th>Marca</th>
                            <th>Marca Sintese</th>
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

    var url_link = $('#data-table').data('link');
    var url_exportar = $('#data-table').data('exportar');

    $(function () {

        initTable( $("#data-table").data('url') );

        $("#fornecedores").change(function() {

            if ( $(this).val() != "" ) {

                $('#btnExport').attr('href', url_exportar + '/' + $(this).val());

                initTable( $("#data-table").data('url') + '/' + $(this).val(), 1 );
            }   
        });
    });

    function initTable(url, novo = null) {

        if (novo != null) {
            $('#data-table').DataTable().destroy();
        }

        var dt = $('#data-table').DataTable({
            "processing": true,
            "serverSide": false,
            paginate: false,
            ajax: {
                url: url,
                type: 'post',
                dataType: 'json'
            },
            "order": [[ 0, "asc" ]],
            columns: [
                { name: 'codigo', data: 'codigo', width: '120px' },
                { name: 'produto_descricao', data: 'produto_descricao' },
                { name: 'marca', data: 'marca' },
                { name: 'marca_sintese', data: 'marca_sintese' },
                { defaultContent: '', orderable: false, searchable: false },
            ],
            rowCallback: function (row, data) {
                var btn = $(`<button href="${url_link}${data.id_marca}" data-codigo="${data.codigo}" data-marca="${data.id_marca}" data-toggle="tooltip" title="Alterar Marca" class="btn btn-sm btn-secondary"><i class="fas fa-link"></i></button>`);

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
                                $('#modalMarca').remove();
                                dt.ajax.reload();
                            }).on('shown.bs.modal', function() {
                                var codigo = btn.data('codigo');
                                var id_marca = btn.data('marca');

                                var marca;
                                if (id_marca == 0 || id_marca == null) {
                                    marca = "Sem Marca Sintese";
                                } else {
                                    marca = data.marca_sintese;
                                }

                                $('#codigo', '#modalMarca').val(codigo);
                                $('#id_fornecedor', '#modalMarca').val( $("#fornecedores").val() );
                                $('#id_marca_old', '#modalMarca').val(marca);
                            });
                        }
                    });         
                });

                $('td:eq(4)', row).html(btn);
            },
            drawCallback: function () {

                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    }
</script>
</body>

