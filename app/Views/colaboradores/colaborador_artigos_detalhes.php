<?php
use CodeIgniter\I18n\Time;

?>

<?= $this->extend('layouts/colaboradores'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid py-3">
	<div class="container d-flex justify-content-center">
		<div class="col-lg-8">
			<h1 class="display-2 text-black"><?= $artigo['titulo']; ?></h1>
			<div class="position-relative mb-3">
				<div class="pt-3 pb-3">
					<!-- <div class="mb-3">
							<?php //foreach($artigo['categorias'] as $categoria): ?>
								<span class="badge vl-bg-c m-1 p-1">
									<a href="<? //base_url() . 'site/artigos/'.$categoria['id']; ?>"><? //$categoria['nome']; ?></a>
								</span>
							<?php //endforeach; ?>
						</div> -->
					<div>
						<p><?= str_replace("\n", '<br/>', $artigo['texto_producao']); ?></p>
						<h4 class="mb-3">Referências:</h4>
						<p><?= str_replace("\n", '<br/>', $artigo['referencias']); ?></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?= $this->endSection(); ?>