<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>
<head>

    <title>This is a test</title>

    <style type="text/css">
        body
        {
            background-image:url('<?php echo base_url('images/img/background.jpg'); ?>');
        }
    </style>

</head>
<body class="bg-light">

<div class="content">

</div>

<div class="container-fluid">
<div class="row mt-3">
    <div class="col-12 col-lg-2">
        <ul class="list-group">
            <li class="list-group-item">

                <a href="#como-fazer-login">
                    Como fazer o login?
                </a>

            </li>

            <li class="list-group-item">

                <a href="#chamado">
                    Como abrir um chamado?
                </a>

            </li>
            <li class="list-group-item">

                <a href="#ordem-compra">
                    Como utilizar o dashboard?
                </a>

            </li>
            <li class="list-group-item">

                <a href="#ordem-compra">
                    Como utilizar o BI?
                </a>
            <li class="list-group-item">

                <a href="#ordem-compra">
                    Como abrir uma ordem de compra?
                </a>

            </li>

            </li>

        </ul>
    </div>
    <div class="col-12 col-lg-8">

        <h2 class="font-weight-bold text-white p-2" style="text-shadow: 2px 2px grey;"><strong>Central de ajuda </strong></h2>
        <div id="accordion" style="border-radius: 10px;">

            <a name="como-fazer-login"></a>


            <div class="card" style="box-shadow: 2px 2px 4px; border-radius: 10px;">
                <div style="background-image: linear-gradient(#d7d7d7,#bebebe); border-radius: 10px" class="card-header"
                     id="headingOne">
                    <h3 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"
                                aria-expanded="true" aria-controls="collapseOne">
                            <h5 class="text-black">Fazer login na Plataforma <h5/>
                        </button>
                    </h3>
                </div>

                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">

                    <div class="card-body text-secondary" style="font-size: 16px;border-radius: 10px;">
                        1) Clique no botão entrar;<br>
                        <img alt="tutorial" src="<?php echo base_url('images/img/login1.png'); ?>"
                             style="width:200px; margin: 20px; box-shadow: 2px 2px 4px;">
                        <br>
                        2) Preencha os dados e clique em "acessar sistema";<br>
                        <img alt="tutorial" src="<?php echo base_url('images/img/login2.png'); ?>"
                             style="width:200px; margin: 20px; box-shadow: 2px 2px 4px;">
                        <br>
                        3) caso o usuário tenha mais de um cnpj aparecerá outras opções
                        de empresa. caso contrário o dashboard aparecerá diretamente.<br>
                        <img alt="tutorial" src="<?php echo base_url('images/img/login3.png'); ?>"
                             style="width:500px; margin: 20px; box-shadow: 2px 2px 4px;">
                        <br>
                        Para mais dúvidas, assista o tutorial abaixo:
                        <br>
                        <iframe src="https://player.vimeo.com/video/469057453" width="500" height="360"
                                margin:"20px" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
                    </div>

                </div>
            </div>

            <!-- Segundo collapse -->
            <a name="chamado"></a>
            <div class="card" style="box-shadow: 2px 2px 4px; border-radius: 10px;">
                <div style="background-image: linear-gradient(#d7d7d7,#bebebe); border-radius: 10px" class="card-header"
                     id="headingTwo">
                    <h3 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseTwo"
                                aria-expanded="true" aria-controls="collapseTwo">
                            <h5 class="text-black">Abrir um chamado</h5>
                        </button>
                        </h5>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="card-body" style="font-size: 16px;>
                        1) Acesse o Portal Pharmanexo e
                        navegue até o menu ”Suporte” e clique em ”Chamados”.
                        <br>

                        <img alt=" tutorial
                    " src="<?php echo base_url('images/img/suporte1.png'); ?>"
                    style="width:200px; margin: 20px; box-shadow: 2px 2px 4px;">
                    <br>
                    2) A tela inicial irá apresentar todos os chamados com seu status e opções de filtros
                    <br>
                    3) Para abrir um novo chamado clique em
                    <img alt="tutorial" src="<?php echo base_url('images/img/suporte2.png'); ?>"
                         style="width:120px; margin: 10px; box-shadow: 2px 2px 4px;">
                    <br>
                    <img alt="tutorial" src="<?php echo base_url('images/img/novochamado.png'); ?>"
                         style="width:350px; margin: 20px; box-shadow: 2px 2px 4px;">
                    <p style="font-size: 10px;margin-left: 20px;">
                        Preencha o formulário com detalhes, adicione quantos anexos for necessário.
                        <br>Clique em salvar e aguarde que será encaminhado página de detalhes.</p>

                    <br>

                    4) Na página de detalhes é possível interagir com o atendente do chamado, ler as
                    mensagens e ver os anexos.
                    <br>

                    <img alt="tutorial" src="<?php echo base_url('images/img/suporte5.png'); ?>"
                         style="width:400px; margin: 20px; box-shadow: 2px 2px 4px;">
                    <br>
                    Para escrever uma nova resposta, basta clicar em "Escrever uma resposta".
                    <br>
                    <iframe src="https://player.vimeo.com/video/469035445"
                            width="500" height="360" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
                </div>
            </div>
        </div>

        <!-- Terceiro collapse -->

        <div class="card" style="box-shadow: 2px 2px 4px; border-radius: 10px;">
            <div style="background-image: linear-gradient(#d7d7d7,#bebebe); border-radius: 10px" class="card-header"
                 id="headingThree">
                <h3 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseThree"
                            aria-expanded="true" aria-controls="collapseTree">
                        <h5 class="text-black">Dashboard</h5>
                    </button>
                </h3>
            </div>

            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                <div class="card-body" style="font-size: 16px;">

                    <br>

        Ao fazer login no portal do fornecedor, o Dashboard é aberto. <br>
                    É possível ver os seguintes elementos:
                    <br>
                    1) Total de ofertado <br>
                    <img alt="tutorial" src="<?php echo base_url('images/img/dashboard1.png'); ?>"
                         style="width:200px; margin: 20px; box-shadow: 2px 2px 4px;">
                    <br>
                    2) Total de cotações em aberto <br>
                    <img alt="tutorial" src="<?php echo base_url('images/img/dashboard2.png'); ?>"
                         style="width:200px; margin: 20px; box-shadow: 2px 2px 4px;">
                    <br>
                    3) Valor total ofertado <br> <img alt="tutorial"
                                                      src="<?php echo base_url('images/img/dashboard3.png'); ?>"
                                                      style="width:200px; margin: 20px; box-shadow: 2px 2px 4px;">
                    <br>
                    4) Valor total convertido
                    <br> <img alt="tutorial" src="<?php echo base_url('images/img/dashboard4.png'); ?>"
                              style="width:200px; margin: 20px; box-shadow: 2px 2px 4px;">
                    <br>
                    5) Gráfico de total de cotações mensal <br>
                    <img alt="tutorial" src="<?php echo base_url('images/img/dashboard5.png'); ?>"
                         style="width:200px; margin: 20px; box-shadow: 2px 2px 4px;">
                    <br>
                    6) Mapa cotações em aberto por estado <br>
                    <img alt="tutorial" src="<?php echo base_url('images/img/dashboard6.png'); ?>"
                         style="width:300px; margin: 20px; box-shadow: 2px 2px 4px;">
                    <br>
                    7) Gráfico de produtos a vencer <br>
                    <img alt="tutorial" src="<?php echo base_url('images/img/dashboard7.png'); ?>"
                         style="width:300px; margin: 20px; box-shadow: 2px 2px 4px;">
                    <br>
                    8) Ao clicar nas barras do gráfico, a tela de produtos a vencer é aberta, onde é possível
                    ver os detalhes dos produtos que estão para vencer. <br>
                    <img alt="tutorial" src="<?php echo base_url('images/img/dashboard8.png'); ?>"
                         style="width:800px; margin: 20px; box-shadow: 2px 2px 4px;">
                    <br>
                    9) É possível fazer download do excel onde possui o detalhes dos produtos a vencer. <br>
                    <img alt="tutorial" src="<?php echo base_url('images/img/dashboard9.png'); ?>"
                         style="width:200px; margin: 20px; box-shadow: 2px 2px 4px;">
                    <br>
                    Para mais dúvidas, assistir o vídeo abaixo:
                    <br>
                    <iframe src="https://player.vimeo.com/video/470560157" width="500" height="360"
                            frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>


                </div>
            </div>
        </div>

        <div class="card" style="box-shadow: 2px 2px 4px; border-radius: 10px;">
            <div style="background-image: linear-gradient(#d7d7d7,#bebebe); border-radius: 10px" class="card-header"
                 id="headingFour">
                <h3 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseFour"
                            aria-expanded="true" aria-controls="collapseFour">
                        <h5 class="text-black">BI Analítico</h5>
                    </button>
                </h3>
            </div>

            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">

                <div class="card-body" style="font-size: 16px;">

                    <div class="accordion" id="accordion1">

                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseOneA">

                                    <h5 class="text-primary">Preços ofertados</h5>

                                </a>
                            </div>

                            <div id="collapseOneA" class="accordion-body collapse mt-1">
                                <br>
                                <div class="accordion-inner">

                                    <p>Onde é possível filtrar o preço dos produtos ofertados, seja por data, estado ou hospital.
                                    </p>
                                    1) Selecione a loja desejada
                                    <br>
                                    <img alt="tutorial" src="<?php echo base_url('images/img/precosofertados1.png'); ?>"
                                         style="width:250px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>
                                    2) Selecione o comprador desejado <br>

                                    <img alt="tutorial" src="<?php echo base_url('images/img/precosofertados2.png'); ?>"
                                         style="width:400px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>

                                    3) Data de início a ser filtrada. <br>
                                    <img alt="tutorial" src="<?php echo base_url('images/img/precosofertados3.png'); ?>"
                                         style="width:300px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>

                                    4) Data final a ser filtrada.
                                    <br>
                                    <img alt="tutorial" src="<?php echo base_url('images/img/precosofertados4.png'); ?>"
                                         style="width:300px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>

                                    5) Hospital a ser feito a cotação.
                                    <br>
                                    <img alt="tutorial" src="<?php echo base_url('images/img/precosofertados5.png'); ?>"
                                         style="width:1000px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>
                                    6) Ao clicar em "produtos" será exibido os produtos do local selecionado.
                                    <br>
                                    <img alt="tutorial" src="<?php echo base_url('images/img/precosofertados6.png'); ?>"
                                         style="width:1000px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>
                                    7) Ofertas dos produtos selecionados
                                    <br>
                                    <img alt="tutorial" src="<?php echo base_url('images/img/precosofertados7.png'); ?>"
                                         style="width:1000px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>
                                    <p>Para mais dúvidas, assistir o vídeo abaixo: </p>
                                    <br>



                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="accordion" id="accordion2">

                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwoA">

                                    <h5 class="text-primary">Análise cotações</h5>

                                </a>
                            </div>
                            <br>
                            <div id="collapseTwoA" class="accordion-body collapse">
                                <div class="accordion-inner">

                                    <p>Gráficos onde há o resumo dos cotações
                                    </p>
                                    1) Selecione a oja desejada.
                                    <br>
                                    <img alt="tutorial" src="<?php echo base_url('images/img/analisecotacoes1.png'); ?>"
                                         style="width:300px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>
                                    2) Selecione a data desejada. <br>

                                    <img alt="tutorial" src="<?php echo base_url('images/img/analisecotacoes2.png'); ?>"
                                         style="width:500px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>

                                    3) Selecione o comprador. <br>
                                    <img alt="tutorial" src="<?php echo base_url('images/img/analisecotacoes3.png'); ?>"
                                         style="width:300px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>

                                    4) Data final a ser filtrada.
                                    <br>
                                    <img alt="tutorial" src="<?php echo base_url('images/img/analisecotacoes4.png'); ?>"
                                         style="width:300px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>

                                    5) Número de cotações do período selecionada e restrições.
                                    <br>
                                    <img alt="tutorial" src="<?php echo base_url('images/img/analisecotacoes5.png'); ?>"
                                         style="width:300px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>
                                    6) Número total de cotações do portal
                                    <br>
                                    <img alt="tutorial" src="<?php echo base_url('images/img/analisecotacoes6.png'); ?>"
                                         style="width:200px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>
                                    7) Número de cotações combinadas com os produtos da síntese.
                                    <br>
                                    <img alt="tutorial" src="<?php echo base_url('images/img/analisecotacoes7.png'); ?>"
                                         style="width:200px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>

                                    8) Total de cotações ofertadas
                                    <br>
                                    <img alt="tutorial" src="<?php echo base_url('images/img/analisecotacoes8.png'); ?>"
                                         style="width:200px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>
                                    9) Total ofertado
                                    <br>
                                    <img alt="tutorial" src="<?php echo base_url('images/img/analisecotacoes9.png'); ?>"
                                         style="width:200px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion" id="accordion3">

                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#collapseThreeA">

                                    <h5 class="text-primary">Produtos Validade</h5>

                                </a>
                            </div>
                            <br>
                            <div id="collapseThreeA" class="accordion-body collapse">
                                <div class="accordion-inner">
                                    <p>Onde é possível filtrar o preço dos produtos ofertados, seja por data, estado ou hospital.
                                    </p>
                                    1) Selecione a loja desejada
                                    <br>
                                    <img alt="tutorial" src="<?php echo base_url('images/img/produtosvalidade1.png'); ?>"
                                         style="width:250px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>
                                    2) Área do gráfico onde apresenta o número total de produtos a vencer. <br>

                                    <img alt="tutorial" src="<?php echo base_url('images/img/produtosvalidade2.png'); ?>"
                                         style="width:400px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>

                                    3) Ao clicar na área do mapa desejada, abre uma área onde mostra os detalhes dos produtos a vencer. <br>
                                    <img alt="tutorial" src="<?php echo base_url('images/img/produtosvalidade3.png'); ?>"
                                         style="width:300px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>


                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion" id="accordion4">

                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion4" href="#collapseFourA">

                                    <h5 class="text-primary">Vendas diferenciadas</h5>

                                </a>
                            </div>
                            <br>
                            <div id="collapseFourA" class="accordion-body collapse">
                                <div class="accordion-inner">
                                    <p>Onde é possível filtrar o preço dos produtos ofertados, seja por data, estado ou hospital.
                                    </p>
                                    1) Selecione a loja desejada
                                    <br>
                                    <img alt="tutorial" src="<?php echo base_url('images/img/vendasdiferenciadas1.png'); ?>"
                                         style="width:800px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>
                                    2) Gráfico das vendas <br>

                                    <img alt="tutorial" src="<?php echo base_url('images/img/vendasdiferenciadas2.png'); ?>"
                                         style="width:400px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>

                                    3) Resumo dos números das vendas <br>
                                    <img alt="tutorial" src="<?php echo base_url('images/img/vendasdiferenciadas3.png'); ?>"
                                         style="width:1000px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>

                                   4) Produtos de vendas diferenciadas. <br>
                                    <img alt="tutorial" src="<?php echo base_url('images/img/vendasdiferenciadas4.png'); ?>"
                                         style="width:1000px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion" id="accordion5">

                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion5" href="#collapseFiveA">

                                    <h5 class="text-primary">IA Analítica</h5>

                                </a>
                            </div>
                            <br>
                            <div id="collapseFiveA" class="accordion-body collapse">
                                <div class="accordion-inner">

                                    <p>Onde é possível filtrar o preço dos produtos ofertados, seja por data, estado ou hospital.
                                    </p>
                                    1) Selecione os filtros desejados
                                    <br>
                                    <img alt="tutorial" src="<?php echo base_url('images/img/relatorioia1.png'); ?>"
                                         style="width:1000px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>
                                    2) Gráfico dos produtos <br>

                                    <img alt="tutorial" src="<?php echo base_url('images/img/relatorioia2.png'); ?>"
                                         style="width:1000px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>

                                    3) Selecione o produto desejado. <br>
                                    <img alt="tutorial" src="<?php echo base_url('images/img/relatorioia3.png'); ?>"
                                         style="width:1000px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion" id="accordion6">

                        <div class="accordion-group">
                            <div class="accordion-heading">
                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion6" href="#collapseSixA">

                                    <h5 class="text-primary">Análise de mercado</h5>

                                </a>
                            </div>
                            <br>
                            <div id="collapseSixA" class="accordion-body collapse">
                                <div class="accordion-inner">
                                    <p>Onde é possível comparar o preço de um produto da concorrência.
                                    </p>
                                    1) Selecione o estado desejado
                                    <br>
                                    <img alt="tutorial" src="<?php echo base_url('images/img/analisemercado1.png'); ?>"
                                         style="width:250px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>
                                    2) Escreva o nome do produto desejado. <br>

                                    <img alt="tutorial" src="<?php echo base_url('images/img/analisemercado2.png'); ?>"
                                         style="width:400px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>

                                    3) Clique para visualizar detalhes do produto. <br>
                                    <img alt="tutorial" src="<?php echo base_url('images/img/analisemercado3.png'); ?>"
                                         style="width:300px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>

                                    4) Compare os preços do produto, onde é possível visualizar o Preço Mínimo Concorrência,
                                    Preço Médio Concorrência e Preço Unitário Oncoprod.
                                    <br>
                                    <img alt="tutorial" src="<?php echo base_url('images/img/analisemercado4.png'); ?>"
                                         style="width:300px; margin: 20px; box-shadow: 2px 2px 4px;">
                                    <br>


                                </div>
                            </div>
                        </div>
                    </div>




                </div>
            </div>
        </div>

        <a name="ordem-compra"></a>

        <div class="card" style="box-shadow: 2px 2px 4px; border-radius: 10px;">
            <div style="background-image: linear-gradient(#d7d7d7,#bebebe); border-radius: 10px" class="card-header"
                 id="headingFive">
                <h3 class="mb-0">
                    <button class="btn btn-link" data-toggle="collapse" data-target="#collapseFive"
                            aria-expanded="true" aria-controls="collapseFive">
                        <h5 class="text-black">Ordens de compra</h5>
                    </button>
                    </h5>
            </div>
            <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion">
                <div class="card-body" style="font-size: 16px;>
                        1) Acesse o Portal Pharmanexo e
                        navegue até o menu ”Suporte” e clique em ”Chamados”.
                        <br>

                        <img alt=" tutorial
                " src="<?php echo base_url('images/img/ordemcompra1.png'); ?>"
                style="width:200px; margin: 20px; box-shadow: 2px 2px 4px;">
                <br>
                2) A tela inicial irá apresentar todos os chamados com seu status e opções de filtros
                <br>
                3) Para abrir um novo chamado clique em
                <img alt="tutorial" src="<?php echo base_url('images/img/ordemcompra2.png'); ?>"
                     style="width:120px; margin: 10px; box-shadow: 2px 2px 4px;">
                <br>
                <img alt="tutorial" src="<?php echo base_url('images/img/ordemcompra3.png'); ?>"
                     style="width:350px; margin: 20px; box-shadow: 2px 2px 4px;">
                <p style="font-size: 10px;margin-left: 20px;">
                    Preencha o formulário com detalhes, adicione quantos anexos for necessário.
                    <br>Clique em salvar e aguarde que será encaminhado página de detalhes.</p>

                <br>

                4) Na página de detalhes é possível interagir com o atendente do chamado, ler as
                mensagens e ver os anexos.
                <br>

                <img alt="tutorial" src="<?php echo base_url('images/img/ordemcompra4.png'); ?>"
                     style="width:400px; margin: 20px; box-shadow: 2px 2px 4px;">
                <br>

                <img alt="tutorial" src="<?php echo base_url('images/img/ordemcompra5.png'); ?>"
                     style="width:400px; margin: 20px; box-shadow: 2px 2px 4px;">
                <br>
            </div>
        </div>
    </div>

    <div class="card" style="box-shadow: 2px 2px 4px; border-radius: 10px;">
        <div style="background-image: linear-gradient(#d7d7d7,#bebebe); border-radius: 10px" class="card-header"
             id="headingSix">
            <h3 class="mb-0">
                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseSix"
                        aria-expanded="true" aria-controls="collapseSix">
                    <h5 class="text-black">Promoções automáticas</h5>
                </button>
                </h5>
        </div>
        <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion">
            <div class="card-body" style="font-size: 16px;">

            <br>
            1) A tela inicial irá apresentar os produtos, ao selecionar é possível fazer alterações com os botões da direita.
                <br>
                <img alt="tutorial" src="<?php echo base_url('images/img/promoauto3.png'); ?>"
                     style="width:120px; margin: 10px; box-shadow: 2px 2px 4px;">
            <br>
            2) Para alterar detalhes do produto selecionado, clicar nesses botões:
            <img alt="tutorial" src="<?php echo base_url('images/img/promoauto2.png'); ?>"
                 style="width:120px; margin: 10px; box-shadow: 2px 2px 4px;">
            <br>


        </div>
    </div>
</div>


    <div class="card" style="box-shadow: 2px 2px 4px; border-radius: 10px;">
        <div style="background-image: linear-gradient(#d7d7d7,#bebebe); border-radius: 10px" class="card-header"
             id="headingSeven">
            <h3 class="mb-0">
                <button class="btn btn-link" data-toggle="collapse" data-target="#collapseSeven"
                        aria-expanded="true" aria-controls="collapseSeven">
                    <h5 class="text-black">Promoções por validade</h5>
                </button>
                </h5>
        </div>
        <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion">
            <div class="card-body" style="font-size: 16px;">
                1) Escolha o estado desejado.
                        <br>

                        <img alt="tutorial" src="<?php echo base_url('images/img/promovali1.png'); ?>"
                style="width:300px; margin: 20px; box-shadow: 2px 2px 4px;">
           <br>
            2) Escolha o estado desejado.
                <br>
            <img alt="tutorial" src="<?php echo base_url('images/img/promovali2.png'); ?>"
                 style="width:800px; margin: 10px; box-shadow: 2px 2px 4px;">

        </div>
    </div>
</div>

<div class="card" style="box-shadow: 2px 2px 4px; border-radius: 10px;">
    <div style="background-image: linear-gradient(#d7d7d7,#bebebe); border-radius: 10px" class="card-header"
         id="headingEight">
        <h3 class="mb-0">
            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseEight"
                    aria-expanded="true" aria-controls="collapseEight">
                <h5 class="text-black">Regras de venda</h5>
            </button>
            </h3>
    </div>
    <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordion">
        <div class="card-body" style="font-size: 16px;">

        <div class="accordion" id="accordion1">

            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseOneA">

                        <h5 class="text-primary">Configurações de Envio</h5>

                    </a>
                </div>

                <div id="collapseOneA" class="accordion-body collapse mt-1">
                    <br>
                    <div class="accordion-inner">

                        <p>Onde é possível filtrar o preço dos produtos ofertados, seja por data, estado ou hospital.
                        </p>
                        1) Digite o estado desejado.
                        <br>
                        <img alt="tutorial" src="<?php echo base_url('images/img/promovali1.png'); ?>"
                             style="width:250px; margin: 20px; box-shadow: 2px 2px 4px;">
                        <br>
                        2) Selecione o estado desejado <br>

                        <img alt="tutorial" src="<?php echo base_url('images/img/promovali2.png'); ?>"
                             style="width:800px; margin: 20px; box-shadow: 2px 2px 4px;">
                        <br>

                        3) Configure as especificações necessárias. <br>
                        <img alt="tutorial" src="<?php echo base_url('images/img/promovali3.png'); ?>"
                             style="width:800px; margin: 20px; box-shadow: 2px 2px 4px;">
                        <br>


                    </div>
                </div>
            </div>
        </div>
<br>

        <div class="accordion" id="accordion2">

            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwoA">

                        <h5 class="text-primary">Controle de Cotações</h5>

                    </a>
                </div>
                <br>
                <div id="collapseTwoA" class="accordion-body collapse">
                    <div class="accordion-inner">

                        <p>Gráficos onde há o resumo dos cotações
                        </p>
                        1) Pesquise o estado desejado.
                        <br>
                        <img alt="tutorial" src="<?php echo base_url('images/img/promovali4.png'); ?>"
                             style="width:200px; margin: 20px; box-shadow: 2px 2px 4px;">
                        <br>
                        2) Selecione o estado desejado. <br>

                        <img alt="tutorial" src="<?php echo base_url('images/img/promovali5.png'); ?>"
                             style="width:700px; margin: 20px; box-shadow: 2px 2px 4px;">
                        <br>
                        2) Box para configurar o controle de cotações. <br>

                        <img alt="tutorial" src="<?php echo base_url('images/img/promovali7.png'); ?>"
                             style="width:700px; margin: 20px; box-shadow: 2px 2px 4px;">
                        <br>

                        4) Para adicionar um novo registro, selecione o botão de "+". <br>
                        <img alt="tutorial" src="<?php echo base_url('images/img/promovali6.png'); ?>"
                             style="width:200px; margin: 20px; box-shadow: 2px 2px 4px;">
                        <br>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion" id="accordion3">

            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#collapseThreeA">

                        <h5 class="text-primary">Formas de Pagamento</h5>

                    </a>
                </div>
                <br>
                <div id="collapseThreeA" class="accordion-body collapse">
                    <div class="accordion-inner">

                        1) Selecione a loja desejada
                        <br>
                        <img alt="tutorial" src="<?php echo base_url('images/img/promovali8.png'); ?>"
                             style="width:300px; margin: 20px; box-shadow: 2px 2px 4px;">
                        <br>
                        2) Área do gráfico onde apresenta o número total de produtos a vencer. <br>

                        <img alt="tutorial" src="<?php echo base_url('images/img/promovali9.png'); ?>"
                             style="width:600px; margin: 20px; box-shadow: 2px 2px 4px;">


                    </div>
                </div>
            </div>
        </div>

        <div class="accordion" id="accordion4">

            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion4" href="#collapseFourA">

                        <h5 class="text-primary">Prazos de Entrega</h5>

                    </a>
                </div>
                <br>
                <div id="collapseFourA" class="accordion-body collapse">
                    <div class="accordion-inner">
                        <p>Onde é possível filtrar o preço dos produtos ofertados, seja por data, estado ou hospital.
                        </p>
                        1) Selecione a loja desejada
                        <br>
                        <img alt="tutorial" src="<?php echo base_url('images/img/promovali10.png'); ?>"
                             style="width:600px; margin: 20px; box-shadow: 2px 2px 4px;">
                        <br>
                        2) Selecione o estado desejado. <br>

                        <img alt="tutorial" src="<?php echo base_url('images/img/promovali11.png'); ?>"
                             style="width:600px; margin: 20px; box-shadow: 2px 2px 4px;">
                        <br>
                        2) Box de edição do prazo de entrega no estado selecionado <br>

                        <img alt="tutorial" src="<?php echo base_url('images/img/prazoentrega1.png'); ?>"
                             style="width:600px; margin: 20px; box-shadow: 2px 2px 4px;">
                        <br>

                    </div>
                </div>
            </div>
        </div>

        <div class="accordion" id="accordion5">

            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion5" href="#collapseFiveA">

                        <h5 class="text-primary">Restrições de Produtos</h5>

                    </a>
                </div>
                <br>
                <div id="collapseFiveA" class="accordion-body collapse">
                    <div class="accordion-inner">

                        <p>Onde é possível filtrar o preço dos produtos ofertados, seja por data, estado ou hospital.
                        </p>
                        1) Selecione os filtros desejados
                        <br>
                        <img alt="tutorial" src="<?php echo base_url('images/img/promovali11.png'); ?>"
                             style="width:600px; margin: 20px; box-shadow: 2px 2px 4px;">
                        <br>

                    </div>
                </div>
            </div>
        </div>

        <div class="accordion" id="accordion6">

            <div class="accordion-group">
                <div class="accordion-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion6" href="#collapseSixA">

                        <h5 class="text-primary">Desconto e Valor Mínimo</h5>

                    </a>
                </div>
                <br>
                <div id="collapseSixA" class="accordion-body collapse">
                    <div class="accordion-inner">
                        <p>Onde é possível comparar o preço de um produto da concorrência.
                        </p>
                        1) Selecione o estado desejado
                        <br>
                        <img alt="tutorial" src="<?php echo base_url('images/img/promovali4.png'); ?>"
                             style="width:200px; margin: 20px; box-shadow: 2px 2px 4px;">
                        <br>
                        2) Escreva o nome do produto desejado. <br>

                        <img alt="tutorial" src="<?php echo base_url('images/img/descvalor1.png'); ?>"
                             style="width:800px; margin: 20px; box-shadow: 2px 2px 4px;">
                        <br>


                    </div>
                </div>
            </div>
        </div>

        <div class="accordion" id="accordion7">

        <div class="accordion-group">
            <div class="accordion-heading">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion7" href="#collapseSevenA">

                    <h5 class="text-primary">Vendas Diferenciadas</h5>

                </a>
            </div>
            <br>
            <div id="collapseSevenA" class="accordion-body collapse">
                <div class="accordion-inner">
                    <p>Onde é possível comparar o preço de um produto da concorrência.
                    </p>
                    1) Selecione o estado desejado
                    <br>
                    <img alt="tutorial" src="<?php echo base_url('images/img/promovali4.png'); ?>"
                         style="width:200px; margin: 20px; box-shadow: 2px 2px 4px;">
                    <br>
                    2) Escreva o nome do produto desejado. <br>

                    <img alt="tutorial" src="<?php echo base_url('images/img/vendasdif2.png'); ?>"
                         style="width:800px; margin: 20px; box-shadow: 2px 2px 4px;">
                    <br>
                    3) Altere as opções das vendas. <br>

                    <img alt="tutorial" src="<?php echo base_url('images/img/vendasdif1.png'); ?>"
                         style="width:800px; margin: 20px; box-shadow: 2px 2px 4px;">
                    <br>
                </div>
            </div>
        </div>
    </div>

    </div>

</div>
</div>

<div class="card" style="box-shadow: 2px 2px 4px; border-radius: 10px;">
    <div style="background-image: linear-gradient(#d7d7d7,#bebebe); border-radius: 10px" class="card-header"
         id="headingNine">
        <h3 class="mb-0">
            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseNine"
                    aria-expanded="true" aria-controls="collapseNine">
                <h5 class="text-black">Produtos</h5>
            </button>
            </h5>
    </div>
    <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordion">
        <div class="card-body" style="font-size: 16px;">


            <div class="accordion" id="accordion1">

                <div class="accordion-group">
                    <div class="accordion-heading">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#collapseOneA">

                            <h5 class="text-primary">Produtos à Vencer</h5>

                        </a>
                    </div>

                    <div id="collapseOneA" class="accordion-body collapse mt-1">
                        <br>
                        <div class="accordion-inner">

                            <p>Onde é possível ver detalhes dos produtos.
                            </p>
                            1) Digite o produto desejado.
                            <br>
                            <img alt="tutorial" src="<?php echo base_url('images/img/produtosvencer1.png'); ?>"
                                 style="width:250px; margin: 20px; box-shadow: 2px 2px 4px;">
                            <br>
                            2) Selecione o produto desejado. <br>

                            <img alt="tutorial" src="<?php echo base_url('images/img/produtosvencer2.png'); ?>"
                                 style="width:800px; margin: 20px; box-shadow: 2px 2px 4px;">
                            <br>
                            3) Área onde é possível editar os detalhes de promoção do produto.
                            <img alt="tutorial" src="<?php echo base_url('images/img/produtosvencer3.png'); ?>"
                                 style="width:800px; margin: 20px; box-shadow: 2px 2px 4px;">


                        </div>
                    </div>
                </div>
            </div>
            <br>

            <div class="accordion" id="accordion2">

                <div class="accordion-group">
                    <div class="accordion-heading">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwoA">

                            <h5 class="text-primary">De->Para Marcas</h5>

                        </a>
                    </div>
                    <br>
                    <div id="collapseTwoA" class="accordion-body collapse">
                        <div class="accordion-inner">

                            <p>Gráficos onde há o resumo dos cotações
                            </p>
                            1) Digite o produto desejado.
                            <br>
                            <img alt="tutorial" src="<?php echo base_url('images/img/pesquisar-registros.png'); ?>"
                                 style="width:300px; margin: 20px; box-shadow: 2px 2px 4px;">
                            <br>
                            2) Selecione o produto desejado, ao clicar no ícone do lado direito do produto, é possível alterar a marca. <br>

                            <img alt="tutorial" src="<?php echo base_url('images/img/marcas1.png'); ?>"
                                 style="width:800px; margin: 20px; box-shadow: 2px 2px 4px;">
                            <br>

                            3) Área onde é possível alterar a marca do produto. <br>
                            <img alt="tutorial" src="<?php echo base_url('images/img/marcas2.png'); ?>"
                                 style="width:450px; margin: 20px; box-shadow: 2px 2px 4px;">
                            <br>
                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion" id="accordion3">

                <div class="accordion-group">
                    <div class="accordion-heading">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion3" href="#collapseThreeA">

                            <h5 class="text-primary">De->Para</h5>

                        </a>
                    </div>
                    <br>
                    <div id="collapseThreeA" class="accordion-body collapse">
                        <div class="accordion-inner">
                            <br>
                            1) Digite o produto desejado.
                            <br>
                            <img alt="tutorial" src="<?php echo base_url('images/img/pesquisar-registros.png'); ?>"
                                 style="width:300px; margin: 20px; box-shadow: 2px 2px 4px;">
                            <br>
                            2) Selecione a loja desejada.
                            <br>
                            <img alt="tutorial" src="<?php echo base_url('images/img/depara2.png'); ?>"
                                 style="width:800px; margin: 20px; box-shadow: 2px 2px 4px;">
                            <br>
                            3) Área do gráfico onde apresenta o número total de produtos a vencer. <br>

                            <img alt="tutorial" src="<?php echo base_url('images/img/depara3.png'); ?>"
                                 style="width:800px; margin: 20px; box-shadow: 2px 2px 4px;">
                                <br>

                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion" id="accordion4">

                <div class="accordion-group">
                    <div class="accordion-heading">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion4" href="#collapseFourA">

                            <h5 class="text-primary">Upgrade De/Para</h5>

                        </a>
                    </div>
                    <br>
                    <div id="collapseFourA" class="accordion-body collapse">
                        <div class="accordion-inner">
                            <p>Onde é possível filtrar o preço dos produtos ofertados, seja por data, estado ou hospital.
                            </p>
                            1) Selecione o produto desejado.
                            <br>
                            <img alt="tutorial" src="<?php echo base_url('images/img/upgrade1.png'); ?>"
                                 style="width:500px; margin: 20px; box-shadow: 2px 2px 4px;">
                            <br>
                            2) Àrea para combinação dos produtos desejados. <br>

                            <img alt="tutorial" src="<?php echo base_url('images/img/upgrade2.png'); ?>"
                                 style="width:800px; margin: 20px; box-shadow: 2px 2px 4px;">
                            <br>

                        </div>
                    </div>
                </div>
            </div>

            <div class="accordion" id="accordion5">

                <div class="accordion-group">
                    <div class="accordion-heading">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion5" href="#collapseFiveA">

                            <h5 class="text-primary">Catálogo</h5>

                        </a>
                    </div>
                    <br>
                    <div id="collapseFiveA" class="accordion-body collapse">
                        <div class="accordion-inner">

                            <p>Área para filtrar os produtos ativos e inativos.
                            </p>
                            1) Selecione os filtros desejados
                            <br>
                            <img alt="tutorial" src="<?php echo base_url('images/img/catalogo1.png'); ?>"
                                 style="width:300px; margin: 20px; box-shadow: 2px 2px 4px;">
                            <br>
                            2) Produto ativo, clique no ícone da esquerda para inativar o produto.
                            <br>
                            <img alt="tutorial" src="<?php echo base_url('images/img/catalogo2.png'); ?>"
                                 style="width:800px; margin: 20px; box-shadow: 2px 2px 4px;">
                            <br>
                            3) Produto inativo, clique no ícone da direita para ativar o produto.
                            <br>
                            <img alt="tutorial" src="<?php echo base_url('images/img/catalogo3.png'); ?>"
                                 style="width:800px; margin: 20px; box-shadow: 2px 2px 4px;">
                            <br>
                            4) Box para edição do detalhes do produto.
                            <br>
                            <img alt="tutorial" src="<?php echo base_url('images/img/catalogo4.png'); ?>"
                                 style="width:800px; margin: 20px; box-shadow: 2px 2px 4px;">
                            <br>


                        </div>
                    </div>
                </div>
            </div>

    </div>
</div>
</div>

<div class="card" style="box-shadow: 2px 2px 4px; border-radius: 10px;">
    <div style="background-image: linear-gradient(#d7d7d7,#bebebe); border-radius: 10px" class="card-header"
         id="headingTen">
        <h3 class="mb-0">
            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseTen"
                    aria-expanded="true" aria-controls="collapseTen">
                <h5 class="text-black">Distribuidor x Distribuidor</h5>
            </button>
            </h5>
    </div>
    <div id="collapseTen" class="collapse" aria-labelledby="headingTen" data-parent="#accordion">
        <div class="card-body" style="font-size: 16px;>
                        1) Acesse o Portal Pharmanexo e
                        navegue até o menu ”Suporte” e clique em ”Chamados”.
                        <br>

                        <img alt=" tutorial
        " src="<?php echo base_url('images/img/suporte1.png'); ?>"
        style="width:200px; margin: 20px; box-shadow: 2px 2px 4px;">
        <br>
        1) A tela inicial irá apresentar todos os chamados com seu status e opções de filtros
        <br>
        2) Para abrir um novo chamado clique em
        <img alt="tutorial" src="<?php echo base_url('images/img/suporte2.png'); ?>"
             style="width:120px; margin: 10px; box-shadow: 2px 2px 4px;">
        <br>
        <img alt="tutorial" src="<?php echo base_url('images/img/novochamado.png'); ?>"
             style="width:350px; margin: 20px; box-shadow: 2px 2px 4px;">
        <p style="font-size: 10px;margin-left: 20px;">
            Preencha o formulário com detalhes, adicione quantos anexos for necessário.
            <br>Clique em salvar e aguarde que será encaminhado página de detalhes.</p>

        <br>

        3) Na página de detalhes é possível interagir com o atendente do chamado, ler as
        mensagens e ver os anexos.
        <br>

        <img alt="tutorial" src="<?php echo base_url('images/img/suporte5.png'); ?>"
             style="width:400px; margin: 20px; box-shadow: 2px 2px 4px;">
        <br>
        Para escrever uma nova resposta, basta clicar em "Escrever uma resposta".
        <br>
        <iframe src="https://player.vimeo.com/video/469035445"
                width="500" height="360" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
    </div>
</div>
</div>

<div class="card" style="box-shadow: 2px 2px 4px; border-radius: 10px;">
    <div style="background-image: linear-gradient(#d7d7d7,#bebebe); border-radius: 10px" class="card-header"
         id="headingEleven">
        <h3 class="mb-0">
            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseEleven"
                    aria-expanded="true" aria-controls="collapseEleven">
                <h5 class="text-black">Cotações</h5>
            </button>
            </h5>
    </div>
    <div id="collapseEleven" class="collapse" aria-labelledby="headingEleven" data-parent="#accordion">
        <div class="card-body" style="font-size: 16px;>
                        1) Acesse o Portal Pharmanexo e
                        navegue até o menu ”Suporte” e clique em ”Chamados”.
                        <br>

                        <img alt=" tutorial
        " src="<?php echo base_url('images/img/suporte1.png'); ?>"
        style="width:200px; margin: 20px; box-shadow: 2px 2px 4px;">
        <br>
        1) A tela inicial irá apresentar todos os chamados com seu status e opções de filtros
        <br>
        2) Para abrir um novo chamado clique em
        <img alt="tutorial" src="<?php echo base_url('images/img/suporte2.png'); ?>"
             style="width:120px; margin: 10px; box-shadow: 2px 2px 4px;">
        <br>
        <img alt="tutorial" src="<?php echo base_url('images/img/novochamado.png'); ?>"
             style="width:350px; margin: 20px; box-shadow: 2px 2px 4px;">
        <p style="font-size: 10px;margin-left: 20px;">
            Preencha o formulário com detalhes, adicione quantos anexos for necessário.
            <br>Clique em salvar e aguarde que será encaminhado página de detalhes.</p>

        <br>

        3) Na página de detalhes é possível interagir com o atendente do chamado, ler as
        mensagens e ver os anexos.
        <br>

        <img alt="tutorial" src="<?php echo base_url('images/img/suporte5.png'); ?>"
             style="width:400px; margin: 20px; box-shadow: 2px 2px 4px;">
        <br>
        Para escrever uma nova resposta, basta clicar em "Escrever uma resposta".
        <br>
        <iframe src="https://player.vimeo.com/video/469035445"
                width="500" height="360" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
    </div>
</div>
</div>

<div class="card" style="box-shadow: 2px 2px 4px; border-radius: 10px;">
    <div style="background-image: linear-gradient(#d7d7d7,#bebebe); border-radius: 10px" class="card-header"
         id="headingTwelve">
        <h3 class="mb-0">
            <button class="btn btn-link" data-toggle="collapse" data-target="#collapseTwelve"
                    aria-expanded="true" aria-controls="collapseTwelve">
                <h5 class="text-black">Representantes</h5>
            </button>
            </h5>
    </div>
    <div id="collapseTwelve" class="collapse" aria-labelledby="headingTwelve" data-parent="#accordion">
        <div class="card-body" style="font-size: 16px;>
                        1) Acesse o Portal Pharmanexo e
                        navegue até o menu ”Suporte” e clique em ”Chamados”.
                        <br>

                        <img alt=" tutorial
        " src="<?php echo base_url('images/img/suporte1.png'); ?>"
        style="width:200px; margin: 20px; box-shadow: 2px 2px 4px;">
        <br>
        1) A tela inicial irá apresentar todos os chamados com seu status e opções de filtros
        <br>
        2) Para abrir um novo chamado clique em
        <img alt="tutorial" src="<?php echo base_url('images/img/suporte2.png'); ?>"
             style="width:120px; margin: 10px; box-shadow: 2px 2px 4px;">
        <br>
        <img alt="tutorial" src="<?php echo base_url('images/img/novochamado.png'); ?>"
             style="width:350px; margin: 20px; box-shadow: 2px 2px 4px;">
        <p style="font-size: 10px;margin-left: 20px;">
            Preencha o formulário com detalhes, adicione quantos anexos for necessário.
            <br>Clique em salvar e aguarde que será encaminhado página de detalhes.</p>

        <br>

        3) Na página de detalhes é possível interagir com o atendente do chamado, ler as
        mensagens e ver os anexos.
        <br>

        <img alt="tutorial" src="<?php echo base_url('images/img/suporte5.png'); ?>"
             style="width:400px; margin: 20px; box-shadow: 2px 2px 4px;">
        <br>
        Para escrever uma nova resposta, basta clicar em "Escrever uma resposta".
        <br>
        <iframe src="https://player.vimeo.com/video/469035445"
                width="500" height="360" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>
    </div>
</div>
</div>

</div>

</div>





<?php echo $scripts; ?>
</body>
</html>