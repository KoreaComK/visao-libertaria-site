<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Visão Libertária</title>
	<link rel="icon" type="image/x-icon"
		href="https://yt3.googleusercontent.com/ytc/AIf8zZSU5BzsyFkBIMmIdu0lPTvOEIu6c2h3V_DRrviXcA=s176-c-k-c0x00ffffff-no-rj">
	<!-- Favicon -->
	<link rel="shortcut icon" href="images/favicon.png">
	<!-- CSS bootstrap-->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<!--  Style -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.2.0/magnific-popup.css" />
	<link rel="stylesheet" href="<?= site_url(relativePath: 'public/css/style.css'); ?>">
	<style>
		@keyframes slideInUp {
			from {
				opacity: 0;
				transform: translateY(50px);
			}

			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		.banner-section .text-white>*,
		.banner-section .d-flex.flex-column>* {
			opacity: 0;
		}

		.owl-item.active .text-white>*,
		.owl-item.active .d-flex.flex-column>* {
			animation: slideInUp 0.9s ease-out forwards;
		}

		.owl-item.active .text-white h5 {
			animation-delay: 0.4s;
		}

		.owl-item.active .text-white h1 {
			animation-delay: 0.6s;
		}

		.owl-item.active .text-white .d-flex {
			animation-delay: 0.8s;
		}

		.owl-item.active .text-white p:nth-of-type(1) {
			animation-delay: 1.0s;
		}

		.owl-item.active .text-white p:nth-of-type(2) {
			animation-delay: 1.1s;
		}

		.owl-item.active .text-white p:nth-of-type(3) {
			animation-delay: 1.2s;
		}

		.owl-item.active .text-white .btn {
			animation-delay: 1.4s;
		}

		.owl-item.active .d-flex.flex-column .gen-video-popup {
			animation-delay: 1.2s;
		}

		.owl-item.active .d-flex.flex-column h4 {
			animation-delay: 1.3s;
		}

		.owl-item .container {
			opacity: 0;
			padding-top: 0rem;
		}

		.owl-item.active .container {
			animation: slideInUp 1s ease-out 0.5s;
			animation-fill-mode: both;
		}

		#gen-header {
			position: absolute;
			width: 100%;
			z-index: 999;
		}

		#gen-header .gen-bottom-header {
			background: transparent;
		}

		.gen-account-menu {
			display: block !important;
			opacity: 0;
			visibility: hidden;
			transition: opacity 0.3s ease, visibility 0s 0.3s;
			pointer-events: none;
		}

		.gen-account-menu.gen-form-show {
			opacity: 1;
			visibility: visible;
			transition-delay: 0s;
			pointer-events: auto;
		}

		/* Efeito de clique no ícone de usuário */
		#gen-user-btn {
			display: inline-flex;
			/* Garante que a transformação funcione e permite flexbox */
			align-items: center;
			/* Centraliza verticalmente */
			justify-content: center;
			/* Centraliza horizontalmente */
			transition: transform 0.2s ease;
		}

		#gen-user-btn:active {
			transform: scale(0.9);
			transition-duration: 0.05s;
		}

		/* Estilo para o logo e texto na navbar, igual ao loader */
		.navbar-brand {
			display: flex;
			align-items: center;
			user-select: none;
			/* Impede a seleção */
		}

		.navbar-brand .logo {
			border-radius: 50%;
		}

		.navbar-brand div {
			font-size: 1.5rem;
			/* Tamanho similar ao loader, mas ajustado para a navbar */
			font-weight: bold;
			color: #fff;
			margin-left: 15px;
		}

		/* Overlay para escurecer a imagem de fundo do banner */
		.banner-section .item {
			position: relative;
		}

		.banner-section .item::before {
			content: '';
			position: absolute;
			top: 0;
			right: 0;
			bottom: 0;
			left: 0;
			background-color: rgba(0, 0, 0, 0.9);
			/* Overlay preto com 50% de opacidade */
			z-index: 1;
		}

		.banner-section .item>.container {
			position: relative;
			z-index: 2;
			/* Garante que o conteúdo fique na frente do overlay */
		}

		/* Classe de utilidade para usar a cor primária do tema */
		.text-primary-color {
			color: var(--primary-color) !important;
		}

		.btn-primary-color {
			background-color: var(--primary-color) !important;
			border-color: var(--primary-color) !important;
		}

		.btn-primary-color:hover {
			background-color: #fff !important;
			color: var(--primary-color) !important;
		}

		/* Estilos para a nova seção de carrossel de vídeos */
		.video-carousel-section {
			position: relative;
			/* Necessário para posicionar os botões de navegação */
		}

		.video-carousel-section .section-title-holder {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 1.5rem;
		}

		.video-carousel-section .section-title-holder h2 {
			color: #fff;
			font-weight: bold;
			margin: 0;
		}

		.video-carousel-section .movie-card .movie-card-img-container {
			position: relative;
			overflow: hidden;
			border-radius: 5px;
		}

		/* Adicionado para transição suave da imagem */
		.video-carousel-section .movie-card-img-container img {
			transition: transform 0.4s ease;
		}

		/* Efeito de zoom na imagem ao passar o mouse */
		.video-carousel-section .movie-card:hover .movie-card-img-container img {
			transform: scale(1.1);
		}

		.video-carousel-section .movie-card-overlay {
			position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.6);
			/* Overlay preto para escurecer */
			opacity: 0;
			transition: opacity 0.4s ease;
			display: flex;
			align-items: center;
			justify-content: center;
		}

		.video-carousel-section .movie-card:hover .movie-card-overlay {
			opacity: 1;
		}

		/* Ícone de Play que aparece no centro */
		.video-carousel-section .play-icon {
			font-size: 4rem;
			color: white;
			transform: scale(0.7);
			opacity: 0;
			transition: transform 0.3s ease 0.1s, opacity 0.3s ease 0.1s;
		}

		.video-carousel-section .movie-card:hover .play-icon {
			transform: scale(1);
			opacity: 1;
		}

		.video-carousel-section .movie-card-info h5 {
			color: #fff;
			font-size: 1.1rem;
			margin-top: 0.75rem;
			margin-bottom: 0.25rem;
		}

		.video-carousel-section .movie-card-info p {
			font-size: 0.9rem;
			color: #a0a0a0;
		}

		/* Posicionamento dos botões de navegação do carrossel */
		.video-carousel-section .owl-nav button.owl-prev,
		.video-carousel-section .owl-nav button.owl-next {
			position: absolute;
			top: 40%;
			/* Alinha ao meio da altura da imagem */
			transform: translateY(-50%);
			background: rgba(0, 0, 0, 0.5) !important;
			color: #fff !important;
			border-radius: 50%;
			width: 50px;
			height: 50px;
			transition: all 0.3s ease;
			display: flex !important;
			align-items: center;
			justify-content: center;
			padding: 0 !important;
		}

		.video-carousel-section .owl-nav button.owl-prev span,
		.video-carousel-section .owl-nav button.owl-next span {
			font-size: 48px;
			line-height: 1 !important;
			/* Reseta a altura da linha para um alinhamento preciso */
		}

		.video-carousel-section .owl-nav button.owl-prev {
			left: -40px;
			background: transparent !important;
		}

		.video-carousel-section .owl-nav button.owl-next {
			right: -40px;
			background: transparent !important;
		}

		/* Esconde o botão de navegação quando está desativado (início/fim) */
		.video-carousel-section .owl-nav button.disabled {
			opacity: 0;
			pointer-events: none;
		}

		.tag-primary {
			background-color: var(--primary-color);
			color: #fff;
			padding: 3px 10px;
			border-radius: 5px;
			font-size: 0.8rem;
			font-weight: bold;
		}

		/* Animação de zoom para o texto 'Assista ao video' */
		.zoom-parent {
			cursor: pointer;
			position: relative;
		}

		.zoom-on-hover {
			transition: transform 0.7s cubic-bezier(0.22, 1, 0.36, 1);
		}

		.zoom-parent:hover .zoom-on-hover,
		.zoom-on-hover:hover {
			transform: scale(1.12) !important;
		}

		/* Animação da borda do botão de play */
		.zoom-parent .bi-play-fill.zoom-on-hover {
			transition: transform 0.7s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.5s cubic-bezier(0.22, 1, 0.36, 1);
			border: 2px solid transparent;
			border-color: #fff;
		}

		.zoom-parent:hover .bi-play-fill.zoom-on-hover,
		.bi-play-fill.zoom-on-hover:hover {
			box-shadow: 0 0 0 10px rgba(255, 255, 255, 0.15);

		}

		/* Garante que o overlay e o popup do Magnific Popup fiquem acima de tudo */
		.mfp-bg,
		.mfp-wrap,
		.mfp-container,
		.mfp-content,
		.mfp-ready {
			z-index: 99999 !important;
		}

		.mfp-bg,
		.mfp-wrap,
		.mfp-container {
			position: fixed !important;
			top: 0 !important;
			left: 0 !important;
			width: 100vw !important;
			height: 100vh !important;
		}

		/* Garante que os dots do OwlCarousel do slider customizado fiquem visíveis e destacados */
		#custom-slider-owl .owl-dots {
			position: absolute;
			left: 40px;
			bottom: 30px;
			z-index: 10;
			display: flex !important;
			gap: 10px;
		}

		#custom-slider-owl .owl-dot span {
			width: 32px;
			height: 6px;
			background: #fff;
			border-radius: 3px;
			display: block;
			transition: background 0.3s;
			opacity: 0.5;
		}

		#custom-slider-owl .owl-dot.active span {
			background: var(--primary-color);
			opacity: 1;
		}

		/* Centraliza os ícones de navegação do OwlCarousel em telas menores */
		@media (max-width: 768px) {

			/* Esconde completamente a seção custom-slider-section em dispositivos móveis */
			.custom-slider-section {
				display: none !important;
			}

			#custom-slider-owl .owl-nav {
				width: 100% !important;
				display: flex !important;
				justify-content: space-between !important;
				align-items: center !important;
				position: absolute !important;
				top: 50% !important;
				left: 0 !important;
				transform: translateY(-50%) !important;
				padding: 0 0 !important;
				z-index: 20 !important;
			}

			#custom-slider-owl .owl-nav button.owl-prev,
			#custom-slider-owl .owl-nav button.owl-next {
				position: absolute !important;
				top: 50% !important;
				transform: translateY(-50%) !important;
				margin: 0 !important;
				display: flex !important;
				align-items: center !important;
				justify-content: center !important;
				width: 48px !important;
				height: 48px !important;
				background: rgba(0, 0, 0, 0.4) !important;
				border-radius: 50% !important;
				z-index: 21 !important;
			}

			#custom-slider-owl .owl-nav button.owl-prev {
				left: 8px !important;
				right: auto !important;
				position: absolute !important;
				top: 50% !important;
				transform: translateY(-50%) !important;
			}

			#custom-slider-owl .owl-nav button.owl-next {
				right: 8px !important;
				left: auto !important;
			}

			#custom-slider-owl .owl-nav button span {
				display: flex !important;
				align-items: center !important;
				justify-content: center !important;
				width: 100% !important;
				height: 100% !important;
				font-size: 2rem !important;
			}

			#custom-slider-owl .owl-item {
				max-width: 100vw;
				width: 100vw !important;
				margin: 0 !important;
				box-sizing: border-box;
			}

			#custom-slider-owl .row.align-items-center {
				min-height: 320px !important;
				margin-left: 0 !important;
				margin-right: 0 !important;
				width: 100vw !important;
				box-sizing: border-box;
			}

			.custom-slider-section.container,
			.custom-slider-wrapper {
				padding-left: 0 !important;
				padding-right: 0 !important;
				margin-left: 0 !important;
				margin-right: 0 !important;
				width: 100vw !important;
				max-width: 100vw !important;
				box-sizing: border-box;
			}

			body {
				overflow-x: hidden !important;
			}
		}

		@media (max-width: 1024px) {

			html,
			body {
				overflow-x: hidden !important;
				width: 100vw !important;
				max-width: 100vw !important;
			}

			.custom-slider-section.container,
			.custom-slider-wrapper,
			#custom-slider-owl,
			#custom-slider-owl .owl-stage-outer,
			#custom-slider-owl .owl-stage,
			#custom-slider-owl .owl-item,
			#custom-slider-owl .row.align-items-center {
				width: 100vw !important;
				max-width: 100vw !important;
				min-width: 0 !important;
				margin: 0 !important;
				padding: 0 !important;
				box-sizing: border-box !important;
			}
		}

		/* Esconde todos os controles (setas e dots) de todos os carousels */
		.owl-nav,
		.owl-dots {
			display: none !important;
		}

		/* Navbar fixa ao rolar */
		#gen-header.fixed-navbar {
			position: fixed !important;
			top: 0;
			left: 0;
			width: 100vw;
			z-index: 9999;
			box-shadow: 0 2px 16px 0 rgba(0, 0, 0, 0.25);
			animation: fadeInDown 0.4s;
		}

		@keyframes fadeInDown {
			from {
				transform: translateY(-40px);
				opacity: 0;
			}

			to {
				transform: translateY(0);
				opacity: 1;
			}
		}

		/* Back to Top Button */
		#back-to-top-btn {
			position: fixed;
			bottom: 30px;
			right: 30px;
			width: 50px;
			height: 50px;
			background: var(--primary-color);
			color: #fff;
			border-radius: 50%;
			display: none;
			/* Hidden by default */
			justify-content: center;
			align-items: center;
			font-size: 1.5rem;
			z-index: 1000;
			cursor: pointer;
			transition: opacity 0.3s, visibility 0.3s, background-color 0.3s;
			opacity: 0;
			visibility: hidden;
		}

		#back-to-top-btn.show {
			display: flex;
			opacity: 1;
			visibility: visible;
		}

		#back-to-top-btn:hover {
			background: #fff;
			color: var(--primary-color);
		}

		@media (max-width: 400px) {
			.navbar-brand div {
				margin-left: 10px;
				font-size: 1.0rem;
			}

			header#gen-header .gen-bottom-header .navbar .navbar-brand img {
				height: 20px;
			}
		}
	</style>

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
									src="https://yt3.googleusercontent.com/ytc/AIf8zZSU5BzsyFkBIMmIdu0lPTvOEIu6c2h3V_DRrviXcA=s176-c-k-c0x00ffffff-no-rj"
									alt="streamlab-image">
								<div>Visão Libertária</div>
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
										<li class="menu-item">
											<a href="<?= site_url('colaboradores/pautas'); ?>">Notícias</a>
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