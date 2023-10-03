<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<!-- <div class="container">
	<div class="">
		<div class="bg-light py-2 px-4 mb-3">
			<h3 class="m-0">Categorias</h3>
		</div>
	</div>
	<div class="d-flex flex-wrap m-n1 pl-2 pr-2">
		<?php //foreach ($widgetCategorias as $categorias) : ?>
			<a href="<? //base_url() . 'site/artigos/' . $categorias['id']; ?>" class="btn btn-sm btn-outline-secondary m-1 <? //($categorias['id'] == $idCategoriaAtual) ? ('active') : (''); ?>"><? //$categorias['nome']; ?></a>
		<?php //endforeach; ?>
	</div>
</div> -->

<!-- <div class="container-fluid mt-5">
	<div class="container">
		<nav class="breadcrumb bg-transparent m-0 p-y2">
			<a class="breadcrumb-item" href="<?= base_url() . 'site'; ?>">Principal</a>
			<?php if ($nomeCategoriaAtual == null) : ?>
				<span class="breadcrumb-item active">Categorias</span>
			<?php else : ?>
				<a class="breadcrumb-item" href="<?= base_url() . 'site/artigos' ?>">Categorias</a>
				<span class="breadcrumb-item active"><?= $nomeCategoriaAtual; ?></span>
			<?php endif; ?>
		</nav>
	</div>
</div> -->
<div class="container-fluid py-3">
	<div class="container">
		<div class="">
			<div class="d-flex align-items-center justify-content-between bg-light py-2 px-4 mb-3">
				<h3 class="m-0"><?= ($nomeCategoriaAtual !== null) ? ($nomeCategoriaAtual) : ('Todos os artigos'); ?></h3>
			</div>
		</div>

		<div class="col-lg-12 row">
			<?php helper('month_helper');
			foreach ($artigosList['artigos'] as $artigo) : ?>
				<div class="col-lg-3">
					<div class="position-relative mb-3">
						<img class="img-fluid w-100" src="<?= $artigo['imagem']; ?>" style="object-fit: cover; max-height: 135px;">
						<div class="overlay position-relative bg-light p-2">
							<div class="mb-2" style="font-size: 14px;">
								<span><?= date_format(new DateTime($artigo['criado']), 'd') . ' ' . month_helper(date_format(new DateTime($artigo['criado']), 'F'), 3) . ' ' . date_format(new DateTime($artigo['criado']), 'Y'); ?></span>
							</div>
							<p class=""><a class="h5" href="<?= base_url() . 'site/artigo/' . $artigo['url_friendly']; ?>"><?= $artigo['titulo']; ?></a></p>
							<p class=""><?= substr($artigo['texto_revisado'], 0, 50) . '...'; ?></p>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<?php if ($artigosList['pager']) : ?>
			<?= $artigosList['pager']->simpleLinks('artigos', 'default_template') ?>
		<?php endif; ?>
	</div>
</div>


<?= $this->endSection(); ?>