<div class="messages__sidebar">
    <div class="toolbar toolbar--inner mb-3">
        <div class="toolbar__label">Contatos</div>
    </div>

    <div class="messages__search">
        <div class="form-group">
            <input type="text" class="form-control inpt-search-cnt" placeholder="Search...">
        </div>
    </div>

    <div class="listview listview--hover">
        <div class="scrollbar-inner">
            <?php foreach ($contatos as $contato) { ?>
                <a class="listview__item <?php if (isset($contato['active']) && $contato['active'] == true) echo  'listview__item--active'; ?>" data-name="<?php echo $contato['nome']; ?>" href="<?php echo $contato['url']; ?>">
                    <div class="pull-left">
                        <img src="<?php echo $contato['src_logo']; ?>" alt="" class="listview__img">
                    </div>
                    <div class="listview__content">
                        <div class="listview__heading"><?php echo $contato['nome']; ?></div>
                        <small><?php echo $contato['email']; ?></small> <br>
                        <small><?php echo $contato['NIVEL']; ?></small>
                    </div>
                    <?php if (isset($contato['qtd_msg']) && $contato['qtd_msg'] > 0){?>
                    <div class="pull-right">
                        <span class="badge badge-pill badge-info pull-left ml-1"><?php echo $contato['qtd_msg']; ?></span>
                    </div>
                    <?php } ?>
                </a>
            <?php } ?>
        </div>
    </div>
</div>

