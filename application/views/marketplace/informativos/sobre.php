<!DOCTYPE html>
<html lang="pt-BR">
<?php if (isset($header)) echo $header; ?>
<body class="bg-light">
<?php if (isset($navbar)) echo $navbar; ?>
<?php if (isset($sidebar)) echo $sidebar; ?>
<div class="container py-3 mb-3">
    <h3 class="titulo titulo-primary titulo-lg mb-3">Sobre nós</h3>
    <div class="row">
        <div class="col-12 col-lg-5">
            <img src="<?php echo base_url("images/sobre/sobre_5.jpg"); ?>" class="img-fluid" alt="">
        </div>
        <div class="col-12 col-lg-7 text-justify small">
            <p id="u73862-2">A primeira plataforma do brasil especializados em produtos pré-vencidos, auxiliamos a Industria e grandes distribuidores a diminuir seus estoques, melhorar a perfomance, diminuir a perda de produtos por vencimento, e transformar um problema dos fornecedores em uma grande oportunidade para milhares de clientes.</p>
            <p id="u73862-5">A PHARMANEXO intermediadora de negócios, foi desenvolvida com o objetivo de gerar oportunidades únicas e exclusivas aos usuários da Plataforma, transformando prejuízos em oportunidades de negócio, diminuindo os prejuízos dos distribuidores, auxiliando na melhoria da performance e atuando diretamente na redução de custo para nossos clientes.</p>
            <p id="u73862-8">Possuimos dezenas de distribuidores habilitados, em todo território nacional, nos segmentos farma, hospitalar, odontológico, cosmético, veterinário e natural, os quais representaram no ano de 2016 mais de R$ 2,6 bilhões em faturamento, assim como um banco de dados com milhares de PJs.</p>
            <p id="u73862-11">Possuímos call center próprio e especialistas prontos para garantir o suporte técnico de nossos clientes e fornecedores.</p>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-lg-3">
            <img src="<?php echo base_url("images/sobre/sobre_1.png"); ?>" class="img-fluid" alt="">
        </div>
        <div class="col-12 col-lg-3">
            <img src="<?php echo base_url("images/sobre/sobre_2.jpg"); ?>" class="img-fluid" alt="">
        </div>
        <div class="col-12 col-lg-3">
            <img src="<?php echo base_url("images/sobre/sobre_3.jpg"); ?>" class="img-fluid" alt="">
        </div>
        <div class="col-12 col-lg-3">
            <img src="<?php echo base_url("images/sobre/sobre_4.jpg"); ?>" class="img-fluid" alt="">
        </div>
    </div>
</div>
<?php if (isset($scripts)) echo $scripts; ?>
</body>
</html>
