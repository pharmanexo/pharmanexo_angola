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
                <div class="legends"></div>
                <div class="table-responsive">
                    <table id="data-table" class="table table-condensed table-hover no-filtered" data-url="<?php echo $dataTable; ?>">
                        <thead>
                        <tr>
                            <th></th>
                            <th>CNPJ</th>
                            <th>Razão Social</th>
                            <th>Cartão CNPJ</th>
                            <th>Responsabilidade Técnica</th>
                            <th>Alvará</th>
                            <th>Validade do Alvará</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<?php echo $scripts; ?>
<script>
    var dt;
    $(function () {
        $('#filter-start-date, #filter-end-date').datepicker({
            format: "dd/mm/yyyy",
            language: "pt-BR",
            orientation: "bottom auto",
            autoclose: true
        });

        dt = $('#data-table').DataTable({
            processing: true,
            serverSide: false,
            lengthChange: false,

            ajax: {
                url: $('#data-table').data('url'),
                type: 'get',
                dataType: 'json',
            },

            columns: [{
                name: 'id',
                data: 'id',
                searchable: true,
                visible: false
            },
                {
                    name: 'cnpj',
                    data: 'cnpj',
                    searchable: true
                },
                {
                    name: 'razao_social',
                    data: 'razao_social',
                    searchable: true
                },
                {
                    name: 'cartao_cnpj',
                    data: 'cartao_cnpj',
                    searchable: true,
                    className: 'text-center'
                },
                {
                    name: 'responsabilidade_tecnica',
                    data: 'responsabilidade_tecnica',
                    searchable: true,
                    className: 'text-center'
                },
                {
                    name: 'alvara',
                    data: 'alvara',
                    searchable: true,
                    className: 'text-center'
                },
                {
                    name: 'validade_alvara',
                    data: 'validade_alvara',
                    searchable: true,
                    className: 'text-center'
                },

            ],
            rowCallback: function (row, data) {
                $(row).data('id', data.id).css('cursor', 'pointer');

                var days = verificar(data.validade_alvara);

                if (days < 0) {
                    $(row).addClass('table-danger');
                } else if (days <= 60) {
                    $(row).addClass('table-warning');
                }

                var btnAlvara = $(`<a href="${data.alvara}" target="_blank" class="text-primary openModal"><i class="fas fa-download"></i></a>`);
                $('td:eq(4)', row).html(btnAlvara);

                var btnRT = $(`<a href="${data.responsabilidade_tecnica}" target="_blank" class="text-primary openModal"><i class="fas fa-download"></i></a>`);
                $('td:eq(3)', row).html(btnRT);

                var btnCNPJ = $(`<a href="${data.cartao_cnpj}" target="_blank" class="text-primary openModal"><i class="fas fa-download"></i></a>`);
                $('td:eq(2)', row).html(btnCNPJ);

            },
            drawCallback: function () {
                $(".dataTables_filter").hide();

                $('.legends').html("<div class='py-3'>Status do alvará: <span class='badge badge-warning small'>Vence em até 60 dias</span> | <span class='badge badge-danger small'>Vencido</span> | <span class='badge badge-success small'>Vigente</span></div>")
            }
        });

        $('[data-index]').on('keyup change', function () {
            var col = $(this).data('index');
            var value = $(this).val();

            dt.columns(col).search(value).draw();
        });

        // remove filter
        $('[data-action="reset-filter"]').click(function (e) {
            e.preventDefault();
            $('[data-index]').val(null);
            $('#data-table').columns([0, 1, 2, 4]).search('').draw();
        });
    });

    function verificar(data) {
        var dt = data.split('/');
        var inicio = new Date(dt[2], dt[1], dt[0]);
        var fim = new Date();

        return Math.round((inicio - fim) / (1000 * 60 * 60 * 24));
    }

</script>

</html>