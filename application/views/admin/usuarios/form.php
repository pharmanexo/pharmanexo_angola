<!DOCTYPE html>
<html lang="pt-BR">

<?php echo $header; ?>

<body class="bg-light">
<?php echo $navbar; ?>
<?php echo $sidebar; ?>

<div class="content">
    <?php echo $heading; ?>

    <div class="content__inner">
        <form data-url="<?php echo $form_action; ?>" data-route_success="<?php echo $url_route_success; ?>"  method="POST" id="formUsuario" autocomplete="off" enctype="multipart/form-data">
             <?php if (isset($usuario) && !empty($usuario)) : ?>
                <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
            <?php endif; ?>
            <div class="row mx-auto mt-3">
                <div class="col-12 col-lg-3 text-center">
                   <div class="card">
                       <div class="card-body">
                           <div class="imgPreview">
                               <img id="imgPrev" src="<?php echo $foto ?>" alt="Imagem" class="img-fluid rounded-circle w-50">
                           </div>
                           <label class="btn btn-outline-primary btn-block mt-3" for="foto">
                               <input type="file" name="foto" id="foto" class="d-none">
                               Trocar Imagem
                           </label>
                           <hr>
                           <input type="checkbox" value="1" <?php if (isset($usuario['login_fe']) && $usuario['login_fe'] == '1') echo 'checked'; ?> name="login_fe"> Permitir login fora de expediente?
                       </div>
                   </div>
                </div>
                <div class="col-12 col-lg-9">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title">Dados Cadastrais</h6>
                        </div>
                        <div class="card-body">
                            <div class="content__inner">
                                <div class="row">
                                    <div class="form-group col-6">
                                        <label for="">Nickname</label>
                                        <input type="text" id="nickname" name="nickname" class="form-control" value="<?php if (isset($usuario['nickname'])) echo $usuario['nickname']; ?>" >
                                    </div>
                                    <div class="form-group col-6">
                                        <label for="">E-mail</label>
                                        <input type="search" id="email" name="email" class="form-control" autocomplete="disabled" value="<?php if (isset($usuario['email'])) echo $usuario['email']; ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Telefone Comercial</label>
                                            <input type="text" id="telefone" name="telefone" class="form-control text-center" data-inputmask="tel" value="<?php if (isset($usuario['telefone'])) echo $usuario['telefone']; ?>">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="">Celular</label>
                                            <input type="text" id="celular" name="celular" class="form-control text-center" data-inputmask="cel" value="<?php if (isset($usuario['celular'])) echo $usuario['celular']; ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">

                                    <div class="col-6">
                                         <div class="form-group">
                                            <label for="perfil">Perfil</label>
                                            <select name="perfil" id="perfil" class="select2" data-placeholder="Selecione"> 
                                                <option></option>
                                                <option value="1" <?php if (isset($usuario) && $usuario['administrador'] == '1') echo 'selected'; ?> >Administração</option>
                                                <option value="2" <?php if (isset($usuario) && $usuario['administrador'] == '0') echo 'selected'; ?> >Fornecedor</option>
                                                <!-- <option value="3" <?php if (isset($perfil) && $perfil == '3') echo 'selected'; ?> >Representante</option> -->
                                            </select>
                                        </div>
                                    </div>  

                                    <div class="col-6" id="campoNivel" hidden>
                                         <div class="form-group">
                                            <label for="nivel">Nivel</label>
                                            <select name="nivel" id="nivel" class="select2" data-placeholder="Selecione"> 
                                                <option value=""></option>
                                            </select>
                                        </div>
                                    </div>  
                                </div>

                                <div class="row" id="rowFornecedor" hidden>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="fornecedores">Fornecedores</label>
                                            <select class="select2" name="fornecedores[]" id="fornecedores" multiple data-placeholder="Selecione">
                                                <?php foreach($fornecedores as $f): ?>
                                                    <option value="<?php echo $f['id']; ?>" <?php echo (isset($user_fornecedores) && in_array($f['id'], $user_fornecedores)) ? 'selected' : '' ?> ><?php echo $f['razao_social']; ?></option>
                                                <?php endforeach ?>
                                            </select>
                                       </div>
                                    </div>
                                </div>    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if (isset($scripts)) echo $scripts; ?>
<script>

    var url = $('#formUsuario').data('url');

    var url_perfis = "<?php echo $url_perfis; ?>";
   
    $(function () { 

        $('li.select2-search').find('input').css('opacity', "0");

        password_popover('#senha', '#c_senha');


        <?php if( isset($usuario) ): ?>
            change_form(<?php echo ( $usuario['administrador'] == 1 ) ? 1 : 2; ?>, <?php echo $usuario['nivel']; ?>);
        <?php endif; ?>

        $("#perfil").on('change', function () { change_form($(this).val()); });

        $('#formUsuario').submit(function(e) {
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                url: url,
                type: 'post',
                contentType: false,
                processData: false,
                data: formData,
                beforeSend: function(jqXHR, settings) {
                    if ( $('#email').val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo e-mail é obrigatório!"});
                        return jqXHR.abort();
                    }   

                    if ( $('#perfil').val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo perfil é obrigatório!"});
                        return jqXHR.abort();
                    }   

                    if ( $('#nivel').val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo nivel é obrigatório!"});
                        return jqXHR.abort();
                    }

                    if ( $('#perfil').val() == 2 && $('#fornecedores').val() == '' ) {
                        formWarning({ type: 'warning', message: "O campo fornecedores é obrigatório!"});
                        return jqXHR.abort();
                    }   
                },
                success: function(xhr) {

                    if (xhr.type == 'warning') {

                        if ( typeof xhr.message == 'string' ) {
                            xhr.message = {message: xhr.message };
                        }

                        $.each(xhr.message, function(i, v) {
                            
                            formWarning({ type: 'warning', message: v });
                        });
                    } else {
                        formWarning(xhr);
                        setTimeout(function() { window.location.href = $("#formUsuario").data('route_success'); }, 1500);
                    }
                },
                error: function(xhr) {
                    console.log(xhr);
                    formWarning({ type: 'warning', message: "Erro ao salvar as informações!" });
                }
            });
        });

        $("#foto").change(function () { readURL(this); });
        
        var senha = document.getElementById("senha");
        var c_senha = document.getElementById("c_senha");

        senha.addEventListener('keyup', function() { checkPassword(senha.value, c_senha.value); });
        senha.addEventListener('focus', function() { checks(null, senha.value, c_senha.value); });
    });


    function change_form(perfil, updateValue = null) {
        $("#nivel").find('option:not(:first)').remove();

        $.ajax({
            url: url_perfis,
            type: 'post',
            data: {
                perfil: perfil
            },
            success: function(xhr) {

                $.map(xhr['options'], function (row) {

                    var newOption = new Option(row.value, row.id, true, true);
                    $('#nivel').append(newOption).trigger('change');
                });   

                if ( updateValue != null ) {

                    $('#nivel').val(updateValue);
                    $('#nivel').trigger('change');
                } else {

                    $('#nivel').val("");
                    $('#nivel').trigger('change');
                }
            },
            error: function(xhr) { console.log(xhr);}
        });

        if ( perfil == 1 ) {

            $("#rowFornecedor").attr("hidden", true);
            $('#campoNivel').attr("hidden", false);
        } else if ( perfil == 2 ) {

            $("#rowFornecedor").attr("hidden", false);
            $('#campoNivel').attr("hidden", false);
        } else {

            $("#rowFornecedor").attr("hidden", false);
            $('#campoNivel').attr("hidden", true);
        }
    }

    function validaForm() {

        var resp = validaPassword($('#senha').val(), $('#c_senha').val());

        if ( resp != 1 ) { 

            return false;
        } else { 

            return true; 
        }
    }

    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#imgPrev').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
</body>

</html>
