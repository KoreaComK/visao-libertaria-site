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

		[data-mdb-theme=dark] .vl-bg-c {
			background-color: #d3a901 !important;
		}

		[data-mdb-theme=dark] .text-dark {
			color: var(--mdb-surface-inverted-color) !important;
		}

		[data-mdb-theme=dark] .bg-dark {
			background-color: var(--mdb-divider-color) !important;
		}

		[data-mdb-theme=dark] a {
			color: var(--mdb-surface-inverted-color) !important;
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

		.vl-bg-c-opaco {
			background-color: rgba(244, 203, 41, 0.4)
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

		.scrolled-down {
			transform: translateY(-100%);
			transition: all 0.6s ease-in-out;
		}

		.scrolled-up {
			transform: translateY(0);
			transition: all 0.6s ease-in-out;
		}

		.button {
			position: fixed;
			bottom: 0.5rem;
			right: 0.5rem;
			padding: 1rem;
			left: 0.1rem;
			right: auto;
			top: 50%;
			bottom: auto;
			z-index: 999;
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
		<link rel="stylesheet" href="<?= site_url('public/assets/estilos.css'); ?>" crossorigin="anonymous">
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
						<?php if (!isset($_SESSION['colaboradores']) || $_SESSION['colaboradores']['id'] === null): ?>
							<li class="nav-item">
								<a class="nav-link ps-0" href="<?= site_url('site/cadastrar'); ?>">Cadastre-se</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="<?= site_url('site/login'); ?>">Acessar</a>
							</li>
						<?php endif; ?>
						<?php if (isset($_SESSION['colaboradores']) && $_SESSION['colaboradores']['id'] !== null): ?>
							<li class="nav-item">
								<a class="nav-link ps-0" href="<?= site_url('colaboradores/artigos/dashboard'); ?>">Área do
									colaborador</a>
							</li>
							<?php if (in_array('7', $_SESSION['colaboradores']['permissoes']) || in_array('8', $_SESSION['colaboradores']['permissoes']) || in_array('9', $_SESSION['colaboradores']['permissoes']) || in_array('10', $_SESSION['colaboradores']['permissoes'])): ?>
								<li class="nav-item">
									<a class="nav-link ps-0"
										href="<?= site_url('colaboradores/admin/dashboard'); ?>">Administração</a>
								</li>
							<?php endif; ?>
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
		<nav class="navbar navbar-expand-lg vl-bg-c shadow-0" id="barra-navegacao">
			<div class="container">
				<div>
					<a class="navbar-brand mt-2 mt-lg-0" href="<?= site_url('site'); ?>">
						<img class="img-thumbnail rounded-circle mr-3" style="max-width: 3rem;"
							src="<?= (file_exists('public/assets/rodape.png')) ? (site_url('public/assets/rodape.png')) : ('https://yt3.googleusercontent.com/ytc/AIf8zZSU5BzsyFkBIMmIdu0lPTvOEIu6c2h3V_DRrviXcA=s176-c-k-c0x00ffffff-no-rj'); ?>"
							alt="MDB Logo" loading="lazy">
						<span class="lead fw-bold"><?= $_SESSION['site_config']['texto_nome']; ?></span>
					</a>
				</div>
				<button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-bs-toggle="collapse"
					data-bs-target="#menuPrincipal" data-target="#menuPrincipal" aria-controls="menuPrincipal"
					aria-expanded="false" aria-label="Toggle navigation">
					<i class="fas fa-bars"></i>
				</button>

				<div class="collapse navbar-collapse" id="menuPrincipal">
					<ul class="navbar-nav h6">
						<li class="nav-item active">
							<a class="nav-link" href="<?= site_url('site'); ?>">Home</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?= site_url('site/artigos'); ?>">Artigos dos colaboradores</a>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link" href="<?= site_url('colaboradores/pautas/'); ?>" role="button"
								aria-expanded="false">Pautas e Notícias</a>
						</li>
						<?php if (isset($_SESSION) && isset($_SESSION['site_config']['paginas']['menu_site'])): ?>
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#">
									Páginas</a>
								<ul class="dropdown-menu vl-bg-c" aria-labelledby="menuArtigosColaboradores">
									<?php foreach ($_SESSION['site_config']['paginas']['menu_site'] as $pagina): ?>
										<li> <a class="dropdown-item"
												href="<?= site_url('site/pagina/' . $pagina['link']); ?>"><?= $pagina['titulo']; ?></a>
										</li>
									<?php endforeach; ?>
								</ul>
							</li>
						<?php endif; ?>
						<li class="nav-item dropdown">
							<a class="nav-link" href="<?= site_url('site/contato'); ?>" role="button"
								aria-expanded="false">Contato</a>
						</li>
					</ul>
					<div class="navbar-nav align-items-center ms-auto menu-direita">
						<?php if (isset($_SESSION['colaboradores']) && $_SESSION['colaboradores']['id'] !== null): ?>

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
									<div class="dropdown-menu rounded-3 vl-bg-c" aria-labelledby="navbarDropdownMenuLink">
										<a class="d-none d-lg-none d-xl-none d-md-block d-sm-block dropdown-item"
											href="<?= site_url('colaboradores/perfil/notificacoes'); ?>">
											Notificações</a>
										<a class="dropdown-item rounded-top-3"
											href="<?= site_url('colaboradores/perfil'); ?>">Meu
											Perfil</a>
										<a class="dropdown-item rounded-bottom-3"
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

	<?php if (isset($_SESSION['colaboradores']['id'])): ?>
		
		<button type="button" class="btn btn-primary rounded-circle button btn-tooltip" data-bs-toggle="modal" data-bs-target="#modalSugerirPauta"
		data-bs-titulo-modal="Cadastre uma pauta" title="Sugerir Pauta"
			data-toggle="tooltip" data-placement="right"><i class="h4 bi bi-pen lh-1"></i></button>

		<?php if (strpos($_SERVER['PHP_SELF'], '/colaboradores/pautas') === false): ?>
			<div class="modal fade" id="modalSugerirPauta" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h3 class="modal-title fs-5"></h3>
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<form method="post" id="pautas_form" name="pautas_form" autocomplete="off">

								<div class="mb-3">
									<label for="username">Link da Notícia</label>
									<div class="input-group">
										<i class="input-group-text bi bi-link-45deg"></i>
										<input type="text" class="form-control" id="link"
											placeholder="Link da notícia para pauta" name="link"
											onblur="getInformationLink(this.value)" data-bs-target="#modal-loading"
											autocomplete="off" required>
									</div>
								</div>

								<div class="mb-3">
									<label for="titulo">Título</label>
									<input type="hidden" id="pauta_antiga" name="pauta_antiga" value="N" />
									<input type="hidden" id="id_pauta" name="id_pauta" value="" />
									<input type="text" class="form-control" id="titulo" name="titulo"
										placeholder="Título da pauta" autocomplete="off" required>
								</div>

								<div class="mb-3">
									<label for="address">Texto <span
											class="text-muted"><?php if (!in_array('7', $_SESSION['colaboradores']['permissoes'])): ?>Máx.
												<?= $_SESSION['site_config']['pauta_tamanho_maximo']; ?> palavras. Mín.
												<?= $_SESSION['site_config']['pauta_tamanho_minimo']; ?> palavras.</span><?php endif; ?> (<span
											class="pull-right label label-default text-muted"
											id="count_message"></span>)</label>
									<textarea class="form-control" name="texto" id="texto" autocomplete="off"
										required></textarea>
								</div>

								<div class="mb-3">
									<label for="address">Link da Imagem</label>
									<div class="input-group">
										<i class="input-group-text bi bi-link-45deg"></i>
										<input type="text" class="form-control" autocomplete="off" id="imagem" name="imagem"
											placeholder="Link da imagem da notícia" required>
									</div>
								</div>

								<div class="text-center preview_imagem_div mb-3 collapse">
									<image class="img-thumbnail img-preview-modal" src="" data-toggle="tooltip"
										data-placement="top" id="preview_imagem" title="Preview da Imagem da Pauta"
										style="max-height: 200px;" />
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-secondary btn-reset" data-bs-dismiss="modal">Cancelar</button>
							<button type="button" class="btn btn-primary btn-enviar">Enviar</button>
						</div>
					</div>
				</div>
			</div>

			<script>

				const exampleModal = document.getElementById('modalSugerirPauta');
				exampleModal.addEventListener('show.bs.modal', event => {
					const button = event.relatedTarget;

					const titulo = button.getAttribute('data-bs-titulo-modal');

					$('.modal-title').html(titulo);
						$('#pautas_form').trigger('reset');
						$('.img-preview-modal').attr('src', '');
				});

				exampleModal.addEventListener('hide.bs.modal', event => {
					$(".btn-reset").trigger("click");
				});

				$('#imagem').change(function () {
					$('.preview_imagem_div').show();
					form = new FormData(pautas_form);
					$.ajax({
						url: "<?= site_url('colaboradores/pautas/verificaImagem'); ?>",
						method: "POST",
						data: form,
						processData: false,
						contentType: false,
						cache: false,
						dataType: "json",
						beforeSend: function () { $('#modal-loading').show(); },
						complete: function () { $('#modal-loading').hide() },
						success: function (retorno) {
							if (retorno.status) {
								$('#preview_imagem').attr('src', $('#imagem').val());
							} else {
								$('#preview_imagem').attr('src', '<?= base_url('public/assets/imagem-default.png'); ?>');
								$('#imagem').val('<?= base_url('public/assets/imagem-default.png'); ?>');
								popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
							}
						}
					});
				});

				$('.btn-reset').on('click', function () {
					$('#link').attr('disabled', false);
					$('#pautas_form').trigger('reset');
					$('.img-preview-modal').attr('src', '');
					$('.preview_imagem_div').hide();
					$('#id_pauta').val('');
				})

				$('#texto').keyup(contapalavras);

				$('.btn-enviar').on('click', function () {
					form = new FormData(pautas_form);
					idPauta = "";
					if ($('#id_pauta').val() != "") {
						idPauta = '/' + $('#id_pauta').val();
					}
					$.ajax({
						url: "<?= site_url('colaboradores/pautas/cadastrar'); ?>" + idPauta,
						method: "POST",
						data: form,
						processData: false,
						contentType: false,
						cache: false,
						dataType: "json",
						beforeSend: function () { $('#modal-loading').show(); },
						complete: function () { $('#modal-loading').hide() },
						success: function (retorno) {
							if (retorno.status) {
								popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
								setTimeout(function () {
									document.location.href = '<?= site_url('colaboradores/pautas'); ?>';
								}, 2000);
								$(".btn-reset").trigger("click");
							} else {
								popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
							}
						}
					});
				})

				$(document).ready(function () {
					contapalavras();
				})

				function contapalavras() {
					var texto = $("#texto").val().replaceAll('\n', " ");
					texto = texto.replace(/[0-9]/gi, "");
					var matches = texto.split(" ");
					number = matches.filter(function (word) {
						return word.length > 0;
					}).length;
					var s = "";
					if (number > 1) {
						s = 's'
					} else {
						s = '';
					}
					$('#count_message').html(number + " palavra" + s)
				}

				function getInformationLink(link) {
					$('#pautas_form').trigger("reset");

					link = link.trim();
					link = link.substring(0, 254);
					$('#link').val(link);

					form = new FormData();
					form.append('link_pauta', link);
					if (link == '') { return false; }

					$.ajax({
						url: "<?= site_url('colaboradores/pautas/verificaPautaCadastrada'); ?>",
						method: "POST",
						data: form,
						processData: false,
						contentType: false,
						cache: false,
						dataType: "json",
						beforeSend: function () { $('#modal-loading').show(); },
						complete: function () { $('#modal-loading').hide() },
						success: function (retorno) {
							if (retorno.status) {
								$('#titulo').val(retorno.titulo);
								$('#imagem').val(retorno.imagem);
								$('#texto').val(retorno.texto);
								$('#preview_imagem').attr('src', retorno.imagem);
								$('.preview_imagem_div').show();
								contapalavras()
								if (retorno.mensagem == null) {
									$('#pauta_antiga').val('N');
								} else {
									$('#pauta_antiga').val('S');
									popMessage('ATENÇÃO!', retorno.mensagem, TOAST_STATUS.INFO);
								}
								if (retorno.imagem == "") {
									$('#imagem').val('<?= base_url('public/assets/imagem-default.png'); ?>');
									$('#preview_imagem').attr('src', '<?= base_url('public/assets/imagem-default.png'); ?>');
								}
							} else {
								$('#preview_imagem').attr('src', '<?= base_url('public/assets/imagem-default.png'); ?>');
								$('#imagem').val('<?= base_url('public/assets/imagem-default.png'); ?>');
								popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
							}
						}
					});
				}
			</script>
		<?php endif; ?>
	<?php endif; ?>

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
											class="fab fa-youtube-square fa-fw me-2"
											style="color:#ff0000;"></i>YouTube</a></li>
								<li class="nav-item"><a class="" href="https://www.instagram.com/ancap.su"><i
											class="fab fa-instagram-square fa-fw me-2 text-youtube"></i>Instagram</a>
								</li>
								<li class="nav-item"><a class="" href="https://twitter.com/ancapsu"><i
											style="color: #40bff5;"
											class="fab fa-twitter-square fa-fw me-2 text-youtube"></i>Twitter</a></li>
								<li class="nav-item text-uppercase mt-2">Safe source</li>
								<li class="nav-item"><a class="" href="https://www.youtube.com/@safesrc"><i
											class="fab fa-youtube-square fa-fw me-2"
											style="color:#ff0000;"></i>YouTube</a></li>
								<li class="nav-item"><a class="" href="https://twitter.com/safesrc1"><i
											style="color: #40bff5;"
											class="fab fa-twitter-square fa-fw me-2 text-youtube"></i>Twitter</a></li>
							</ul>
						</div>
						<div class="col-6">
							<ul class="nav flex-column">
								<li class="nav-item text-uppercase">VISÃO LIBERTÁRIA</li>
								<li class="nav-item"><a class="" href="https://www.youtube.com/@Visao_Libertaria"><i
											class="fab fa-youtube-square fa-fw me-2"
											style="color:#ff0000;"></i>YouTube</a></li>
								</li>
								<li class="nav-item"><a class="" href="https://twitter.com/visaolibertaria"><i
											style="color: #40bff5;"
											class="fab fa-twitter-square fa-fw me-2 text-youtube"></i>Twitter</a></li>
								<li class="nav-item">&nbsp;</li>
								<li class="nav-item text-uppercase mt-2">mundo em revolução</li>
								<li class="nav-item"><a class="" href="https://www.youtube.com/@wrevolving"><i
											class="fab fa-youtube-square fa-fw me-2"
											style="color:#ff0000;"></i>YouTube</a></li>
								</li>
								<li class="nav-item"><a class="" href="https://twitter.com/MundoEmRevo"><i
											style="color: #40bff5;"
											class="fab fa-twitter-square fa-fw me-2 text-youtube"></i>Twitter</a></li>
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

	$(document).ready(function () {

		el_autohide = $('#barra-navegacao');

		if (el_autohide) {
			var last_scroll_top = 0;
			window.addEventListener('scroll', function () {
				if (screen.availWidth > 992) {

					if (window.scrollY > 50) {
						el_autohide.addClass('fixed-top');
						// add padding top to show content behind navbar
						navbar_height = document.querySelector('.navbar').offsetHeight;
						document.body.style.paddingTop = navbar_height + 'px';
					} else {
						el_autohide.removeClass('fixed-top');
						// remove padding top from body
						document.body.style.paddingTop = '0';
					}

					if (window.scrollY > 500) {
						let scroll_top = window.scrollY;
						if (scroll_top < last_scroll_top) {
							el_autohide.removeClass('scrolled-down');
							el_autohide.addClass('scrolled-up');
						}
						else {
							el_autohide.removeClass('scrolled-up');
							el_autohide.addClass('scrolled-down');
						}
						last_scroll_top = scroll_top;
					}
				} else {
					el_autohide.removeClass('fixed-top');
					el_autohide.removeClass('scrolled-up');
					el_autohide.removeClass('scrolled-down');
				}
			});
		}
	});
</script>

</html>