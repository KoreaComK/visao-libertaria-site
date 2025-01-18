<!DOCTYPE html>
<html lang="pt-BR">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous"> -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

	<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script> -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
		crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.7.0.min.js"
		integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

	<script src="https://cdn.jsdelivr.net/npm/bs-custom-file-input/dist/bs-custom-file-input.min.js"></script>
	<script src="https://js.hcaptcha.com/1/api.js" async defer></script>


	<meta property="og:type" content="website" />
	<meta property="og:url" content="<?= current_url(true); ?>" />
	<meta name="theme-color" content="#F3C921">

	<meta name="twitter:card" content="summary_large_image">
	<meta property="twitter:domain" content="<?= site_url(); ?>">
	<meta property="twitter:url" content="<?= current_url(true); ?>">
	<meta name="twitter:title" content="<?= $_SESSION['site_config']['texto_nome']; ?>">
	<meta name="twitter:description" content="<?= $_SESSION['site_config']['texto_rodape']; ?>">
	<meta name="twitter:image"
		content="<?= (file_exists('public/assets/favicon.ico')) ? (site_url('public/assets/favicon.ico')) : ('https://yt3.googleusercontent.com/ytc/AIf8zZSU5BzsyFkBIMmIdu0lPTvOEIu6c2h3V_DRrviXcA=s176-c-k-c0x00ffffff-no-rj'); ?>">
	<meta property="og:title" content="<?= $_SESSION['site_config']['texto_nome']; ?>" />
	<meta property="og:image"
		content="<?= (file_exists('public/assets/favicon.ico')) ? (site_url('public/assets/favicon.ico')) : ('https://yt3.googleusercontent.com/ytc/AIf8zZSU5BzsyFkBIMmIdu0lPTvOEIu6c2h3V_DRrviXcA=s176-c-k-c0x00ffffff-no-rj'); ?>" />
	<meta property="og:description" content="<?= $_SESSION['site_config']['texto_rodape']; ?>" />

	<!-- Font Awesome -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
	<!-- MDB -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.min.css" rel="stylesheet" />

	<style type="text/css">
		.dropdown:hover>.dropdown-menu {
			display: block;
			margin-top: -2px;
		}

		.dropdown>.dropdown-toggle:active {
			/*Without this, clicking will make it sticky*/
			pointer-events: none;
		}

		[data-mdb-theme=dark] .card {
			background-color: var(--mdb-picker-header-bg);
		}

		[data-mdb-theme=dark] .text-dark {
			color: var(--mdb-surface-inverted-color) !important;
		}

		[data-mdb-theme=dark] .bg-dark {
			background-color: var(--mdb-divider-color) !important;
		}

		[data-mdb-theme=dark] .vl-bg-c {
			background-color: #d3a901 !important;
		}

		[data-mdb-theme=dark] a {
			color: var(--mdb-surface-inverted-color) !important;
		}

		[data-mdb-theme=dark] .btn-light {
			background-color: var(--mdb-btn-disabled-color);
		}

		.btn-link {
			background: linear-gradient(to right, currentColor 0%, currentColor 100%);
			background-position-x: 0%;
			background-position-y: 0%;
			background-repeat: repeat;
			background-size: auto;
			background-size: 0px 6%;
			background-repeat: no-repeat;
			background-position: left 100%;
			-webkit-transition-duration: 0.5s;
			transition-duration: 0.5s;
			font-weight: inherit;
			padding: 0;
		}

		.btn-link:hover {
			background-size: 100% 6%;
		}

		.card-title {
			line-height: 1.5;
		}

		.pointer+.pointer:before {
			content: "\2022";
			color: inherit;
			padding-left: .35rem;
			padding-right: .25rem;
			opacity: 0.8;
		}

		.vl-bg-c,
		.btn-outline-secondary,
		.btn-primary {
			background-color: #f3c921 !important;
			color: #181818;
		}

		.btn-primary {
			border-color: #f3c921 !important;
		}

		a .vl-bg-c:hover {
			background-color: #e6e6e6 !important;
		}

		.bg-light {
			background-color: #e6e6e6;
			color: #181818;
		}

		a {
			color: #4b515c;
		}


		@media screen and (min-width: 600px) {
			.menu-direita {
				align-items: start !important;
			}
		}
	</style>
	<?php
	if (file_exists('public/assets/estilos.css')):
		?>
		<link rel="stylesheet" href="public/assets/estilos.css" crossorigin="anonymous">
		<?php
	endif;
	?>
	<title>
		<?= $_SESSION['site_config']['texto_nome']; ?>
	</title>
	<link rel="icon" type="image/x-icon"
		href="<?= (file_exists('public/assets/favicon.ico')) ? (site_url('public/assets/favicon.ico')) : ('https://yt3.googleusercontent.com/ytc/AIf8zZSU5BzsyFkBIMmIdu0lPTvOEIu6c2h3V_DRrviXcA=s176-c-k-c0x00ffffff-no-rj'); ?>">


	<link rel="stylesheet"
		href="https://cdn.jsdelivr.net/npm/bootstrap-toaster@5.2.0-beta1.1/dist/css/bootstrap-toaster.min.css">

</head>

<body>
	<script
		src="https://cdn.jsdelivr.net/npm/bootstrap-toaster@5.2.0-beta1.1/dist/umd/bootstrap-toaster.min.js"></script>
	<script>
		let toast = {
			title: "",
			message: "",
			status: TOAST_STATUS.SUCCESS,
			timeout: 3000
		}
		Toast.setTheme(TOAST_THEME.LIGHT);
		Toast.enableTimers(TOAST_TIMERS.DISABLED);
		Toast.setMaxCount(10);
		Toast.enableQueue(true);
		function popMessage(titulo, mensagem, status) {
			toast.message = mensagem;
			toast.title = titulo;
			toast.status = status;
			Toast.create(toast);
		}
	</script>

	<div class="modal bg-light" style="opacity: 0.4; z-index:7000;" id="modal-loading" tabindex="-1"
		aria-labelledby="modal-loadingLabel" aria-hidden="true">
		<div class="position-absolute w-100 h-100 d-flex flex-column align-items-center justify-content-center">
			<div class="spinner-border" role="status">
				<span class="visually-hidden">Loading...</span>
			</div>
		</div>
	</div>

	<header>
		<div class="navbar-top d-lg-block navbar-expand-lg small">
			<div class="container">
				<div class="d-md-flex justify-content-between align-items-center my-2">
					<!-- Top bar left -->
					<ul class="nav">
						<?php if (isset($_SESSION) && $_SESSION['colaboradores']['id'] !== null): ?>
							<li class="nav-item">
								<a class="nav-link ps-0" href="<?= site_url('site'); ?>">Voltar ao site</a>
							</li>
						<?php endif; ?>
						<?php if (in_array('7', $_SESSION['colaboradores']['permissoes']) || in_array('8', $_SESSION['colaboradores']['permissoes']) || in_array('9', $_SESSION['colaboradores']['permissoes']) || in_array('10', $_SESSION['colaboradores']['permissoes'])): ?>
							<li class="nav-item">
								<a class="nav-link ps-0"
									href="<?= site_url('colaboradores/admin/dashboard'); ?>">Administração</a>
							</li>
						<?php endif; ?>
					</ul>
					<!-- Top bar right -->
					<div class="d-flex align-items-center">
						<!-- Dark mode options START -->
						<div class="nav-item dropdown mx-2">
							<!-- Switch button -->
							<span class="modeswitch dark-button btn-tertiary" aria-expanded="false"
								data-bs-toggle="dropdown" data-bs-display="static">
								<i class="fas fa-moon fa-2x"></i>
							</span>
							<span class="modeswitch light-button btn-tertiary" aria-expanded="false"
								data-bs-toggle="dropdown" data-bs-display="static">
								<i class="far fa-moon fa-2x"></i>
							</span>
						</div>
						<!-- Dark mode options END -->
					</div>
				</div>
			</div>
		</div>
		<nav class="navbar navbar-expand-lg shadow-0 vl-bg-c">
			<div class="container">
				<div>
					<a class="navbar-brand mt-2 mt-lg-0" href="<?= site_url('colaboradores/artigos/dashboard'); ?>">
						<img class="img-thumbnail rounded-circle mr-3" style="max-width: 3rem;"
							src="<?= (file_exists('public/assets/rodape.png')) ? (site_url('public/assets/rodape.png')) : ('https://yt3.googleusercontent.com/ytc/AIf8zZSU5BzsyFkBIMmIdu0lPTvOEIu6c2h3V_DRrviXcA=s176-c-k-c0x00ffffff-no-rj'); ?>"
							loading="lazy">
						<span class="lead fw-bold"><?= $_SESSION['site_config']['texto_nome']; ?></span>
					</a>
				</div>
				<button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-bs-toggle="collapse"
					data-bs-target="#menuPrincipal" data-target="#menuPrincipal" aria-controls="menuPrincipal"
					aria-expanded="false" aria-label="Toggle navigation">
					<i class="fas fa-bars"></i>
				</button>

				<div class="collapse navbar-collapse" id="menuPrincipal">
					<ul class="navbar-nav d-flex justify-content-center">
						<li class="nav-item active">
							<a class="nav-link" href="<?= site_url('colaboradores/artigos/dashboard'); ?>"><i
									class="fas fa-globe"></i> Dashboard</a>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#"><i class="fas fa-pen"
									id="menuArtigosColaboradores"></i>
								Artigos</a>
							<ul class="dropdown-menu vl-bg-c" aria-labelledby="menuArtigosColaboradores">
								<li> <a class="dropdown-item"
										href="<?= site_url('colaboradores/artigos/cadastrar'); ?>">Escrever novo</a>
								</li>
								<li> <a class="dropdown-item"
										href="<?= site_url('colaboradores/artigos/meusArtigos'); ?>">Meus artigos</a>
								</li>
								<?php if (in_array('3', $_SESSION['colaboradores']['permissoes']) || in_array('4', $_SESSION['colaboradores']['permissoes']) || in_array('5', $_SESSION['colaboradores']['permissoes']) || in_array('6', $_SESSION['colaboradores']['permissoes'])): ?>
									<li> <a class="dropdown-item"
											href="<?= site_url('colaboradores/artigos/artigosColaborar'); ?>">Colaborar com
											artigos</a>
									</li>
								<?php endif; ?>
								<?php if (in_array('7', $_SESSION['colaboradores']['permissoes'])): ?>
									<li> <a class="dropdown-item"
											href="<?= site_url('colaboradores/admin/artigos'); ?>">Gerenciar todos os
											artigos</a> </li>
								<?php endif; ?>
							</ul>
						</li>
						<?php if (isset($_SESSION) && isset($_SESSION['site_config']['paginas']['menu_colaborador'])): ?>
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#"><i class="far fa-file-lines"></i>
									Páginas</a>
								<ul class="dropdown-menu vl-bg-c" aria-labelledby="menuArtigosColaboradores">
									<?php foreach ($_SESSION['site_config']['paginas']['menu_colaborador'] as $pagina): ?>
										<li> <a class="dropdown-item"
												href="<?= site_url('site/pagina/' . $pagina['link']); ?>"><?= $pagina['titulo']; ?></a>
										</li>
									<?php endforeach; ?>
								</ul>
							</li>
						<?php endif; ?>
					</ul>
					<div class="navbar-nav align-items-center ms-auto menu-direita">
						<?php if (isset($_SESSION) && $_SESSION['colaboradores']['id'] !== null): ?>

							<ul class="navbar-nav">
								<li class="nav-item dropdown">
									<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
										data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<img id="avatar_menu" src="<?= $_SESSION['colaboradores']['avatar']; ?>" width="30"
											height="30" class="rounded-circle">
										<span class="apelido_colaborador">
											<?= $_SESSION['colaboradores']['nome']; ?>
										</span>
									</a>
									<div class="dropdown-menu vl-bg-c" aria-labelledby="navbarDropdownMenuLink">
										<a class="d-none d-lg-none d-xl-none d-md-block d-sm-block dropdown-item rounded-top"
											href="<?= site_url('colaboradores/perfil/notificacoes'); ?>">
											Notificações</a>
										<a class="dropdown-item rounded-top"
											href="<?= site_url('colaboradores/perfil'); ?>">Meu
											Perfil</a>
										<a class="dropdown-item rounded-bottom"
											href="<?= site_url('site/logout'); ?>">Sair</a>
									</div>
								</li>
							</ul>
							<div class="collapse navbar-collapse" id="navbar-list-5">
								<ul class="navbar-nav d-flex flex-row me-1">
									<li class="nav-item me-3 me-lg-0">
										<div>
											<a class="link-white me-3 text-reset"
												href="<?= site_url('colaboradores/perfil/notificacoes'); ?>"
												id="navbarDropdownMenuLink" role="button" data-mdb-toggle="dropdown"
												aria-expanded="false">
												<i class="bi bi-bell" style="font-size:25px;" <?php if (isset($_SESSION) && (!isset($_SESSION['colaboradores']['notificacoes']) || $_SESSION['colaboradores']['notificacoes'] > 0)): ?> class="bi bi-bell"
													<?php else: ?> class="bi bi-fill" <?php endif; ?>></i>
												<span class="badge badge-counter bg-danger"
													style="float:right; margin-top:0px; margin-left:-20px;"><?= (isset($_SESSION) && (!isset($_SESSION['colaboradores']['notificacoes']) || $_SESSION['colaboradores']['notificacoes'] == 0)) ? ('0') : ($_SESSION['colaboradores']['notificacoes']); ?></span>
											</a>
										</div>
									</li>
								</ul>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</nav>
	</header>

	<?= $this->renderSection('content'); ?>

	<footer class="mb-3 mt-5">
		<div class="container">
			<div class="text-center">Desenvolvido por <a class="text-reset btn-link font-light"
					href="https://github.com/KoreaComK/">KoreacomK</a> e a
				comunidade.
			</div>
		</div>
	</footer>

	<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"
		id="mi-modal">
		<div class="modal-dialog modal-md modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">ATENÇÃO!</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<p class="conteudo-modal"></p>
				</div>
				<div class="modal-footer d-flex justify-content-between">
					<button type="button" class="btn btn-default" data-bs-dismiss="modal" id="modal-btn-no">Não</button>
					<button type="button" class="btn btn-primary" id="modal-btn-si">Sim</button>
				</div>
			</div>
		</div>
	</div>
</body>

<script type="text/javascript">

	$(document).ready(function () {
		bsCustomFileInput.init()
	})

	$(function () {
		$('.btn-tooltip').tooltip();
	});

	var darkMode;
	if (localStorage.getItem('dark-mode')) {
		darkMode = localStorage.getItem('dark-mode');
	} else {
		darkMode = 'light';
	}

	localStorage.setItem('dark-mode', darkMode);

	if (localStorage.getItem('dark-mode') == 'dark') {
		$('body').attr('data-mdb-theme', 'dark');
		$('.dark-button').hide();
		$('.light-button').show();
	} else {
		$('.dark-button').show();
		$('.light-button').hide();
	}

	// Toggle dark UI
	$('.dark-button').on('click', function () {
		$('.dark-button').hide();
		$('.light-button').show();
		$('body').attr('data-mdb-theme', 'dark');
		localStorage.setItem('dark-mode', 'dark');
	});

	$('.light-button').on('click', function () {
		$('.light-button').hide();
		$('.dark-button').show();
		$('body').attr('data-mdb-theme', '');
		localStorage.setItem('dark-mode', 'light');
	});
</script>

</html>