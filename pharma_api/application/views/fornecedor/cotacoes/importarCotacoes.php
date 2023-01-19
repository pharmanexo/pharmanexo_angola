<!DOCTYPE html>
<html lang="pt-BR">
<?php echo $header; ?>

<body class="bg-light">
    <?php echo $navbar; ?>
    <?php echo $sidebar; ?>
    <div class="content">
        <?php echo $heading; ?>
        <div class="content__inner">
            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="">Selecione o portal</label>
                        <select name="integrador" id="integrador" class="form-control">
                            <option value="">Selecione</option>
                            <?php foreach ($portais as $portal){ ?>
                                <option value="<?php echo $portal['id']; ?>"><?php echo $portal['desc']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="col-4">

                </div>
                <div class="col-4">

                </div>
            </div>
        </div>
    </div>

    <?php echo $scripts; ?>

    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

    <script>
        $(function () {

        });
    </script>
</body>

</html>