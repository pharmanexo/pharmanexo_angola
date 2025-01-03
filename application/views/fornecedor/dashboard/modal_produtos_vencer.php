<div class="modal fade" id="modalProdutosVencer" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><?php echo $title; ?></h5>
            </div>

            <div class="modal-body">
                <div class="table-responsive">
                    <table id="dataTable" class="table table-condensend table-hover" data-url="<?php echo $datasource ?>">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Produto</th>
                                <th>Marca</th>
                                <th>Quantidade</th>
                                <th>Validade</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-link" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>


<script>
    $(function() {
        var dt = $('#dataTable').DataTable({
            serverSide: true,
            lengthChange: false,
            displayLength: 10,
            ajax: {
                url: $('#dataTable').data('url'),
                type: 'get',
                dataType: 'json'
            },

            columns: [
                {name: 'produtos_fornecedores_validades.codigo', data: 'codigo'},
                {name: 'produtos_fornecedores_validades.nome_comercial', data: 'nome_comercial'},
                {name: 'produtos_fornecedores_validades.marca', data: 'marca'},
                {name: 'produtos_fornecedores_validades.quantidade_unidade', data: 'quantidade_unidade'},
                {name: 'produtos_fornecedores_validades.validade', data: 'validade'}
            ],

            rowCallback: function(row, data) {},
            drawCallback: function() {
            }
        });
    });
</script>