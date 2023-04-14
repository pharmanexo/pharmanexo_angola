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
                <ul class="nav nav-tabs pull-left">
                    <li class="nav-item">
                        <a class="nav-item nav-link active"
                           href="<?php echo base_url("cotacoes"); ?>" >Cotações em aberto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           href="<?php echo base_url("cotacoes/encerradas"); ?>">Cotações encerradas</a>
                    </li>
                </ul>
                <table id="data-table" class="table w-100 table-hover" data-url="<?php if (isset($to_datatable)) echo $to_datatable; ?>"
                       data-update="<?php if(isset($urlUpdate)) echo $urlUpdate; ?>">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>CD Cotação</th>
                        <th>Dt Abertura</th>
                        <th>Dt Vencimento</th>
                        <th>Descrição</th>
                        <th>Comprador</th>
                        <th>UF Cotação</th>
                        <th></th>
                    </tr>
                    </thead>
                </table>

            </div>
        </div>
    </div>
</div>
</body>

<?php echo $scripts; ?>

<script>

    $(function () {


        var dt1 = $('#data-table').DataTable({
            serverSide: false,
            pageLength: 100,
            lengthChange: false,
            dom: 'Bfrtip',
            ajax: {
                url: $('#data-table').data('url'),
                type: 'post',
                dataType: 'json',
            },
            columns: [
                {name: 'cot.id', data: 'id', visible: false},
                {name: 'cot.id', data: 'id'},
                {name: 'cot.dt_abertura', data: 'dt_abertura'},
                {name: 'cot.dt_vencimento', data: 'dt_vencimento'},
                {name: 'cot.ds_cotacao', data: 'ds_cotacao'},
                {name: 'c.nif', data: 'nif'},
                {name: 'c.estado', data: 'estado'},
                {defaultContent: '', width: '100px', orderable: false, searchable: false},
            ],
            order: [[3, 'ASC']],
            rowCallback: function (row, data) {
                $(row).data('id', data.id).css('cursor', 'pointer');

                $('td:not(:last-child)', row).each(function () {
                    $(this).on('click', function () {
                        window.location.href = $('#data-table').data('update') + data.id
                    });
                });

            },
            drawCallback: function () {

                $('[data-toggle="tooltip"]').tooltip();

            }
        });
    });
</script>
</html>