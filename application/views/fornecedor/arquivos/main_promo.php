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

                <form action="<?php if (isset($urlImport)) echo $urlImport; ?>"  class="formSend" id="formEstoque" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-8">
                            <div class="form-group">
                                <label for="">Arquivo</label>
                                <input type="file" name="file" id="file" class="form-control">
                                <br> <span class="small">Enviar apenas arquivos CSV</span>
                                <br> <span class="small">Se precisar de ajuda para converter para CSV, solicite ao suporte.</span>
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

                    <p>Modelo para importação: <a href="<?php echo base_url(PUBLIC_PATH . "Pharmanexo_PROMO.csv"); ?>">Baixar modelo</a></p>
                    <p class="small text-danger">Não altere os campos do modelo, caso não tenha a informação, deixe o campo em branco, não remova ou inclua nenhum campo.</p>
                    <p class="small text-danger">As informações importadas são de responsabilidade do usuário logado, em caso de dúvidas, consulte o suporte.</p>
                </form>

                <div class="row">
                    <div class="col-12 text-right">
                        <button type="submit" form="formEstoque" class="btn btn-primary">Enviar Arquivo</button>
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