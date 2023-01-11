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
                        <label>Portal</label>
                        <select name="s_int" data-toggle="tooltip" title="" id="s_int" class="form-control" data-original-title="Selecione qual integrador da cotação">
                            <option value="TODOS">Todos</option>
                            <option value="SINTESE">Síntese</option>
                            <option value="BIONEXO">Bionexo</option>
                            <option value="APOIO" >Apoio</option>
                        </select>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label>Código</label>
                            <input type="number" id="codigo" class="form-control" min="1" step="1">
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" id="nome" class="form-control">
                        </div>
                    </div>

                </div>

                <div class="row">
                    <button type="button" class="btn btn-primary btn-block" id="buscarCotacoes">
                        <i class="fas fa-search"></i> Buscar Cotações
                    </button>
                </div>

                <div class="table-responsive col-sm" id="campoTabela" hidden>
                    <table id="data-table" class="table table-condensend table-hover"
                    data-url="<?php echo $urlDatatable; ?>"
                    data-filtro="<?php echo $urlFiltro; ?>"
                    data-cotacao="<?php echo $urlCotacao; ?>">
                        <thead>
                        <tr>
                            <th>Numero</th>
                            <th>Qtd Solicitada</th>
                            <th>Periodo</th>
                            <th>UF</th>
                            <th>Comprador</th>
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
<?php echo $scripts; ?>
<script>

    $(function () {

        $("#codigo, #nome").keyup(function(event) { if (event.keyCode === 13) { document.getElementById("buscarCotacoes").click(); } });

        $("#buscarCotacoes").on('click', function () {

            $('#buscarCotacoes').html("<i class='fa fa-spin fa-spinner'></i> Buscando cotações... ").attr('disabled', true);
            $("#campoTabela").attr('hidden', true);

            if ( $.fn.DataTable.isDataTable('#data-table') ) {

                $('#data-table').DataTable().destroy();
            }

            if ( $("#codigo").val() != '' || $("#nome").val() != '' ) {

                var table = $('#data-table').DataTable({
                    processing: true,
                    serverSide: true,
                    searching: false,
                    ajax: {
                        url: $('#data-table').data('filtro'),
                        type: "POST",
                        data: {
                            codigo : $("#codigo").val(),
                            nome: $("#nome").val(),
                            s_integrador: $('#s_int option:selected').val()}
                    },
                     order: [[5, "ASC"]],
                    columns: [
                        { data: 'cd_cotacao', className: 'text-nowrap' },
                        { data: 'qtd_solicitada', className: 'text-nowrap  text-center' },
                        { data: 'data', className: 'text-nowrap text-center' },
                        { data: 'estado', className: 'text-nowrap  text-center' },
                        { data: 'comprador', className: 'text-nowrap'},
                        { data: 'dt_fim_cotacao', visible: false},
                        { data: 'integrador', visible: false},
                        { defaultContent: '', orderable: false, searchable: false },
                    ],
                    rowCallback: function (row, data) {
                        $(row).css('cursor', 'pointer');

                        $('td', row).each(function () {
                            $(this).on('click', function () {

                                window.location.href = $('#data-table').data('cotacao') + '/'+data.integrador+'/' + data.cd_cotacao;
                            })
                        })

                    },
                    drawCallback: function() {

                        $("#campoTabela").attr('hidden', false);
                        $('#buscarCotacoes').html("<i class='fas fa-search'></i> Buscar Cotações").attr('disabled', false);
                    }
                });
            } else {

                formWarning({ 'type': 'warning', 'message': 'É necessário preencher algum dos filtros para exibir as cotações!' });
                $('#buscarCotacoes').html("<i class='fas fa-search'></i> Buscar Cotações").attr('disabled', false);
            }
        });
    });
</script>
</body>

