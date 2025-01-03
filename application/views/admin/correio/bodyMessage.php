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

          <!-- <ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="inbox" data-toggle="tab" data-action="inbox" href="#home" role="tab" aria-controls="home" aria-selected="true">Caixa de Entrada</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="sendbox" data-toggle="tab" data-action="sendbox" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Enviados</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="trashbox" data-toggle="tab" data-action="trashbox" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Lixeira</a>
  </li>
</ul> -->

          <?php #echo $row['mensagem']; 
          ?>


          <div class="card">
            <div class="card-body">
              <h4 class="card-title"><?php echo $row['assunto']; ?></h4>
              <div class="row">
                <div class="col-12 col-lg-6">
                  <h6 class="card-subtitle">De: <text class="text-muted"><?php echo $row['em_remetente']; ?></text>

                    </br>
                    Para: <text class="text-muted"><?php echo $row['em_destinatario']; ?></text>
                  </h6>

                </div>
                <div class="col-12 col-lg-6 text-right">

                  <h6 class="card-subtitle mb-2 text-muted"><?php echo (new DateTime($row['dt_registro']))->format('d/m/Y H:i:s'); ?></h6>

                </div>
              </div>

              </br>
              <p class="card-text" id="idBodyMessage"></p>
              <blockquote class="blockquote mb-0">
                <footer class="blockquote-footer"><?php echo $row['nm_remetente']; ?></footer>
              </blockquote>
              <a href="#" id="btnTeste" class="card-link">Responder</a>
              <a href="/fornecedor/correio" class="card-link">Voltar</a>
            </div>

            <textarea name="editor" id="editor">

  
  </textarea>
          </div>

          <?php echo $scripts; ?>

          <script>
            var editor;

            window.onload = function() {
              editor = CKEDITOR.replace('editor', {
                filebrowserBrowseUrl: '/ckfinder/ckfinder.html',
                filebrowserUploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files'
              });
            };



            $(function() {

              $('#btnTeste').click(function() {


                console.log(editor.getData());

              })
            })
          </script>







</body>


</html>