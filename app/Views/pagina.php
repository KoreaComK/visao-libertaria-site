<?php use CodeIgniter\I18n\Time; ?>

<?php if(in_array($estatica['localizacao'],array('menu_site','rodape_site'))): ?>
	<?= $this->extend('layouts/main'); ?>
<?php elseif(in_array($estatica['localizacao'],array('menu_colaborador'))): ?>
	<?= $this->extend('layouts/colaboradores'); ?>
<?php elseif(in_array($estatica['localizacao'],array('menu_administrador'))): ?>
	<?= $this->extend('layouts/administradores'); ?>
<?php endif; ?>

<?= $this->section('content'); ?>

<div class="container-fluid py-3">
	<div class="container d-flex justify-content-center">
		<div class="col-lg-8">
			<h1 class="display-2"><?= $estatica['titulo']; ?></h1>
			<div class="position-relative mb-3">
				<div class="pt-3 pb-3">
					<div>
						<div><?= str_replace("\n", '<br/>', $estatica['conteudo']); ?></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?= $this->endSection(); ?>