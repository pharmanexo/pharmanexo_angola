<div class="modal fade" id="modalMarca" aria-hidden="true">
    <div class="modal-dialog modal-sm" style="max-width: 500px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title pull-left"><?php echo $title; ?></h5>
            </div>

            <div class="modal-body">
                <form data-url="<?php echo $form_action ?>" id="form" method="POST">
                    <input type="hidden" name="marca" id="desc_marca" value="">
                    <div class="form-group">
                        <label>Marca</label>
                        <select name="id_marca" id="marcas" class="w-100 form-control" style="width: 100%" data-url="<?php echo $select2_marcas; ?>"></select>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="submit" form="form" class="btn btn-primary">Selecionar</button>
                <button type="button" class="btn btn-link" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>

    var slc_marca = $('#marcas');

    $(function() {

        slc_marca.select2({
            placeholder: 'Selecione...',
            dropdownParent: $('#modalMarca'),
            ajax: {
                url: slc_marca.data('url'),
                type: 'get',
                dataType: 'json',
                delay: 300,
                data: function (params) {
                    return {
                        columns: [{
                            name: 'marca',
                            search: params.term
                        }],
                        page: params.page || 1,
                    }
                }
            },
            processResults: function (data) {
                return {
                    results: data
                }
            },

            templateResult: function (data, container) {
                if (!data.id) {
                    return data.text;
                }

                return data.marca;
            },
            templateSelection: function (data, container) {
                if (!data.id) {
                    return data.marca;
                }

                return (typeof data.marca !== 'undefined') ? data.marca : '';
            }
        });

        $('#form').submit(function(e) {
            e.preventDefault();

            console.log($('#marcas').val());

             var formData = new FormData(this);

            $.ajax({
                url: $('#form').data('url'),
                type: 'post',
                contentType: false,
                processData: false,
                data: formData,
                beforeSend: function(jqXHR, settings) {
                    if ($('#marcas').val() == null ) {
                        formWarning({ type: 'warning', message: "O campo marca é obrigatório."});
                        return jqXHR.abort();
                    }   
                },
                success: function(xhr) {
                    if (xhr.type === 'warning') {
                       formWarning(xhr);
                    } else {
                        formWarning(xhr);
                        $('#modalMarca').modal('hide');
                    }
                },
                error: function(xhr) {
                    formWarning({ type: 'warning', message: "Erro ao salvar as informações!" });
                }
            })
        });
    });
</script>
