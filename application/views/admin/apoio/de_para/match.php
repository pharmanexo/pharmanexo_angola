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
                <p>Não encontrou o produto? teste a busca avançada utilizando os campos abaixo.</p>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Pesquisa por Descrição</label>
                            <input type="text" data-index="3" id="searchItem" placeholder="Digite ao menos 3 caracteres" class="form-control">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="">Pesquisa por Marca</label>
                            <input type="text" data-index="4" id="searchItem" placeholder="Digite ao menos 3 caracteres" class="form-control">
                        </div>
                    </div>
                </div>
                <p class="text-right">
                    <button id="btnSubmit" type="submit" form="produtosDePara" class="btn btnSubmit btn-primary">Combinar Selecionados</button>
                </p>
                <form action="<?php echo $url_update ?>" id="produtosDePara">
                    <div class="table-responsive col-sm">
                        <table id="data-table" class="table table-condensend table-hover" data-url="<?php echo $to_datatable; ?>"
                            data-update="<?php echo $url_update ?>"
                            data-ocultar="<?php echo $url_ocultar ?>">
                            <thead>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>ID Produto</th>
                                <th>Descrição</th>
                                <th>Marca</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </form>
                <p class="text-right">
                    <button id="btnSubmit" type="submit" form="produtosDePara" class="btn btnSubmit btn-primary">Combinar Selecionados</button>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-12 text-center">
                <div class="alert alert-secondary" role="alert">
                    Não encontrou o produto? <b><a data-href="<?php if (isset($urlNewProduct)) echo $urlNewProduct; ?>" id="btnNewProd" class="alert-link">Clique aqui</a></b> para cadastrar.
                </div>
            </div>
        </div>
    </div>
</div>
<?php echo $scripts; ?>
<script>

    var url_update = $('#data-table').data('update');
    var url_ocultar = $('#data-table').data('ocultar');
    var url_main = "<?php echo $url_main; ?>";
    $(function () {

        $('#produtosDePara').on('submit', function (e) {
            e.preventDefault();

            $('.btnSubmit').html('<i class="fas fa-spin fa-spinner"></i> Processando... ');

            var ids = [];

            $.map(dt1.rows('.selected').data(), function (item) {
                ids.push(item.id_produto);
            });

            if (ids.length > 0) {
                $.post($(this).attr('action'), {produtos: ids}, function (xhr) {
                    formWarning(xhr);
                    $('.btnSubmit').html('Combinar Selecionados');
                    setTimeout(function () {
                        window.location.href = "<?php echo base_url('admin/apoio/catalogo/consolidacao/')?>";
                    }, 3000)
                }, 'json');
            } else {
                formWarning({
                    type: 'warning',
                    message: "Nenhum registro selecionado!"
                });
                $('.btnSubmit').html('Combinar Selecionados').attr('disabled', false);
            }
        });

        var dt1 = $('#data-table').DataTable({
            "processing": true,
            "serverSide": true,
            lengthChange: false,
            pageLength: 2000,
            ajax: {
                url: $('#data-table').data('url'),
                type: 'get',
                dataType: 'json',
                scrollY:        200,
                deferRender:    true,
                scroller:       true
            },
            columns: [
                {defaultContent: '', width: '100px', orderable: false, searchable: false },
                {name: 'id', data: 'id', visible: false },
                {name: 'id_produto', data: 'id_produto', visible: true },
                {name: 'descricao', data: 'descricao', searchable: true },
                {name: 'marca', data: 'marca', searchable: true, visible: false },
            ],
            columnDefs: [
                {orderable: false, className: 'select-checkbox', targets: 0 },
                {targets: [1], visible: false }
            ],
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            order: [[ 3, 'asc' ]],
            rowCallback: function (row, data) {
                // $(row).data('id', data.id_sintese).css('cursor', 'pointer');
                // var input = $(`<input type="checkbox" value="${data.id_sintese}" name="produtos[]">`)

                // $("td:eq(0)", row).html(input);

            },
            drawCallback: function () {
            }
        });

        $("#btnNewProd").on('click', function (e) {

            e.preventDefault();

            let me = $(this);

            $.ajax({
                url: me.data('href'),
                type: 'post',
                dataType: 'json',
                success: function (xhr) {
                    formWarning(xhr);
                    setTimeout(function() { window.location.reload(); }, 4000);
                }
            });
        });

        $('#btn_ocultar').on('click', function(e) {
            e.preventDefault();

            var text = "";
            var value;

            <?php if ($dados['ocultar'] == 0): ?>
                text = "Tem certeza que deseja ocultar o produto?";
                value = 1;
            <?php else: ?>
                text = "Tem certeza que deseja desocultar o produto?";
                value = 0;
            <?php endif; ?>

            Swal.fire({
                title: text,
                text: "",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não, cancelar'
            }).then((result) => {
                if (result.isConfirmed) {

                    $.post(`${url_ocultar}${value}`, {}, function (xhr) {
                        formWarning(xhr);
                        setTimeout(function() { window.location.href = url_main }, 1500);
                    }, 'JSON')
                    .fail(function(xhr) {});
                }
            })
        })

        $('[data-index]').on('keyup', function () {
            var col = $(this).data('index');
            var value = $(this).val();

            if(value.length > 2){

                dt1.columns(col).search(value).draw();

            }
        });

        // remove filter
        $('[data-action="reset-filter"]').click(function (e) {
            e.preventDefault();
            $('[data-index]').val(null);
            $('#data-table').columns([0, 1, 2, 4]).search('').draw();
        });
    });
</script>
</body>

