<?php
use CodeIgniter\I18n\Time;
?>

<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<div class="container-fluid">
	<div class="container">
		<nav class="breadcrumb bg-transparent m-0 p-0">
			<a class="breadcrumb-item" href="<?=base_url() . 'site/';?>">Principal</a>
			<a class="breadcrumb-item" href="<?=base_url() . 'colaboradores/pautas/'?>">Pautas</a>
			<span class="breadcrumb-item active"><?= $pauta['titulo'];?></span>
		</nav>
	</div>
</div>
<div class="container-fluid py-3">
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="position-relative mb-3">
					<img class="w-100" height="auto;" src="<?=$pauta['imagem']?>"></img>
					<div class="overlay position-relative bg-light p-3">
						<div class="mb-3">
							<span><?= Time::createFromFormat('Y-m-d H:i:s', $pauta['criado'])->toLocalizedString('dd MMMM yyyy'); ?></span>
						</div>
						<div class="mb-3">
							<div class="badge badge-secondary m-1 p-1">Sugerido: <?=$pauta['colaborador']['apelido']; ?></div>
						</div>
						<div>
							<h3 class="mb-3"><?= $pauta['titulo'];?></h3>
							<p><?= str_replace("\n",'<br/>',$pauta['texto']);?></p>
							<p class="mb-3"><a href="<?=$pauta['link'];?>" target="_blank">Ler notícia original</a></p>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<meta property="og:title" content="<?= $pauta['titulo'];?>" />
<meta property="og:image" content="<?= $pauta['imagem'];?>"/>
<meta property="og:description" content="<?= $pauta['texto'];?>" />

<meta property="twitter:title" content="<?= $pauta['titulo'];?>" />
<meta property="twitter:description" content="<?= $pauta['imagem'];?>"/>
<meta property="twitter:image" content="<?= $pauta['texto'];?>" />

<?= $this->endSection(); ?>