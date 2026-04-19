<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title><?= $_SESSION['site_config']['texto_nome']; ?></title>
	<link rel="icon" type="image/x-icon"
		href="<?= (file_exists('public/assets/favicon.ico')) ? (site_url('public/assets/favicon.ico')) : ('https://yt3.googleusercontent.com/ytc/AIf8zZSU5BzsyFkBIMmIdu0lPTvOEIu6c2h3V_DRrviXcA=s176-c-k-c0x00ffffff-no-rj'); ?>">
	<!-- Favicon -->
	<link rel="shortcut icon" href="images/favicon.png">
	<!-- CSS bootstrap-->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<!--  Style -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.2.0/magnific-popup.css" />
	<link rel="stylesheet" href="<?= site_url(relativePath: 'public/css/style.css'); ?>">
	<link rel="stylesheet" href="<?= site_url(relativePath: 'public/css/site-public-layout.css'); ?>">

	<!-- 1. jQuery (DEVE SER O PRIMEIRO) -->
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

	<!-- 2. Bootstrap 5 (o bundle já inclui o Popper.js) -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

	<!-- 3. Outros Plugins -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.2.0/jquery.magnific-popup.min.js"></script>

	<!-- 4. Arquivos de funções do tema (se necessário) -->
	<script rel="stylesheet" href="<?= site_url('public/js/functions.js'); ?>"></script>


	<meta property="og:type" content="website" />
	<meta property="og:url" content="<?= current_url(true); ?>" />
	<meta name="theme-color" content="#F3C921">

	<meta name="twitter:card" content="summary_large_image">
	<meta property="twitter:domain" content="<?= site_url(); ?>">
	<meta property="twitter:url" content="<?= current_url(true); ?>">

	<?php if (isset($meta) && is_array($meta)): ?>

		<meta name="twitter:title" content="<?= $meta['title']; ?>">
		<meta name="twitter:image" content="<?= $meta['image']; ?>">
		<meta name="twitter:description" content="<?= $meta['description']; ?>">

		<meta property="og:title" content="<?= $meta['title']; ?>" />
		<meta property="og:image" content="<?= $meta['image']; ?>" />
		<meta property="og:description" content="<?= $meta['description']; ?>" />

	<?php else: ?>
		<meta name="twitter:title" content="<?= $_SESSION['site_config']['texto_nome']; ?>">
		<meta name="twitter:description" content="<?= $_SESSION['site_config']['texto_rodape']; ?>">
		<meta name="twitter:image"
			content="<?= (file_exists('public/assets/favicon.ico')) ? (site_url('public/assets/favicon.ico')) : ('https://yt3.googleusercontent.com/ytc/AIf8zZSU5BzsyFkBIMmIdu0lPTvOEIu6c2h3V_DRrviXcA=s176-c-k-c0x00ffffff-no-rj'); ?>">

		<meta property="og:title" content="<?= $_SESSION['site_config']['texto_nome']; ?>" />
		<meta property="og:image"
			content="<?= (file_exists('public/assets/favicon.ico')) ? (site_url('public/assets/favicon.ico')) : ('https://yt3.googleusercontent.com/ytc/AIf8zZSU5BzsyFkBIMmIdu0lPTvOEIu6c2h3V_DRrviXcA=s176-c-k-c0x00ffffff-no-rj'); ?>" />
		<meta property="og:description" content="<?= $_SESSION['site_config']['texto_rodape']; ?>" />
	<?php endif; ?>
</head>

<body>
	<?= view('components/_loader'); ?>

	<header id="gen-header" class="gen-header-style-1 gen-has-sticky">
		<div class="gen-bottom-header">
			<div class="container">
				<div class="row">
					<div class="col-lg-12">
						<nav class="navbar navbar-expand-lg navbar-light">
							<a class="navbar-brand" href="<?= site_url('site'); ?>">
								<img class="img-fluid logo"
									src="<?= (file_exists('public/assets/favicon.ico')) ? (site_url('public/assets/favicon.ico')) : ('https://yt3.googleusercontent.com/ytc/AIf8zZSU5BzsyFkBIMmIdu0lPTvOEIu6c2h3V_DRrviXcA=s176-c-k-c0x00ffffff-no-rj'); ?>"
									alt="<?= $_SESSION['site_config']['texto_nome']; ?>">
								<div><?= $_SESSION['site_config']['texto_nome']; ?></div>
							</a>
							<div class="collapse navbar-collapse" id="navbarSupportedContent">
								<div id="gen-menu-contain" class="gen-menu-contain">
									<ul id="gen-main-menu" class="navbar-nav ms-auto">
										<li class="menu-item <?= $active_menu === 'home' ? 'active' : ''; ?>">
											<a href="<?= site_url('/'); ?>">Home</a>
										</li>
										<li class="menu-item <?= $active_menu === 'videos' ? 'active' : ''; ?>">
											<a href="<?= site_url('site/videos'); ?>">Vídeos</a>
										</li>
										<li class="menu-item <?= ($active_menu ?? '') === 'noticias' ? 'active' : ''; ?>">
											<a href="<?= site_url('site/noticias'); ?>">Notícias</a>
										</li>
										<?php if (isset($_SESSION['colaboradores']) && $_SESSION['colaboradores']['id'] !== null): ?>
											<li class="menu-item">
												<a href="<?= site_url('colaboradores/artigos/dashboard'); ?>">Colaborar</a>
											</li>
										<?php endif; ?>
										<?php if (isset($_SESSION['colaboradores']) && $_SESSION['colaboradores']['id'] !== null && in_array('7', $_SESSION['colaboradores']['permissoes']) || in_array('8', $_SESSION['colaboradores']['permissoes']) || in_array('9', $_SESSION['colaboradores']['permissoes']) || in_array('10', $_SESSION['colaboradores']['permissoes'])): ?>
											<li class="menu-item">
												<a href="<?= site_url('colaboradores/admin/dashboard'); ?>">Administrar</a>
											</li>
										<?php endif; ?>
									</ul>
								</div>
							</div>
							<div class="gen-header-info-box">
								<?php if (!isset($_SESSION['colaboradores']) || $_SESSION['colaboradores']['id'] === null): ?>
									<div class="gen-account-holder">
										<a href="javascript:void(0)" id="gen-user-btn"><i class="bi bi-person-fill"
												style="font-size: 2.5rem;"></i></a>
										<div class="gen-account-menu">
											<ul class="gen-account-menu">
												<li>
													<a href="<?= site_url('site/login'); ?>"><i
															class="bi bi-box-arrow-in-right"></i>
														login </a>
												</li>
											</ul>
										</div>
									</div>
								<?php endif; ?>
								<?php if (isset($_SESSION['colaboradores']) && $_SESSION['colaboradores']['id'] !== null): ?>
									<div class="gen-account-holder">
										<a href="javascript:void(0)" id="gen-user-btn"><img id="avatar_menu"
												src="<?= $_SESSION['colaboradores']['avatar']; ?>"
												class="rounded-circle"></a>
										<div class="gen-account-menu">
											<ul class="gen-account-menu rounded-bottom-3 rounded-top-3">
												<li>
													<a class="dropdown-item rounded-top-3"
														href="<?= site_url('colaboradores/perfil'); ?>">Meu
														Perfil</a>
												</li>
												<li>
													<a class="dropdown-item"
														href="<?= site_url('colaboradores/perfil/notificacoes'); ?>">
														Notificações</a>
												</li>
												<li>
													<a class="dropdown-item rounded-bottom-3"
														href="<?= site_url('site/logout'); ?>">Sair</a>
												</li>
											</ul>
										</div>
									</div>
								<?php endif; ?>
								<?php if (!isset($_SESSION['colaboradores']) || $_SESSION['colaboradores']['id'] === null): ?>
									<div class="gen-btn-container">
										<a href="<?= site_url('site/cadastrar'); ?>" class="gen-button">
											<div class="gen-button-block">
												<span class="gen-button-line-left"></span>
												<span class="gen-button-text">Cadastre-se</span>
											</div>
										</a>
									</div>
								<?php endif; ?>
							</div>
							<button class="navbar-toggler" type="button" data-bs-toggle="collapse"
								data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
								aria-expanded="false" aria-label="Toggle navigation">
								<i class="bi bi-list"></i>
							</button>
						</nav>
					</div>
				</div>
			</div>
		</div>
	</header>

	<?= $this->renderSection('content'); ?>

	<footer class="pb-0">
		<div class="container">
			<hr>
			<!-- Widgets START -->
			<div class="row pt-5">
				<!-- Footer Widget -->
				<div class="col-md-6 col-lg-4 mb-4">
					<img class="img-thumbnail rounded-circle mr-3" style="max-width: 3rem;"
						src="<?= (file_exists('public/assets/rodape.png')) ? (site_url('public/assets/rodape.png')) : ('https://yt3.googleusercontent.com/ytc/AIf8zZSU5BzsyFkBIMmIdu0lPTvOEIu6c2h3V_DRrviXcA=s176-c-k-c0x00ffffff-no-rj'); ?>" />
					<span class="lead"><?= $_SESSION['site_config']['texto_nome']; ?></span>
					<p class="mt-2 lh-sm fw-light"><?= $_SESSION['site_config']['texto_rodape']; ?></p>
				</div>

				<!-- Footer Widget -->
				<div class="col-md-6 col-lg-3 mb-4">
					<h5 class="mb-4">Navegação</h5>
					<div class="row">
						<div class="col-6">
							<ul class="nav flex-column">
								<li class="nav-item"><a class="mb-2" href="<?= site_url('site/artigos'); ?>">Artigos</a>
								</li>
							</ul>
						</div>
						<div class="col-6">
							<ul class="nav flex-column">
								<li class="nav-item"><a class="mb-2" href="<?= site_url('site/contato'); ?>">Contato</a>
								</li>
							</ul>
						</div>
						<div class="col-6">
							<ul class="nav flex-column">
								<li class="nav-item"><a class="mb-2" href="<?= site_url('links'); ?>">Todos os
										projetos</a>
								</li>
							</ul>
						</div>
						<?php if (isset($_SESSION) && isset($_SESSION['site_config']['paginas']['rodape_site'])): ?>
							<?php foreach ($_SESSION['site_config']['paginas']['rodape_site'] as $pagina): ?>
								<div class="col-6">
									<ul class="nav flex-column">
										<li class="nav-item"><a class="mb-2"
												href="<?= site_url('site/pagina/' . $pagina['link']); ?>"><?= $pagina['titulo']; ?></a>
										</li>
									</ul>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>

				<!-- 
				<div class="col-sm-6 col-lg-3 mb-4">
					<h5 class="mb-4">Browse by Tag</h5>
					<ul class="list-inline">
						<li class="list-inline-item"><a href="#" class="btn btn-sm btn-primary-soft">Travel</a></li>
						<li class="list-inline-item"><a href="#" class="btn btn-sm btn-warning-soft">Business</a></li>
						<li class="list-inline-item"><a href="#" class="btn btn-sm btn-success-soft">Tech</a></li>
						<li class="list-inline-item"><a href="#" class="btn btn-sm btn-danger-soft">Gadgets</a></li>
						<li class="list-inline-item"><a href="#" class="btn btn-sm btn-info-soft">Lifestyle</a></li>
						<li class="list-inline-item"><a href="#" class="btn btn-sm btn-primary-soft">Vaccine</a></li>
						<li class="list-inline-item"><a href="#" class="btn btn-sm btn-warning-soft">Marketing</a></li>
						<li class="list-inline-item"><a href="#" class="btn btn-sm btn-success-soft">Sports</a></li>
						<li class="list-inline-item"><a href="#" class="btn btn-sm btn-danger-soft">Covid-19</a></li>
						<li class="list-inline-item"><a href="#" class="btn btn-sm btn-info-soft">Politics</a></li>
					</ul>
				</div> -->

				<!-- Footer Widget -->
				<div class="col-sm-12 col-lg-5 mb-4">
					<h5 class="mb-4">Nossas redes sociais</h5>
					<div class="row">
						<div class="col-6">
							<ul class="nav flex-column">
								<li class="nav-item text-uppercase">Ancapsu</li>
								<li class="nav-item"><a class="" href="https://www.youtube.com/@ancap_su"><i
											class="bi bi-youtube me-2"
											style="color:#ff0000;"></i>YouTube</a></li>
								<li class="nav-item"><a class="" href="https://www.instagram.com/ancap.su"><i
											class="bi bi-instagram me-2"
											style="color:#E4405F;"></i>Instagram</a>
								</li>
								<li class="nav-item"><a class="" href="https://twitter.com/ancapsu"><i
											style="color: #40bff5;"
											class="bi bi-twitter-x me-2"></i>Twitter</a></li>
								<li class="nav-item text-uppercase mt-2">Safe source</li>
								<li class="nav-item"><a class="" href="https://www.youtube.com/@safesrc"><i
											class="bi bi-youtube me-2"
											style="color:#ff0000;"></i>YouTube</a></li>
								<li class="nav-item"><a class="" href="https://twitter.com/safesrc1"><i
											style="color: #40bff5;"
											class="bi bi-twitter-x me-2"></i>Twitter</a></li>
							</ul>
						</div>
						<div class="col-6">
							<ul class="nav flex-column">
								<li class="nav-item text-uppercase">VISÃO LIBERTÁRIA</li>
								<li class="nav-item"><a class="" href="https://www.youtube.com/@Visao_Libertaria"><i
											class="bi bi-youtube me-2"
											style="color:#ff0000;"></i>YouTube</a></li>
								</li>
								<li class="nav-item"><a class="" href="https://twitter.com/visaolibertaria"><i
											style="color: #40bff5;"
											class="bi bi-twitter-x me-2"></i>Twitter</a></li>
								<li class="nav-item">&nbsp;</li>
								<li class="nav-item text-uppercase mt-2">mundo em revolução</li>
								<li class="nav-item"><a class="" href="https://www.youtube.com/@wrevolving"><i
											class="bi bi-youtube me-2"
											style="color:#ff0000;"></i>YouTube</a></li>
								</li>
								<li class="nav-item"><a class="" href="https://twitter.com/MundoEmRevo"><i
											style="color: #40bff5;"
											class="bi bi-twitter-x me-2"></i>Twitter</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<!-- Widgets END -->
		</div>
		<div class="container container-fluid py-2 px-sm-3 px-md-5">
			<p class="m-0 text-center">
				Desenvolvido por
				<a class="text-reset btn-link font-light" href="https://github.com/KoreaComK/">KoreacomK</a> e a
				comunidade.
			</p>
		</div>
	</footer>

	<script>
		$(document).ready(function () {
			// --- User Account Menu Toggle ---
			$('#gen-user-btn').on('click', function (e) {
				e.stopPropagation();
				var menu = $('.gen-account-menu');

				// Usamos a classe que o tema espera: 'gen-form-show'
				if (menu.hasClass('gen-form-show')) {
					menu.removeClass('gen-form-show');
				} else {
					menu.addClass('gen-form-show');
				}
			});

			// Fecha o menu se clicar fora dele
			$(document).on('click', function (e) {
				var menu = $('.gen-account-menu');
				if (!$(e.target).closest('.gen-account-holder').length) {
					if (menu.hasClass('gen-form-show')) {
						menu.removeClass('gen-form-show');
					}
				}
			});
			// --- Fim do script do menu de usuário ---

			// Fixar navbar ao rolar 300px
			$(window).on('scroll', function () {
				if ($(window).scrollTop() > 300) {
					$('#gen-header').addClass('fixed-navbar');
				} else {
					$('#gen-header').removeClass('fixed-navbar');
				}
			});

			// Back to Top Button
			var backToTopBtn = $('#back-to-top-btn');
			$(window).on('scroll', function () {
				if ($(window).scrollTop() > 300) {
					backToTopBtn.addClass('show');
				} else {
					backToTopBtn.removeClass('show');
				}
			});
			backToTopBtn.on('click', function (e) {
				e.preventDefault();
				$('html, body').animate({ scrollTop: 0 }, '300');
			});
		});
	</script>

	<!-- Back to Top Button -->
	<a href="#" id="back-to-top-btn">
		<i class="bi bi-arrow-up"></i>
	</a>
</body>

</html>