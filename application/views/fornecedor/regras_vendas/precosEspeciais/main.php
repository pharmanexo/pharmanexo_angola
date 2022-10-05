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
                
                <div class="row">
                    <?php if( isset($filiais) ): ?>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="id_fornecedor">Loja</label>
                                <select class="select2" id="id_fornecedor" data-placeholder="Selecione" data-allow-clear="true">
                                    <option></option>
                                    <?php foreach($filiais as $f): ?>
                                        <option value="<?php echo $f['id']; ?>"><?php echo $f['nome_fantasia']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div> 
                    <?php endif ?>
                </div>

                <div class="table-responsive">
                    <table id="data-table" class="table table-condensed table-hover no-filtered" data-url="<?php if (isset($urlDatatables)) echo $urlDatatables; ?>">
                        <thead>
                        <tr>
                            <th>
                                <div class="checkbox">
                                    <input type="checkbox" id="checkall">
                                    <label class="checkbox__label" for="checkall"></label>
                                </div>
                            </th>
                            <th>Código</th>
                            <th>Produto</th>
                            <th>Comprador</th>
                            <th>Fornecedor</th>
                            <th>Preço Base</th>
                            <th>Preço MIX</th>
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

    var url_delete_multiple = "<?php if( isset($url_delete_multiple) ) echo $url_delete_multiple; ?>";

    $(function () {

        var table = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            lengthChange: false,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'post',
                dataType: 'json',
                data: function (data) {

                    if ($('#id_fornecedor').val() != '') {

                        data.columns[7].search.value = $('#id_fornecedor').val().toString();
                        data.columns[7].search.type = 'equal';
                    }

                    return data;
                }
            },
            columns: [
                { defaultContent: '', width: '100px', orderable: false, searchable: false },
                { name: 'pp.codigo', data: 'codigo'},
                { name: 'pc.nome_comercial', data: 'nome_comercial'},
                { name: 'c.razao_social', data: 'razao_social' },
                { name: 'f.nome_fantasia', data: 'nome_fantasia'},
                { name: 'pp.preco_base', data: 'preco_base'},
                { name: 'pp.preco_mix', data: 'preco_mix'},
                { name: 'pp.id_fornecedor', data: 'id_fornecedor', visible: false},
            ],
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0 },
            ],
            select: { style: 'multi', selector: 'td:first-child' },
            order: [[ 0, "ASC" ]],
            rowCallback: function (row, data) {

            },
            drawCallback: function () {}
        });

        $('#btnDeleteMultiple').click(function (e) {

            e.preventDefault();

            var registros = [];

            $.map(table.rows('.selected').data(), function (item) {
                registros.push({
                    codigo : item.codigo,
                    id_fornecedor : item.id_fornecedor,
                    id_cliente : item.id_cliente
                });
            });

            if (registros.length > 0) {
                $.post(url_delete_multiple, {registros}, function (xhr) {
                    $('#data-table').DataTable().ajax.reload();
                    formWarning(xhr);

                    $('[data-select]').attr('checked', false)
                }, 'JSON');
            } else {

                formWarning({type: 'warning', message: "Nenhum registro selecionado!"});
            }
        });

        $("#id_fornecedor").on('change', function () {

            table.draw();
        });

        $("#btnImport").on("click", function (e) {

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
                    });
                }
            })
        });

        $('#checkall').click(function (event) { checkall(table, document.getElementById('checkall')); });
    });

    function checkall(table, checkall) 
    {
        if (checkall.checked == true) {
            table.rows({search:'applied'}).select();
        } else {
            table.rows().deselect();
        }
    }
</script>
</body>
</html>
