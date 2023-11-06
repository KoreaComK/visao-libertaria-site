<?php

use CodeIgniter\I18n\Time;

?>

<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>


<div class="container-fluid py-3">
	<div class="container">
		<div class="row">
			<div class="col-lg-8">
				<?php if (isset($banner) && is_array($banner) && $config['home_banner_mostrar'] == '1') : ?>
					<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
						<ol class="carousel-indicators">
							<?php foreach ($banner as $chave => $b) : ?>
								<li data-target="#<?= $chave; ?>" data-slide-to="<?= $chave; ?>" class="<?= ($chave == 0) ? ('active') : (''); ?>"></li>
							<?php endforeach; ?>
						</ol>
						<div class="carousel-inner">
							<?php foreach ($banner as $chave => $artigo) : ?>
								<div class="carousel-item <?= ($chave == 0) ? ('active') : (''); ?>">
									<img class="img-fluid w-100" style="max-height: 480px;" src="<?= $artigo['imagem']; ?>">
									<div class="carousel-caption d-none d-md-block" style="background: RGBA(0,0,0,0.5);">
										<h5><span class="text-reset">
												<?= Time::createFromFormat('Y-m-d H:i:s', $artigo['criado'])->toLocalizedString('dd MMMM yyyy'); ?>
											</span>
										</h5>
										<p><a class="h2 m-0 font-weight-bold text-reset" href="<?= site_url('site/artigo/' . $artigo['url_friendly']); ?>">
												<?= $artigo['titulo']; ?>
											</a></p>
									</div>
								</div>
							<?php endforeach; ?>
						</div>
						<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
							<span class="carousel-control-prev-icon" aria-hidden="true"></span>
							<span class="sr-only">Previous</span>
						</a>
						<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
							<span class="carousel-control-next-icon" aria-hidden="true"></span>
							<span class="sr-only">Next</span>
						</a>
					</div>
				<?php endif; ?>
			</div>

			<?php if($config['home_talvez_goste_mostrar'] == '1'): ?>
				<div class="col-lg-4 pb-3">
					<div class="bg-light py-2 px-4 mb-3">
						<h3 class="m-0">Talvez você goste</h3>
					</div>
					<?php if (is_array($rand)) : ?>
						<?php foreach ($rand as $chave => $r) : ?>
							<div class="d-flex mb-3">
								<img src="<?= $r['imagem']; ?>" style="width: 100px; height: 100px; object-fit: cover;">
								<div class="w-100 d-flex flex-column justify-content-center bg-light px-3" style="height: 100px;">
									<span>
										<?= Time::createFromFormat('Y-m-d H:i:s', $r['criado'])->toLocalizedString('dd MMMM yyyy'); ?>
									</span>
									<a class="h6 m-0" href="<?= site_url('site/artigo/' . $r['url_friendly']); ?>">
										<?= $r['titulo']; ?>
									</a>
								</div>
							</div>
						<?php endforeach; ?>
					<?php else : ?>
						<div class="text-center">Nenhum artigo encontrado.</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

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
		</div>
	</div>
</div>
<div class="container-fluid py-3">
	<div class="container">
		<div class="row">
			<?php if($config['home_ultimos_videos_mostrar'] == '1'): ?>
				<div class="col-lg-8">
					<div class="row mb-3">
						<div class="col-12">
							<div class="d-flex align-items-center justify-content-between bg-light py-2 px-4 mb-3">
								<h3 class="m-0">Últimos Vídeos Visão Libertária</h3>
								<a class="text-secondary font-weight-medium text-decoration-none" href="<?= site_url('site/artigos') ?>">Ver Todos</a>
							</div>
						</div>
						<div class="col-lg-12 row">
							<?php if (is_array($artigos)) : ?>
								<?php foreach ($artigos as $chave => $artigo) : ?>

									<?php if ($chave < 2) : ?>
										<div class="col-lg-6 mb-3">
											<img class="img-fluid w-100" src="<?= $artigo['imagem'] ?>" style="object-fit: cover;">
											<div class="overlay position-relative bg-light">
												<div class="mb-2" style="font-size: 14px;">
													<span>
														<?= Time::createFromFormat('Y-m-d H:i:s', $artigo['criado'])->toLocalizedString('dd MMMM yyyy'); ?>
													</span>
												</div>
												<a class="h4" href="<?= site_url('site/artigo/' . $artigo['url_friendly']); ?>">
													<?= $artigo['titulo'] ?>
												</a>
												<p class="m-0">
													<?= $artigo['gancho'] ?>
												</p>
											</div>
										</div>
									<?php else : ?>
										<div class="col-lg-6 d-flex mb-3">
											<img src="<?= $artigo['imagem'] ?>" style="width: 100px; height: 100px; object-fit: cover;">
											<div class="w-100 d-flex flex-column justify-content-center bg-light px-3" style="height: 100px;">
												<div class="mb-1" style="font-size: 13px;">
													<span>
														<?= Time::createFromFormat('Y-m-d H:i:s', $artigo['criado'])->toLocalizedString('dd MMMM yyyy'); ?>
													</span>
												</div>
												<a class="h6 m-0" href="<?= site_url('site/artigo/' . $artigo['url_friendly']); ?>">
													<?= $artigo['titulo'] ?>
												</a>
											</div>
										</div>
									<?php endif; ?>

								<?php endforeach; ?>
							<?php else : ?>
								<div class="w-100 text-center">Nenhum artigo encontrado.</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
			<div class="col-lg-4 pt-3 pt-lg-0">

				<div class="pb-3">

					<div class="bg-light py-2 px-4 mb-3">
						<h3 class="m-0">Esteira de Produção</h3>
					</div>
					<div class="d-flex py-2 px-3 text-center">
						<?php foreach ($widgetEsteiraProducao as $key => $qtde) : ?>
							<div class="badge <?= getColors($key); ?> m-1 p-1">
								<?= $key; ?>:
								<?= number_format($qtde, 0, ',', '.'); ?>
							</div>
						<?php endforeach; ?>
					</div>

				</div>


				<!-- <div class="pb-3">
					<div class="bg-light py-2 px-4 mb-3">
						<h3 class="m-0">Mais Comentados</h3>
					</div>
					<div class="d-flex mb-3">
						<img src="https://demo.htmlcodex.com/1290/free-bootstrap-magazine-template/img/news-100x100-1.jpg"
							style="width: 100px; height: 100px; object-fit: cover;">
						<div class="w-100 d-flex flex-column justify-content-center bg-light px-3"
							style="height: 100px;">
							<div class="mb-1" style="font-size: 13px;">
								<a href="">Technology</a>
								<span class="px-1">/</span>
								<span>January 01, 2045</span>
							</div>
							<a class="h6 m-0" href="">Lorem ipsum dolor sit amet consec adipis elit</a>
						</div>
					</div>
					<div class="d-flex mb-3">
						<img src="https://demo.htmlcodex.com/1290/free-bootstrap-magazine-template/img/news-100x100-2.jpg"
							style="width: 100px; height: 100px; object-fit: cover;">
						<div class="w-100 d-flex flex-column justify-content-center bg-light px-3"
							style="height: 100px;">
							<div class="mb-1" style="font-size: 13px;">
								<a href="">Technology</a>
								<span class="px-1">/</span>
								<span>January 01, 2045</span>
							</div>
							<a class="h6 m-0" href="">Lorem ipsum dolor sit amet consec adipis elit</a>
						</div>
					</div>
					<div class="d-flex mb-3">
						<img src="https://demo.htmlcodex.com/1290/free-bootstrap-magazine-template/img/news-100x100-3.jpg"
							style="width: 100px; height: 100px; object-fit: cover;">
						<div class="w-100 d-flex flex-column justify-content-center bg-light px-3"
							style="height: 100px;">
							<div class="mb-1" style="font-size: 13px;">
								<a href="">Technology</a>
								<span class="px-1">/</span>
								<span>January 01, 2045</span>
							</div>
							<a class="h6 m-0" href="">Lorem ipsum dolor sit amet consec adipis elit</a>
						</div>
					</div>
					<div class="d-flex mb-3">
						<img src="https://demo.htmlcodex.com/1290/free-bootstrap-magazine-template/img/news-100x100-4.jpg"
							style="width: 100px; height: 100px; object-fit: cover;">
						<div class="w-100 d-flex flex-column justify-content-center bg-light px-3"
							style="height: 100px;">
							<div class="mb-1" style="font-size: 13px;">
								<a href="">Technology</a>
								<span class="px-1">/</span>
								<span>January 01, 2045</span>
							</div>
							<a class="h6 m-0" href="">Lorem ipsum dolor sit amet consec adipis elit</a>
						</div>
					</div>
					<div class="d-flex mb-3">
						<img src="https://demo.htmlcodex.com/1290/free-bootstrap-magazine-template/img/news-100x100-5.jpg"
							style="width: 100px; height: 100px; object-fit: cover;">
						<div class="w-100 d-flex flex-column justify-content-center bg-light px-3"
							style="height: 100px;">
							<div class="mb-1" style="font-size: 13px;">
								<a href="">Technology</a>
								<span class="px-1">/</span>
								<span>January 01, 2045</span>
							</div>
							<a class="h6 m-0" href="">Lorem ipsum dolor sit amet consec adipis elit</a>
						</div>
					</div>
				</div> -->
			
				<?php if($config['home_newsletter_mostrar'] == '1'): ?>
					<div class="pb-3">
						<div class="bg-light py-2 px-4 mb-3">
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

			</div>
		</div>
	</div>
</div>
<?= $this->endSection(); ?>