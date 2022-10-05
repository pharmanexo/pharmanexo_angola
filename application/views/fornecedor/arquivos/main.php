<div class="modal fade" id="modalArquivos" tabindex="-1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><?php echo $title; ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Estoque</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Tabela de Preços</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Catálogo</a>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <form action="<?php if (isset($urlEstoque)) echo $urlEstoque; ?>"  class="formSend" id="formEstoque" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-8">
                                    <div class="form-group">
                                        <label for="">Arquivo</label>
                                        <input type="file" name="file" id="file" class="form-control">
                                        <br> <span class="small">Enviar apenas arquivos CSV</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="">Separador</label>
                                        <select name="separador" id="separador">
                                            <option value=",">Vírgula (,)</option>
                                            <option value=";">Ponto e vírgula (;) </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <p>Modelo para importação: <a href="<?php echo base_url(PUBLIC_PATH . "Pharmanexo_ESTOQUE.csv"); ?>">Baixar modelo</a></p>
                            <p class="small text-danger">Não altere os campos do modelo, caso não tenha a informação, deixe o campo em branco, não remova ou inclua nenhum campo.</p>
                        </form>

                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="submit" form="formEstoque" class="btn btn-primary">Enviar Arquivo</button>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <form action="<?php if (isset($urlPreco)) echo $urlPreco; ?>" class="formSend" id="formPreco" method="post" enctype="multipart/form-data">

                            <div class="row">
                                <div class="col-8">
                                    <div class="form-group">
                                        <label for="">Arquivo</label>
                                        <input type="file" name="file" id="file" class="form-control">
                                        <br> <span class="small">Enviar apenas arquivos CSV</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="">Separador</label>
                                        <select name="separador" id="separador">
                                            <option value=",">Vírgula (,)</option>
                                            <option value=";">Ponto e vírgula (;) </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <p>Modelo para importação: <a href="<?php echo base_url(PUBLIC_PATH . "Pharmanexo_PRECO.csv"); ?>">Baixar modelo</a></p>
                            <p class="small text-danger">Não altere os campos do modelo, caso não tenha a informação, deixe o campo em branco, não remova ou inclua nenhum campo.</p>
                        </form>

                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="submit" form="formPreco" class="btn btn-primary">Enviar Arquivo</button>
                            </div>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                        <form action="<?php if (isset($urlCatalogo)) echo $urlCatalogo; ?>"  class="formSend" id="formCatalogo" method="post" enctype="multipart/form-data">

                            <div class="row">
                                <div class="col-8">
                                    <div class="form-group">
                                        <label for="">Arquivo</label>
                                        <input type="file" name="file" id="file" class="form-control">
                                        <br> <span class="small">Enviar apenas arquivos CSV</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="form-group">
                                        <label for="">Separador</label>
                                        <select name="separador" id="separador">
                                            <option value=",">Vírgula (,)</option>
                                            <option value=";">Ponto e vírgula (;) </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <p>Modelo para importação: <a href="<?php echo base_url(PUBLIC_PATH . "Pharmanexo_CATALOGO.csv"); ?>">Baixar modelo</a></p>
                            <p class="small text-danger">Não altere os campos do modelo, caso não tenha a informação, deixe o campo em branco, não remova ou inclua nenhum campo.</p>
                        </form>

                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="submit" form="formCatalogo" class="btn btn-primary">Enviar Arquivo</button>
                            </div>
                        </div>

                    </div>
                </div>

                <p class="msg small"></p>
            </div>
        </div>
    </div>
</div>

<script>
    $(function () {


        $('form').on("submit", function (event) {
            event.preventDefault();
            var form = $(this);

            var formUpdate = $(this);
            var formData = new FormData(this);

            $('#modalArquivos .btn').html("<i class='fas fa-spinner'></i> Processando... ");

            $.ajax({
                type: 'POST',
                url: formUpdate.attr('action'),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {

                    if (response.type == 'success'){
                        $('#modalArquivos .btn').prop('disabled', 'disabled').html("Arquivo enviado!");
                        $('.msg').html("Por motivo de segurança bloqueamos o botão após a importação, para realizar uma nova importação, feche a tela e abra novamente.");
                    }else{
                        $('#modalArquivos .btn').html("Enviar Arquivo");
                    }
                    formWarning(response);

                }
            });
            return false;
        });
    });
</script>