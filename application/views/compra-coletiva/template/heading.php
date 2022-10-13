<div class="row topo">
	<div class="col-6 text-left title-page">
		<h4 class="text-muted"><?php echo isset($title) ? $title : 'PORTAL DE ADESÕES'?></h4>
	</div>
	<div class="col-6 text-right pt-3">
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
<hr class="mb-3">
