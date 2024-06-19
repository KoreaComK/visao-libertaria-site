<?php

use CodeIgniter\I18n\Time;

?>

<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js"
	integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous"
	async></script>

<script src="https://unpkg.com/infinite-scroll@4/dist/infinite-scroll.pkgd.min.js"></script>

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
			<?php if ($nomeCategoriaAtual == null): ?>
				<span class="breadcrumb-item active">Categorias</span>
			<?php else: ?>
				<a class="breadcrumb-item" href="<?= base_url() . 'site/artigos' ?>">Categorias</a>
				<span class="breadcrumb-item active"><?= $nomeCategoriaAtual; ?></span>
			<?php endif; ?>
		</nav>
	</div>
</div> -->
<div class="container-fluid py-3">
	<div class="container">

		<section class="pt-4 pb-4">
			<div class="container">
				<div class="row">
					<div class="col-12 p-0">
						<div class="bg-dark p-4 text-center rounded-4">
							<h1 class="text-white">
								<?= ($nomeCategoriaAtual !== null) ? ($nomeCategoriaAtual) : ('Artigos Publicados'); ?>
							</h1>
							<nav class="d-flex justify-content-center" aria-label="breadcrumb">
								<ol class="breadcrumb breadcrumb-dark m-0">
									<li class="breadcrumb-item "><a href="<?= site_url(); ?>" class="text-white"><i
												class="bi bi-house me-1"></i>
											Home</a></li>
									<li class="breadcrumb-item active text-secondary">
										<?= ($nomeCategoriaAtual !== null) ? ($nomeCategoriaAtual) : ('Artigos Publicados'); ?>
									</li>
								</ol>
							</nav>
						</div>
					</div>
				</div>
			</div>
		</section>

		<div class="row list-artigos" data-masonry='{"percentPosition": true }'>
			<?php foreach ($artigosList['artigos'] as $artigo): ?>

				<div class="card shadow-0 col-sm-6 col-lg-3">
					<div class="bg-image hover-zoom rounded-3">
						<img class="w-100" src="<?= $artigo['imagem']; ?>" style="object-fit: cover;">
					</div>
					<div class="card-body p-2">
						<h5 class="card-title fw-bold"><a class="btn-link text-black h5"
								href="<?= base_url() . 'site/artigo/' . $artigo['url_friendly']; ?>">
								<?= $artigo['titulo']; ?></a>
						</h5>
						<div>
								<small>
									<ul class="nav nav-divider">
										<li class="nav-item pointer">
											<div class="d-flex text-muted">
												<span class="">Por <a href="#"
														class="text-muted btn-link"><?= $artigo['apelido']; ?></a></span>
											</div>
										</li>
										<li class="nav-item pointer text-muted">
											<?= Time::createFromFormat('Y-m-d H:i:s', $artigo['publicado'])->toLocalizedString('dd/MM/yyyy'); ?>
										</li>
									</ul>
								</small>
							<p class="">
								<?= substr($artigo['texto_revisado'], 0, strpos($artigo['texto_revisado'], "\n")); ?>
							</p>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<div class="d-none">
			<?php if ($artigosList['pager']): ?>
				<?= $artigosList['pager']->simpleLinks('artigos', 'default_template') ?>
			<?php endif; ?>
		</div>
	</div>
</div>

<script>
	var $grid = $('.list-artigos').masonry({
		// Masonry options...
		itemSelector: '.card',
		horizontalOrder: true
	});

	var msnry = $grid.data('masonry');

	$grid.infiniteScroll({
		// Infinite Scroll options...
		path: '.next_page',
		append: '.card',
		history: false,
		outlayer: msnry,
	});
</script>

<?= $this->endSection(); ?>