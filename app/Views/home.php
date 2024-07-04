<?php

use CodeIgniter\I18n\Time;

?>

<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>

<style>
	.main-carousel .carousel-cell {
		width: 28%;
		height: auto;
		margin-right: 0.75rem;
		counter-increment: carousel-cell;
	}

	.avisos-carousel .carousel-cell {
		width: 100%;
		height: auto;
		margin-right: 3rem;
	}

	.main-carousel-videos .carousel-cell {
		width: 40%;
		height: auto;
		margin-right: 0.75rem;
		counter-increment: carousel-cell;
	}

	.avisos-carousel>.flickity-prev-next-button.previous {
		right: 50px;
		left: inherit;
	}

	.avisos-carousel .flickity-page-dots {
		display: none;
	}

	.banner .flickity-viewport {
		min-height: 13rem;
	}

	.banner-bigger .flickity-viewport .bg-image {
		min-height: 18rem;
	}

	.avisos-carousel .flickity-button {
		background: transparent;
		width: 25px;
		height: 25px;
	}

	.avisos-carousel .card {
		background: transparent;
	}

	@media (max-width: 768px) {
		.main-carousel .carousel-cell {
			width: 50%;
		}

		.main-carousel-videos .carousel-cell {
			width: 100%;
		}
	}

	@media (max-width: 992px) {
		.side-image {
			margin-top: 1.5rem;
		}
	}
</style>

<link rel="stylesheet" href="https://unpkg.com/flickity@2/dist/flickity.min.css">
<script src="https://unpkg.com/flickity@2/dist/flickity.pkgd.min.js"></script>

<!-- Title -->



<div class="container-fluid py-3">
	<div class="container">
		<div class="row">
			<section class="col-lg-12">
				<div class="container vl-bg-c-opaco p-2 rounded-3 position-relative mt-1 mb-3">
					<span class="badge vl-bg-c p-2 px-3 position-absolute top-50 start-0 translate-middle badge"
						style="z-index:1; margin-left: 2.8rem;">Avisos:</span>
					<div class="avisos-carousel mb-0 mt-0 data-flickity" style="margin-left: 3.8rem;">
						<div class="carousel-cell card shadow-0" id="tns1-item0">
							<div class="card-body pt-1 pb-1">
								<a href="#" class="">The most
									common business debate isn't as black and white
									as you might think</a>
							</div>
						</div>
						<div class="carousel-cell card shadow-0" id="tns1-item0">
							<div class="card-body pt-1 pb-1">
								<a href="#" class="">The most
									common business debate isn't as black and white
									as you might think</a>
							</div>
						</div>
					</div>
				</div>
			</section>
			<section class="col-lg-12">
				<div class="row">
					<!-- Left big card -->
					<div class="col-lg-6">
						<div class="card bg-image hover-zoom"
							style="background-position: center center; background-size: cover; height:30rem;">
							<img src="<?= $banner[0]['imagem']; ?>" style="height:30rem;" />
							<!-- Card Image overlay -->
							<div class="mask align-items-center p-3 p-sm-4"
								style="position: absolute; background-color: rgba(0, 0, 0, 0.6);">
								<div class="w-100" style="margin-top:12rem;">
									<!-- Card title -->
									<h2 class="h1"><a
											href="<?= site_url('site/artigo/' . $banner[0]['url_friendly']) ?>"
											class="btn-link stretched-link text-white">
											<?= $banner[0]['titulo']; ?>
										</a></h2>
									<p class="text-white"><?= $banner[0]['gancho']; ?></p>
									<!-- Card info -->
									<ul class="nav nav-divider align-items-center">
										<li class="nav-item pointer">
											<div class="d-flex align-items-center text-white position-relative">
												<?php if ($banner[0]['avatar'] != NULL && $banner[0]['avatar'] != ""): ?>
													<div class="avatar avatar-sm">
														<img class="avatar-img rounded-circle"
															src="<?= $banner[0]['avatar']; ?>" style="width:45px;"
															alt="avatar">
													</div>
												<?php endif; ?>
												<span
													class="<?= ($banner[0]['avatar'] != NULL && $banner[0]['avatar'] != "") ? ('ms-3') : (''); ?>">por
													<a href="#"
														class="stretched-link text-white btn-link"><?= $banner[0]['apelido']; ?></a></span>
											</div>
										</li>
										<li class="nav-item pointer text-white">
											<?= Time::createFromFormat('Y-m-d H:i:s', $banner[0]['publicado'])->toLocalizedString('dd MMM yyyy'); ?>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<!-- Right small cards -->
					<div class="col-lg-6 side-image">
						<div class="row">
							<!-- Card item START -->
							<div class="col-12">
								<div class="card bg-image hover-zoom"
									style="background-position: center center; background-size: cover; height:15rem;">
									<img src="<?= $banner[1]['imagem']; ?>" style="height:15rem;" />
									<!-- Card Image -->
									<!-- Card Image overlay -->
									<div class="mask align-items-center p-3 p-sm-4"
										style="position: absolute; background-color: rgba(0, 0, 0, 0.6);">
										<div class="w-100" style="margin-top:7rem;">
											<!-- Card title -->
											<h4 class="text-white"><a
													href="<?= site_url('site/artigo/' . $banner[1]['url_friendly']) ?>"
													class="btn-link stretched-link text-reset"><?= $banner[1]['titulo']; ?></a>
											</h4>
											<!-- Card info -->
											<ul class="nav nav-divider align-items-center">
												<li class="nav-item pointer">
													<div class="d-flex align-items-center text-white position-relative">
														<?php if ($banner[1]['avatar'] != NULL && $banner[1]['avatar'] != ""): ?>
															<div class="avatar avatar-sm">
																<img class="avatar-img rounded-circle"
																	src="<?= $banner[1]['avatar']; ?>" style="width:45px;"
																	alt="avatar">
															</div>
														<?php endif; ?>
														<span
															class="<?= ($banner[1]['avatar'] != NULL && $banner[1]['avatar'] != "") ? ('ms-3') : (''); ?>">por
															<a href="#"
																class="stretched-link text-white btn-link"><?= $banner[1]['apelido']; ?></a></span>
													</div>
												</li>
												<li class="nav-item pointer text-white">
													<?= Time::createFromFormat('Y-m-d H:i:s', $banner[0]['publicado'])->toLocalizedString('dd MMM yyyy'); ?>
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<!-- Card item END -->
							<!-- Card item START -->
							<div class="col-md-6 g-4 pt-2">
								<div class="card bg-image hover-zoom"
									style="background-position: center center; background-size: cover; height:13rem;">
									<img src="<?= $banner[2]['imagem']; ?>" style="height:15rem;" />
									<!-- Card Image overlay -->
									<div class="mask align-items-center p-3 p-sm-4"
										style="position: absolute; background-color: rgba(0, 0, 0, 0.6);">
										<div class="w-100" style="margin-top:2rem;">
											<!-- Card category -->
											<h5 class="text-white"><a
													href="<?= site_url('site/artigo/' . $banner[2]['url_friendly']) ?>"
													class="btn-link stretched-link text-reset"><?= $banner[2]['titulo']; ?></a>
											</h5>
											<!-- Card info -->
											<ul class="nav nav-divider align-items-center">
												<li class="nav-item pointer">
													<div class="d-flex align-items-center text-white position-relative">
														<?php if ($banner[2]['avatar'] != NULL && $banner[2]['avatar'] != ""): ?>
															<div class="avatar avatar-sm">
																<img class="avatar-img rounded-circle"
																	src="<?= $banner[2]['avatar']; ?>" style="width:45px;"
																	alt="avatar">
															</div>
														<?php endif; ?>
														<span
															class="<?= ($banner[2]['avatar'] != NULL && $banner[2]['avatar'] != "") ? ('ms-3') : (''); ?>">por
															<a href="#"
																class="stretched-link text-white btn-link"><?= $banner[2]['apelido']; ?></a></span>
													</div>
												</li>
												<li class="nav-item pointer text-white">
													<?= Time::createFromFormat('Y-m-d H:i:s', $banner[0]['publicado'])->toLocalizedString('dd MMM yyyy'); ?>
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							<!-- Card item END -->
							<!-- Card item START -->
							<div class="col-md-6 g-4 pt-2">
								<div class="card bg-image hover-zoom"
									style="background-position: center center; background-size: cover; height:13rem;">
									<img src="<?= $banner[3]['imagem']; ?>" style="height:15rem;" />
									<!-- Card Image overlay -->
									<div class="mask align-items-center p-3 p-sm-4"
										style="position: absolute; background-color: rgba(0, 0, 0, 0.6);">
										<div class="w-100" style="margin-top:2rem;">
											<h5 class="text-white"><a
													href="<?= site_url('site/artigo/' . $banner[3]['url_friendly']) ?>"
													class="btn-link stretched-link text-reset"><?= $banner[3]['titulo']; ?></a>
											</h5>
											<!-- Card info -->
											<ul class="nav nav-divider align-items-center">
												<li class="nav-item pointer">
													<div class="d-flex align-items-center text-white position-relative">
														<?php if ($banner[3]['avatar'] != NULL && $banner[3]['avatar'] != ""): ?>
															<div class="avatar avatar-sm">
																<img class="avatar-img rounded-circle"
																	src="<?= $banner[3]['avatar']; ?>" style="width:45px;"
																	alt="avatar">
															</div>
														<?php endif; ?>
														<span
															class="<?= ($banner[3]['avatar'] != NULL && $banner[0]['avatar'] != "") ? ('ms-3') : (''); ?>">por
															<a href="#"
																class="stretched-link text-white btn-link"><?= $banner[3]['apelido']; ?></a></span>
													</div>
												</li>
												<li class="nav-item pointer text-white">
													<?= Time::createFromFormat('Y-m-d H:i:s', $banner[0]['publicado'])->toLocalizedString('dd MMM yyyy'); ?>
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- <div class="col-lg-4">
				<div class="d-flex align-items-center justify-content-between bg-light py-2 px-4 mb-3">
					<h3 class="m-0">Categorias</h3>
					<a class="text-secondary font-weight-medium text-decoration-none" href="">Ver Todos</a>
				</div>
				<div class="d-flex flex-wrap m-n1">
					<?php //foreach($widgetCategorias as $categorias): 
					?>
						<a href="<? //base_url().'site/artigos/'.$categorias['id'];
						?>" class="btn btn-sm btn-outline-secondary m-1"><? //$categorias['nome'];
						?></a>
					<?php //endforeach; 
					?>
				</div>
			</div> -->

			<section class="col-lg-12 banner">

				<div class="main-carousel mb-4 mt-4">
					<?php foreach ($banner as $chave => $b): ?>
						<?php if ($chave > 3): ?>
							<div class="carousel-cell mb-3">
								<div class="card bg-image hover-zoom round-5">
									<img class="card-img" src="<?= $b['imagem']; ?>" alt="<?= $b['titulo']; ?>"
										style="height: inherit;">
									<div class="card-img-overlay d-flex flex-column p-3 p-sm-4">
										<div class="w-100 mt-auto">
											<h4 class="text-white"><a
													href="<?= site_url('site/artigo/' . $b['url_friendly']) ?>"
													class="text-white stretched-link btn-link"><?= $b['titulo']; ?></a>
											</h4>
											<ul
												class="nav nav-divider text-white align-items-center d-none d-sm-inline-block small">
												<li class="nav-item">
													<div class="text-white">por <a
															href="<?= site_url('site/artigo/' . $b['url_friendly']) ?>"
															class="text-white btn-link"><?= $b['apelido']; ?></a>
													</div>
												</li>
												<li class="nav-item">
													<?= Time::createFromFormat('Y-m-d H:i:s', $b['publicado'])->toLocalizedString('dd MMM yyyy'); ?>
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>

			</section>

			<section class="col-lg-12 banner-bigger">

				<div class="m-4">
					<h2 class="m-0"><i class="bi bi-hourglass-top me-2"></i>Últimos vídeos do Visão Libertária</h2>
					<p>Os vídeos mais recentes pra você se manter informado.</p>
				</div>

				<div class="main-carousel-videos mb-4 mt-4">
					<?php for ($max = 0; $max < 5; $max++): ?>
						<div class="carousel-cell">
							<div class="card mb-3 shadow-0">
								<!-- Card img -->
								<div class="position-relative bg-image hover-zoom shadow-1-strong rounded"
									data-mdb-ripple-init data-mdb-ripple-color="light">
									<img class="card-img" alt="Card image" style="height:auto;"
										src="<?= $artigos[$max]['imagem']; ?>">
									<a href="<?= site_url('site/artigo/' . $b['url_friendly']) ?>">
										<div class="mask" style="background-color: hsla(0, 0%, 98%, 0.2)"></div>
									</a>
									<!-- <div class="card-img-overlay d-flex align-items-start flex-column p-3">
												<div class="w-100 mt-auto">
													<a href="#" class="badge text-bg-warning mb-2"><i
															class="fas fa-circle me-2 small fw-bold"></i>Technology</a>
												</div>
											</div> -->
								</div>
								<div class="card-body px-3 pt-3">
									<h4 class="card-title"><a href="<?= site_url('site/artigo/' . $b['url_friendly']) ?>"
											class="btn-link text-reset stretched-link fw-bold"><?= $artigos[$max]['titulo']; ?></a>
									</h4>
									<p class="card-text"><?= $artigos[$max]['gancho']; ?></p>
									<!-- Card info -->
									<ul class="nav nav-divider align-items-center">
										<li class="nav-item pointer">
											<div class="d-flex align-items-center position-relative">
												<?php if ($artigos[$max]['avatar'] != NULL && $artigos[$max]['avatar'] != ""): ?>
													<div class="avatar avatar-xs">
														<img class="avatar-img rounded-circle"
															src="<?= $artigos[$max]['avatar']; ?>" style="width:45px;"
															alt="avatar">
													</div>
												<?php endif; ?>
												<span
													class="<?= ($artigos[$max]['avatar'] != NULL && $artigos[$max]['avatar'] != "") ? ('ms-3') : (''); ?>">por
													<a href="#"
														class="stretched-link text-reset btn-link"><?= $artigos[$max]['apelido']; ?></a></span>
											</div>
										</li>
										<li class="nav-item pointer">
											<?= Time::createFromFormat('Y-m-d H:i:s', $artigos[$max]['publicado'])->toLocalizedString('dd MMM yyyy'); ?>
										</li>
									</ul>
								</div>
							</div>
						</div>
					<?php endfor; ?>
				</div>
			</section>



			<?php if ($config['home_ultimos_videos_mostrar'] == '1'): ?>
				<div class="col-lg-8">
					<div class="row mb-3">
						<div class="mb-4 d-md-flex justify-content-between align-items-center">
							<h2 class="m-0"><i class="bi bi-megaphone"></i> Nossos vídeos</h2>
							<a class="text-secondary font-weight-medium text-decoration-none"
								href="<?= site_url('site/artigos') ?>">Ver Todos</a>
						</div>
						<div class="col-lg-12 row">

							<?php if (is_array($artigos)): ?>
								<?php foreach ($artigos as $chave => $artigo): ?>
									<?php if ($chave > 4): ?>
										<div class="card shadow-0 col-6 mb-3">
											<div class="row g-3">
												<div class="col-5">
													<img class="rounded" style="max-width: inherit;" src="<?= $artigo['imagem'] ?>"
														alt="">
												</div>
												<div class="col-7">
													<h6 class="m-0"><a href="<?= site_url('site/artigo/' . $artigo['url_friendly']); ?>"
															class="btn-link stretched-link text-reset">Ten
															<?= $artigo['titulo'] ?></a></h6>
													<!-- Card info -->
													<ul class="nav nav-divider align-items-center mt-1 small">
														<li class="nav-item">
															<?= Time::createFromFormat('Y-m-d H:i:s', $artigo['publicado'])->toLocalizedString('dd MMM yyyy'); ?>
														</li>
													</ul>
												</div>
											</div>
										</div>
									<?php endif; ?>
								<?php endforeach; ?>
							<?php else: ?>
								<div class="w-100 text-center">Nenhum artigo encontrado.</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<div class="col-lg-4 pt-3 pt-lg-0">

				<div class="pb-3">

					<div class="mb-4 d-md-flex justify-content-between align-items-center">
						<h3 class="m-0">Esteira de Produção</h3>
					</div>
					<div class="py-2 px-3 text-center">
						<?php foreach ($widgetEsteiraProducao as $key => $qtde): ?>
							<div class="badge <?= getColors($key); ?> m-1 p-1">
								<?= $key; ?>:
								<?= number_format($qtde, 0, ',', '.'); ?>
							</div>
						<?php endforeach; ?>
					</div>

				</div>


				<?php if ($config['home_newsletter_mostrar'] == '0'): ?>
					<div class="pb-3">
						<div class="mb-4 d-md-flex justify-content-between align-items-center">
							<h3 class="m-0">Newsletter</h3>
						</div>
						<div class="bg-light text-center p-4 mb-3">
							<p>Assine nossa Newsletter
							<div class="input-group" style="width: 100%;">
								<input type="text" class="form-control form-control-lg" placeholder="Seu e-mail">
								<div class="input-group-append">
									<button class="btn btn-primary">Assinar</button>
								</div>
							</div>
							<small>Toda semana, um e-mail com as novidades.</small>
						</div>
					</div>
				<?php endif; ?>

				<?php if ($config['home_talvez_goste_mostrar'] == '1'): ?>
					<div class="col-lg-12 pb-3">
						<div class="mb-4 d-md-flex justify-content-between align-items-center">
							<h3 class="m-0">Talvez você goste</h3>
						</div>
						<?php if (is_array($rand)): ?>
							<?php foreach ($rand as $chave => $r): ?>

								<div class="d-flex position-relative mb-3">
									<span
										class="me-3 mt-n1 fa-fw fw-bold fs-3 text-dark opacity-25"><?= ($chave + 1 > 10) ? ('0' . $chave + 1) : ($chave + 1); ?>
									</span>
									<h6><a href="<?= site_url('site/artigo/' . $r['url_friendly']); ?>"
											class="text-reset btn-link"><?= $r['titulo']; ?></a> -
										<?= Time::createFromFormat('Y-m-d H:i:s', $r['criado'])->toLocalizedString('dd MMM yyyy'); ?>
									</h6>
								</div>
							<?php endforeach; ?>
						<?php else: ?>
							<div class="text-center">Nenhum artigo encontrado.</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>

			</div>
		</div>
	</div>
</div>



<script type="text/javascript">
	$('.main-carousel').flickity({
		// options
		cellAlign: 'left',
		contain: true,
		freeScroll: true,
		wrapAround: true,
		groupCells: true
	});

	$('.avisos-carousel').flickity({
		// options
		cellAlign: 'left',
		contain: true,
		freeScroll: true,
		wrapAround: true,
		groupCells: true,
		autoPlay: true,
		draggable: false
	});


	$('.main-carousel-videos').flickity({
		// options
		cellAlign: 'left',
		contain: true,
		freeScroll: true,
		wrapAround: true,
		groupCells: true
	});
</script>
<?= $this->endSection(); ?>