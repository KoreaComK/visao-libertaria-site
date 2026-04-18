<?= $this->extend('layouts/main'); ?>

<?= $this->section('content'); ?>
<?php
$avatarPadrao = site_url('public/assets/avatar-default.png');
$avatarBruto = isset($colaborador['avatar']) ? trim((string) $colaborador['avatar']) : '';
$avatarSrc = ($avatarBruto !== '') ? $avatarBruto : $avatarPadrao;
?>

<div class="container py-4">
	<div class="card border-0 shadow-sm mb-4">
		<div class="card-body p-4 p-lg-5 vl-perfil-hero">
			<div class="row align-items-center g-4">
				<div class="col-md-auto text-center text-md-start">
					<div class="position-relative d-inline-block mb-3">
						<img class="rounded-circle border border-3 border-white shadow d-block"
							style="width: 8.5rem; height: 8.5rem; object-fit: cover;"
							src="<?= esc($avatarSrc, 'attr'); ?>"
							alt="Avatar de <?= esc($colaborador['apelido']); ?>">
						<span class="position-absolute top-100 start-50 translate-middle badge bg-danger rounded-pill px-3 py-2 shadow-sm text-nowrap"><?= (int) $contador_artigos; ?>
							artigo<?= ($contador_artigos > 1) ? 's' : ''; ?></span>
					</div>
				</div>
				<div class="col min-w-0 text-center text-md-start">
					<h1 class="h2 mb-2 mb-md-3"><?= esc($colaborador['apelido']); ?></h1>
					<div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-2 mb-3">
						<?php foreach ($atribuicoes as $atribuicao):
							$corAtrib = esc($atribuicao['cor'], 'attr');
							$corBgSubtle = ($atribuicao['cor'] === 'white') ? 'light' : $corAtrib;
							?>
							<span class="badge rounded-pill fw-semibold text-dark bg-<?= $corBgSubtle; ?>-subtle border border-<?= $corAtrib; ?> border-opacity-50"><?= esc($atribuicao['nome']); ?></span>
						<?php endforeach; ?>
					</div>
					<p class="text-muted small mb-0">
						<i class="bi bi-calendar3 me-1" aria-hidden="true"></i>
						Cadastrou-se no site há <?= esc($tempo); ?>.
					</p>
				</div>
				<?php if (! empty($conquistaDestaque)): ?>
					<div class="col-12 col-md-auto text-center text-md-end ms-md-auto pt-3 pt-md-0 ps-md-4 border-top border-md-0 border-md-start border-secondary border-opacity-25">
						<p class="small fw-semibold text-dark text-uppercase mb-3 mb-md-2" style="font-size: 0.72rem; letter-spacing: 0.06em;">Maior conquista</p>
						<img class="rounded-circle shadow-sm border border-white vl-conquista-destaque-img"
							src="<?= esc(site_url($conquistaDestaque['imagem']), 'attr'); ?>"
							alt="<?= esc($conquistaDestaque['nome'] ?? 'Conquista', 'attr'); ?>"
							data-bs-toggle="tooltip"
							data-bs-placement="left"
							data-bs-title="<?= esc('Recebida após publicar ' . (int) $conquistaDestaque['pontuacao'] . ' artigo' . ((int) $conquistaDestaque['pontuacao'] > 1 ? 's' : '') . ' como escritor.', 'attr'); ?>">
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div class="card border-0 shadow-sm mb-4">
		<div class="card-body p-4">
			<div class="row g-4 align-items-start">
				<div class="col-lg-7">
					<h3 class="h6 text-dark mb-3">Atividade em artigos publicados</h3>
					<ul class="list-inline small mb-3 mb-lg-0">
						<li class="list-inline-item me-3"><span class="text-dark fw-semibold"><?= esc(sprintf('%02d', (int) $contagem_papeis['escrito'])); ?></span> como escritor</li>
						<li class="list-inline-item me-3"><span class="text-dark fw-semibold"><?= esc(sprintf('%02d', (int) $contagem_papeis['revisado'])); ?></span> como revisor</li>
						<li class="list-inline-item me-3"><span class="text-dark fw-semibold"><?= esc(sprintf('%02d', (int) $contagem_papeis['narrado'])); ?></span> como narrador</li>
						<li class="list-inline-item"><span class="text-dark fw-semibold"><?= esc(sprintf('%02d', (int) $contagem_papeis['produzido'])); ?></span> como produtor</li>
					</ul>
					<p class="small text-body-secondary mb-0">
						<i class="bi bi-clock-history me-1" aria-hidden="true"></i>
						<?php if ($ultima_publicacao_participacao_formatada !== null): ?>
							Última publicação em que participou: <span class="text-dark fw-semibold"><?= esc($ultima_publicacao_participacao_formatada); ?></span>
						<?php else: ?>
							Ainda não há artigos publicados com a participação deste colaborador.
						<?php endif; ?>
					</p>
				</div>
				<div class="col-lg-5">
					<h3 class="h6 text-dark mb-3">Veja também</h3>
					<div class="d-flex flex-wrap gap-2">
						<a href="<?= esc(site_url('site/artigos'), 'attr'); ?>" class="btn btn-sm btn-outline-secondary">Todos os artigos</a>
						<a href="<?= esc(site_url('site/colaborador/' . rawurlencode($colaborador['apelido'])), 'attr'); ?>" class="btn btn-sm btn-outline-secondary">Perfil de colaborador</a>
					</div>
					<p class="small text-body-secondary mt-3 mb-0">O perfil de colaborador mostra <span class="text-dark fw-semibold">pautas</span> reservadas no site.</p>
				</div>
			</div>
		</div>
	</div>

	<?php
	$apelEsc = esc($colaborador['apelido']);
	$legendasJs = [
		'todos' => 'Artigos publicados em que <span class="text-dark fw-semibold">' . $apelEsc . '</span> participou como <span class="text-dark fw-semibold">escritor, revisor, narrador ou produtor</span>.',
		'escrito' => 'Artigos publicados em que <span class="text-dark fw-semibold">' . $apelEsc . '</span> foi o <span class="text-dark fw-semibold">escritor</span>.',
		'revisado' => 'Artigos publicados em que <span class="text-dark fw-semibold">' . $apelEsc . '</span> foi o <span class="text-dark fw-semibold">revisor</span>.',
		'narrado' => 'Artigos publicados em que <span class="text-dark fw-semibold">' . $apelEsc . '</span> foi o <span class="text-dark fw-semibold">narrador</span>.',
		'produzido' => 'Artigos publicados em que <span class="text-dark fw-semibold">' . $apelEsc . '</span> foi o <span class="text-dark fw-semibold">produtor</span>.',
	];
	$urlListaEscritor = site_url('site/escritorList/' . rawurlencode($colaborador['apelido']));
	?>
	<section class="mb-5">
		<header class="border-bottom pb-3 mb-4">
			<div class="row align-items-center g-3 mb-3">
				<div class="col-12 col-md-6 col-xl-7">
					<h2 class="h4 mb-0 text-dark" id="vlEscritorListaTitulo">Participação em artigos</h2>
				</div>
				<div class="col-12 col-md-6 col-xl-5">
					<div class="d-flex flex-row flex-nowrap align-items-center gap-2 justify-content-md-end">
						<label for="vlEscritorPapelFiltro" class="small text-body mb-0 text-nowrap flex-shrink-0 fw-medium">Participou como</label>
						<select id="vlEscritorPapelFiltro" class="form-select form-select-sm flex-grow-1 flex-md-grow-0" style="min-width: 10rem; max-width: 100%;" autocomplete="off">
							<option value="todos">Todos os papéis</option>
							<option value="escrito" selected>Escritor</option>
							<option value="revisado">Revisor</option>
							<option value="narrado">Narrador</option>
							<option value="produzido">Produtor</option>
						</select>
					</div>
				</div>
			</div>
			<p class="small mb-0 text-secondary" id="vlEscritorListaSub"><?= $legendasJs['escrito']; ?></p>
		</header>
		<div class="row listagem-escritor" id="vlEscritorListaRow" data-vl-papel="escrito"></div>
	</section>
</div>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
			new bootstrap.Tooltip(el);
		});
	});

	(function () {
		var baseLista = <?= json_encode($urlListaEscritor, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;
		var legendasSub = <?= json_encode($legendasJs, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE); ?>;

		function vlEscritorCarregarLista(papel) {
			papel = papel || 'escrito';
			$('#vlEscritorListaRow').attr('data-vl-papel', papel);
			var sub = legendasSub[papel] || legendasSub.escrito;
			$('#vlEscritorListaSub').html(sub);
			$.ajax({
				url: baseLista,
				type: 'get',
				dataType: 'html',
				data: { papel: papel },
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide(); },
				success: function (data) {
					$('.listagem-escritor').html(data);
				}
			});
		}

		$(document).off('click.vlEscritorPager', '.listagem-escritor .page-link').on('click.vlEscritorPager', '.listagem-escritor .page-link', function (e) {
			e.preventDefault();
			e.stopImmediatePropagation();
			var papel = $('#vlEscritorPapelFiltro').val() || 'escrito';
			var u;
			try {
				u = new URL(this.href, window.location.href);
			} catch (err) {
				return;
			}
			u.searchParams.set('papel', papel);
			$.ajax({
				url: u.toString(),
				type: 'get',
				dataType: 'html',
				beforeSend: function () { $('#modal-loading').show(); },
				complete: function () { $('#modal-loading').hide(); },
				success: function (data) {
					$('.listagem-escritor').html(data);
				}
			});
		});

		$(document).ready(function () {
			vlEscritorCarregarLista($('#vlEscritorPapelFiltro').val());
			$('#vlEscritorPapelFiltro').on('change', function () {
				vlEscritorCarregarLista($(this).val());
			});
		});
	})();
</script>

<?= $this->endSection(); ?>
