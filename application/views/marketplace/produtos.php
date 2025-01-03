<!DOCTYPE html>
<html lang="pt-BR">
<?php if (isset($header)) echo $header; ?>
<body class="bg-light">
<?php if (isset($navbar)) echo $navbar; ?>
<?php if (isset($sidebar)) echo $sidebar; ?>
<section class="bg-gray">
    <div class="container py-3">
        <h3 class="titulo titulo-light titulo-lg  mb-3">Linha Hospitalar</h3>

        <div id="myCarouseldestaques" class="mb-3 carousel slide" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="flex-row d-flex">
                        <div class=" mx-3">
                            <div class="card">
                                <img class="card-img-top" src="https://www.konkero.com.br/revistawp/wp-content/uploads/2014/11/9-planos-de-sa%C3%BAde-que-d%C3%A3o-desconto-em-rem%C3%A9dios.jpg" alt="Imagem de capa do card">
                                <div class="card-body">
                                    <h5 class="card-title">Título do card</h5>
                                    <p class="card-text">Um exemplo de texto rápido para construir o título do card e fazer preencher o conteúdo do card.</p>
                                    <a href="#" class="btn btn-primary">Visitar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>

        </div>
</section>
<div class="container my-3">
    <?php if (isset($heading)) echo $heading; ?>
    <div class="card">
        <div class="card-body">
            <div class="table-reponsive">
                <table id="data-table" class="table table-condensed table-hover" data-url="<?php if (isset($to_datatable)) echo $to_datatable; ?>" data-update="<?php if (isset($url_update)) echo $url_update; ?>">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Descrição do Produto</th>
                        <th>Marca</th>
                        <th>Estoque</th>
                        <th>Preço (R$)</th>
                        <th>Validade</th>
                        <th></th>
                    </tr
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<?php if (isset($scripts)) echo $scripts; ?>
<script>
    $(function () {

        $('#myCarouseldestaques').carousel({
            interval: 12000,
        });

        let dt = $('#data-table');
        dt = dt.dataTable({
            dom: 'tp',
            processing: true,
            serverSide: true,
            pageLength: 20,
            ajax: {
                url: dt.data('url'),
                type: 'get',
                dataType: 'json'
            },
            columns: [
                {name: 'id', data: 'id', visible: false},
                {name: 'produto_descricao', data: 'produto_descricao'},
                {name: 'marca', data: 'marca'},
                {name: 'quantidade', data: 'quantidade'},
                {name: 'preco', data: 'preco'},
                {name: 'validade', data: 'validade'},
                {defaultContent: '', width: '30px', orderable: false, searchable: false}
            ],
            "order": [[5, 'ASC'], [1, "ASC"]],
            rowCallback: function (row, data) {
                $('td:eq(5)', row).html(`<a class="addProdCart" href="carrinho/add_item/${data.id}" title="Carrinho"><i class="fas fa-cart-plus "></i></a>`);
            },
            drawCallback: function () {
                $('.addProdCart').on('click', function (e) {
                    e.preventDefault();
                    let me = $(this);
                    $.ajax({
                        url: me.attr('href'),
                        type: 'get',
                        dataType: 'html',
                        success: function (xhr) {
                            jQuery.noConflict();
                            $('body').append(xhr);
                            $('.modal').modal({
                                keyboard: false
                            }, 'show').on('hidden.bs.modal', function () {
                                $('.modal').remove();
                            });
                        }
                    })
                });
            }
        });
    })
</script>
</body>
</html>
