<?php

use CodeIgniter\I18n\Time;

$temLinhas = $artigos !== null && !empty($artigos);

$fasesKanban = array(
	'1' => array('titulo' => 'Escrevendo', 'icon' => 'fa-file-lines', 'bs' => 'success'),
	'2' => array('titulo' => 'Revisando', 'icon' => 'fa-pen-to-square', 'bs' => 'primary'),
	'3' => array('titulo' => 'Narrando', 'icon' => 'fa-microphone', 'bs' => 'info'),
	'4' => array('titulo' => 'Produzindo', 'icon' => 'fa-video', 'bs' => 'secondary'),
	'5' => array('titulo' => 'Publicando', 'icon' => 'fab fa-youtube', 'bs' => 'danger'),
);

$porFase = array();
foreach (array_keys($fasesKanban) as $fid) {
	$porFase[$fid] = array();
}
$porFase['_outros'] = array();

if ($temLinhas) {
	foreach ($artigos as $artigo) {
		$fid = (string) $artigo['fase_producao_id'];
		if (isset($porFase[$fid])) {
			$porFase[$fid][] = $artigo;
		} else {
			$porFase['_outros'][] = $artigo;
		}
	}
}
?>
<?php if (!$temLinhas): ?>
	<div class="text-center py-5 px-3">
		<i class="fas fa-folder-open fa-2x text-muted mb-3 d-block" aria-hidden="true"></i>
		<p class="fw-semibold text-body mb-1">Nenhum artigo em produção</p>
		<p class="small text-muted mb-3">Quando tiver rascunhos ou artigos no pipeline, eles aparecem aqui.</p>
		<a href="<?= site_url('colaboradores/artigos/cadastrar'); ?>" class="btn btn-sm btn-primary">Novo artigo</a>
	</div>
<?php else: ?>
	<div class="kanban-producao-wrap p-2 p-md-3 rounded border bg-body-secondary bg-opacity-25">
		<div class="kanban-producao-scroll d-flex gap-2 pb-1">
			<?php foreach ($fasesKanban as $fid => $meta): ?>
				<?php
				$lista = $porFase[$fid];
				$n = count($lista);
				?>
				<div class="kanban-producao-col flex-shrink-0 rounded-3 border bg-body d-flex flex-column"
					style="width: min(100%, 17rem); max-height: min(62vh, 34rem);">
					<div class="kanban-producao-col-head px-2 py-2 border-bottom bg-body-secondary bg-opacity-50 rounded-top-3">
						<div class="d-flex align-items-center gap-2">
							<i class="<?= strpos($meta['icon'], 'fab ') === 0 ? $meta['icon'] : ('fas ' . $meta['icon']); ?> small text-<?= $meta['bs']; ?>"
								aria-hidden="true"></i>
							<span class="small fw-semibold text-uppercase" style="letter-spacing: 0.03em;"><?= esc($meta['titulo']); ?></span>
							<span class="badge bg-secondary ms-auto"><?= $n; ?></span>
						</div>
					</div>
					<div class="kanban-producao-col-body flex-grow-1 p-2 overflow-y-auto">
						<?php if ($n === 0): ?>
							<p class="small text-muted mb-0 fst-italic text-center py-3">Nenhum nesta fase</p>
						<?php else: ?>
							<?php foreach ($lista as $artigo): ?>
								<div class="card border shadow-sm mb-2">
									<div class="card-body p-2">
										<a class="fw-semibold small text-decoration-none d-block mb-2 kanban-card-titulo"
											href="<?= site_url('colaboradores/artigos/detalhamento/' . $artigo['id']); ?>"
											title="<?= esc($artigo['titulo']); ?>"><?= esc($artigo['titulo']); ?></a>
										<div class="d-flex flex-wrap align-items-center gap-1 mb-2">
											<span class="badge text-bg-<?= ($artigo['tipo_artigo'] == 'T') ? ('primary') : ('danger'); ?>">
												<?= ($artigo['tipo_artigo'] == 'T') ? ('Teórico') : ('Notícia'); ?>
											</span>
										</div>
										<p class="small text-muted mb-2 mb-md-3">
											<i class="far fa-clock me-1" aria-hidden="true"></i>
											<?= Time::createFromFormat('Y-m-d H:i:s', $artigo['atualizado'])->toLocalizedString('dd MMM yyyy'); ?>
										</p>
										<?php if ($artigo['fase_producao_id'] == '1'): ?>
											<div class="d-flex gap-1 justify-content-end pt-1 border-top border-opacity-50">
												<button type="button" class="btn btn-light btn-sm btn-floating btn-tooltip btn-descartar"
													data-artigo-id="<?= $artigo['id']; ?>" data-toggle="tooltip" data-placement="top"
													title="Descartar artigo" aria-label="Descartar artigo">
													<i class="fas fa-trash-can" aria-hidden="true"></i>
												</button>
												<a href="<?= site_url('colaboradores/artigos/cadastrar/') . $artigo['id']; ?>"
													class="btn btn-light btn-sm btn-floating btn-tooltip" data-toggle="tooltip" data-placement="top"
													title="Continuar escrevendo" aria-label="Continuar escrevendo">
													<i class="fas fa-pencil" aria-hidden="true"></i>
												</a>
											</div>
										<?php endif; ?>
									</div>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
			<?php if (!empty($porFase['_outros'])): ?>
				<div class="kanban-producao-col flex-shrink-0 rounded-3 border bg-body d-flex flex-column"
					style="width: min(100%, 17rem); max-height: min(62vh, 34rem);">
					<div class="kanban-producao-col-head px-2 py-2 border-bottom bg-body-secondary bg-opacity-50 rounded-top-3">
						<div class="d-flex align-items-center gap-2">
							<i class="fas fa-layer-group small text-body" aria-hidden="true"></i>
							<span class="small fw-semibold text-uppercase" style="letter-spacing: 0.03em;">Outras fases</span>
							<span class="badge bg-secondary ms-auto"><?= count($porFase['_outros']); ?></span>
						</div>
					</div>
					<div class="kanban-producao-col-body flex-grow-1 p-2 overflow-y-auto">
						<?php foreach ($porFase['_outros'] as $artigo): ?>
							<div class="card border shadow-sm mb-2">
								<div class="card-body p-2">
									<a class="fw-semibold small text-decoration-none d-block mb-2"
										href="<?= site_url('colaboradores/artigos/detalhamento/' . $artigo['id']); ?>"><?= esc($artigo['titulo']); ?></a>
									<span class="badge bg-<?= $artigo['cor']; ?> bg-opacity-10 text-<?= $artigo['cor']; ?>"><?= esc($artigo['nome']); ?></span>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
<?php endif; ?>
<script>
	(function () {
		var $root = $('.tabela-meus-producao');
		$root.find('.btn-tooltip').tooltip();

		$root.find('.btn-descartar').off('click.meusProducao').on('click.meusProducao', function () {
			$('.conteudo-modal').html('Deseja realmente descartar este artigo?');
			artigoId = $(this).attr('data-artigo-id');
			$("#mi-modal").modal('show');
		});
	})();
</script>
