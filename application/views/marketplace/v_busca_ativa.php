<?php 
  $integracao=$this->session->userdata("integracao");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<?php if (isset($header)) echo $header; ?>
<body class="bg-light">
<?php if (isset($navbar)) echo $navbar; ?>
<?php if (isset($sidebar)) echo $sidebar; ?>
<div class="container my-3">
    <?php if (isset($heading)) echo $heading; ?>
<div class="container p-4">
    <h3 class="titulo titulo-primary titulo-lg mb-3"> Busca Ativa</h3>
    <p class="small">A busca ativa serve para que possamos encontrar a melhor oferta com base nos produtos e preços informador pelo cliente <br>
    Informe os produtos nos campos abaixo, envie para nossos analistas e lhe enviares uma resposta assim que a cotação for efetuada.
    </p>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <input type="text" id="descricao" placeholder="Descrição do Produto" class="form-control">
                    </div>
                </div>
                <div class="col-12 col-lg-2">
                    <input type="number" id="quantidade" placeholder="Quantidade" class="form-control text-center">
                </div>
                <div class="col-12 col-lg-2">
                    <input type="text" id="preco" placeholder="Preço máximo" data-inputmask="money" class="form-control text-right">
                </div>
                <div class="col-12 col-lg-2">
                    <button type="button" id="addProd" class="btn btn-outline-primary btn-block"><i class="fas fa-plus"></i> Adicionar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-3">
        <form action="">
            <div class="card-body" id="containerProds">
            </div>
        </form>
    </div>
</div>
    <?php if (isset($scripts)) echo $scripts; ?>
<script>
    let produto;
    let id_row = 1;
    $(function () {

        $("#addProd").click(function (e) {
            e.preventDefault();

            var desc = $('#descricao').val();
            var preco = $('#preco').val();
            var qtd = $('#quantidade').val();
            var row = $(`<div class='row border-bottom my-1' data-rowid='${id_row}'></div>`);

            var field1 = $(`<div class='col-12 col-lg-6'><input type='hidden' value="${desc}" name='produtos[][descricao]'>${desc}</div>`);
            var field2 = $(`<div class='col-12 col-lg-2'><input type='hidden' value="${preco}" name='produtos[][preco]'>${preco}</div>`);
            var field3 = $(`<div class='col-12 col-lg-2'><input type='hidden' value="${qtd}" name='produtos[][qtd]'>${qtd}</div>`);
            var button = $(`<div class='col-12 col-lg-2'><button type="button" data-rowid='${id_row}' class='btn btn-sm my-3 btn-outline-danger btn-block'><i class="fas fa-trash"></i> </button></div>`);

            button.click(function (e) {
                e.preventDefault();
                $(this).parent().remove();


            });

            row.append(field1).append(field2).append(field3).append(button);

            $('#containerProds').append(row);


        });

    })
</script>
</body>
</html>
