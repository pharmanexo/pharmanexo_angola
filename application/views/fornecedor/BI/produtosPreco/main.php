<!DOCTYPE html>
<html lang="pt-BR">
<?php echo $header; ?>
<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>
<div class="content">
    <?php echo $heading; ?>

    <div class="card">
        <div class="card-body">
            <form method="post" id="formExportar">

                 <div class="row">

                    <div class="col-4">
                        <div class="form-group">
                            <label for="id_cliente">Comprador</label>
                            <select class="select2" name="id_cliente" id="id_cliente" data-allow-clear="true" data-placeholder="Selecione">
                                <option></option>
                                <?php foreach($compradores as $c): ?>
                                    <option value="<?php echo $c['id']; ?>"><?php echo $c['comprador']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="col-4">
                        <label>Data Inicio</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="zmdi zmdi-calendar"></i></span>
                            </div>
                            <input type="date" class="form-control hidden-md-up" placeholder="Selecione uma data">
                            <input type="text" name="dataini" id="dataini" class="form-control date-picker hidden-sm-down" placeholder="Selecione">
                        </div>
                    </div>

                    <div class="col-4">
                        <label>Data fim</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="zmdi zmdi-calendar"></i></span>
                            </div>
                            <input type="date" class="form-control hidden-md-up" placeholder="Selecione uma data">
                            <input type="text" name="datafim" id="datafim" class="form-control date-picker hidden-sm-down" placeholder="Selecione">
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-4">
                        <div class="form-group">
                            <label for="produto">Produto</label>
                            <select class="select2" name="produto" id="produto" data-allow-clear="true" data-placeholder="Selecione">
                                <option value=""></option>

                                <?php foreach($produtos as $prod): ?>
                                    <option value="<?php echo $prod['codigo']; ?>"><?php echo $prod['produto']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <?php if( isset($selectMatriz) ): ?>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="id_fornecedor">Lojas</label>
                                <select class="select2" name="id_fornecedor" id="id_fornecedor" data-allow-clear="true" data-placeholder="Selecione">
                                    <option></option>
                                    <?php foreach($selectMatriz as $f): ?>
                                        <option value="<?php echo $f['id']; ?>"><?php echo $f['nome_fantasia']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="col-4 mt-4">
                        <div class="form-group">

                            <button type="button" class="btn btn-outline-primary btn-block mt-2" id="btnFiltrar"><i class="fas fa-search"></i> Filtrar</button>
                        </div>
                    </div>

                </div>

            </form>
        </div>
    </div>

    <h5 class="text-muted text-center" id="loadingCharts"> <i class="fas fa-spin fa-spinner"></i> Buscando informações no banco de dados... </h5>

    <div class="accordion" id="accordionCompradores"></div>
    <p class="text-center" id="btnCarregar"></p>
</div>

<?php echo $scripts; ?>

<script>

    var url =  "<?php echo $urlRelatorioProdutosComprador; ?>";
    var urlExcel =  "<?php echo $urlExcel; ?>";
    var urlPdf =  "<?php echo $urlPdf; ?>";

    var countResults = 0;

    $(document).ajaxStop(function () { load(null); });

    $(function() {

        $("#dataini").flatpickr({ "locale": "pt", "dateFormat": "d/m/Y", 'defaultDate': "<?php echo date('01/m/Y'); ?>" });
        $("#datafim").flatpickr({ "locale": "pt", "dateFormat": "d/m/Y", 'defaultDate': "<?php echo date('t/m/Y'); ?>" });

        main(countResults);

        $("#btnFiltrar").on('click', function (e) {

            $("#btnCarregar").html("");
            $("#accordionCompradores").parent().removeClass('text-center');
            $("#accordionCompradores").html("");

            load(1);
            countResults = 0;
            main(countResults);
        });

        $("#btnPdf").on("click", function (e) {

            e.preventDefault();

            $("#formExportar").attr('action', urlPdf);
            $("#formExportar").submit();
        });

        $("#btnExcel").on("click", function (e) {

            e.preventDefault();

            $("#formExportar").attr('action', urlExcel);
            $("#formExportar").submit();
        });
    });

    function load( set = null )
    {
        if ( set != null ) {

            $("#loadingCharts").show();
            $("#accordionCompradores").hide();
        } else {

            $("#loadingCharts").hide();
            $("#accordionCompradores").prop('hidden', false);
            $("#accordionCompradores").show();
        }
    }

    function main(page, btnExiste = null)
    {
        var periodo = getData();

        var data = {
            page: page,
            produto: $("#produto").val(),
            dataini: periodo.dataini,
            datafim: periodo.datafim,
            id_cliente: $("#id_cliente").val(),
            uf_cotacao: '',
            id_fornecedor: ( $("#id_fornecedor").val() != undefined ) ? $("#id_fornecedor").val() : ''
        };

        $.post(url, data, function( xhr ) {

            if ( xhr.length == 0 ) {

                $("#btnCarregar").html("");

                if (btnExiste != 1) {

                    var card = $(`
                            <div class="card mb-1">
                                <div class="card-header">

                                    <div class="row">
                                        <div class="col-md-6 text-left">
                                            <h6 class="mb-0 text-muted">
                                               Nenhum registro encontrado
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);

                    $("#accordionCompradores").append(card);
                }
            } else {

                if ( btnExiste != 1 && xhr.length >= 4 ) {

                    // $("#btnCarregar").html(`<button class="btn btn-outline-primary mb-3" data-page="0">
                    //         <i class="fas fa-plus"></i> carregar mais </button>`);

                    $("[data-page]").on('click', function (e) {
                        e.preventDefault();

                        main(countResults, 1);
                    });
                }
            }

            if ( typeof xhr == "string" ) { xhr = JSON.parse(xhr); }

            Object.entries(xhr).forEach(([key, comprador]) => {

                var cardProdutos = "";


                Object.entries(comprador.produtos).forEach(([key2, produto]) => {

                    var precos = "";
                    var datas = "";

                    Object.entries(produto.ultimos_precos).forEach(([key3, preco]) => {

                        precos += `<td>${preco.format}</td>`;
                        datas += `<td>${preco.data}</td>`;
                    });

                    if ( produto.ultimos_precos.length != 5 ) {

                        var tam = 5 - produto.ultimos_precos.length;

                        for (var i = 0; i < tam; i++) {

                            precos += `<td></td>`;
                            datas += `<td></td>`;
                        }
                    }


                    precos += `<td><b>Média: </b>${produto.mediaFormatada}</td>`;

                    var produto_descricao = ( produto.descricao != '' ) ? `${produto.nome_comercial} - ${produto.descricao}` : produto.nome_comercial;

                    cardProdutos += `
                            <div class="card mb-2">
                                <div class="card-header" style="cursor: pointer;" id="headingPreco${key}${key2}" data-toggle="collapse" data-target="#collapsePreco${key}${key2}" aria-expanded="true" aria-controls="collapsePreco${key}_${key2}">
                                    <h6 class="mb-0 text-muted">
                                        COD.: ${produto.codigo} - ${produto_descricao}
                                    </h6>
                                </div>

                                <div id="collapsePreco${key}${key2}" class="collapse show" aria-labelledby="headingPreco${key}${key2}" data-parent="#accordionPrecos">
                                    <div class="card-body">
                                       <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                   <th>Data Oferta</th>
                                                   ${datas}
                                                </tr>
                                                <tr>
                                                    <th>Ultimos Preços (R$)</th>
                                                    ${precos}
                                                </tr>
                                            </thead>

                                       </table>
                                    </div>
                                </div>
                            </div>
                        `;
                });

                var card = $(`
                        <div class="card mb-1">
                            <div class="card-header">

                               <div class="row">
                                    <div class="col-md-6 text-left">
                                        <h6 class="mb-0 text-muted">
                                            ${comprador.cnpj} - ${comprador.razao_social}
                                        </h6>
                                    </div>
                                    <div class="col-md-2 offset-md-4 text-right">
                                        <button class="btn btn-secondary" type="button" id="headingComprador${key}" data-toggle="collapse" data-target="#collapseComprador${key}" aria-expanded="true" aria-controls="collapseComprador${key}">
                                            Produtos <i class="fas fa-chevron-down ml-3"></i>
                                        </button>
                                    </div>
                                   </div>
                            </div>

                            <div id="collapseComprador${key}" class="collapse show" aria-labelledby="headingComprador${key}" data-parent="#accordionCompradores">
                                <div class="card-body">
                                    <div class="accordion" id="accordionPrecos">${cardProdutos}</div>
                                </div>
                            </div>
                        </div>
                    `);

                $("#accordionCompradores").append(card);
            });

            countResults = countResults + 1;
        });
    }

    function getData()
    {

        var dt1 = $("#dataini").val().split('/');
        var dt2 = $("#datafim").val().split('/');

        var dataini =  `${dt1[2]}-${dt1[1]}-${dt1[0]}`;
        var datafim =  `${dt2[2]}-${dt2[1]}-${dt2[0]}`;

        return { dataini: dataini, datafim: datafim };
    }
</script>
</body>
</html>