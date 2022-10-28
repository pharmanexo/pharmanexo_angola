<!DOCTYPE html>
<html lang="en">

<?php if (isset($header)) echo $header; ?>

<body>
<?php if (isset($navbar)) echo $navbar; ?>
<!-- ======= Hero Section ======= -->
<section id="hero" class="d-flex align-items-center" style="width: 100%; height: 100%">
    <div class="container position-relative" data-aos="fade-up" data-aos-delay="100">
        <div class="row justify-content-center">
            <div class="col-xl-12 col-lg-12 text-center" style="margin-top: -350px">
                <h1 style="text-transform: uppercase ">O MAIOR PORTAL DE INTEGRAÇÃO MULTIPLAFORMAS PARA</h1>
                <h1 style="text-transform: uppercase ">PRODUTOS FARMACÊUTICOS DO BRASIL</h1><br>
                <a href="<?php echo base_url('dashboard') ?>" style="background-color: #ED3236" class="btn-get-started btn-danger scrollto">ENTRAR</a>
            </div>
        </div>
        <div class="row justify-content-center" >
            <div class="col-xl-7 col-lg-9 text-center" style="margin-top: -120px">
                <h1 style="text-transform: uppercase; font-size: 16px ">INTEGRANDO E AUTOMATIZANDO A RESPOSTA DE COTAÇÕES DA INDÚSTRIA E DISTRIBUIDORES (MULTIPLATAFORMAS)</h1>
            </div>
        </div>

        <div class="row icon-boxes" hidden>
            <div class="col-md-6 col-lg-6 d-flex align-items-stretch mb-5 mb-lg-0" data-aos="zoom-in" data-aos-delay="200">
                <div class="icon-box">
                    <div class="icon"><i class="ri-stack-line"></i></div>
                    <h4 class="title"><a href="">Portal de Adesão (compras coletivas)</a></h4>
                    <a href="https://pharmanexo.com.br/adesao" class="btn-light">ACESSAR PORTAL</a>
                    <p class="description">Este tipo de compra são ações exclusivas e programadas, criadas para atender hospitais indicados pelos Portais de Cotações. Acesso exclusivo a estes clientes. <br></p>
                </div>
            </div>

            <div class="col-md-6 col-lg-6 d-flex align-items-stretch mb-5 mb-lg-0" data-aos="zoom-in" data-aos-delay="300">
                <div class="icon-box">
                    <div class="icon"><i class="ri-message-line"></i></div>
                    <h4 class="title"><a href="">FALE COM NOSSO SUPORTE TÉCNICO</a></h4>
                    <a href="#" class="btn-light" data-toggle="modal" data-target="#exampleModal">INICIAR UM ATENDIMENTO</a>
                    <p class="description">Fale com nosso suporte quando precisar</p>
                </div>
            </div>

        </div>
    </div>
</section><!-- End Hero -->

<!-- ======= Clients Section ======= -->
<section id="clients" class="clients">
    <div class="container" data-aos="fade-up">
        <div class="owl-carousel clientes-carousel justify-content-center align-items-center">


            <?php foreach ($marcas as $marca) { ?>
                <div class="clients-item">
                    <img src="<?php echo ASSETS_PATH ?>/marcas/<?php echo $marca; ?>" class="img-fluid" alt="">
                </div>
            <?php } ?>

        </div>
    </div>
</section><!-- End Clients Section -->

<main id="main">

    <!-- ======= About Section ======= -->
    <section id="about" class="about">
        <div class="container" data-aos="fade-up">

            <div class="section-title">
                <h2>Sobre Nós</h2>

                <p>Integrando indústrias e distribuidores aos sistemas de leilão reverso. </p>
            </div>

            <div class="row content">
                <div class="col-lg-6 text-justify">
                    <p>O pharmanexo é a maior ferramenta de integração transparente do Brasil, conectando distribuidores a portais de cotação, automatizando 100% do processo de recebimento e resposta de seus usuários.
                        Possuímos integração com diversos ERP's como: Totvs (Winthor, Protheus), Sankhya, SAP, assim como disponibilizamos API para integração entre os mais diversos sistemas. </p>
                    <p>
                       Entregamos aos nosso clientes uma ferramenta validade e homologada pelos 3 maiores portais do mercado (Bionexo, Síntese e Apoio Cotações), oferecemos outras ferramentas exclusivas,
                        como por exemplo: a ferramenta Integranexo (especializada em produtos pré vencidos e produtos promocionais, auxiliando a
                        Industria e grandes Distribuidores a aumentar a rotatividade de seu estoque, assim como melhorar a performance de vendas), área de representantes, área de compras coletivas programadas, dentre outras.
                        <br><br>
                        A Pharmanexo intermediadora de negócios, é um projeto disruptivo que utiliza tecnologia de ponta e inteligência artificial para buscar as melhores oportunidades de negócio se integrando "real time" diretamente com o estoque dos fornecedores e levando aos usuários dos Portais de Leilão reverso oportunidades exclusivas.
                        <br><br>
                        No ano de 2021 recebemos mais de 500 mil cotações, com aproximadamente R$ 5,4 bilhões em respostas enviadas, sendo 94,3% respondidas de forma totalmente automática.
                    </p>
                </div>
                <div class="col-lg-6 pt-4 pt-lg-0">
                    <img src="<?php echo base_url("/application/views/assets/img/portais.png"); ?>" style="width: 100%!important;" alt="">
                    <br>
                    <iframe width="100%" class="mt-3" height="330" src="https://www.youtube.com/embed/u1uBhj6qMqw">
                    </iframe>
                    <br>
                    <img src="<?php echo base_url("/application/views/assets/img/erps.png"); ?>" style="width: 100%!important;" alt="">
                </div>
            </div>

        </div>
    </section><!-- End About Section -->

    <!-- ======= Cta Section ======= -->
    <section id="cta" class="cta">
        <div class="container" data-aos="zoom-in">
            <div class="text-center">
                <h3>PORTAL DE ADESÕES</h3>
                <p> Se você recebeu um convite do administrador de seu Portal de Cotação, acesse aqui utilizando seu login e senha fornecidos por ele.</p>
                <a class="cta-btn" href="https://pharmanexo.com.br/adesao">ACESSAR PORTAL</a>
            </div>
        </div>
    </section><!-- End Cta Section -->

    <!-- ======= Frequently Asked Questions Section ======= -->
    <section id="faq" class="faq section-bg">
        <div class="container" data-aos="fade-up">

            <div class="section-title">
                <h2>Perguntas frequentes</h2>
            </div>
            <div class="faq-list">
                <?php foreach ($faq as $item) { ?>
                    <ul>
                        <li data-aos="fade-up" data-aos-delay="100">
                            <i class="bx bx-help-circle icon-help"></i> <a data-toggle="collapse" href="#faq-list-<?php echo $item['id']; ?>" class="collapsed"><?php echo $item['pergunta']; ?> <i class="bx bx-chevron-down icon-show"></i><i class="bx bx-chevron-up icon-close"></i></a>
                            <div id="faq-list-<?php echo $item['id']; ?>" class="collapse" data-parent=".faq-list">
                                <p>
                                    <?php echo $item['resposta']; ?>
                                </p>
                            </div>
                        </li>

                    </ul>
                <?php } ?>
            </div>

        </div>
    </section><!-- End Frequently Asked Questions Section -->

    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact">
        <div class="container" data-aos="fade-up">

            <div class="section-title">
                <h2>contato</h2>
                <p>Estamos sempre querendo te ouvir, utilize os meios de contato abaixo para falar conosco, será um prazer lhe atender.</p>
            </div>

            <div>
                <iframe style="border:0; width: 100%; height: 270px;" src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7483.256305779943!2d-40.292836!3d-20.315653!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x2f91e8604faf31ef!2sPharmanexo%20Portal%20de%20Oportunidades%20de%20Produtos%20Farmac%C3%AAuticos!5e0!3m2!1spt-BR!2sbr!4v1590624053465!5m2!1spt-BR!2sbr" frameborder="0" allowfullscreen></iframe>
            </div>

            <div class="row mt-5">

                <div class="col-lg-4">
                    <div class="info">
                        <div class="address">
                            <i class="icofont-google-map"></i>
                            <h4>Localização:</h4>
                            <p>Avenida Nossa Senhora dos Navegantes, 955 Sala 719 Torre B - Enseada do Suá, Vitória - ES, 29050-335
                            </p>
                        </div>

                        <div class="email">
                            <i class="icofont-envelope"></i>
                            <h4>Email:</h4>
                            <p>administracao@pharmanexo.com.br</p>
                        </div>

                        <div class="phone">
                            <i class="icofont-phone"></i>
                            <h4>Telefone:</h4>
                            <p>+55 027 2464-0012 / 011 4858-0294</p>
                        </div>

                    </div>

                </div>

                <div class="col-lg-8 mt-5 mt-lg-0">

                    <form id="formContact" action="<?php echo base_url('contato/sendMessage'); ?>" method="post" role="form">
                        <div class="form-row">
                            <div class="col-md-6 form-group">
                                <input type="text" name="name" class="form-control" required id="name" placeholder="Seu Nome" data-rule="minlen:4" data-msg="Informe seu nome completo"/>
                                <div class="validate"></div>
                            </div>
                            <div class="col-md-6 form-group">
                                <input type="email" class="form-control" name="email" required id="email" placeholder="Seu E-mail" data-rule="email" data-msg="Por favor informe um e-mail válido"/>
                                <div class="validate"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="subject" required id="subject" placeholder="Assunto" data-rule="minlen:4" data-msg="Por favor, informe o assunto, mínimo de 8 caracteres"/>
                            <div class="validate"></div>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" name="message" rows="5" required data-rule="required" data-msg="Por favor, escreva algo para nós" placeholder="Mensagem"></textarea>
                            <div class="validate"></div>
                        </div>
                        <div class="mb-3">
                            <div class="error-message text-danger"></div>
                            <div class="sent-message text-success"></div>
                        </div>
                        <div class="text-center">
                            <button class="btn-get-started btnSubmit" type="submit">Enviar Mensagem</button>
                        </div>
                    </form>

                </div>

            </div>

        </div>
    </section><!-- End Contact Section -->

</main><!-- End #main -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Abrir Chamado de Suporte</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formSupport" action="<?php echo base_url('support/open_ticket'); ?>" method="post">
                    <div class="form-group">
                        <input type="text" name="name" class="form-control" id="name" required placeholder="Seu Nome" data-rule="minlen:4" data-msg="Informe seu nome completo"/>
                        <div class="validate"></div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6 form-group">
                            <input type="text" name="telefone" class="form-control" id="telefone" required placeholder="Seu Telefone" data-rule="minlen:4" data-msg="Informe seu telefone de contato"/>
                            <div class="validate"></div>
                        </div>
                        <div class="col-md-6 form-group">
                            <input type="email" class="form-control" name="email" id="email" required placeholder="Seu E-mail" data-rule="email" data-msg="Por favor informe um e-mail válido"/>
                            <div class="validate"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="subject" id="subject" required placeholder="Assunto" data-rule="minlen:4" data-msg="Por favor, informe o assunto, mínimo de 8 caracteres"/>
                        <div class="validate"></div>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" name="message" rows="5" required data-rule="required" data-msg="Por favor, escreva algo para nós" placeholder="Mensagem"></textarea>
                        <div class="validate"></div>
                    </div>
                    <div class="mb-3">
                        <div class="error-message text-danger"></div>
                        <div class="text-success"></div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="submit" form="formSupport" class="btn btn-primary btnSubmit">Enviar</button>
            </div>
        </div>
    </div>
</div>

<?php if (isset($footer)) echo $footer; ?>
<?php if (isset($scripts)) echo $scripts; ?>

<script>
    $(function (e) {


       $('#formSupport').submit(function (e) {
            e.preventDefault();
            var me = $(this);
           var btn = $('.btnSubmit');
           btn.html("<i class='fas fa-spin fa-spinner'></i> Enviando...").attr('disabled', true);
            $.post(me.attr('action'), me.serialize(), function (xhr) {
                btn.html("Enviar Mensagem").attr('disabled', false);
                if (xhr.type === 'success'){
                    $('.text-success', '#formSupport').html(xhr.message);
                }else{
                    $('.error-message', '#formSupport').html(xhr.message);
                }

            }, 'JSON')
        })

        $('#exampleModal').on('hidden.bs.modal', function () {
            $('input, textarea', '#formSupport').val('');
            $('.text-success', '#formSupport').html('');
            $('.error-message', '#formSupport').html('');
        });

        $('#formContact').submit(function (e) {
            e.preventDefault();
            var me = $(this);
            var btn = $('.btnSubmit');
            btn.html("<i class='fas fa-spin fa-spinner'></i> Enviando...").attr('disabled', true);
            $.post(me.attr('action'), me.serialize(), function (xhr) {
                btn.html("Enviar Mensagem").attr('disabled', false);
                if (xhr.type === 'success'){
                    console.log(xhr);
                    $('.sent-message', '#formContact').html(xhr.message);

                }else{
                    $('.error-message', '#formContact').html(xhr.message);

                }

                setTimeout(function(){
                    $('input, textarea', '#formContact').val('');
                    $('.sent-message', '#formContact').html('');
                    $('.error-message', '#formContact').html('');

                }, 5000);



            }, 'JSON')
        })

    })

</script>

</body>

</html>
