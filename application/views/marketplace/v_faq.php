<?php
$integracao = $this->session->userdata("integracao");
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
    <h3 class="titulo titulo-primary titulo-lg mb-3">Perguntas e Respotas</h3>
    <div class="accordion" id="questions_faq">
        <?php if(isset($questions)){ ?>
            <?php foreach ($questions as $question){ ?>
        <div class="card">
            <div class="card-header" id="heading<?php echo $question['id']; ?>">
                <h2 class="mb-0">
                    <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse<?php echo $question['id']; ?>" aria-expanded="true" aria-controls="collapse<?php echo $question['id']; ?>">
                        <?php echo $question['pergunta']; ?>
                    </button>
                </h2>
            </div>

            <div id="collapse<?php echo $question['id']; ?>" class="collapse" aria-labelledby="heading<?php echo $question['id']; ?>" data-parent="#questions_faq">
                <div class="card-body">
                    <h3 class="text-muted text-center">RESPOSTA</h3>
                    <br>
                    <?php echo $question['resposta']; ?>
                </div>
            </div>
        </div>
        <?php } }?>
    </div>
</div>
    <?php if (isset($scripts)) echo $scripts; ?>
</body>
</html>
