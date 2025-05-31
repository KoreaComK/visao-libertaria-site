<?php

use CodeIgniter\I18n\Time;

?>

<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js"
	integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous"
	async></script>

<script src="https://unpkg.com/infinite-scroll@4/dist/infinite-scroll.pkgd.min.js"></script>

<style>
	.page-load-status {
		display: none;
	}
</style>

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


<div class="container-fluid py-3">
	<div class="container">

		<section class="pt-4 pb-4">
			<div class="row">
				<div class="col-12">
					<nav class="custom-breadcrumb" aria-label="breadcrumb">
						<ol class="breadcrumb">
							<li class="breadcrumb-item">
								<a href="<?= site_url(); ?>">
									<i class="bi bi-house-fill pe-1"></i>Home
								</a>
							</li>
							<li class="breadcrumb-item">
								<a href="javascript:void(0);">
									<i class="bi bi-journal-text pe-1"></i>Artigos
								</a>
							</li>
							<li class="breadcrumb-item active" aria-current="page">
								<i class="bi bi-grid-fill pe-1"></i>Todos os Artigos Publicados
							</li>
						</ol>
					</nav>
				</div>
			</div>
		</section>

		<div class="row list-artigos" data-masonry='{"percentPosition": true }'>
			<?php foreach ($artigosList['artigos'] as $artigo): ?>
				<?= view_cell('\App\Libraries\Cards::cardsVerticaisSimples', $artigo); ?>
			<?php endforeach; ?>
		</div>

		<div class="d-none">
			<?php if ($artigosList['pager']): ?>
				<?= $artigosList['pager']->simpleLinks('artigos', 'default_template') ?>
			<?php endif; ?>
		</div>

		<div class="page-load-status">
			<div class="infinite-scroll-request d-flex justify-content-center mt-5 mb-5">
				<div class="spinner-border" role="status">
					<span class="visually-hidden">Carregando...</span>
				</div>
			</div>
			<p class="infinite-scroll-last">Fim do conteúdo</p>
			<p class="infinite-scroll-error">Todo o conteúdo foi carregado.</p>
		</div>
	</div>
</div>

<script>
	$(document).ready(function () {
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
			status: '.page-load-status'
		});
	});
</script>

<?= $this->endSection(); ?>