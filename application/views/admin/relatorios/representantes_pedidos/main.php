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
                    <div class="col-4">
                        <div class="form-group">
                            <label for="filtro-cliente">Filtrar por representante</label>
                            <select class="form-control select2" id="filtro-rep" data-index="0">
                                <option value="">Selecione</option>
                                <?php foreach ($representantes as $k => $value) { ?>
                                <option value="<?php echo $value['id']; ?>"><?php echo "{$value['nome']}"; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="filtro-cliente">Filtrar por fornecedor</label>
                            <select class="form-control select2" id="filtro_forn">
                                <option value="">Selecione</option>
                                <?php foreach ($fornecedores as $forn => $value) { ?>
                                <option value="<?php echo $value['id']; ?>"><?php echo "{$value['cnpj']} - {$value['razao_social']}"; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="filtro-cliente">Filtrar por status</label>
                            <select class="form-control select2" id="filtro-status" data-index="6">
                                <option value="">Selecione</option>
                                <?php foreach (statusPedidoRepresentante() as $k => $value) { ?>
                                <option value="<?php echo $k; ?>"><?php echo $value; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 col-sm">
                        <div class="table-responsive">

                            <table id="table" class="table table-condensend table-hover w-100" 
                                data-url="<?php echo $datasource; ?>" data-update="<?php echo $url_update; ?>">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th> Representante </th>
                                        <th> Fornecedor </th>
                                        <th> Comprador </th>
                                        <th> Situação </th>
                                        <th> Situação </th>
                                        <th> UF do Cliente </th>
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
            serverSide: true,
            lengthChange: false,
            responsive: true,
            pageLength: 10,
            ajax: {
                url: $('#table').data('url'),
                type: 'POST',
                dataType: 'json',
                data: function(data){
                    let nw_data = data;
                    if ( $('#filtro_forn').val() !== '' ){
                        nw_data.columns[1].search.value = $('#filtro_forn').val();
                        nw_data.columns[1].search.type = 'equal';
                    }
                    return nw_data;
                }
            },
            columns: [
                { data: 'id_representante', name: 'rep.id', visible: false },
                { data: 'id_fornecedor', name: 'forn.id', visible: false },
                { data: 'representante', name: 'rep.nome' },
                { data: 'fornecedor', name: 'forn.razao_social' },
                { data: 'comprador', name: 'comp.razao_social' },
                { data: 'lbl_situacao', name: 'pr.situacao' },
                { data: 'situacao', name: 'pr.situacao', visible: false },
                { data: 'uf_comprador', name: 'pr.uf_comprador' },
            ],
            select: { style: "multi", selector: "td:first-child"},
            order: [[ 1, 'asc' ]],
            rowCallback: function(row, data) {
                $(row).data('id', data.id).css('cursor', 'pointer');

                switch (data.situacao) {
                    case '1':
                        $(row).addClass('table-light');
                        break;
                    case '2':
                        $(row).addClass('table-warning');
                        break;
                    case '3':
                        $(row).addClass('table-info');
                        break;
                    case '4':
                        $(row).addClass('table-success');
                        break;
                    case '5':
                        $(row).addClass('table-danger');
                        break;
                }

                $('td', row).each(function() {
                    $(this).on('click', function () {
                        window.location.href = url_update + data.id;
                    });
                });


            },
            drawCallback: function() {
            },
        });

        $('[data-index]').on('keyup change', function() {
            var col = $(this).data('index');
            var value = $(this).val();

            table.columns(col).search(value).draw();
        });

        $('#filtro_forn').on('change', function() {
            table.ajax.reload();
        });
    });
</script>

</html>
