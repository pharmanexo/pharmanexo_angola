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
                    <table id="data-table" class="table table-condensed table-hover no-filtered"
                           data-url="<?php if (isset($urlDatatables)) echo $urlDatatables; ?>"
                           data-detalhes="<?php if (isset($urlDetalhes)) echo $urlDetalhes; ?>"
                           data-change_status="<?php if (isset($urlChangeStatusPending)) echo $urlChangeStatusPending; ?>">
                        <thead>
                        <tr>
                            <th>CNPJ</th>
                            <th>Razão Social</th>
                            <th>Nome Fantasia</th>
                            <th>Cidade</th>
                            <th>Estado</th>
                            <th>Interior</th>
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


    $(function () {
        var dt1 = $('#data-table').DataTable({
            "processing": true,
            "serverSide": false,


            lengthChange: false,
            buttons: [],
            ajax: {
                url: $('#data-table').data('url'),
                type: 'get',
                dataType: 'json'
            },
            columns: [
                {name: 'compradores.cnpj', data: 'cnpj', width: '150px'},
                {name: 'compradores.razao_social', data: 'razao_social'},
                {name: 'compradores.nome_fantasia', data: 'nome_fantasia'},
                {name: 'compradores.cidade', data: 'cidade'},
                {name: 'compradores.estado', data: 'estado'},
                {name: 'compradores.spi', data: 'spi'},
            ],
            // columnDefs: [
            //     {orderable: false, className: 'select-checkbox', targets: 5},
            // ],
            // select: {
            //     style: 'multi',
            //     selector: 'td:first-child'
            // },
            // order: [[7, "desc"]],
            rowCallback: function (row, data) {
                $(row).css('cursor', 'pointer');
                //   console.log(data.id);


                $('td:eq(5)', row).html(`<input class="j_change" value="${data.id}"  ${data.spi.checked ? "checked" : ''} type="checkbox">`);
            },
            drawCallback: function () {
            }
        });


        $(document).on("change", ".j_change", function () {

            $.post('<?= base_url('/fornecedor/clientes_spi/update') ?>', {
                id_cliente: $(this).val()
            }, function (json) {
                formWarning(json);
            }, 'json').fail(function () {
                formWarning({
                    type: 'warning',
                    message: 'Ops, Ocorreu um erro, verique sua conexão.',
                });
            });


        });


    });


</script>

</html>
