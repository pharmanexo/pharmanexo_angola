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
                        <div class="col-6">
                            <div class="form-group">
                                <label for="">Grupo</label>
                                <select class="select2" id="grupos">
                                    <option value="">selecione</option>
                                    <?php foreach ($grupos as $grupo) { ?>
                                         <option value="<?php echo $grupo['id_grupo'] ?>"><?php echo $grupo['grupo'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <label for="">Marca</label>
                            <select class="w-100 select2" id="slct_marcas" style="width: 100%" data-url="<?php echo $slct_marcas; ?>"></select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <?php $url = (isset($datasource)) ? $datasource : ''; ?>
                                <table id="dataTable" class="table table-condensed table-hover w-100" data-url="<?php echo $url; ?>">
                                    <thead>
                                        <tr>
                                            <th>ID Produto</th>
                                            <th>Produto</th>
                                            <th>Marca</th>
                                            <th>Grupo</th>
                                            <th>Motivo Exclusão</th>
                                            <th>Efetuado por</th>
                                            <th></th>
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

    <?php echo $scripts; ?>

    <script>

        var slct_marcas;

        $(function() {

            var dt = $('#dataTable').DataTable({
                serverSide: true,
                lengthChange: false,
                ajax: {
                    url: $('#dataTable').data('url'),
                    type: 'post',
                    dataType: 'json',
                    data: function (data) {
                        let nw_data = data;
                        if ($('#slct_marcas').val() !== '') {
                            let filtro1 = $('#slct_marcas').val();

                            nw_data.columns[6].search.value =  filtro1;
                            nw_data.columns[6].search.type = 'equal';
                        }

                        if ($('#grupos').val() !== '') {
                            let filtro2 = $('#grupos').val();

                            nw_data.columns[7].search.value =  filtro2;
                            nw_data.columns[7].search.type = 'equal';
                        }

                        return nw_data;
                    }
                },
                columns: [
                    {name: 'pms.id_produto', data: 'id_produto', className: 'text-nowrap'},
                    {name: 'pms.descricao', data: 'descricao', className: 'text-nowrap' },
                    {name: 'pms.marca', data: 'marca'},
                    {name: 'pms.grupo', data: 'grupo', searchable: false },
                    {name: 'pms.motivo_exclusao', data: 'motivo_exclusao'},
                    {name: 'u.nome', data: 'usuario' },
                    {name: 'pms.id_marca', data: 'id_marca', searchable: true, visible: false },
                    {name: 'pms.id_grupo', data: 'id_grupo', searchable: true, visible: false }
                ],
                order: [[ 1, "asc" ]],
                rowCallback: function(row, data) {},
                drawCallback: function() {}
            });

            slct_marcas = $('#slct_marcas');

            slct_marcas.select2({
                placeholder: 'Selecione...',
                ajax: {
                    url: slct_marcas.data('url'),
                    type: 'get',
                    dataType: 'json',
                    delay: 300,
                    data: function (params) {
                        return {
                            columns: [{
                                name: 'marca',
                                search: params.term
                            }],
                            page: params.page || 1
                        }
                    }
                },
                escapeMarkup: function(markup) { return markup; },
                processResults: function (data) { return {results: data } },
                templateResult: function (data, container) {
                    if (!data.id) { return data.text; }

                    var ret = data.marca;

                    return ret;
                },
                templateSelection: function (data, container) {
                    if (!data.id) { return data.text; }
                   
                    return (typeof data.marca !== 'undefined') ? `${data.marca}` : '';
                }
            });

            $('#slct_marcas, #grupos').on('change', function() { dt.ajax.reload(); });
            
        });
    </script>
</html>