<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<?php helper('verifica_imagem_existente'); ?>

<div class="container-fluid">
	<div class="container">
		<nav class="breadcrumb bg-transparent m-0 p-0">
			<a class="breadcrumb-item" href="<?=base_url() . 'site/';?>">Principal</a>
			<a class="breadcrumb-item" href="<?=base_url() . 'site/artigos/'?>">Artigos</a>
			<span class="breadcrumb-item active"><?= $artigo['titulo'];?></span>
		</nav>
	</div>
</div>
<div class="container-fluid py-3">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="position-relative mb-3">
					<img class="w-100" height="625" src="<?=verifica_imagem_existente($artigo['imagem']);?>"></img>
					<div class="overlay position-relative bg-light p-3">
						<div class="mb-3">
							<?php helper("month_helper"); ?>
							<span><?= date_format(new DateTime($artigo['criado']), 'd') . ' ' . month_helper(date_format(new DateTime($artigo['criado']), 'F'), 3) . '. ' . date_format(new DateTime($artigo['criado']), 'Y'); ?></span>
						</div>
						<!-- <div class="mb-3">
							<?php //foreach($artigo['categorias'] as $categoria): ?>
								<span class="badge vl-bg-c m-1 p-1">
									<a href="<? //base_url() . 'site/artigos/'.$categoria['id']; ?>"><? //$categoria['nome'];?></a>
								</span>
							<?php //endforeach; ?>
						</div> -->
						<div class="mb-3">
							<a href="<?=$artigo['link_video_youtube']?>">Ver no YouTube</a>
						</div>
						<div class="mb-3">
							<?php if ($artigo['colaboradores']['sugerido'] !== null) : ?>
							<div class="badge badge-secondary m-1 p-1">Sugerido: <?=$artigo['colaboradores']['sugerido']; ?></div>
							<?php endif; ?>
							<?php if ($artigo['colaboradores']['escrito'] !== null) : ?>
							<div class="badge badge-secondary m-1 p-1">Escritor: <?=$artigo['colaboradores']['escrito']; ?></div>
							<?php endif; ?>
							<?php if ($artigo['colaboradores']['revisado'] !== null) : ?>
							<div class="badge badge-danger m-1 p-1">Revisor: <?=$artigo['colaboradores']['revisado']; ?></div>
							<?php endif; ?>
							<?php if ($artigo['colaboradores']['narrado'] !== null) : ?>
							<div class="badge badge-warning m-1 p-1">Narrador: <?=$artigo['colaboradores']['narrado']; ?></div>
							<?php endif; ?>
							<?php if ($artigo['colaboradores']['produzido'] !== null) : ?>
							<div class="badge badge-success m-1 p-1">Produtor: <?=$artigo['colaboradores']['produzido']; ?></div>
							<?php endif; ?>
						</div>
						<div>
							<h3 class="mb-3"><?= $artigo['titulo'];?></h3>
							<p><?= str_replace("\n",'<br/>',$artigo['texto_revisado']);?></p>
							<h4 class="mb-3">Referências:</h4>
							<p><?= str_replace('\r\n','<br/>',$artigo['referencias']);?></p>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<?= $this->endSection(); ?>