<?php
use CodeIgniter\I18n\Time;

?>
<?= $this->extend('layouts/administradores'); ?>

<?= $this->section('content'); ?>

<style>
	/* Listagem densa: mais linhas visíveis sem perder legibilidade */
	.pautas-fechadas-detalhe-page .pautas-fechadas-detalhe-table {
		font-size: 0.8125rem;
	}

	.pautas-fechadas-detalhe-page .pautas-fechadas-detalhe-table thead th {
		font-size: 0.7rem;
		text-transform: uppercase;
		letter-spacing: 0.03em;
		font-weight: 600;
		padding-top: 0.35rem;
		padding-bottom: 0.35rem;
		vertical-align: middle;
	}

	.pautas-fechadas-detalhe-page .pautas-fechadas-detalhe-table tbody td {
		padding-top: 0.3rem;
		padding-bottom: 0.3rem;
		vertical-align: middle;
	}

	.pautas-fechadas-detalhe-page .pautas-fechadas-detalhe-table .col-titulo {
		line-height: 1.25;
	}

	.pautas-fechadas-detalhe-page .pautas-fechadas-grupo-tema + .pautas-fechadas-grupo-tema {
		margin-top: 0.75rem;
	}
</style>

<div class="container py-3 pautas-fechadas-detalhe-page">
	<div class="row align-items-center g-2 mb-2">
		<div class="col-12 col-md">
			<h1 class="mb-0 h4"><?= esc($titulo); ?></h1>
			<?php
			$nomeFechamento = '';
			if (is_array($pautaDetail ?? null)) {
				$nomeFechamento = trim((string) ($pautaDetail['titulo'] ?? ''));
			}
			?>
			<?php if ($nomeFechamento !== ''): ?>
				<p class="text-muted small mb-0"><?= esc($nomeFechamento); ?></p>
			<?php else: ?>
				<p class="text-muted small mb-0">Pautas encerradas neste fechamento, por tema.</p>
			<?php endif; ?>
		</div>
		<div class="col-12 col-md-auto">
			<a href="<?= site_url('colaboradores/pautas/fechadas'); ?>" class="btn btn-primary btn-sm w-100 w-sm-auto">Voltar</a>
		</div>
	</div>

	<div class="card shadow-sm border-0">
		<div class="card-header bg-white py-2 px-3">
			<h2 class="h6 mb-0 text-muted">Itens por tema</h2>
		</div>
		<div class="card-body p-2 p-md-3">
			<?php foreach ($pautasList as $tag => $pautas): ?>
				<div class="pautas-fechadas-grupo-tema">
					<div class="d-flex flex-wrap align-items-baseline gap-2 px-2 py-1 border border-bottom-0 rounded-top bg-body-secondary bg-opacity-50 small">
						<span class="badge text-bg-primary"><?= esc($tag); ?></span>
						<span class="text-muted">Sugerido por</span>
						<span><?= esc(implode(', ', $pautas['colaboradores'])); ?></span>
					</div>
					<div class="table-responsive border rounded-bottom">
						<table class="table table-sm table-striped table-hover mb-0 pautas-fechadas-detalhe-table">
							<thead class="table-light">
								<tr>
									<th scope="col" class="border-0" style="width:1.5rem"></th>
									<th scope="col" class="border-0 text-nowrap" style="width:5.5rem">Data</th>
									<th scope="col" class="border-0">Pauta</th>
									<th scope="col" class="border-0 text-end text-nowrap" style="width:1%">Ações</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($pautas['pautas'] as $pauta): ?>
									<?php
									$tCriado = Time::createFromFormat('Y-m-d H:i:s', $pauta['criado']);
									$dataCurta = $tCriado ? $tCriado->format('d/m/Y') : '';
									?>
									<tr>
										<td class="text-center px-1">
											<?php if ($pauta['pauta_antiga'] == 'S'): ?>
												<span class="text-danger" title="Pauta antiga"><i class="bi bi-exclamation-circle-fill" aria-hidden="true"></i><span class="visually-hidden">Pauta antiga</span></span>
											<?php else: ?>
												<span class="text-transparent user-select-none" aria-hidden="true">·</span>
											<?php endif; ?>
										</td>
										<td class="text-muted text-nowrap small"><?= esc($dataCurta); ?></td>
										<td class="col-titulo"><?= esc($pauta['titulo']); ?></td>
										<td class="text-end text-nowrap py-1">
											<div class="btn-group btn-group-sm" role="group">
												<?php
												$idPautaRow = isset($pauta['id']) ? (string) $pauta['id'] : '';
												$qc = (int) ($pauta['vl_qtde_comentarios'] ?? $pauta['qtde_comentarios'] ?? 0);
												?>
												<a href="javascript:void(0)" class="btn btn-outline-secondary py-0 px-2" data-bs-toggle="modal"
													data-bs-target="#modalComentariosPauta"
													data-bs-texto="<?= esc($pauta['texto'] ?? '', 'attr'); ?>"
													data-bs-pautas-id="<?= esc($idPautaRow, 'attr'); ?>"
													data-bs-imagem="<?= esc($pauta['imagem'] ?? '', 'attr'); ?>"
													data-bs-titulo="<?= esc($pauta['titulo'], 'attr'); ?>"
													title="<?= $qc === 1 ? '1 comentário' : esc($qc . ' comentários', 'attr'); ?>">
													<i class="bi bi-chat-left-text me-1" aria-hidden="true"></i><span class="visually-hidden">Comentários: </span><?= $qc ?>
												</a>
												<a class="btn btn-outline-secondary py-0 px-2" href="<?= esc($pauta['link'] ?? '', 'attr'); ?>" target="_blank" rel="noopener noreferrer" title="Ir para a notícia original">Ir para notícia</a>
											</div>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<?= view('template/modal_comentarios_pauta'); ?>

<?= $this->endSection(); ?>