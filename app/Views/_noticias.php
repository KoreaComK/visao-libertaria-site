<?= $this->extend('layouts/_main'); ?>

<?= $this->section('content'); ?>

<?php if (isset($_SESSION['colaboradores']['id'])): ?>
	<link rel="stylesheet"
		href="https://cdn.jsdelivr.net/npm/bootstrap-toaster@5.2.0-beta1.1/dist/css/bootstrap-toaster.min.css">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-toaster@5.2.0-beta1.1/dist/umd/bootstrap-toaster.min.js"></script>
	<script>
		let toast = {
			title: "",
			message: "",
			status: TOAST_STATUS.SUCCESS,
			timeout: 3000
		};
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
	<div class="modal vl-noticias-loading-overlay" style="z-index:7000;" id="modal-loading" tabindex="-1"
		aria-labelledby="modal-loadingLabel" aria-hidden="true">
		<div class="position-absolute w-100 h-100 d-flex flex-column align-items-center justify-content-center">
			<div class="spinner-border vl-noticias-spinner" role="status">
				<span class="visually-hidden">Carregando…</span>
			</div>
		</div>
	</div>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js"
	integrity="sha384-GNFwBvfVxBkLMJpYMOABq3c+d3KnQxudP/mGPkzpZSTYykLBNsZEnG2D9G/X/+7D" crossorigin="anonymous"
	async></script>

<script src="https://cdn.jsdelivr.net/npm/infinite-scroll@4.0.1/dist/infinite-scroll.pkgd.min.js"
	integrity="sha384-+83ma0Y8eQWtTIhmx2gjueu3BY0XU4gX4EkL12u3M+WPc4SDskKaIpIL7QiB8ikh"
	crossorigin="anonymous"></script>

<div class="container-fluid py-3 vl-site-noticias">
	<div class="container">

		<section class="pt-4 pb-4 margin-top-ultra">
			<div class="row">
				<div class="col-12">
					<nav class="custom-breadcrumb mb-4" aria-label="Migalhas de navegação">
						<ol class="breadcrumb d-flex align-items-center">
							<li class="breadcrumb-item">
								<a href="<?= site_url('site'); ?>">
									<i class="bi bi-house-fill pe-1" aria-hidden="true"></i>Home
								</a>
							</li>
							<li class="breadcrumb-item active" aria-current="page">
								<i class="bi bi-newspaper pe-1" aria-hidden="true"></i>Notícias
							</li>
						</ol>
					</nav>
				</div>
			</div>
		</section>

		<section class="mb-4">
			<div class="row">
				<div class="col-12">
					<div class="card border-secondary bg-dark text-light">
						<div class="card-header border-secondary">
							<h2 class="h5 card-title mb-0 text-white">
								<i class="bi bi-funnel pe-2" aria-hidden="true"></i>Buscar notícias
							</h2>
						</div>
						<div class="card-body">
							<form method="get" id="formFiltroNoticias" action="<?= site_url('site/noticias'); ?>">
								<div class="row g-3 align-items-end">
									<div class="col-12 col-md-9">
										<label for="pesquisa" class="form-label">Buscar</label>
										<div class="input-group input-group-sm">
											<span class="input-group-text bg-secondary border-secondary text-white"><i class="bi bi-search" aria-hidden="true"></i></span>
											<input type="text" class="form-control form-control-sm bg-dark text-light border-secondary" id="pesquisa" name="pesquisa"
												placeholder="Buscar por título, texto ou link…"
												value="<?= isset($_GET['pesquisa']) ? esc($_GET['pesquisa']) : ''; ?>">
										</div>
									</div>
									<div class="col-12 col-md-3 d-flex flex-column justify-content-end">
										<div class="vl-noticias-filtro-botoes d-flex flex-column flex-sm-row gap-2 w-100 align-items-stretch">
											<button type="submit" class="btn vl-noticias-btn-filtro text-nowrap">
												<i class="bi bi-search pe-2" aria-hidden="true"></i>Filtrar
											</button>
											<a href="<?= site_url('site/noticias'); ?>" id="vl-noticias-limpar" role="button" class="btn btn-outline-light text-nowrap">
												<i class="bi bi-x-lg pe-1" aria-hidden="true"></i>Limpar
											</a>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>

		<?php if (isset($_SESSION['colaboradores']['id'])): ?>
			<div class="mb-4 text-center">
				<button type="button" class="btn vl-noticias-btn-filtro" id="btn-sugerir-pauta"
					<?php if ($limiteDiario === false && $limiteSemanal === false): ?>
						data-bs-toggle="modal" data-bs-target="#modalSugerirPauta" data-bs-titulo-modal="Cadastre uma pauta"
					<?php else: ?>
						data-bs-toggle="tooltip" data-bs-placement="top"
						title="<?= $limiteDiario === true
							? 'Você atingiu o limite diário de pautas. Tente novamente amanhã.'
							: 'Você atingiu o limite semanal de pautas. Tente novamente outro dia.'; ?>"
					<?php endif; ?>>
					Sugerir pauta
				</button>
			</div>
		<?php else: ?>
			<p class="text-center text-white-50 small mb-4">
				Quer sugerir uma pauta ou colaborar?
				<a href="<?= site_url('site/login'); ?>" class="vl-noticias-entrar-link">Entrar</a>
			</p>
		<?php endif; ?>

		<div id="vl-noticias-list-root">
			<?= view('template/templatePautasListSite', ['pautasList' => $pautasList]); ?>
		</div>

		<div class="page-load-status">
			<div class="infinite-scroll-request d-flex justify-content-center mt-5 mb-5">
				<div class="spinner-border vl-noticias-spinner" role="status">
					<span class="visually-hidden">Carregando mais notícias…</span>
				</div>
			</div>
			<p class="infinite-scroll-last h6 text-white-50" role="status">Fim da lista</p>
			<p class="infinite-scroll-error h6 text-danger" role="alert">Não foi possível carregar mais páginas</p>
		</div>

	</div>
</div>

<?php if (isset($_SESSION['colaboradores']['id'])):
	$pautaListPermissoes = $_SESSION['colaboradores']['permissoes'] ?? [];
	?>
	<div class="modal fade vl-noticias-pauta-modal" id="modalSugerirPauta" tabindex="-1" role="dialog" aria-labelledby="modalSugerirPautaTitulo"
		aria-hidden="true">
		<div class="modal-dialog modal-dialog-scrollable" role="document">
			<div class="modal-content border border-secondary shadow-lg" data-bs-theme="dark">
				<div class="modal-header border-secondary">
					<h5 class="modal-title text-white" id="modalSugerirPautaTitulo"></h5>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
				</div>
				<div class="modal-body">
					<form method="post" id="pautas_form" name="pautas_form" autocomplete="off">

						<div class="mb-3">
							<label for="link" class="form-label">Link da Notícia</label>
							<div class="input-group">
								<span class="input-group-text" aria-hidden="true"><i class="bi bi-link-45deg"></i></span>
								<input type="text" class="form-control" id="link" placeholder="Link da notícia para pauta"
									name="link" onblur="getInformationLink(this.value)"
									autocomplete="off" required>
							</div>
							<div class="form-text">Ao sair deste campo, os dados da URL são buscados automaticamente.</div>
						</div>

						<div class="mb-3">
							<label for="titulo" class="form-label">Título</label>
							<input type="hidden" id="pauta_antiga" name="pauta_antiga" value="N" />
							<input type="hidden" id="id_pauta" name="id_pauta" value="" />
							<input type="text" class="form-control" id="titulo" name="titulo" placeholder="Título da pauta"
								autocomplete="off" required>
						</div>

						<div class="mb-3">
							<label for="texto" class="form-label">Texto<?php if (! in_array('7', $pautaListPermissoes, false)): ?>
									<span class="text-muted"> Máx. <?= esc($config['pauta_tamanho_maximo']); ?> palavras. Mín. <?= esc($config['pauta_tamanho_minimo']); ?> palavras.</span><?php endif; ?>
									(<span class="text-muted" id="count_message"></span>)</label>
							<textarea class="form-control" name="texto" id="texto" autocomplete="off" required></textarea>
						</div>

						<div class="mb-3">
							<label for="imagem" class="form-label">Link da Imagem</label>
							<div class="input-group">
								<span class="input-group-text" aria-hidden="true"><i class="bi bi-link-45deg"></i></span>
								<input type="text" class="form-control" autocomplete="off" id="imagem" name="imagem"
									placeholder="Link da imagem da notícia" required>
							</div>
						</div>

						<div class="text-center preview_imagem_div mb-3 collapse">
							<img class="img-thumbnail img-preview-modal" src="" data-bs-toggle="tooltip" data-bs-placement="top"
								id="preview_imagem" alt="Pré-visualização da imagem da pauta" title="Pré-visualização da imagem da pauta" style="max-height: 200px;">
						</div>
					</form>
				</div>
				<div class="modal-footer border-secondary d-flex flex-wrap gap-2 justify-content-between">
					<button type="button" class="me-auto btn btn-outline-danger btn-excluir">Excluir pauta</button>
					<div class="d-flex flex-wrap gap-2 ms-auto">
						<button type="button" class="btn btn-secondary" id="btn-reset-modal-pauta" data-bs-dismiss="modal">Cancelar</button>
						<button type="button" class="btn btn-warning btn-enviar">Enviar</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade vl-noticias-pauta-modal" id="modalComentariosPauta" tabindex="-1" role="dialog" aria-labelledby="modalComentariosPautaTitulo"
		aria-hidden="true">
		<div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
			<div class="modal-content border border-secondary shadow-lg" data-bs-theme="dark">
				<div class="modal-header border-secondary">
					<h5 class="modal-title text-white" id="modalComentariosPautaTitulo">Comentários da Pauta</h5>
					<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
				</div>
				<div class="modal-body">
					<div class="card mb-3 border-secondary bg-dark text-light">
						<img src="" class="card-img-top modalImagem" alt="Imagem de destaque da pauta">
						<div class="card-body">
							<h5 class="card-title modalTitulo"></h5>
							<p class="card-text modalTexto"></p>
						</div>
					</div>

					<div class="row">
						<div class="col-12 text-center">
							<button class="btn btn-primary mt-3 mb-3 col-md-6" id="btn-comentarios" type="button">Atualizar
								Comentários</button>
						</div>
						<div class="col-12 d-flex justify-content-center">

							<div class="col-12 div-comentarios">
								<div class="col-12">
									<div class="mb-3">
										<label for="comentario" class="form-label">Seu comentário</label>
										<input type="hidden" id="idPauta" name="idPauta" />
										<input type="hidden" id="id_comentario" name="id_comentario" />
										<textarea id="comentario" name="comentario" class="form-control" rows="5"
											placeholder="Digite seu comentário aqui"></textarea>
									</div>
									<div class="mb-3 text-center">
										<button class="btn btn-primary mt-3 col-md-6" id="enviar-comentario"
											type="button">Enviar comentário</button>
									</div>
								</div>
								<div class="card m-3 div-list-comentarios"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer border-secondary">
					<button type="button" class="btn btn-secondary" id="btn-fechar-modal-comentarios" data-bs-dismiss="modal">Fechar</button>
				</div>
			</div>
		</div>
	</div>
<?php endif; ?>

<script>
(function () {
	var listUrl = <?= json_encode(site_url('site/noticias')); ?>;
	var debounceMs = 380;
	var debounceTimer = null;
	var listAbort = null;
	var lastFetchedTerm = null;

	function destroyNoticiasListWidgets($wrap) {
		var $grid = $wrap.find('.pautas-list');
		if (!$grid.length) {
			return;
		}
		var inf = $grid.data('infiniteScroll');
		if (inf && typeof inf.destroy === 'function') {
			inf.destroy();
			$grid.removeData('infiniteScroll');
		} else if (typeof $grid.infiniteScroll === 'function') {
			try {
				$grid.infiniteScroll('destroy');
			} catch (e) { /* ignore */ }
		}
		if ($grid.data('masonry') && typeof $grid.masonry === 'function') {
			try {
				$grid.masonry('destroy');
			} catch (e2) { /* ignore */ }
		}
	}

	function initTooltipsIn($root) {
		($root && $root.length ? $root : $(document)).find('[data-bs-toggle="tooltip"]').each(function () {
			var el = this;
			if (typeof bootstrap === 'undefined' || !bootstrap.Tooltip) {
				return;
			}
			var existing = bootstrap.Tooltip.getInstance(el);
			if (existing) {
				existing.dispose();
			}
			new bootstrap.Tooltip(el);
		});
	}

	function initNoticiasMasonryIn($wrap) {
		var $grid = $wrap.find('.pautas-list');
		if (!$grid.length || typeof $grid.masonry !== 'function') {
			return;
		}
		$grid.masonry({
			stagger: 100,
			itemSelector: '.card',
			horizontalOrder: true,
			gutter: 16,
			percentPosition: true
		});
		var msnry = $grid.data('masonry');
		if (msnry && typeof $grid.infiniteScroll === 'function') {
			$grid.infiniteScroll({
				path: '.next_page',
				append: '.card',
				history: false,
				outlayer: msnry,
				status: '.vl-site-noticias .page-load-status',
				scrollThreshold: 100
			});
		}
		initTooltipsIn($wrap);
	}

	function syncUrlPesquisa(term) {
		var u = new URL(window.location.href);
		if (term) {
			u.searchParams.set('pesquisa', term);
		} else {
			u.searchParams.delete('pesquisa');
		}
		u.searchParams.delete('page_noticias');
		u.searchParams.delete('partial');
		var qs = u.searchParams.toString();
		history.replaceState({}, '', u.pathname + (qs ? '?' + qs : ''));
	}

	function fetchNoticiasList(term, force) {
		var $root = $('#vl-noticias-list-root');
		if (!$root.length || typeof fetch !== 'function') {
			return;
		}
		var t = term == null ? '' : String(term).trim();
		if (!force && lastFetchedTerm !== null && lastFetchedTerm === t) {
			return;
		}
		if (listAbort) {
			listAbort.abort();
		}
		listAbort = new AbortController();
		var params = new URLSearchParams();
		if (t) {
			params.set('pesquisa', t);
		}
		params.set('partial', '1');
		var url = listUrl + (listUrl.indexOf('?') >= 0 ? '&' : '?') + params.toString();
		fetch(url, {
			method: 'GET',
			credentials: 'same-origin',
			signal: listAbort.signal,
			headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' }
		})
			.then(function (res) {
				if (!res.ok) {
					throw new Error(String(res.status));
				}
				return res.text();
			})
			.then(function (html) {
				listAbort = null;
				lastFetchedTerm = t;
				destroyNoticiasListWidgets($root);
				$root.html(html);
				initNoticiasMasonryIn($root);
				syncUrlPesquisa(t);
			})
			.catch(function (err) {
				if (err && err.name === 'AbortError') {
					return;
				}
				listAbort = null;
				console.error('Notícias:', err);
			});
	}

	function scheduleFetchFromInput(force) {
		var $inp = $('#pesquisa');
		if (!$inp.length) {
			return;
		}
		clearTimeout(debounceTimer);
		debounceTimer = setTimeout(function () {
			debounceTimer = null;
			fetchNoticiasList($inp.val(), force);
		}, force ? 0 : debounceMs);
	}

	$(document).ready(function () {
		var $root = $('#vl-noticias-list-root');
		if (!$root.length) {
			return;
		}
		initNoticiasMasonryIn($root);

		if (typeof fetch !== 'function') {
			return;
		}

		lastFetchedTerm = $('#pesquisa').val() != null ? String($('#pesquisa').val()).trim() : '';

		$('#pesquisa').on('keyup input', function () {
			scheduleFetchFromInput(false);
		});

		$('#formFiltroNoticias').on('submit', function (e) {
			e.preventDefault();
			clearTimeout(debounceTimer);
			debounceTimer = null;
			fetchNoticiasList($('#pesquisa').val(), true);
		});

		$('#vl-noticias-limpar').on('click', function (e) {
			e.preventDefault();
			$('#pesquisa').val('');
			clearTimeout(debounceTimer);
			debounceTimer = null;
			fetchNoticiasList('', true);
		});
	});

	window.VL_refreshNoticiasListaNoticias = function () {
		var $root = $('#vl-noticias-list-root');
		if (!$root.length || typeof fetch !== 'function') {
			return;
		}
		fetchNoticiasList($('#pesquisa').val() != null ? String($('#pesquisa').val()).trim() : '', true);
	};
})();
</script>

<?php if (isset($_SESSION['colaboradores']['id'])): ?>
<script>
(function () {
	function resetPautaFormUi() {
		$('#modalSugerirPauta #link').prop('disabled', false);
		var f = document.getElementById('pautas_form');
		if (f) {
			f.reset();
		}
		$('#modalSugerirPauta .img-preview-modal').attr('src', '');
		$('#modalSugerirPauta .preview_imagem_div').hide();
		$('#id_pauta').val('');
		if (window.VL_contagemPalavrasAtualizar) {
			window.VL_contagemPalavrasAtualizar();
		}
	}

	var exampleModal = document.getElementById('modalSugerirPauta');
	var modalComentarios = document.getElementById('modalComentariosPauta');

	if (exampleModal) {
		exampleModal.addEventListener('show.bs.modal', function (event) {
			var button = event.relatedTarget;
			var recipient = button ? button.getAttribute('data-bs-pautas-id') : null;
			var titulo = button ? button.getAttribute('data-bs-titulo-modal') : null;
			$('#modalSugerirPautaTitulo').html(titulo || 'Cadastre uma pauta');

			if (recipient != null && recipient !== '') {
				$('#modalSugerirPauta .btn-excluir').show();
				$.ajax({
					url: "<?= site_url('colaboradores/pautas/detalhe/'); ?>" + recipient,
					method: "POST",
					data: '',
					processData: false,
					contentType: false,
					cache: false,
					dataType: "json",
					beforeSend: function () { $('#modal-loading').show(); },
					complete: function () { $('#modal-loading').hide(); },
					success: function (retorno) {
						if (retorno.status) {
							$('#id_pauta').val(recipient);
							$('#titulo').val(retorno.titulo);
							$('#link').val(retorno.link);
							$('#link').prop('disabled', true);
							$('#texto').val(retorno.texto);
							$('#imagem').val(retorno.imagem);
							$('#pauta_antiga').val(retorno.pauta_antiga);
							$('#imagem').trigger('change');
							$('#texto').trigger('input');
						} else {
							popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
						}
					}
				});
			} else {
				resetPautaFormUi();
				$('#modalSugerirPauta .btn-excluir').hide();
			}
		});

		exampleModal.addEventListener('hide.bs.modal', function () {
			resetPautaFormUi();
		});
	}

	if (modalComentarios) {
		modalComentarios.addEventListener('show.bs.modal', function (event) {
			var button = event.relatedTarget;
			if (!button) {
				return;
			}
			$('.modalImagem').attr('src', button.getAttribute('data-bs-imagem') || '');
			$('.modalTexto').html(button.getAttribute('data-bs-texto') || '');
			$('.modalTitulo').html(button.getAttribute('data-bs-titulo') || '');
			$('#idPauta').val(button.getAttribute('data-bs-pautas-id') || '');
			if (typeof getComentarios === 'function') {
				getComentarios();
			}
		});
	}

	$('#modalSugerirPauta .btn-excluir').on('click', function () {
		var form = new FormData(document.getElementById('pautas_form'));
		var idPauta = $('#id_pauta').val();
		$.ajax({
			url: "<?= site_url('colaboradores/pautas/excluir/'); ?>" + idPauta,
			method: "POST",
			data: form,
			processData: false,
			contentType: false,
			cache: false,
			dataType: "json",
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide(); },
			success: function (retorno) {
				if (retorno.status) {
					popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
					var modalEl = document.getElementById('modalSugerirPauta');
					if (modalEl && typeof bootstrap !== 'undefined') {
						bootstrap.Modal.getOrCreateInstance(modalEl).hide();
					}
					if (typeof window.VL_refreshNoticiasListaNoticias === 'function') {
						window.VL_refreshNoticiasListaNoticias();
					}
				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
					if (typeof Toast !== 'undefined' && typeof toast !== 'undefined') {
						Toast.create(toast);
					}
				}
			}
		});
	});

	$('#modalSugerirPauta #imagem').on('change', function () {
		$('#modalSugerirPauta .preview_imagem_div').show();
		var form = new FormData(document.getElementById('pautas_form'));
		$.ajax({
			url: "<?= site_url('colaboradores/pautas/verificaImagem'); ?>",
			method: "POST",
			data: form,
			processData: false,
			contentType: false,
			cache: false,
			dataType: "json",
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide(); },
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

	$('#modalSugerirPauta .btn-enviar').on('click', function () {
		var form = new FormData(document.getElementById('pautas_form'));
		var idPauta = '';
		if ($('#id_pauta').val() !== '') {
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
			complete: function () { $('#modal-loading').hide(); },
			success: function (retorno) {
				if (retorno.status) {
					popMessage('Sucesso!', retorno.mensagem, TOAST_STATUS.SUCCESS);
					var modalEl = document.getElementById('modalSugerirPauta');
					if (modalEl && typeof bootstrap !== 'undefined') {
						bootstrap.Modal.getOrCreateInstance(modalEl).hide();
					}
					if (typeof window.VL_refreshNoticiasListaNoticias === 'function') {
						window.VL_refreshNoticiasListaNoticias();
					}
				} else {
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});
	});

	var vlImgDefaultPauta = <?= json_encode(base_url('public/assets/imagem-default.png')); ?>;

	function vlClearPautaCamposDependentes() {
		var $m = $('#modalSugerirPauta');
		$m.find('#titulo, #texto').val('');
		$m.find('#imagem').val('');
		$m.find('#pauta_antiga').val('N');
		$m.find('.preview_imagem_div').hide();
		$m.find('#preview_imagem').attr('src', vlImgDefaultPauta);
		if (window.VL_contagemPalavrasAtualizar) {
			window.VL_contagemPalavrasAtualizar();
		}
	}

	window.getInformationLink = function (link) {
		var $m = $('#modalSugerirPauta');
		var $linkInput = $m.find('#link');
		if ($linkInput.prop('disabled')) {
			return false;
		}

		vlClearPautaCamposDependentes();

		link = (link || '').trim().substring(0, 254);
		$linkInput.val(link);

		if (link === '') {
			return false;
		}

		var fd = new FormData();
		fd.append('link_pauta', link);
		var idP = $m.find('#id_pauta').val();
		if (idP) {
			fd.append('id_pauta', idP);
		}

		$.ajax({
			url: "<?= site_url('colaboradores/pautas/verificaPautaCadastrada'); ?>",
			method: "POST",
			data: fd,
			processData: false,
			contentType: false,
			cache: false,
			dataType: "json",
			beforeSend: function () { $('#modal-loading').show(); },
			complete: function () { $('#modal-loading').hide(); },
			success: function (retorno) {
				if (retorno.status) {
					$m.find('#titulo').val(retorno.titulo);
					$m.find('#imagem').val(retorno.imagem);
					$m.find('#texto').val(retorno.texto);
					$m.find('#preview_imagem').attr('src', retorno.imagem || vlImgDefaultPauta);
					$m.find('.preview_imagem_div').show();
					if (window.VL_contagemPalavrasAtualizar) {
						window.VL_contagemPalavrasAtualizar();
					}
					if (retorno.mensagem == null) {
						$m.find('#pauta_antiga').val('N');
					} else {
						$m.find('#pauta_antiga').val('S');
						popMessage('ATENÇÃO!', retorno.mensagem, TOAST_STATUS.INFO);
					}
					if (retorno.imagem === '') {
						$m.find('#imagem').val(vlImgDefaultPauta);
						$m.find('#preview_imagem').attr('src', vlImgDefaultPauta);
					}
				} else {
					vlClearPautaCamposDependentes();
					$m.find('#imagem').val(vlImgDefaultPauta);
					$m.find('#preview_imagem').attr('src', vlImgDefaultPauta);
					popMessage('ATENÇÃO', retorno.mensagem, TOAST_STATUS.DANGER);
				}
			}
		});
	};
})();
</script>
<?php
	$pautaListPermissoesRodape = $_SESSION['colaboradores']['permissoes'] ?? [];
	$pautaListAdminPerm7 = in_array('7', $pautaListPermissoesRodape, false);
	$pautaListAplicaLimites = ! $pautaListAdminPerm7;
	$contagemPalavrasListModal = [
		'endpoint'         => site_url('colaboradores/artigos/contarPalavrasTexto'),
		'textareaSelector' => '#texto',
		'outputSelector'   => '#count_message',
		'debounceMs'       => 200,
		'submitSelector'   => $pautaListAplicaLimites ? '#modalSugerirPauta .btn-enviar' : null,
		'minPalavras'      => $pautaListAplicaLimites ? (int) $config['pauta_tamanho_minimo'] : null,
		'maxPalavras'      => $pautaListAplicaLimites ? (int) $config['pauta_tamanho_maximo'] : null,
	];
	echo view('template/colaboradores_contagem_palavras_init', ['contagemPalavrasConfig' => $contagemPalavrasListModal]);
	?>
<script type="text/javascript">
	if (typeof window.VL_CONTAGEM_PALAVRAS_INIT === 'function') {
		window.VL_CONTAGEM_PALAVRAS_INIT();
	}
</script>
<?= view('template/colaboradores_comentarios_init', [
	'comentariosConfig' => [
		'endpointPrefix'     => base_url('colaboradores/pautas/comentarios/'),
		'entityIdSelector'   => '#idPauta',
		'autoLoad'           => false,
	],
]); ?>
<?php endif; ?>

<?= $this->endSection(); ?>
