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
                <div class="card-header">
                    <p class="text-muted border-bottom"><strong>Dados do produto</strong></p>
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <strong>Produto</strong> <br>
                            <?php if (isset($produto)) echo $produto['codigo'] . ' - ' . $produto['nome_comercial']; ?>
                        </div>
                        <div class="col-12 col-lg-8">
                            <strong>Apresentação</strong> <br>
                            <?php echo ( !empty($produto['apresentacao']) ) ? $produto['apresentacao'] : $produto['descricao']; ?>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <strong>Marca</strong> <br>
                            <?php if (isset($produto['marca'])) echo $produto['marca']; ?>
                        </div>
                        <div class="col-12 col-lg-2">
                            <strong>Quantidade Unidade</strong> <br>
                            <p class=""><?php if (isset($produto['quantidade_unidade'])) echo $produto['quantidade_unidade']; ?></p>
                        </div>
                    </div>
                    <br>
                    <p class="text-muted border-bottom"><strong>Dados do fornecedor</strong></p>
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <strong>CNPJ</strong> <br>
                            <?php if (isset($fornecedor)) echo $fornecedor['cnpj']; ?>
                        </div>
                        <div class="col-12 col-lg-8">
                            <strong>Nome Fantasia</strong> <br>
                            <?php if ( !empty($fornecedor) ) echo $fornecedor['nome_fantasia']; ?>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="padding: 0; overflow: hidden">
                   <?php foreach($produto['data'] as $row): ?>

                    <div class="row row-list p-3">
                       
                        <div class="col-1" data-toggle="tooltip" title="<?php echo $row['estado']['descricao']; ?>">
                            <div class="form-group">
                                <label>Estado</label>
                                <input type="text" class="form-control" value="<?php echo $row['estado']['uf']; ?>" readonly>
                            </div>
                        </div>
                       
                        <div class="col-2">
                            <div class="form-group">
                                <label class="text-nowrap">Preço Mínimo Concorrência</label>
                                <input type="text" class="form-control" value="<?php echo number_format($row['preco_minimo'], 4, ',', '.'); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label class="text-nowrap">Preço Médio Concorrência</label>
                                <input type="text" class="form-control" value="<?php echo number_format($row['preco_medio'], 4, ',', '.'); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label class="text-nowrap">Preço Unit. Oncoprod</label>
                                <input type="text" class="form-control" value="<?php echo number_format($row['preco_catalogo'], 4, ',', '.'); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label>Média Ofertada</label>
                                <input type="text" class="form-control" value="<?php echo number_format($row['media_oferta'], 4, ',', '.'); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label>Diferença %</label>
                                <input type="text" class="form-control" value="<?php echo number_format($row['valor_diferenca'], 4, ',', '.'); ?>" readonly>
                            </div>
                        </div>

                        <div class="col-1 mt-1">
                            <?php if( $row['media_oferta'] > 0 ): ?>
                                <button class="btn btn-light btn--icon-text mt-4" onclick="consulta(<?php echo "{$produto['codigo']}, {$produto['id_fornecedor']}, '{$row['estado']['uf']}', {$row['preco_medio']}"; ?>)" data-toogle="tooltip" title="Analise de mercado"><i class="fas fa-volume-up"></i></button>
                            <?php endif; ?>
                        </div>
                    </div>

                   <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <?php echo $scripts; ?>

    <script>
        var urlAnaliseMercado = "<?php echo $urlAnaliseMercado; ?>";

        function consulta( codigo, id_fornecedor, uf, preco ) 
        {

            var data = {
                codigo: codigo,
                id_fornecedor: id_fornecedor,
                uf: uf,
                preco: preco,
            };

            $.post(urlAnaliseMercado, data, function( xhr ) { 
                
                if ( xhr.type == 'success' ) {

                    Object.entries(xhr.message).forEach(([key, value]) => {

                        if ('speechSynthesis' in window) {

                            var synthesis = window.speechSynthesis;

                            // Get the first `en` language voice in the list
                            var voice = synthesis.getVoices().filter(function(voice) {
                                return voice.lang === 'pt-br';
                            })[0];

                            // Create an utterance object
                            var utterance = new SpeechSynthesisUtterance(value);

                            // Set utterance properties
                            utterance.voice = voice;
                            // utterance.pitch = 1.5;
                            // utterance.rate = 1.25;
                            // utterance.volume = 0.8;

                            // Speak the utterance
                            synthesis.speak(utterance);
                        } else {

                          console.log('Text-to-speech not supported.');
                        }
                         
                    });
                }
            });
            
            return;
        }
    </script>
</body>

</html>
