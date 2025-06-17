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
			<?php if ($avisos !== NULL && !empty($avisos)): ?>
				<section class="col-12">
					<div class="vl-bg-c-opaco p-2 rounded-3 position-relative mt-1 mb-3">
						<!-- Badge de Avisos -->
						<div class="badge vl-bg-c p-2 px-2 position-absolute top-50 start-0 translate-middle"
							style="z-index: 1; margin-left: 2.8rem;">
							<i class="bi bi-bell-fill me-1"></i>Avisos
						</div>
						<!-- Carrossel de Avisos -->
						<div class="avisos-carousel mb-0 mt-0" style="margin-left: 3.4rem;">
							<?php foreach ($avisos as $aviso): ?>
								<div class="carousel-cell">
									<div class="card shadow-0 border-0 bg-transparent">
										<div class="card-body py-1">
											<?php if (!empty($aviso['link'])): ?>
												<a href="<?= $aviso['link']; ?>" class="text-decoration-none">
													<?= $aviso['aviso']; ?>
												</a>
											<?php else: ?>
												<span><?= $aviso['aviso']; ?></span>
											<?php endif; ?>
										</div>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</section>
			<?php endif; ?>
			<section class="col-lg-12">
				<div class="row">
					<!-- Card Principal -->
					<div class="col-lg-6">
						<div class="card bg-image hover-zoom"
							style="background-position: center center; background-size: cover; height:30rem;">
							<img src="<?= 'https://img.youtube.com/vi/'.preg_replace('/^.*(?:youtu\.be\/|watch\?v=)([^&\n]+).*$/', '$1', $banner[0]['link_video_youtube']).'/maxresdefault.jpg'; ?>" style="height:30rem; object-fit: cover;"
								alt="<?= $banner[0]['titulo']; ?>" />

							<div class="mask align-items-center p-3 p-sm-4"
								style="position: absolute; background-color: rgba(0, 0, 0, 0.6);">
								<div class="me-3" style="position: absolute; top: 50%; transform: translateY(-50%);">
									<h2 class="h1">
										<a href="javascript:void(0);"
											data-video-id="<?= preg_replace('/^.*(?:youtu\.be\/|watch\?v=)([^&\n]+).*$/', '$1', $banner[0]['link_video_youtube']); ?>"
											class="btn-link stretched-link text-white video-thumbnail">
											<?= $banner[0]['titulo']; ?>
										</a>
									</h2>
									<p class="text-white"><?= $banner[0]['gancho']; ?></p>

									<ul class="nav nav-divider align-items-center">
										<li class="nav-item pointer">
											<div class="d-flex align-items-center text-white position-relative">
												<?php if (!empty($banner[0]['avatar'])): ?>
													<div class="avatar avatar-sm">
														<img class="avatar-img rounded-circle"
															src="<?= $banner[0]['avatar']; ?>" style="width:45px;"
															alt="<?= $banner[0]['apelido']; ?>">
													</div>
												<?php endif; ?>
												<span class="<?= !empty($banner[0]['avatar']) ? 'ms-3' : ''; ?>">
													por <a
														href="<?= site_url('site/escritor/' . urlencode($banner[0]['apelido'])); ?>"
														class="stretched-link text-white btn-link">
														<?= $banner[0]['apelido']; ?>
													</a>
												</span>
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

					<!-- Cards Secundários -->
					<div class="col-lg-6 side-image">
						<div class="row">
							<!-- Card Secundário Principal -->
							<div class="col-12">
								<div class="card bg-image hover-zoom"
									style="background-position: center center; background-size: cover; height:15rem;">
									<img src="<?= 'https://img.youtube.com/vi/'.preg_replace('/^.*(?:youtu\.be\/|watch\?v=)([^&\n]+).*$/', '$1', $banner[1]['link_video_youtube']).'/maxresdefault.jpg'; ?>" style="height:15rem; object-fit: cover;"
										alt="<?= $banner[1]['titulo']; ?>" />

									<div class="mask align-items-center p-3 p-sm-4"
										style="position: absolute; background-color: rgba(0, 0, 0, 0.6);">
										<div class="me-3"
											style="position: absolute; top: 50%; transform: translateY(-50%);">
											<h4 class="text-white">
												<a href="javascript:void(0);"
													data-video-id="<?= preg_replace('/^.*(?:youtu\.be\/|watch\?v=)([^&\n]+).*$/', '$1', $banner[1]['link_video_youtube']); ?>"
													class="btn-link stretched-link text-white video-thumbnail">
													<?= $banner[1]['titulo']; ?>
												</a>
											</h4>

											<ul class="nav nav-divider align-items-center">
												<li class="nav-item pointer">
													<div class="d-flex align-items-center text-white position-relative">
														<?php if (!empty($banner[1]['avatar'])): ?>
															<div class="avatar avatar-sm">
																<img class="avatar-img rounded-circle"
																	src="<?= $banner[1]['avatar']; ?>" style="width:45px;"
																	alt="<?= $banner[1]['apelido']; ?>">
															</div>
														<?php endif; ?>
														<span
															class="<?= !empty($banner[1]['avatar']) ? 'ms-3' : ''; ?>">
															por <a
																href="<?= site_url('site/escritor/' . urlencode($banner[1]['apelido'])); ?>"
																class="stretched-link text-white btn-link">
																<?= $banner[1]['apelido']; ?>
															</a>
														</span>
													</div>
												</li>
												<li class="nav-item pointer text-white">
													<?= Time::createFromFormat('Y-m-d H:i:s', $banner[1]['publicado'])->toLocalizedString('dd MMM yyyy'); ?>
												</li>
											</ul>
										</div>
									</div>
								</div>
							</div>

							<!-- Cards Secundários Menores -->
							<?php for ($i = 2; $i <= 3; $i++): ?>
								<div class="col-md-6 g-4 pt-2">
									<div class="card bg-image hover-zoom"
										style="background-position: center center; background-size: cover; height:13rem;">
										<img src="<?= 'https://img.youtube.com/vi/'.preg_replace('/^.*(?:youtu\.be\/|watch\?v=)([^&\n]+).*$/', '$1', $banner[$i]['link_video_youtube']).'/maxresdefault.jpg'; ?>" style="height: inherit; object-fit: cover;"
											alt="<?= $banner[$i]['titulo']; ?>" />

										<div class="mask align-items-center p-3 p-sm-4"
											style="position: absolute; background-color: rgba(0, 0, 0, 0.6);">
											<div class="me-3"
												style="position: absolute; top: 50%; transform: translateY(-50%);">
												<h5 class="text-white">
													<a href="javascript:void(0);"
														data-video-id="<?= preg_replace('/^.*(?:youtu\.be\/|watch\?v=)([^&\n]+).*$/', '$1', $banner[$i]['link_video_youtube']); ?>"
														class="btn-link stretched-link text-white video-thumbnail">
														<?= $banner[2]['titulo']; ?>
													</a>
												</h5>

												<ul class="nav nav-divider align-items-center">
													<li class="nav-item pointer">
														<div class="d-flex align-items-center text-white position-relative">
															<?php if (!empty($banner[$i]['avatar'])): ?>
																<div class="avatar avatar-sm">
																	<img class="avatar-img rounded-circle"
																		src="<?= $banner[$i]['avatar']; ?>" style="width:45px;"
																		alt="<?= $banner[$i]['apelido']; ?>">
																</div>
															<?php endif; ?>
															<span
																class="<?= !empty($banner[$i]['avatar']) ? 'ms-3' : ''; ?>">
																por <a
																	href="<?= site_url('site/escritor/' . urlencode($banner[$i]['apelido'])); ?>"
																	class="stretched-link text-white btn-link">
																	<?= $banner[$i]['apelido']; ?>
																</a>
															</span>
														</div>
													</li>
													<li class="nav-item pointer text-white">
														<?= Time::createFromFormat('Y-m-d H:i:s', $banner[$i]['publicado'])->toLocalizedString('dd MMM yyyy'); ?>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							<?php endfor; ?>
						</div>
					</div>
				</div>
			</section>

			<section class="col-lg-12 banner mb-4">
				<div class="main-carousel mb-4 mt-4">
					<?php foreach ($banner as $chave => $b): ?>
						<?php if ($chave > 3): ?>
							<div class="carousel-cell mb-3" style="height: 15rem;">
								<div class="card bg-image hover-zoom round-5" style="height: inherit;">
									<img class="card-img" src="<?= 'https://img.youtube.com/vi/'.preg_replace('/^.*(?:youtu\.be\/|watch\?v=)([^&\n]+).*$/', '$1', $b['link_video_youtube']).'/maxresdefault.jpg'; ?>" alt="<?= $b['titulo']; ?>"
										style="height: inherit; object-fit: cover;">
									<div class="card-img-overlay d-flex flex-column p-3 p-sm-4"
										style="background-color: rgba(0, 0, 0, 0.6);">
										<div class="w-100 mt-auto">
											<h4 class="text-white"><a href="javascript:void(0);"
													data-video-id="<?= preg_replace('/^.*(?:youtu\.be\/|watch\?v=)([^&\n]+).*$/', '$1', $b['link_video_youtube']); ?>"
													class="btn-link stretched-link text-white video-thumbnail">
													<?= $b['titulo']; ?>
												</a>
											</h4>
											<ul
												class="nav nav-divider text-white align-items-center d-none d-sm-inline-block small">
												<li class="nav-item">
													<div class="text-white">por <a
															href="<?= site_url('site/escritor/'); ?><?= urlencode($b['apelido']); ?>"
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

			<?php foreach ($videos_projetos as $projeto => $videos): ?>
				<section class="col-lg-12">
					<div class="row g-2">
						<?php foreach ($videos as $chave => $video): ?>
							<div class="card col-lg-<?= ($chave < 3) ? ('4') : ('3'); ?> col-md-6 col-sm-12 shadow-0">
								<!-- Card img -->
								<div class="position-relative bg-image hover-zoom rounded" data-mdb-ripple-color="light">
									<img class="card-img" alt="Card image" style="object-fit: cover; cursor: pointer;"
										src="https://img.youtube.com/vi/<?= $video['video_id']; ?>/maxresdefault.jpg">
									<div class="mask" style="background-color: hsla(0, 0%, 98%, 0.2)"></div>
									<div class="card-img-overlay d-flex align-items-start flex-column p-3">
										<div class="w-100 mt-auto">
											<a href="#" class="badge text-bg-warning mb-2"><i
													class="fas fa-circle me-2 small fw-bold"></i><?= $projeto; ?></a>
										</div>
									</div>
								</div>
								<div class="card-body px-1 pt-1 pb-0">
									<p class="card-title <?= ($chave < 3) ? ('fs-5') : ('fs-6'); ?> "><a
											href="javascript:void(0);" data-video-id="<?= $video['video_id']; ?>"
											class="btn-link text-reset stretched-link fw-bold text-justify video-thumbnail"><?= $video['titulo']; ?></a>
									</p>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</section>

			<?php endforeach; ?>

			<section class="col-lg-12 banner-bigger">
				<div class="m-4">
					<h2 class="m-0"><i class="bi bi-hourglass-top me-2"></i>Últimos vídeos do Visão Libertária</h2>
				</div>
				<div class="main-carousel-videos mb-4 mt-4">
					<?php for ($max = 0; $max < 6; $max++): ?>
						<div class="carousel-cell">
							<div class="card mb-3 shadow-0">
								<!-- Card img -->
								<div class="position-relative bg-image hover-zoom shadow-1-strong rounded"
									data-mdb-ripple-init data-mdb-ripple-color="light">
									<img class="card-img" alt="Card image" style="height:20rem; object-fit: cover;"
									src="<?= 'https://img.youtube.com/vi/'.preg_replace('/^.*(?:youtu\.be\/|watch\?v=)([^&\n]+).*$/', '$1', $artigos[$max]['link_video_youtube']).'/maxresdefault.jpg'; ?>" alt="<?= $b['titulo']; ?>" >
									<a href="javascript:void(0);" class="video-thumbnail" data-video-id="<?= preg_replace('/^.*(?:youtu\.be\/|watch\?v=)([^&\n]+).*$/', '$1', $artigos[$max]['link_video_youtube']); ?>">
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
									<h4 class="card-title"><a href="javascript:void(0);" data-video-id="<?= preg_replace('/^.*(?:youtu\.be\/|watch\?v=)([^&\n]+).*$/', '$1', $artigos[$max]['link_video_youtube']); ?>"
											class="btn-link text-reset stretched-link fw-bold video-thumbnail"><?= $artigos[$max]['titulo']; ?></a>
									</h4>
									<p class="card-text"><?= $artigos[$max]['gancho']; ?></p>
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
													<a href="<?= site_url('site/escritor/'); ?><?= urlencode($artigos[$max]['apelido']); ?>"
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
									<?php if ($chave > 5): ?>
										<?php $artigo['class'] = 'col-md-6 col-lg-6'; ?>
										<?= view_cell('\App\Libraries\Cards::cardsHorizontais', $artigo); ?>
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


				<?php if ($config['home_newsletter_mostrar'] == '1'): ?>
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
										class="me-3 mt-n1 fa-fw fw-bold fs-3 text-dark opacity-25"><?= ($chave + 1 < 10) ? ('0' . $chave + 1) : ($chave + 1); ?>
									</span>
									<h6><a href="javascript:void(0);"
											class="text-reset btn-link video-thumbnail" data-video-id="<?= preg_replace('/^.*(?:youtu\.be\/|watch\?v=)([^&\n]+).*$/', '$1', $r['link_video_youtube']); ?>"><?= $r['titulo']; ?></a> -
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

<!-- Modal do YouTube -->
<div class="modal fade" id="youtubeModal" tabindex="-1" aria-labelledby="youtubeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body p-0">
				<div class="ratio ratio-16x9">
					<iframe id="youtubeIframe" src="" title="YouTube video" allowfullscreen></iframe>
				</div>
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

<script>
	$(document).ready(function () {
		$('.video-thumbnail').on('click', function () {
			const videoId = $(this).data('video-id');
			$('#youtubeIframe').attr('src', `https://youtube.com/embed/${videoId}?&autoplay=1`);
			$('#youtubeModal').modal('show');
		});

		// Limpar o iframe quando a modal for fechada
		$('#youtubeModal').on('hidden.bs.modal', function () {
			$('#youtubeIframe').attr('src', '');
		});
	});
</script>
<?= $this->endSection(); ?>