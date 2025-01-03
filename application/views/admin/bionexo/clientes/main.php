<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner">
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="estados">Filtrar por Estado</label>
                    <br>
                    <select class="form-control" id="estados" multiple="multiple" style="heigth: 60%"
                            data-live-search="true" title="Selecione">
                        <?php foreach ($estados as $estado): ?>
                            <option value="<?php echo $estado['uf']; ?>"><?php echo $estado['estado']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-6" hidden>
                <div class="form-group">
                    <label for="id_cliente">Filtrar por Comprador</label>
                    <br>
                    <select class="select2" id="id_cliente" data-placeholder="Selecione"
                            data-allow-clear="true" data-toggle="tooltip"
                            title="Clique para selecionar">
                        <option></option>
                        <?php foreach ($compradores as $comprador): ?>
                            <option value="<?php echo $comprador['id']; ?>"><?php echo $comprador['comprador']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="row" hidden>
            <div class="col-8">
                <p><strong>Seu progresso diário</strong></p>
                <div class="progress" style="height: 20px;">
                    <div class="progress-bar" style="width: <?php echo $percent; ?>%; color: #000; " role="progressbar" aria-valuenow="<?php echo $n; ?>" aria-valuemin="320" aria-valuemax="600"><?php echo $n . "/" . META_DEPARA; ?></div>
                </div>
            </div>
            <div class="col-2">
                <p class="text-center"><strong>Meta Diária</strong></p>
                <p class="text-center"><?php echo $n; ?> de <?php echo META_DEPARA; ?> produtos</p>
            </div>
            <div class="col-2" >
                <p class="text-center"><strong>Total Mês</strong></p>
                <p class="text-center"> <?php echo (isset($meta['total']) ? $meta['total'] : 0)?> produtos</p>

            </div>
        </div>
        <hr>
        <div class="card">
            <div class="card-body">
                <p class="small">clique na linha do registro para editá-lo</p>
                <div class="table-responsive col-sm">

                    <table id="data-table" class="table table-condensend table-hover" data-url="<?php echo $datasource; ?>" data-update="<?php echo $urlUpdate ?>" data-depara="<?php echo $urlDepara; ?>">
                        <thead>
                        <tr>
                            <th>CNPJ</th>
                            <th>NOME</th>
                            <th>ESTADO</th>
                            <th>DE/PARA</th>
                            <th>SEM DE/PARA</th>
                            <th>OCULTOS</th>
                            <th>Falta (%)</th>
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

    var urlDepara = $('#data-table').data('depara');

    $(function () {

        $('#estados').selectpicker();

        var dt1 = $('#data-table').DataTable({
            processing: true,
            serverSide: true,
            lengthChange: false,
            pageLength: 5,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'post',
                dataType: 'json',
                data: function (data) {

                    if ($('#estados').val() != '') {

                        data.columns[2].search.value = $('#estados').val().toString();
                        data.columns[2].search.type = 'in';
                    }
                    return data;
                }
            },
            columns: [
                {name: 'c.cnpj', data: 'cnpj', className: 'text-nowrap'},
                {name: 'c.razao_social', data: 'razao_social', className: 'text-nowrap'},
                {name: 'c.estado', data: 'estado', className: 'text-center'},
                {name: 'c.id', data: 'comdepara', className: 'text-center', searchable: false},
                {name: 'c.id', data: 'semdepara', className: 'text-center', searchable: false },
                {name: 'c.id', data: 'ocultos', className: 'text-center', searchable: false },
                {name: 'c.id', data: 'percent', className: 'text-center', searchable: false },
                {defaultContent: '', width: '100px', orderable: false, searchable: false }
            ],
            rowCallback: function (row, data) {
                $(row).data('id', data.codigo).css('cursor', 'pointer');

                var btnDepara = $(`<a href="${urlDepara}/${data.id}" class="btn btn-secondary btn-sm" data-toggle="tooltip"  title="Acessar De -> Para"><i class="fas fa-exchange-alt"></i></a>`);

                $('td:eq(7)', row).html(btnDepara);

                if(data.semdepara > 0){
                    $(row).addClass('table-danger');
                }else{
                    $(row).remove();
                }

                if(data.semdepara == 0){
                    $(row).addClass('table-success');
                }

                if(data.ocultos > 0){
                    $(row).addClass('table-warning');
                }

                $('td:not(:first-child):not(:last-child)', row).each(function() {
                    $(this).on('click', function () {

                        window.location.href = $('#data-table').data('update') + '/' + data.id;
                    });
                });
            },
            drawCallback: function () {

                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        $('#estados, #id_cliente, #cd_cotacao, #integrador').on('change', function () {
            dt1.draw();
        });
    });
</script>
</body>

