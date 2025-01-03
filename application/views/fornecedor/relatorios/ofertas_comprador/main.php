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
                <form action="<?php if(isset($url_filtros)) echo $url_filtros; ?>" method="post" id="formFiltro">
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="periodo">Filtrar por período</label>
                                <select class="select2" name="periodo" id="periodo">
                                    <option value="current">Mês atual</option>
                                    <option value="30days">Ultímos 30 dias</option>
                                    <option value="60days">Ultímos 60 dias</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="estados">Filtrar por Estado</label>
                                    <select class="select2" id="estado" name="estado" data-placeholder="Todas" data-allow-clear="true">
                                        <option data-url="<?php echo base_url("fornecedor/cotacoes"); ?>"></option>
                                        <?php foreach ($estados as $e): ?>
                                            <option value="<?php echo $e['uf']; ?>" ><?php echo $e['estado']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive col-sm">
                    <table id="data-table" class="table table-condensend table-hover" data-url="<?php echo $to_datatable; ?>">
                        <thead>
                            <tr>
                                <th>CNPJ</th>
                                <th>Nome</th>
                                <th>UF</th>
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

        var table = $('#data-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'POST',
                dataType: 'json',
                data: function (data) {

                    data.estado = 'ES';

                    return data

                }
            },
            columns: [
                { name: 'c.cnpj', data: 'cnpj' },
                { name: 'c.razao_social', data: 'razao_social' },
                { name: 'c.estado', data: 'estado' }
            ],
            order: [[ 1, 'asc' ]],
            rowCallback: function (row, data) { },
            drawCallback: function () {
            },
            initComplete: function (settings, json) {
                $(this).closest('.dataTables_wrapper').prepend(''); // Add custom button (fullscreen, print and export)
            }
        });

        $('#periodo, #estado').on('change', function () {

            var form = $("#formFiltro");

            $.ajax({
                url: form.attr('action'),
                type: 'post',
                data: form.serialize(),
                success: function(xhr) {

                    table.ajax.reload();  
                },
                error: function(xhr) {}
            });
        });
    });
</script>
</body>