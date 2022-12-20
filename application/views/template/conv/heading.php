
<div class="content__inner bg-light sticky-top px-3 py-3" style="top: 71px; z-index: 3">
    <div class="row">
        <div class="col-6">
            <h3 class="text-muted mb-3"><?php if (isset($page_title)) echo $page_title; ?></h3>
        </div>
        <div class="col-6 text-right">
            <?php if (isset($buttons)) { ?>
                <?php foreach ($buttons as $button) { ?>
                    <?php if ($button['type'] == 'submit') { ?>
                        <button type="submit" id="<?php if (isset($button['id'])) echo $button['id'] ?>" form="<?php if (isset($button['form'])) echo $button['form'] ?>" class="btn <?php if (isset($button['class'])) echo $button['class'] ?>">
                            <i class="fas <?php if (isset($button['icone'])) echo $button['icone'] ?>"></i> <?php if (isset($button['label'])) echo $button['label'] ?>
                        </button>
                    <?php } else { ?>
                        <a href="<?php if (isset($button['url'])) echo $button['url']; ?>" id="<?php if (isset($button['id'])) echo $button['id'] ?>" class="btn <?php if (isset($button['class'])) echo $button['class'] ?>" <?php if ( isset($button['label']) && $button['label'] == 'Exportar Excel' ) echo 'data-toggle="tooltip" title="Exportar todos os registros" ' ?>>
                            <i class="fas <?php if (isset($button['icone'])) echo $button['icone'] ?>"></i> <?php if (isset($button['label'])) echo $button['label'] ?>
                        </a>
                    <?php } ?>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</div>
