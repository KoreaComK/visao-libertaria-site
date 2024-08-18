<?php
use CodeIgniter\I18n\Time;

?>

<?= $this->extend('layouts/main', ['meta' => $meta]); ?>

<?= $this->section('content'); ?>

<div class="container-fluid py-3">
	<div class="container d-flex justify-content-center">
		<div class="col-lg-8">
			<h1 class="display-2"><?= $artigo['titulo']; ?></h1>
			<p class="lead"><?= $artigo['gancho']; ?></p>
			<ul class="nav nav-divider align-items-center">
				<li class="nav-item pointer">
					<div class="nav-link ps-0 pe-0 text-reset">
						Escrito por <a
							href="<?= site_url('site/escritor/'); ?><?= urlencode($artigo['colaboradores']['escrito']['apelido']); ?>"
							class="text-reset btn-link"><?= $artigo['colaboradores']['escrito']['apelido']; ?></a>
					</div>
				</li>
				<li class="nav-item pointer">
					<?= Time::createFromFormat('Y-m-d H:i:s', $artigo['criado'])->toLocalizedString('dd MMMM yyyy'); ?>
				</li>
			</ul>
			<div class="position-relative mb-3">
				<div class="bg-image hover-zoom rounded-6">
					<img class="w-100 img-fluid" height="auto" style="max-height:22rem; object-fit: cover;"
						src="<?= $artigo['imagem'] ?>"></img>
					<a class="text-white" href="<?= $artigo['link_video_youtube'] ?>" target="_blank">
						<div class="mask" style="background-color: rgba(0, 0, 0, 0.6);">
							<div class="d-flex align-items-center justify-content-center  h-100 m-4">
								<h1 class="m-5 display-6">Ver no YouTube</h1>
							</div>
						</div>
					</a>
				</div>
				<div class="pt-3 pb-3">
					<!-- <div class="mb-3">
							<?php //foreach($artigo['categorias'] as $categoria): ?>
								<span class="badge vl-bg-c m-1 p-1">
									<a href="<? //base_url() . 'site/artigos/'.$categoria['id']; ?>"><? //$categoria['nome']; ?></a>
								</span>
							<?php //endforeach; ?>
						</div> -->
					<div>

						<p><?= str_replace("\n", '<br/>', $artigo['texto']); ?></p>
						<h4 class="mb-3">Referências:</h4>
						<p><?= str_replace("\n", '<br/>', $artigo['referencias']); ?></p>
					</div>
				</div>

				<div class="d-flex p-2 p-md-4 my-3 bg-primary bg-opacity-10 rounded-6">
					<div>
						<div class="d-sm-flex align-items-center justify-content-between">
							<div class="row">
								<?php if ($artigo['colaboradores']['sugerido'] !== NULL): ?>
									<div class="col-lg-6 d-flex mb-2">
										<?php if ($artigo['colaboradores']['sugerido']['avatar'] !== NULL): ?>
											<a
												href="<?= site_url('site/colaborador/'); ?><?= urlencode($artigo['colaboradores']['sugerido']['apelido']); ?>">
												<div class="avatar rounded-circle  me-2 me-md-4">
													<img class="avatar-img rounded-circle" style="width: 3rem;"
														src="<?= $artigo['colaboradores']['sugerido']['avatar'] ?>"
														alt="avatar">
												</div>
											</a>
										<?php endif; ?>
										<div>
											<h4 class="m-0"><a
													href="<?= site_url('site/colaborador/'); ?><?= urlencode($artigo['colaboradores']['sugerido']['apelido']); ?>"><?= $artigo['colaboradores']['sugerido']['apelido'] ?></a>
											</h4>
											<small>Colaborador</small>
										</div>
									</div>
								<?php endif; ?>
								<?php if ($artigo['colaboradores']['escrito'] !== NULL): ?>
									<div class="col-lg-6 d-flex mb-2">
										<?php if ($artigo['colaboradores']['escrito']['avatar'] !== NULL): ?>
											<a
												href="<?= site_url('site/escritor/'); ?><?= urlencode($artigo['colaboradores']['escrito']['apelido']); ?>">
												<div class="avatar rounded-circle me-2 me-md-4">
													<img class="avatar-img rounded-circle" style="width: 3rem;"
														src="<?= $artigo['colaboradores']['escrito']['avatar'] ?>" alt="avatar">
												</div>
											</a>
										<?php endif; ?>
										<div>
											<h4 class="m-0"><a
													href="<?= site_url('site/escritor/'); ?><?= urlencode($artigo['colaboradores']['escrito']['apelido']); ?>"><?= $artigo['colaboradores']['escrito']['apelido'] ?></a>
											</h4>
											<small>Escritor</small>
											<div><a href="<?= site_url('site/escritor/'); ?><?= urlencode($artigo['colaboradores']['escrito']['apelido']); ?>"
													class="btn">Veja mais artigos deste autor</a></div>
										</div>
									</div>
								<?php endif; ?>
								<?php if ($artigo['colaboradores']['revisado'] !== NULL): ?>
									<div class="col-lg-6 d-flex mb-2">
										<?php if ($artigo['colaboradores']['revisado']['avatar'] !== NULL): ?>
											<a
												href="<?= site_url('site/escritor/'); ?><?= urlencode($artigo['colaboradores']['revisado']['apelido']); ?>">
												<div class="avatar rounded-circle  me-2 me-md-4">
													<img class="avatar-img rounded-circle" style="width: 3rem;"
														src="<?= $artigo['colaboradores']['revisado']['avatar'] ?>"
														alt="avatar">
												</div>
											</a>
										<?php endif; ?>
										<div>
											<h4 class="m-0"><a
													href="<?= site_url('site/escritor/'); ?><?= urlencode($artigo['colaboradores']['revisado']['apelido']); ?>"><?= $artigo['colaboradores']['revisado']['apelido'] ?></a>
											</h4>
											<small>Revisor</small>
										</div>
									</div>
								<?php endif; ?>
								<?php if ($artigo['colaboradores']['narrado'] !== NULL): ?>
									<div class="col-lg-6 d-flex mb-2">
										<?php if ($artigo['colaboradores']['narrado']['avatar'] !== NULL): ?>
											<a href="<?= site_url('site/escritor/'); ?><?= urlencode($artigo['colaboradores']['narrado']['apelido']); ?>">
												<div class="avatar rounded-circle  me-2 me-md-4">
													<img class="avatar-img rounded-circle" style="width: 3rem;"
														src="<?= $artigo['colaboradores']['narrado']['avatar'] ?>" alt="avatar">
												</div>
											</a>
										<?php endif; ?>
										<div>
											<h4 class="m-0"><a
													href="<?= site_url('site/escritor/'); ?><?= urlencode($artigo['colaboradores']['narrado']['apelido']); ?>"><?= $artigo['colaboradores']['narrado']['apelido'] ?></a>
											</h4>
											<small>Narrador</small>
										</div>
									</div>
								<?php endif; ?>
								<?php if ($artigo['colaboradores']['produzido'] !== NULL): ?>
									<div class="col-lg-6 d-flex mb-2">
										<?php if ($artigo['colaboradores']['produzido']['avatar'] !== NULL): ?>
											<a href="<?= site_url('site/escritor/'); ?><?= urlencode($artigo['colaboradores']['produzido']['apelido']); ?>">
												<div class="avatar avatar-xxl me-2 me-md-4">
													<img class="avatar-img rounded-circle" style="width: 3rem;"
														src="<?= $artigo['colaboradores']['produzido']['avatar'] ?>"
														alt="avatar">
												</div>
											</a>
										<?php endif; ?>
										<div>
											<h4 class="m-0"><a
													href="<?= site_url('site/escritor/'); ?><?= urlencode($artigo['colaboradores']['produzido']['apelido']); ?>"><?= $artigo['colaboradores']['produzido']['apelido'] ?></a>
											</h4>
											<small>Produtor</small>
										</div>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="row g-0 mt-5 mx-0 border-top border-bottom">

				<div class="col-sm-6 py-3 py-md-4">
					<?php if (!empty($artigo['proximo'])): ?>
						<div class="d-flex align-items-center position-relative">
							<div class="py-1">
								<i class="fas fa-angle-left fa-3x"></i>
							</div>
							<div class="ms-3">
								<h5 class="m-0"><a
										href="<?= site_url('site/artigo/' . $artigo['proximo'][0]['url_friendly']); ?>"
										class="stretched-link btn-link text-reset"><?= $artigo['proximo'][0]['titulo']; ?></a>
								</h5>
							</div>
						</div>
					<?php endif; ?>
				</div>
				<div class="col-sm-6 py-3 py-md-4 text-sm-end">
					<?php if (!empty($artigo['anterior'])): ?>
						<div class="d-flex align-items-center position-relative">
							<!-- Title -->
							<div class="me-3">
								<h5 class="m-0"><a
										href="<?= site_url('site/artigo/' . $artigo['anterior'][0]['url_friendly']); ?>"
										class="stretched-link btn-link text-reset"><?= $artigo['anterior'][0]['titulo']; ?></a>
								</h5>
							</div>
							<!-- Icon -->
							<div class="py-1">
								<i class="fas fa-angle-right fa-3x"></i>
							</div>
						</div>
					<?php endif; ?>
				</div>

			</div>

		</div>
	</div>
</div>
<?= $this->endSection(); ?>