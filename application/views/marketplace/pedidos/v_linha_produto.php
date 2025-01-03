<?php
$integracao = $this->session->userdata("integracao");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<?php if (isset($header)) echo $header; ?>
<body class="bg-light">
<?php if (isset($navbar)) echo $navbar; ?>
<?php if (isset($sidebar)) echo $sidebar; ?>
<style type="text/css">
    .tab_compra {
        display: block;
        max-height: 350px;
        overflow-y: scroll;
    }
</style>

<div class="container p-4">
    <form id="formulario" name="formulario" method="post">
        <div class=" w-100 mx-auto ">
            <div class="text-left">
                <h4 class="text-primary">Destaques: Linha <?php echo $linha; ?> <img src="<?php echo base_url('images/img/bottom.png') ?>" style="max-width:100%;"></h4>
                <input type="hidden" id="linha" value="<?php echo $linha; ?>">
            </div>
            <div class="carousel">
                <?php
                if ($destaques) {
                    foreach ($destaques->result() as $destaque) {
                        $imagem = base_url('images/fotos_produtos/' . $destaque->imagem_produto); ?>
                        <div class="col-12 border-right">
                            <img class="rounded" src="<?php echo $imagem ?>"
                                 alt="<?php echo $destaque->produto_descricao; ?>"
                                 style="border-radius: 20px;   height: 150px; width:300px;max-height: 50%; max-width:100%;">
                            <div class="col-12 text-justify" style="height: 120px"><p
                                        style="color:black"><?php echo $destaque->produto_descricao . ' <br> ' . $destaque->marca; ?></p>
                            </div>
                            <a href="#" class="btn btn-primary btn-sm float-right" onclick="carrinho('<?php echo $destaque->id ?>');" title="Carrinho">
                                <i class="fas fa-shopping-cart "></i> R$ <?php echo number_format($destaque->preco_unidade, '2', ',', '.')?></a>

                        </div>
                    <?php }
                } ?>
            </div>
            <br>
            <div class="d-flex flex-row mx-auto col-12">
                <div class=" col-5 ">
                    <label><h5> Marcas</h5></label>
                    <select class="form-control form-control selectpicker" data-live-search="true" id="marca"
                            name="marca">
                        <option value="">Todas Marcas</option>
                        <?php
                        if ($marcas) {
                            foreach ($marcas->result() as $marca) : ?>
                                <option value="<?= $marca->id_marca ?>"><?= $marca->marca ?></option>
                            <?php endforeach;
                        } ?>
                    </select>
                </div>
                <div class=" col-6">
                    <label><h5> Produto</h5></label>
                    <input class="form-control form-control" type="text" id="product" name="product">
                </div>
                <div class="col-1 mx-auto" style="margin-top:39px; " align="right">
                    <a href="#" style="text-decoration: none;"
                       onclick="listarEstoque(1, 7,$('#product').val(),$('#marca').val());">
                        <button type="button" class="btn float-right btn-primary btn-primary "><i
                                    class="fas fa-fw fa-search"></i></button>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-12" style="margin-top: 15px;">
            <div class="table-responsive">
                <div id="detalhe"></div>
            </div>
        </div>
    </form>
</div>
<?php if (isset($scripts)) echo $scripts; ?>
<script>
    var qnt_result_pg = 20; //quantidade de registro por página
    var pagina = 1; //página inicial

    $(document).ready(function () {
        $('#tabCompra').DataTable({
            "scrollX": false
            , "scrollY": "250px"
            , "scrollCollapse": true
            , "paging": false
            , "searching": false
            , "info": false
            , "ordering": true
        });

        listarEstoque(pagina, qnt_result_pg); //Chamar a função para listar os registros

        $('.carousel').slick({
            dots: true,
            slidesToShow: 4,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2000,
        });

        // mostra a quantiade de itens no carrinho
        $.post(base_url + "Compra/listaCompra", {}, function (data) {

            ni = data.length;
            $('#ni').html(ni);
        }, 'json');
    });

    // lista itens em estoque
    function listarEstoque(pagina, qnt_result_pg, product, marca) {
        $.post(base_url + 'Compra/estoqueFornecedor/' + pagina + '/' + qnt_result_pg, {
            pagina: pagina,
            qnt_result_pg: qnt_result_pg,
            product: product,
            marca: marca,
        }, function (data) {
            //Subtitui o valor no seletor id="conteudo"
            $("#detalhe").html(data);
            $('#tabela').DataTable({
                "bFilter": false,
                "bInfo": false,
                "pageLength": 7,
                "bLengthChange": false,
                "bSort": true,
                "paging": false,
                "language": {
                    "sEmptyTable": "Nenhum registro encontrado",
                    "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
                    "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
                    "sInfoFiltered": "(Filtrados de _MAX_ registros)",
                    "sInfoPostFix": "",
                    "sLengthMenu": "",
                    "sInfoThousands": ".",
                    "sLoadingRecords": "Carregando...",
                    "sProcessing": "Processando...",
                    "sZeroRecords": "Nenhum registro encontrado",
                    "sSearch": "Pesquisar",
                    "oPaginate": {
                        "sNext": "Próximo",
                        "sPrevious": "Anterior",
                        "sFirst": "Primeiro",
                        "sLast": "Último"
                    }
                }
            });
        });
    }


    //lista itens do carrinho
    function listacompra() {
        var link1 = "<a class='btn btn-secondary float-left' href='javascript:limparCarrinho()'><i class='fas fa-cart-arrow-down'> Limpar</i></a>";

        var link2 = "<a class='btn btn-primary float-right' href='javascript:gerar_pedido()'><i class='fas fa-check'> Comprar</i></a>";
        $('#limpa_compra').html(link1);
        $('#salva_compra').html(link2);
        subtotal = 0;
        total = 0;
        $.post(base_url + "Compra/listaCompra", {}, function (data) {
            //montar a lista(tabela) com os produtos do carrinho
            ni = data.length;
            $('#ni').html(ni);

            var detalhe2 = "";
            var detalhe1 = "<table id='tabCompra' cellspacing='0' width='100%' class='table tab_compra table-striped  table-hover '>" +
                "<thead  class='table-ligth'>      " +
                "<tr>" +
                "<th>Produto</th>" +
                "<th>Marca</th>" +
                "<th class='text-right'>Preço</th>" +
                "<th class='text-center'>Quantidade</th>" +
                "<th class='text-right'>SubTotal</th>" +
                "<th></th>" +
                "</tr>" +
                "</thead>" +

                "<tbody>";
            for (var i = 0; data.length > i; i++) {
                subtotal = data[i].quantidade * data[i].preco_unidade;
                total = total + subtotal;
                detalhe2 = detalhe2 + "<tr>" +
                    "<td>" + data[i].produto_descricao + "</td>" +
                    "<td>" + data[i].marca + "</td>" +
                    "<td class='text-right'>" + number_format(data[i].preco_unidade, 2, ',', '.') + "</td>" +
                    "<td class='text-center'>" + data[i].quantidade + "</td>" +
                    "<td class='text-right'>" + number_format(subtotal, 2, ',', '.') + "</td>" +
                    "<td class='text-center'><a href='#' onclick='rpc(" + data[i].id + ")'><i class='fas fa-trash text-danger'></i></a></td>" +
                    "</tr>"
            }
            ;
            var detalhe3 = "</tbody>" +
                "<tfoot>" +
                "<tr>" +
                "<th></th>" +
                "<th></th>" +
                "<th></th>" +
                "<th class='text-right'>Total</th>" +
                "<th class='text-right'>" + number_format(total, 2, ',', '.') + "</th>" +
                "</tr>" +
                "</tfoot>" +
                "</table>";
            var detalhe = detalhe1 + detalhe2 + detalhe3;
            $('#lista_produto').html(detalhe);
            if (ni > 0) $('#modal_compra').modal('show');
        }, 'json');

    };


    // retirar produtos do carrinho
    function rpc(id) {
        swal({
            title: 'Atenção',
            text: 'Confirma a Retirada do Produto do Carrinho?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim',
            cancelButtonText: 'Não',

        }).then(function () {
            //swal('Exclusão', 'O Valor Minímo foi Excluido com Sucesso!', 'success');
            retirar_produto_carrinho(id);
        }).then(function () {
            listacompra();
        });
    };
    function retirar_produto_carrinho(id) {
        //o id indica a posição do produto na tabela de estoque
        $.post(base_url + "Compra/retirar_produto_carrinho/" + id, {
            id: id,
        });
    };

    //limpa o carrinho
    function limparCarrinho() {
        swal({
            title: 'Atenção',
            text: 'Confirma a Retirada de Todos Produtos do Carrinho?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim',
            cancelButtonText: 'Não',

        }).then(function () {
            //swal('Exclusão', 'O Valor Minímo foi Excluido com Sucesso!', 'success');
            limpa_carrinho();
        }).then(function () {
            $('#ni').html(0);
            $('#modal_compra').modal('hide');
        });
    };
    function limpa_carrinho() {
        //o id indica a posição do produto na tabela de estoque
        $.post(base_url + "Compra/limpar_carrinho", {});
    };

</script>
</body>
</html>
