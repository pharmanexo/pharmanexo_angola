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

                    <h4 class="card-title">Selecioanr Produtos</h4>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label>Produto</label>
                                <select class="form-control w-100" id="slct_produtos" data-url="<?php echo $url_produtos; ?>" style="width: 100%"></select>
                            </div>
                        </div>
                    </div>


                    <h4 class="card-title">Filtrar por</h4>
                    
                    <div class="row">
                        <div class="col-3">
                            <div class="form-group">
                                <label>Fornecedor</label>
                                <select class="select2" id="fornecedor" data-placeholder="Todos" data-allow-clear="true">
                                    <option></option>
                                    <?php foreach ($fornecedores as $k => $v) : ?>
                                        <option value="<?php echo $v['id']; ?>"><?php echo $v['nome_fantasia']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-5">
                            <div class="form-group">
                                <label for="id_cliente">Comprador</label>
                                <select class="select2" id="id_cliente" data-placeholder="Todos" data-allow-clear="true">
                                    <option></option>
                                    <?php foreach ($compradores as $k => $v) : ?>
                                        <option value="<?php echo $v['id']; ?>"><?php echo $v['cnpj'] . ' - ' . $v['razao_social']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-2">
                            <label>Data Inicio</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="zmdi zmdi-calendar"></i></span>
                                </div>
                                <input type="date" class="form-control hidden-md-up" placeholder="Selecione uma data">
                                <input name="dataini" type="text" id="filter-start-date" class="form-control date-picker hidden-sm-down" placeholder="Selecione">
                            </div>
                        </div>  
                        <div class="col-2">
                            <label>Data fim</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="zmdi zmdi-calendar"></i></span>
                                </div>
                                <input type="date" class="form-control hidden-md-up" placeholder="Selecione uma data">
                                <input name="datafim" type="text" id="filter-end-date" class="form-control date-picker hidden-sm-down" placeholder="Selecione">
                            </div>
                        </div>  
                    </div>

                    <button type="button" class="btn btn-primary btn-lg btn-block">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
<?php echo $scripts; ?>
<script>
    var url_exportar = "<?php echo $url_exportar; ?>";

    $(function() {

        $("#filter-start-date").flatpickr({ "locale": "pt", "dateFormat": "d/m/Y", 'defaultDate': "<?php echo date('01/m/Y'); ?>" });
        $("#filter-end-date").flatpickr({ "locale": "pt", "dateFormat": "d/m/Y", 'defaultDate': "<?php echo date('t/m/Y'); ?>" });


        var slct_prods = $('#slct_produtos');

        slct_prods.select2({
            placeholder: 'Selecione...',
            ajax: {
                url: slct_prods.data('url'),
                type: 'get',
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    return {
                        columns: [{
                            name: 'descricao',
                            search: params.term
                        }],
                        page: params.page || 1
                    }
                }
            },
            processResults: function (data) {
                return {
                    results: data
                }
            },
            templateResult: function (data, container) {

                console.log(data);

                var ret = `${data.id_produto} -  ${data.descricao}`;

                return ret;
            },
            templateSelection: function (data, container) {

                return (typeof data.descricao !== 'undefined') ? `${data.id_produto} - ${data.descricao}` : 'Selecione..';
            }
        });

        slct_prods.on('select2:select', function (e) {

           
        });


        $('#fornecedor').on('change', function() {

            $('#btnExport').attr('href', url_exportar + $(this).val());

            if ($(this).val() != "") {

                $('#data-table').DataTable().destroy();

                newtable( $(this).val() ); 
            }
        }); 
    });

    function newtable(id_fornecedor = null) 
    {
            
        if ( id_fornecedor != null) {

            var url = $('#data-table').data('url') + id_fornecedor;

            var dt = $('#data-table').DataTable({
                processing: true,
                serverSide: false,
                pageLength: 50,
                ajax: {
                    url: url,
                    type: 'post',
                    dataType: 'json'
                },
                order: [[ 0, "ASC" ]],
                columns: [
                    { name: 'oc_prod.Ds_Produto_Comprador', data: 'produto', className: 'text-nowrap' },
                    { name: 'oc_prod.Ds_marca', data: 'marca', className: 'text-nowrap'  },
                    { name: 'oc_prod.Ds_Unidade_Compra', data: 'unidade', className: 'text-nowrap' },
                    { name: 'qtd_total', data: 'qtd_total', searchable: false, className: 'text-center text-nowrap' },
                    { name: 'valor_total', data: 'valor_total', searchable: false },
                ],
                rowCallback: function(row, data) {
                },
                drawCallback: function() {}
            });
        } else {

            var dt = $('#data-table').DataTable({
                serverSide: false,
                processing: false,
                ordering: false,
                rowCallback: function(row, data) {},
                drawCallback: function() {}
            });
        }
    }
</script>
</html>