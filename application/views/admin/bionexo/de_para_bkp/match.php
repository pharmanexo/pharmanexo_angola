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
                        <table id="data-table" class="table table-condensend table-hover" data-url="<?php echo $to_datatable; ?>" data-update="<?php echo $url_update ?>">
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
        <div class="card">
            <div class="card-body">
                <h4>Não encontrou o produto?</h4>
                <p>Comunique ao nosso setor administrativo, para que seja feita uma análise e assim que for corrigido, lhe enviaremos uma notificação.</p>
                <p class="text-center"><a href="<?php if (isset($url_notFound)) echo $url_notFound; ?>" id="btnNotFound" class="btn btn-primary">Comunicar ao Administrativo</a></p>
            </div>
        </div>
    </div>
</div>
<?php echo $scripts; ?>
<script>

    var url_update = $('#data-table').data('update');
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
                        window.location.href = "<?php echo base_url('admin/bionexo/catalogo/consolidacao/')?>";
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
            order: [[ 1, 'asc' ]],
            rowCallback: function (row, data) {
                // $(row).data('id', data.id_sintese).css('cursor', 'pointer');
                // var input = $(`<input type="checkbox" value="${data.id_sintese}" name="produtos[]">`)

                // $("td:eq(0)", row).html(input);

            },
            drawCallback: function () {
            }
        });


        $('#btnNotFound').on('click', function (e) {
            e.preventDefault();
            let me = $(this);
            $.ajax({
                url: me.attr('href'),
                type: 'get',
                dataType: 'html',

                success: function (xhr) {
                    $('body').append(xhr);
                    $('.modal').modal({
                        keyboard: false
                    }, 'show').on('hide.bs.modal', function () {
                        $('.modal').remove();
                    });
                }
            });
        });

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

